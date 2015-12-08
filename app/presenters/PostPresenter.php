<?php

namespace App\Presenters;

use Nette,
    Nette\Application\UI\Form;

class PostPresenter extends BasePresenter {

    public $table;
    public $defaults;

    public function setForms() {
        $this['editForm']->setTable($this->table);
        $this['editForm']->setDefaults($this->defaults);
        $this['editFormDelete']->setDefaults($this->defaults);                
    }

    public function renderShow($address) {
        $this->setView('post');
        $this->table = 'post';
        $post = $this->template->post = $this->database->table('post')->where('address', $address)->fetch();
        $images = $this->template->images = $this->database->table('imgs')->where(':img_sort.gallery_id', $post['gallery_id'])->fetchAll();
        
        $this->defaults = $post->toArray();
        
        if ($post->status != 1 && !$this->user->loggedIn) {
            $this->flashMessage('Prispevok bol odstraneny', 'danger');
            $this->redirect('Homepage:');
        } else {            
            $this->setForms();
        }
    }

    public function renderCategory($address) {
//        $this->template->setFile(__DIR__ . '/templates/Post/showCategory.latte');

        $category = $this->template->category = $this->database->table('post_ctg')->where('address', $address)->fetch();
        if ($category->status != 1 && !$this->user->loggedIn) {
            $this->flashMessage('Kategoria bola odstranena', 'danger');
            $this->redirect('Homepage:');
        } else {
            $posts = $this->template->posts = $this->database->table('post')->where(':post_ctg_sort.ctg_id', $category['id'])->fetchAll();

            $this['editForm']->setDefaults($category->toArray());
            $this['postFormDelete']->setDefaults($category->toArray());
        }
    }

    protected function createComponentPostFormDelete() {
        $form = new Form;
        $form->addHidden('id', 'Id:');
        $form->addSubmit('delete', 'Delete')
                ->setAttribute('class', 'btn');

        $form->onSuccess[] = array($this, 'postFormDeleteSucceeded');
        return $form;
    }

    public function postFormDeleteSucceeded($form, $values) {
        $id = $values['id'];

        if ($this->getAction() == 'show') {
            $address = $this->database->table('post')->where('id', $id)->fetch()->address;
            $this->database->table('post')->where('id', $id)->update(array('status' => 0));
        } elseif ($this->getAction() == 'category') {
            $address = $this->database->table('post_ctg')->where('id', $id)->fetch()->address;
            $this->database->table('post_ctg')->where('id', $id)->update(array('status' => 0));
        }

        $this->database->table('ctrl_menu')->where('address', $address)->delete();

        $this->flashMessage('Prispevok odstraneny.', 'success');
        $this->redirect('Homepage:');
    }

}
