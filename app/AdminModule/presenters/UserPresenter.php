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
    
    public function setForms($id, $table, $defaults) {        
    }

    public function actionDefault() {        
        $users = $this->database->table('users')->fetchAll();
        $this->template->users = $users;
        foreach($users as $u) {
            $this['usersEditor'][$u['id']]->setDefaults($u);
        }
    }
    
    public function createComponentUsersEditor() {
        return new Multiplier(function ($itemId) {
            $rights = array(
                'admin' => 'Admin',
                'user' => 'User',
                'editor' => 'Editor',
                'ban' => 'Banned'
            );
            $form = new Form;   
            $form->addHidden('id');
            $form->addSelect('rights', 'Rights', $rights)
                    ->setAttribute('class', 'browser-default');
            $form->addSubmit('edit', 'Edit')
                    ->setAttribute('class', 'btn');

            $form->onSuccess[] = array($this, 'usersEditoorSuc');
            return $form;
        });
    }
    
    public function usersEditoorSuc($form, $values) {
        $id = $values['id'];
        unset($values['id']);
        
        $this->database->table('users')->where('id', $id)->update($values);
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
