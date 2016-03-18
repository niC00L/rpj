<?php

namespace App\AdminModule\Presenters;

use Nette,
    App\Model,
    Nette\Application\UI\Form,
    Nette\Application\UI\Multiplier,
    Nette\Utils\Strings;

class UserPresenter extends \App\Presenters\BasePresenter {
    
    private $user;
    private $id;

    public function renderDefault() {
        
    }
    public function setForms($id, $table, $defaults) {
        
    }

    public function actionProfile() {
//        $this->user = $this->getUser()->getIdentity();        
//        $this['profileForm']->setDefaults($this->user->toArray());
        $this->id = $this->getUser()->getId();
        $this->user = $this->database->table('users')->where('id', $this->id)->fetch();
        
        $this['editForm']->setForms($this->id, 'users', $this->user);
        
    }
    
    public function createComponentProfileForm() {
        $form = new Form;
        
        return $form;
    }
}
