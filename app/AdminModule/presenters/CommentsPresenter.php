<?php

namespace App\AdminModule\Presenters;

use Nette,
    Nette\Application\UI\Form;

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

    public function renderShow() {
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
        }        
        $this->template->post = $posts = $this->database->table('post')->where('id', $post_ids)->fetchAll();
        $this->template->comments = $comments;
    }

}
