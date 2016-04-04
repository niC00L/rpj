<?php

namespace App\Components\Comments;

use Nette\Application\UI\Control,
    Nette\Application\UI\Form;

class CommentsControl extends \App\AdminModule\Components\baseControl {

    private $id;
    private $table;

    public function setForms($id, $table) {
        $this->id = $id;
        $this->table = $table;
    }

    public function renderShow($id, $table) {
        $template = $this->template;
        $template->setFile(__DIR__ . '/commentsDefault.latte');
        $template->comments = $comments = $this->database->table('comments')->where($table . '_id', $id)->fetchAll();
        $template->render();
    }

    public function createComponentAddCommentForm() {
        $form = new Form;
        $form->addText('name', 'Meno')
                ->setRequired();
        $form->addText('email', 'e-mail');
//                ->addRule(Form::EMAIL);
        $form->addTextarea('text', 'Text')
                ->setRequired()
                ->setAttribute('class', 'materialize-textarea');
        $form->addSubmit('submit', 'Odoslat')
                ->setAttribute('class', 'btn');

        $form->onSuccess[] = array($this, 'addCommentFormSuc');
        return $form;
    }

    public function addCommentFormSuc($form, $values) {
//        dump($this->id);exit;
        $values[$this->table . '_id'] = $this->id;
        $this->database->table('comments')->insert($values);

        $this->presenter->flashMessage('Komentar pridany.', 'success');
        $this->presenter->redirect('this');
    }

}
