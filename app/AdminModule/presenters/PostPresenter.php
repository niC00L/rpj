<?php

namespace App\AdminModule\Presenters;

use Nette,
    Nette\Application\UI\Form;

class PostPresenter extends AdminPresenter {

    public function startup() {
        parent::startup();
        if ($this->getUser()->getRoles()[0] == 'banned') {
            $this->flashMessage('Máte ban');
            $this->redirect('Admin:default');
        } elseif (!$this->getUser()->isAllowed('post', 'edit')) {
            $this->flashMessage('Nemáte prístup k tejto stránke');
            $this->redirect('Admin:default');
        }
    }
    
    public function setForms($id, $table, $defaults) {
        
    }

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
        $this->template->posts = $articles;
        $this->template->uncategorized = $uncategorized;
    }

    public function actionCreatePost() {
        $this['editForm']->setForms(NULL, 'post', NULL);
    }
    
    public function actionCreateCategory() {
        $this['editForm']->setForms(NULL, 'post_ctg', NULL);
    }

//
//    public function createComponentPostFormPublish() {
//        $form = new Form;
//        $form->addHidden('id');
//        $form->addSubmit('publish', 'Publikovat');
//    }
}
