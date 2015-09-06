<?php

namespace App\Presenters;

use Nette,
    Nette\Application\UI\Form;

class PostPresenter extends BasePresenter {

    public function renderShow($address) {
        $this->setView('post');
        $post = $this->template->post = $this->database->table('post')->where('address', $address)->where('status', 1)->fetch();
        if (!$post) {
            $this->flashMessage('Prispevok bol odstraneny', 'danger');
            $this->redirect('Homepage:');
        } else {
            $this['postForm']->setDefaults($post->toArray());
            $this['postFormDelete']->setDefaults($post->toArray());
        }
    }

    public function renderCategory($address) {
//        $this->template->setFile(__DIR__ . '/templates/Post/showCategory.latte');

        $category = $this->template->category = $this->database->table('post_ctg')->where('address', $address)->fetch();
        $ctg_id = $this->database->table('post_ctg')->where('address', $address)->where('status', 1)->fetch();
        if (!$ctg_id) {
            $this->flashMessage('Kategoria bola odstranena', 'danger');
            $this->redirect('Homepage:');
        } else {

            $post_id = $this->database->table('post_ctg_sort')->where('ctg_id', $ctg_id)->select('post_id');
            $posts = $this->template->posts = $this->database->table('post')->where('id', $post_id)->fetchAll();

            $this['postForm']->setDefaults($category->toArray());
            $this['postFormDelete']->setDefaults($category->toArray());
        }
    }

    protected function createComponentPostForm() {

//	ak sa zobrazuje clanok vyberie sa ktore checkboxy maju byt zaskrtnute
        if ($this->getAction() == 'show') {
            $address = $this->getParameter('address');
            $id = $this->database->table('post')->where('address', $address)->where('status', 1)->fetch()->id;

            $ctgs_in = $this->database->table('post_ctg_sort')->where('post_id', $id);
        }

//      vyberu sa vsetky checkboxy
        $ctgs = $this->database->table('post_ctg')->where('status', 1)->fetchAll();

//        vytvori sa formular
        $form = new Form;
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

//	pouzitym sa prida hodnota true
        if ($this->getAction() == 'show') {
            foreach ($ctgs_in as $ctg_in) {
                $form->setValues(['category_' . $ctg_in->ctg_id => true]);
            }
        }

        $form->addSubmit('send', 'Uložit a publikovat')
                ->setAttribute('class', 'btn');

        $form->onSuccess[] = array($this, 'postFormSucceeded');
        return $form;
    }
    
    protected function createComponentPostFormDelete() {
        $form = new Form;
        $form->addHidden('id', 'Id:');
        $form->addSubmit('delete', 'Delete')
                ->setAttribute('class', 'btn');

        $form->onSuccess[] = array($this, 'postFormDeleteSucceeded');
        return $form;
    }

    public function postFormSucceeded($form, $values) {
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
            if ($this->getAction() == 'show') {
                $table = 'post';
            } elseif ($this->getAction() == 'category') {
                $table = 'post_ctg';
            }

            $id = $this->database->table($table)->where('address', $address)->fetch()->id;

//	zapisanie/pridanie obsahu stranky
            $this->database->table($table)->get($id)->update($values);
        } else {
            if ($this->getAction() == 'createPost') {
                $table = 'post';
            } elseif ($this->getAction() == 'createCategory') {
                $table = 'post_ctg';
            }
            $this->database->table($table)->insert($values);
        }

//	zapisanie clankov v kategoriach do spolocnej tabulky
        if ($this->getAction() == 'show') {
            $this->database->table('post_ctg_sort')->where('post_id', $id)->delete();
        }

        foreach ($ctg_sort as $ctg_id) {
            $this->database->table('post_ctg_sort')->insert(array(
                'post_id' => $post['id'],
                'ctg_id' => $ctg_id
            ));
        }


        $this->flashMessage('Uspesne publikovane.', 'success');
        $this->redirect('this', ['address' => $values['address']]);
    }
    
    public function postFormDeleteSucceeded($form, $values) {
        $id = $values['id'];

        if ($this->getAction() == 'show') {
            $address = $this->database->table('post')->where('id',$id)->fetch()->address;
            $this->database->table('post')->where('id',$id)->update(array('status'=> 0));
        }
        elseif ($this->getAction() == 'category') {
            $address = $this->database->table('post_ctg')->where('id',$id)->fetch()->address;
            $this->database->table('post_ctg')->where('id', $id)->update(array('status'=> 0));
        }
        
        $this->database->table('ctrl_menu')->where('address',$address)->delete();
        
        $this->flashMessage('Prispevok odstraneny.', 'success');
        $this->redirect('Homepage:');
    }

}
