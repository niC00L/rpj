<?php

namespace App\AdminModule\Presenters;

use Nette,
    Nette\Application\UI\Form;

class PostPresenter extends AdminPresenter {

    public function renderDefault() {
        $post_ctgs = $this->template->post_ctgs = $this->database->table('post_ctg')->where('status ? OR status ?', 1, 2)->fetchAll();
        $posts = $this->template->posts = $this->database->table('post')->where('status ? OR status ?', 1, 2)->fetchAll();
        $p_ids = $this->database->table('post_ctg_sort')->fetchAll();
        $ids = array();
        foreach ($p_ids as $p_id) {
            $p_id->toArray();
            $ids[$p_id['id']] = $p_id['post_id'];
        }
        $uncategorized = array();
        foreach ($posts as $post_id) {
            if (!in_array($post_id['id'], $ids)) {
                $uncategorized[$post_id['id']] = $posts[$post_id['id']];
            }
        }
        
        $articles = array();
        foreach ($post_ctgs as $ctgs) {
            $articles[$ctgs['id']] = $this->database->table('post')->where('status ? OR status ?', 1, 2)->where(':post_ctg_sort.ctg_id', $ctgs['id'])->fetchAll();
        }
//        dump($articles); exit;
        $this->template->posts = $articles;
        $this->template->uncategorized = $uncategorized;
    }

//    public function createComponentPostForm() {
//
////      vyberu sa vsetky checkboxy
//        $ctgs = $this->database->table('post_ctg')->where('status', 1)->fetchAll();
//
////        vytvori sa formular
//        $form = new Form;
//        $form->addHidden('id');
//        $form->addText('address', 'Adresa:')
//                ->setRequired();
//        $form->addText('title', 'Titulok:')
//                ->setRequired();
//        $form->addTextArea('description', 'Popis:')
//                ->setAttribute('class', 'materialize-textarea');
//
//        if ($this->getAction() == 'show' || $this->getAction() == 'createPost') {
//            $form->addTextArea('text', 'Obsah:')
//                    ->setAttribute('class', 'materialize-textarea');
//
////	vykreslia sa checkboxy
//            foreach ($ctgs as $ctg) {
//                $form->addCheckbox('category_' . $ctg['id'], $ctg['title']);
//            }
//        }
//
//        $form->addSubmit('send', 'Uložit a publikovat')
//                ->setAttribute('class', 'btn');
//
//        $form->onSuccess[] = array($this, 'postFormSucceeded');
//        return $form;
//    }

    public function createComponentPostFormPublish() {
        $form = new Form;
        $form->addHidden('id');
        $form->addSubmit('publish', 'Publikovat');
    }

//    public function postFormSucceeded($form, $values) {
////	Vyradi checkboxy z values aby sa nezapisovali do tabulky post
//        $ctg_sort = array();
//        foreach ($values as $key => $value) {
//            if (Nette\Utils\Strings::startsWith($key, 'category_')) {
////				ak je checkbox zaskrtnuty vlozi jeho hodnotu do pola $ctg_sort
//                if ($values[$key] == true) {
////				do pola sa vlozia len cisla
//                    array_push($ctg_sort, substr($key, -1));
//                }
//                unset($values[$key]);
//            }
//        }
//
//        if ($this->getAction() == 'createPost') {
//            $table = 'post';
//        } elseif ($this->getAction() == 'createCategory') {
//            $table = 'post_ctg';
//        }
//        $post = $this->database->table($table)->insert($values);
//
//        foreach ($ctg_sort as $ctg_id) {
//            $this->database->table('post_ctg_sort')->insert(array(
//                'post_id' => $post['id'],
//                'ctg_id' => $ctg_id
//            ));
//        }
//
//        $this->flashMessage('Uspesne publikovane.', 'success');
//        $this->redirect('this', ['address' => $values['address']]);
//    }

}
