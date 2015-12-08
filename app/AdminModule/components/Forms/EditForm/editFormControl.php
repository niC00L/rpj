<?php

namespace App\AdminModule\Components\Forms\EditForm;

use Nette\Application\UI\Form;

class editFormControl extends \App\AdminModule\Components\baseControl {

    public $table;        
    
    public function render() {
        $temp = $this->template;
        $temp->setFile(__DIR__ . '/editFormDefault.latte');
        $temp->render();
    }
    
    public function setTable($table) {
        $this->table = $table;
    }

    public function setDefaults($defaults) {
        $this['editForm']->setDefaults($defaults);
    }        

    protected function createComponentEditForm() {
        $this->setTable();

//	ak sa zobrazuje clanok vyberie sa ktore checkboxy maju byt zaskrtnute
//        if ($this->getAction() == 'show') {
//            $address = $this->getParameter('address');
//            $id = $this->database->table('post')->where('address', $address)->fetch()->id;
//
//            $ctgs_in = $this->database->table('post_ctg_sort')->where('post_id', $id);
//        }
//      vyberu sa vsetky checkboxy
//        $ctgs = $this->database->table('post_ctg')->where('status', 1)->fetchAll();
//        vytvori sa formular

        $fields = $this->database->query('EXPLAIN '.$this->table);
//        foreach ($fields as $f) {
//            if ($f['Type'] == 'timestamp' || $f['Type'] == 'datetime') {
//                unset($f);
//            }
//        }
//        $f['Field']     //field name

        $form = new Form;

        foreach ($fields as $f) {
            if ($f['Type'] == 'text') {
                $form->addTextArea($f['Field'], $f['Field'])
                ->setAttribute('class', 'materialize-textarea');
            } elseif ($f['Type'] == 'varchar(50)') {
                $form->addText($f['Field'], $f['Field'])
                        ->setRequired();
            } else {
                $form->addHidden($f['Field'], $f['Field'])
                        ->setRequired();
            }
        }
//        $form->addText('address', 'Adresa:')
//                ->setRequired();
//        $form->addText('title', 'Titulok:')
//                ->setRequired();
//        $form->addTextArea('description', 'Popis:')
//                ->setAttribute('class', 'materialize-textarea');
//        if ($this->getAction() == 'show' || $this->getAction() == 'createPost') {
//            $form->addTextArea('text', 'Obsah:')
//                    ->setAttribute('class', 'materialize-textarea');
//	vykreslia sa checkboxy
//            foreach ($ctgs as $ctg) {
//                $form->addCheckbox('category_' . $ctg['id'], $ctg['title']);
//            }
////        }
//	pouzitym sa prida hodnota true
//        if ($this->getAction() == 'show') {
//            foreach ($ctgs_in as $ctg_in) {
//                $form->setValues(['category_' . $ctg_in->ctg_id => true]);
//            }
//        }

        $form->addSubmit('send', 'Uložit a publikovat')
                ->setAttribute('class', 'btn');

        $form->onSuccess[] = array($this, 'editFormSucceeded');
        return $form;
    }

    public function editFormSucceeded($form, $values) {
        $address = $this->getParameter('address');

//	Vyradi checkboxy z values aby sa nezapisovali do tabulky post
        $ctg_sort = array();
        foreach ($values as $key => $value) {
            if (Nette\Utils\Strings::startsWith($key, 'category_')) {
//				ak je checkbox zaskrtnuty vlozi jeho hodnotu do pola $ctg_sort
                if ($values[$key] == true) {
//				do pola sa vlozia len cisla
                    array_push($ctg_sort, substr($key, -1));
                }
                unset($values[$key]);
            }
        }

        if ($address) {
//            if ($this->getAction() == 'show') {
//                $table = 'post';
//            } elseif ($this->getAction() == 'category') {
//                $table = 'post_ctg';
//            }

            $id = $this->database->table($table)->where('address', $address)->fetch()->id;

//	zapisanie/pridanie obsahu stranky
            $this->database->table($table)->get($id)->update($values);
        }

//	zapisanie clankov v kategoriach do spolocnej tabulky
//        if ($this->getAction() == 'show') {
//            $this->database->table('post_ctg_sort')->where('post_id', $id)->delete();
//        }

        foreach ($ctg_sort as $ctg_id) {
            $this->database->table('post_ctg_sort')->insert(array(
                'post_id' => $id,
                'ctg_id' => $ctg_id
            ));
        }


        $this->flashMessage('Uspesne publikovane.', 'success');
        $this->redirect('this', ['address' => $values['address']]);
    }

}
