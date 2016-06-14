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
        $globals = $this->global->getGlobal();
        $this->comments = array(
            'post' => $globals['comment_post'],
            'ctg' => $globals['comment_ctg']
        );

        $this->addComponent(new \App\AdminModule\Components\LoadControls\loadControl($this->database, $this->global, 'post-show-header'), 'PostHeader');
        $this->addComponent(new \App\AdminModule\Components\LoadControls\loadControl($this->database, $this->global, 'post-show-footer'), 'PostFooter');

        $this->addComponent(new \App\AdminModule\Components\LoadControls\loadControl($this->database, $this->global, 'post-category-header'), 'CategoryHeader');
        $this->addComponent(new \App\AdminModule\Components\LoadControls\loadControl($this->database, $this->global, 'post-category-footer'), 'CategoryFooter');
    }

    public function actionShow($address) {
        $this->table = 'post';

        $post = $this->template->post = $this->database->table('post')->where('address', $address)->fetch();
        $author = $this->template->author = $this->database->table('users')->where('id', $post['author'])->fetch();

        $template = $this->database->table('site_templates')->where('site_templates.id', $post['template'])->fetch()->file_name;
        $this->setView($template);

        $this->template->comments = $this->comments;

        $this->defaults = $post->toArray();
        $this->id = $post['id'];

        if ($post->status != 1 && !$this->user->isAllowed('post', 'edit')) {
            $this->flashMessage('Príspevok bol odstranený', 'danger');
            $this->redirect('Homepage:');
        } else {
            $this['editForm']->setForms($this->id, $this->table, $this->defaults);
            $this['deleteForm']->setForms($this->id, $this->table);
            $this['renewForm']->setForms($this->id, $this->table);
            $this['comments']->setForms($this->id, $this->table);
            $this['gallery']->setForms(array('post_id' => $post['id']));
        }
    }

    public function actionCategory($address) {
        $this->table = 'post_ctg';		
        $category = $this->template->category = $this->database->table('post_ctg')->where('address', $address)->fetch();
		$author = $this->template->author = $this->database->table('users')->where('id', $category['author'])->fetch();
        $posts = $this->template->posts = $this->database->table('post')->where(':post_ctg_sort.ctg_id', $category['id'])->where('status', 1)->fetchAll();

        $template = $this->database->table('site_templates')->where('site_templates.id', $category['template'])->fetch()->file_name;
        $this->setView($template);

        $this->template->comments = $this->comments;

        $this->defaults = $category->toArray();
        $this->id = $category['id'];

        if ($category->status != 1 && !$this->user->isAllowed('post', 'edit')) {
            $this->flashMessage('Kategória bola odstránená', 'danger');
            $this->redirect('Homepage:');
        } else {
            $this['editForm']->setForms($this->id, $this->table, $this->defaults);
            $this['deleteForm']->setForms($this->id, $this->table);
            $this['comments']->setForms($this->id, $this->table);
        }
    }

}
