<?php

namespace App\AdminModule\Components\Forms\EditForm;

use Nette\Application\UI\Form,
    Nette\Utils\Strings;

class editFormControl extends \App\AdminModule\Components\baseControl {

    public $table;
    public $id;
    public $defaults;
    public $ignore;

    public function render($template = 'editFormDefault') {
        $this->template->setFile(__DIR__ . '/' . $template . '.latte');
        $this->template->render();
    }

    public function setForms($id, $table, $defaults, $ignore = array()) {
        $this->id = $id;
        $this->table = $table;
        $this->defaults = $defaults;
        $this->ignore = $ignore;
        if ($defaults) {
            $this['editForm']->setDefaults($defaults);
        }
    }

    protected function createComponentEditForm() {
//	ak sa zobrazuje clanok vyberu sa checkboxy kategorii
        if ($this->table == 'post') {
            $ctgs_in = $this->database->table('post_ctg_sort')->where('post_id', $this->id);
//      vyberu sa nazvy kategorii
            $ctgs = $this->database->table('post_ctg')->where('status', 1)->fetchAll();
        }

//      vyberu sa polia pre formular
        $fields = $this->database->query('EXPLAIN ' . $this->table);

        $form = new Form;
        foreach ($fields as $f) {
            if ($f['Field'] == 'author') {
                $form->addHidden('author');
            }
            if (!in_array($f['Field'], $this->ignore)) {
                if (\Nette\Utils\Strings::endsWith($f['Type'], 'text')) {
                    $form->addTextArea($f['Field'], $f['Field'])
                            ->setAttribute('class', 'materialize-textarea ' . $f['Field'])
                            ->setAttribute('placeholder', $f['Field']);
                } elseif (\Nette\Utils\Strings::endsWith($f['Field'], 'image')) {
                    $form->addUpload($f['Field'], 'Image');
                } elseif (Strings::startsWith($f['Type'], 'varchar')) {
                    $form->addText($f['Field'], $f['Field'])
                            ->setAttribute('class', $f['Field'])
                            ->setAttribute('placeholder', $f['Field']);
                } elseif ($f['Field'] == 'template') {
                    $tmps = $this->table;
                    if($this->table == 'controls' && $this->defaults) {
                        $tmps = $this->defaults['component_name'];
                    }
                    $templates = $this->database->table('site_templates')->where('type', $tmps)->where('status', 1)->fetchPairs('id', 'title');
                    $t = $form->addSelect($f['Field'], $f['Field'], $templates);
                }
            }
        }

        if ($this->table == 'post') {
//	vykreslia sa checkboxy
            if ($ctgs) {
                foreach ($ctgs as $ctg) {
                    $i = $form->addCheckbox('category_' . $ctg['id'], $ctg['title']);
                }
//	pouzitym sa prida hodnota true
                if ($this->id) {
                    foreach ($ctgs_in as $ctg_in) {
                        $i = $form->setValues(['category_' . $ctg_in->ctg_id => true]);
                    }
                }
            }
        }

        $form->addSubmit('send', 'Uložit')
                ->setAttribute('class', 'btn');

        $form->onSuccess[] = array($this, 'editFormSucceeded');

        return $form;
    }

    public function editFormSucceeded($form, $values) {
//	Vyradi checkboxy z values aby sa nezapisovali do tabulky post
        $ctg_sort = array();
        foreach ($values as $key => $value) {
            if (Strings::startsWith($key, 'category_')) {
//				ak je checkbox zaskrtnuty vlozi jeho hodnotu do pola $ctg_sort
                if ($values[$key] == true) {
//				do pola sa vlozia len cisla
                    array_push($ctg_sort, substr($key, -1));
                }
                unset($values[$key]);
            }

            if (Strings::endsWith($key, 'image') || Strings::startsWith($key, 'image'))  {
                if (strlen($values[$key]) > 1) {
                    $image = $values[$key];
                    $values[$key] = $image->name;
                    $image->move('images/' . $this->table . '/' . $this->id . '/' . $image->name);
                    $values[$key] = $image->name;
                } else {
                    unset($values[$key]);
                }
            }
            
            if ($key == 'author') {
                $values['author'] = $this->presenter->getUser()->getId();
            }
        }
        if (\Nette\Utils\Strings::startsWith($this->table, 'post') && !$values['address']) {
            $values['address'] = \Nette\Utils\Strings::webalize($values['title']);
        }
        if ($this->id) {
//	zapisanie/pridanie obsahu stranky
            $up = $this->database->table($this->table)->where('id', $this->id)->update($values);
        } else {
            $up = $this->database->table($this->table)->insert($values);
        }

//	zapisanie clankov v kategoriach do spolocnej tabulky
        if ($this->table == 'post') {
            if ($this->id) {
                $this->database->table('post_ctg_sort')->where('post_id', $this->id)->delete();
                $p = $this->id;
            } else {
                $p = $up['id'];
            }
            foreach ($ctg_sort as $ctg_id) {
                $this->database->table('post_ctg_sort')->insert(array(
                    'post_id' => $p,
                    'ctg_id' => $ctg_id
                ));
            }
        }

        $this->presenter->flashMessage('Úspešne publikované.', 'success');
//        if($values['address']) {
//            $this->presenter->redirect('this', ['address' => $values['address']]);
//        }
//        else {
        if (\Nette\Utils\Strings::startsWith($this->table, 'post') && !$this->id) {
            $this->presenter->redirect(':Admin:Post:default');
        } else {
            $this->presenter->redirect('this');
        }
    }

}
