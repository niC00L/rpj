<?php

namespace App\Components\Comments;

use Nette\Application\UI\Control,
    Nette\Application\UI\Form;

class CommentsControl extends \App\AdminModule\Components\baseControl {
    private $id;
    private $table;
    public $user;

    public function setForms($id, $table) {
        $this->id = $id;
        $this->table = $table;
    }

    public function getUser($id) {
        $user = $this->database->table('users')->where('id', $id)->fetch();
        return $user;
    }

    public function renderShow($id, $table) {
        $template = $this->template;
        $template->setFile(__DIR__ . '/commentsDefault.latte');
        $comments = $this->database->table('comments')->where($table . '_id', $id)->where('status', 1)->fetchAll();
        $coms = array();
        foreach ($comments as $comment) {
            $comment = $comment->toArray();
            if ($comment['user_id']) {
                $comment['name'] = $this->getUser($comment['user_id'])['display_name'];
                $comment['email'] = $this->getUser($comment['user_id'])['email'];
                $comment['img'] = $this->getUser($comment['user_id'])['profile_image'];
            }
            array_push($coms, $comment);
        }
        $template->comments = $coms;
        $template->render();
    }

    public function createComponentAddCommentForm() {        
        $form = new Form;
        if (!$this->presenter->user->isLoggedIn()) {
            $form->addText('name', 'Meno')
                    ->setRequired();
            $form->addText('email', 'e-mail')
                    ->addRule(Form::EMAIL);
        }
        $form->addTextarea('text', 'Text')
                ->setRequired()
                ->setAttribute('class', 'materialize-textarea')
                ->setAttribute('length', $this->global->getSetting('comment_length'))
                ->addRule(Form::MAX_LENGTH, 'Komentár má maximálnu dĺžku %d znakov', $this->global->getSetting('comment_length'));
        $form->addSubmit('submit', 'Odoslat')
                ->setAttribute('class', 'btn');
        $form->onSuccess[] = array($this, 'addCommentFormSuc');
        return $form;
    }

    public function addCommentFormSuc($form, $values) {
        $values[$this->table . '_id'] = $this->id;
        if ($this->presenter->user->isLoggedIn()) {
            $values['user_id'] = $this->presenter->getUser()->getId();
        }
        $this->database->table('comments')->insert($values);

        $this->presenter->flashMessage('Komentar pridany.', 'success');
        $this->presenter->redirect('this');
    }

}
