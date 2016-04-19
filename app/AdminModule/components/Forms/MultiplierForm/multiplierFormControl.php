<?php

namespace App\AdminModule\Components\Forms\MultiplierForm;

use Nette\Application\UI\Form,
    Nette\Application\UI\Multiplier,
    Nette\Utils\Strings;

class multiplierFormControl extends \App\AdminModule\Components\baseControl {

    public $table;
    public $id;
    public $type;
    public $defaults;

    public function render($template = 'multiplierFormDefault') {
        $this->template->setFile(__DIR__ . '/' . $template . '.latte');
        $this->template->defaults = $this->defaults;                
        $this->template->render();
    }

    public function setForms($type, $id, $table, $defaults) {
        $this->id = $id;
        $this->type = $type;
        $this->table = $table;
        $this->defaults = $defaults;
        if ($defaults) {
            foreach ($defaults as $default) {
                $this['multiplierForm-'.$default['id']]->setDefaults($default);
            }
        }
    }

    protected function createComponentMultiplierForm() {
        return new Multiplier(function ($itemId) {
            //      vyberu sa polia pre formular
        $fields = $this->database->query('EXPLAIN ' . $this->table);
            $form = new Form;
            foreach ($fields as $f) {
                if ($f['Field'] != 'password' && $f['Field'] != 'token' && $f['Field'] != 'role' && $f['Field'] != 'username') {
                    if (\Nette\Utils\Strings::endsWith($f['Type'], 'text')) {
                        $form->addTextArea($f['Field'], $f['Field'])
                                ->setAttribute('class', 'materialize-textarea ' . $f['Field'])
                                ->setAttribute('placeholder', $f['Field']);
                    } elseif (\Nette\Utils\Strings::endsWith($f['Field'], 'image' ) || \Nette\Utils\Strings::startsWith($f['Field'], 'image')) {
                        $form->addUpload($f['Field']);
                    } elseif (Strings::startsWith($f['Type'], 'varchar')) {
                        $form->addText($f['Field'], $f['Field'])
                                ->setAttribute('class', $f['Field'])
                                ->setAttribute('placeholder', $f['Field'])
                                ->setRequired();
                    } elseif ($f['Field'] == 'template') {
                        $templates = $this->database->table('site_templates')->where('type', $this->table)->fetchPairs('id', 'title');
                        $t = $form->addSelect($f['Field'], $f['Field'], $templates);
                    }
                }
            }

            $form->addSubmit('send', 'Uložit')
                    ->setAttribute('class', 'btn');

            $form->onSuccess[] = array($this, 'multiplierFormSucceeded');

            return $form;
        });
    }

    public function multiplierFormSucceeded($form, $values) {
//	Vyradi checkboxy z values aby sa nezapisovali do tabulky post
        foreach ($values as $key => $value) {

            if (Strings::endsWith($key, 'image')) {
                if (strlen($values[$key]) > 1) {
                    $image = $values[$key];
                    $values[$key] = $image->name;
                    $image->move('images/' . $this->table . '/' . $this->id . '/' . $image->name);
                    $values[$key] = $image->name;
                } else {
                    unset($values[$key]);
                }
            }
        }

        if ($this->id) {
//	zapisanie/pridanie obsahu stranky
            $this->database->table($this->table)->where($this->type.'_id',$this->id)->update($values);
        }

        $this->presenter->flashMessage('Úspešne publikované.', 'success');
//        if($values['address']) {
//            $this->presenter->redirect('this', ['address' => $values['address']]);
//        }
//        else {
        $this->presenter->redirect('this');
//        }
    }

}
