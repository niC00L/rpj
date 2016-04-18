<?php

namespace App\AdminModule\Presenters;

use Nette,
    Nette\Application\UI\Form,
    Nette\Application\UI\Multiplier;

class CommentsPresenter extends AdminPresenter {
    public function startup() {
        parent::startup();
        if ($this->getUser()->getRoles()[0] == 'banned') {
            $this->flashMessage('Máte ban');
            $this->redirect('Admin:default');
        } elseif (!$this->getUser()->isAllowed('comment', 'edit')) {
            $this->flashMessage('Nemáte prístup k tejto stránke');
            $this->redirect('Admin:default');
        }
    }

    public function actionShow() {
        $comments = $this->database->table('comments')->where('NOT(status ?)', 0)->fetchAll();
        $post_ids = array();
        $ctg_ids = array();
        $img_ids = array();
        foreach ($comments as $com) {
            if (!in_array($com->post_id, $post_ids)) {
                array_push($post_ids, $com->post_id);
            }
            if (!in_array($com->post_ctg_id, $ctg_ids)) {
                array_push($ctg_ids, $com->post_ctg_id);
            }
            if (!in_array($com->img_id, $img_ids)) {
                array_push($img_ids, $com->img_id);
            }
            $this['deleteComment-'.$com->id]->setDefaults($com);
        }        
        $this->template->post = $posts = $this->database->table('post')->where('id', $post_ids)->fetchAll();
        $this->template->ctgs = $ctgs = $this->database->table('post_ctg')->where('id', $ctg_ids)->fetchAll();
        $this->template->author = $author = $this->database->table('users')->fetchAll();
        $this->template->comments = $comments;
    }
    
    public function createComponentDeleteComment(){
        return new Multiplier(function ($itemId) {
            $form = new Form;
            $form->addHidden('id');
            $form->addSubmit('submit', 'Zmazať')
                    ->setAttribute('class', 'btn');
            $form->onSuccess[] = array($this, 'deleteCommentSucceeded');
            return $form;
        });    
    }
    public function deleteCommentSucceeded($form, $values) {
        $this->database->table('comments')->where('id', $values['id'])->update(array('status'=> 0));
        $this->presenter->flashMessage('Komentár odstránený');
        $this->redirect('this');
    }
}
