<?php

namespace App\AdminModule\Presenters;

use Nette,
    App\Model;

class BinPresenter extends AdminPresenter {

    public function renderDefault() {
        $posts = $this->template->posts = $this->database->table('post')->where('status', 0)->fetchAll();
        $post_ctgs = $this->template->post_ctgs = $this->database->table('post_ctg')->where('status', 0)->fetchAll();       
    }

    public function createComponentBinRenew() {
        return new Multiplier(function ($itemId) {
            $form = new Form;
            $form->addHidden('id', 'Id:');
            $form->addSubmit('delete', 'Delete')
                    ->setAttribute('class', 'btn');

            $form->onSuccess[] = array($this, 'postFormDeleteSucceeded');
            return $form;
        });
    }

    public function createComponentBinDelete() {
        return new Multiplier(function ($itemId) {
            $form = new Form;
            $form->addHidden('id', 'Id:');
            $form->addSubmit('delete', 'Delete')
                    ->setAttribute('class', 'btn');

            $form->onSuccess[] = array($this, 'postFormDeleteSucceeded');
            return $form;
        });
    }

    public function binDeleteSucceeded($form, $values) {
        $id = $values['id'];

        $this->database->table('post')->where('id', $id)->delete();

        $this->flashMessage('Prispevok odstraneny.', 'success');
        $this->redirect('Homepage:');
    }

}
