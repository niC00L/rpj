<?php

namespace App\AdminModule\Components\Forms\EditForm;

use Nette\Application\UI\Form,
    Nette\Utils\Strings;

class editFormControl extends \App\AdminModule\Components\baseControl {

    public $table;
    public $id;

    public function render($template = 'editFormDefault') {
        $this->template->setFile(__DIR__ . '/'.$template.'.latte');
        $this->template->render();
    }

    public function setForms($id, $table, $defaults) {
        $this->id = $id;
        $this->table = $table;
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
            if ($f['Field'] != 'password' && $f['Field'] != 'token' && $f['Field'] != 'role' && $f['Field'] != 'username') {
                if ($f['Type'] == 'text') {
                    $form->addTextArea($f['Field'], $f['Field'])
                            ->setAttribute('class', 'materialize-textarea ' . $f['Field'])
                            ->setAttribute('placeholder', $f['Field']);
                } elseif ($f['Field'] == 'image_name') {
                    $form->addUpload($f['Field'], 'Image');
                } elseif (Strings::startsWith($f['Type'], 'varchar')) {
                    $form->addText($f['Field'], $f['Field'])
                            ->setAttribute('class', $f['Field'])
                            ->setAttribute('placeholder', $f['Field'])
                            ->setRequired();
                }
            }
        }

        if ($this->table == 'post') {
//	vykreslia sa checkboxy
            if ($ctgs) {
                foreach ($ctgs as $ctg) {
                    $form->addCheckbox('category_' . $ctg['id'], $ctg['title']);
                }
            }
//	pouzitym sa prida hodnota true
            if ($ctgs) {
                foreach ($ctgs_in as $ctg_in) {
                    $form->setValues(['category_' . $ctg_in->ctg_id => true]);
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
        }

        if ($values['image_name']) {
            $image = $values['image_name'];
            unset($values['image_name']);
            $values['image_name'] = $image->name;

            $image->move('images/' . $this->table . '/'.$this->id.'/' . $image->name);
        }

        if ($this->id) {
//	zapisanie/pridanie obsahu stranky
            $this->database->table($this->table)->get($this->id)->update($values);
        }

//	zapisanie clankov v kategoriach do spolocnej tabulky
        if ($this->table == 'post') {
            $this->database->table('post_ctg_sort')->where('post_id', $this->id)->delete();

            foreach ($ctg_sort as $ctg_id) {
                $this->database->table('post_ctg_sort')->insert(array(
                    'post_id' => $this->id,
                    'ctg_id' => $ctg_id
                ));
            }
        }

        $this->presenter->flashMessage('Uspesne publikovane.', 'success');
//        if($values['address']) {
//            $this->presenter->redirect('this', ['address' => $values['address']]);
//        }
//        else {
        $this->presenter->redirect('this');
//        }
    }

}
