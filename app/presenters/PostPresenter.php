<?php

namespace App\Presenters;

use Nette,
    Nette\Application\UI\Form;

class PostPresenter extends BasePresenter {

    public $table;
    public $defaults;
    public $id;

    public function setForms($id, $table, $defaults) {
    }
    
    public function actionShow($address) {
        $this->setView('post');
        $this->table = 'post';
        $post = $this->template->post = $this->database->table('post')->where('address', $address)->fetch();
        $images = $this->template->images = $this->database->table('imgs')->where(':img_sort.gallery_id', $post['gallery_id'])->fetchAll();
                
        $this->defaults = $post->toArray();
        $this->id = $post['id'];
        
        if ($post->status != 1 && !$this->user->loggedIn) {
            $this->flashMessage('Prispevok bol odstraneny', 'danger');
            $this->redirect('Homepage:');
        } else {            
            $this['editForm']->setForms($this->id, $this->table, $this->defaults);
            $this['deleteForm']->setForms($this->id, $this->table);
        }
    }

    public function actionCategory($address) {
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
}
