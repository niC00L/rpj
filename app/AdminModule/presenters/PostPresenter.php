<?php

namespace App\AdminModule\Presenters;

use Nette,
    Nette\Application\UI\Form;

class PostPresenter extends AdminPresenter {

    protected function createComponentPostForm() {

//      vyberu sa vsetky checkboxy
        $ctgs = $this->database->table('post_ctg')->where('status', 1)->fetchAll();

//        vytvori sa formular
        $form = new Form;
        $form->addHidden('id');
        $form->addText('address', 'Adresa:')
                ->setRequired();
        $form->addText('title', 'Titulok:')
                ->setRequired();
        $form->addTextArea('description', 'Popis:')
                ->setAttribute('class', 'materialize-textarea');

        if ($this->getAction() == 'show' || $this->getAction() == 'createPost') {
            $form->addTextArea('text', 'Obsah:')
                    ->setAttribute('class', 'materialize-textarea');

//	vykreslia sa checkboxy
            foreach ($ctgs as $ctg) {
                $form->addCheckbox('category_' . $ctg['id'], $ctg['title']);
            }
        }

        $form->addSubmit('send', 'Uložit a publikovat')
                ->setAttribute('class', 'btn');

        $form->onSuccess[] = array($this, 'postFormSucceeded');
        return $form;
    }

    public function postFormSucceeded($form, $values) {
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

        if ($this->getAction() == 'createPost') {
            $table = 'post';
        } elseif ($this->getAction() == 'createCategory') {
            $table = 'post_ctg';
        }
        $post = $this->database->table($table)->insert($values);

        foreach ($ctg_sort as $ctg_id) {
            $this->database->table('post_ctg_sort')->insert(array(
                'post_id' => $post['id'],
                'ctg_id' => $ctg_id
            ));
        }

        $this->flashMessage('Uspesne publikovane.', 'success');
        $this->redirect('this', ['address' => $values['address']]);
    }

}
