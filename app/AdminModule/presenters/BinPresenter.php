<?php

namespace App\AdminModule\Presenters;

use Nette,
    App\Model;

class BinPresenter extends AdminPresenter {

    public function startup() {
        parent::startup();
        if ($this->getUser()->getRoles()[0] == 'banned') {
            $this->flashMessage('Máte ban');
            $this->redirect('Admin:default');
        }
        elseif (!$this->getUser()->isAllowed('post', 'edit')) {
            $this->flashMessage('Nemáte prístup k tejto stránke');
            $this->redirect('Admin:default');
        }
    }

    public function actionDefault() {
        $posts = $this->template->posts = $this->database->table('post')->where('status', 0)->fetchAll();
        $post_ctgs = $this->template->post_ctgs = $this->database->table('post_ctg')->where('status', 0)->fetchAll();
        $comments = $this->template->comments = $this->database->table('comments')->where('status', 0)->fetchAll();
        $this->template->author = $author = $this->database->table('users')->fetchAll();
        $controls = $this->template->controls = \App\AdminModule\Presenters\ControlsPresenter::getControls($this->database, 0)[0];
        $controls = $this->template->links = \App\AdminModule\Presenters\ControlsPresenter::getControls($this->database, 0)[1];
    }

    public function createComponentBinRenew() {
        return new Multiplier(function ($itemId) {
            $form = new Form;
            $form->addHidden('id', 'Id:');
            $form->addSubmit('delete', 'Delete')
                    ->setAttribute('class', 'btn');

            $form->onSuccess[] = array($this, 'binRenewSucceeded');
            return $form;
        });
    }

    public function createComponentBinDelete() {
        return new Multiplier(function ($itemId) {
            $form = new Form;
            $form->addHidden('id', 'Id:');
            $form->addSubmit('delete', 'Delete')
                    ->setAttribute('class', 'btn');

            $form->onSuccess[] = array($this, 'binDeleteSucceeded');
            return $form;
        });
    }

    public function binRenewSucceeded($form, $values) {
        $id = $values['id'];
        unset($values['id']);

        $this->database->table('post')->where('id', $id)->update($values);

        $this->flashMessage('Príspevok obnovený.', 'success');
        $this->redirect('Homepage:');
    }

    public function binDeleteSucceeded($form, $values) {
        $id = $values['id'];

        $this->database->table('post')->where('id', $id)->delete();

        $this->flashMessage('Príspevok odstranený.', 'success');
        $this->redirect('Homepage:');
    }

}
