<?php

namespace App\Presenters;

use Nette,
    Nette\Application\UI\Form;

class PostPresenter extends BasePresenter {

    public $table;
    public $defaults;
    public $id;
    public $comments;

    public function setForms($id, $table, $defaults) {
        
    }

    public function startup() {
        parent::startup();
        $this->comments = array(
            'post' => $this->global['comment_post'],
            'ctg' => $this->global['comment_ctg']
        );
    }
    
    public function actionShow($address) {
        $this->table = 'post';

        $post = $this->template->post = $this->database->table('post')->where('address', $address)->fetch();
        $images = $this->template->images = $this->database->table('imgs')->where(':img_sort.gallery_id', $post['gallery_id'])->fetchAll();        
        $author = $this->template->author = $this->database->table('users')->where('id', $post['author'])->fetch();
        
        $template = $this->database->table('site_templates')->where('site_templates.id', $post['template'])->fetch()->file_name;
        $this->setView($template);
        
        $this->template->comments = $this->comments;

        $this->defaults = $post->toArray();
        $this->id = $post['id'];

        if ($post->status != 1 && !$this->user->loggedIn) {
            $this->flashMessage('Prispevok bol odstraneny', 'danger');
            $this->redirect('Homepage:');
        } else {
            $this['editForm']->setForms($this->id, $this->table, $this->defaults);
            $this['deleteForm']->setForms($this->id, $this->table);
            $this['comments']->setForms($this->id, $this->table);
        }
    }

    public function actionCategory($address) {
        $this->table = 'post_ctg';
        $category = $this->template->category = $this->database->table('post_ctg')->where('address', $address)->fetch();
        $posts = $this->template->posts = $this->database->table('post')->where(':post_ctg_sort.ctg_id', $category['id'])->fetchAll();

        $template = $this->database->table('site_templates')->where('site_templates.id', $category['template'])->fetch()->file_name;
        $this->setView($template);
        
        $this->template->comments = $this->comments;

        $this->defaults = $category->toArray();
        $this->id = $category['id'];

        if ($category->status != 1 && !$this->user->loggedIn) {
            $this->flashMessage('Kategoria bola odstranena', 'danger');
            $this->redirect('Homepage:');
        } else {
            $this['editForm']->setForms($this->id, $this->table, $this->defaults);
            $this['deleteForm']->setForms($this->id, $this->table);
            $this['comments']->setForms($this->id, $this->table);
        }
    }

}
