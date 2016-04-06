<?php

namespace App\AdminModule\Presenters;

use Nette,
    App\Model,
    Nette\Application\UI\Form,
    Nette\Application\UI\Multiplier,
    Nette\Utils\Strings;

class UserPresenter extends AdminPresenter {
    
    public $id;
    
    public function setForms($id, $table, $defaults) {        
    }

    public function actionDefault() { 
        if(!$this->getUser()->isAllowed('users', 'edit')) {
            $this->flashMessage('Nemáte právo na prístup k tejto stránke');
            $this->redirect('Admin:default');
        }
        $users = $this->database->table('users')->fetchAll();
        $this->template->users = $users;
        foreach($users as $u) {
            $this['usersEditor'][$u['id']]->setDefaults($u);
        }
    }
    
    public function createComponentUsersEditor() {
        return new Multiplier(function ($itemId) {
            $roles = array(
                'admin' => 'Admin',
                'user' => 'User',
                'editor' => 'Editor',
                'ban' => 'Banned'
            );
            $form = new Form;   
            $form->addHidden('id');
            $form->addSelect('role', 'Role', $roles)
                    ->setAttribute('class', 'browser-default');
            $form->addSubmit('edit', 'Edit')
                    ->setAttribute('class', 'btn');

            $form->onSuccess[] = array($this, 'usersEditorSuc');
            return $form;
        });
    }
    
    public function usersEditorSuc($form, $values) {
        $id = $values['id'];
        unset($values['id']);
        
        $this->database->table('users')->where('id', $id)->update($values);
    }

    public function actionProfile($id) {
        if(!$this->getUser()->isAllowed('profile', 'view')) {
            $this->flashMessage('Nemáte právo na prístup k tejto stránke');
            $this->redirect(':Homepage:default');
        }
        $user = $this->database->table('users')->where('id', $id)->fetch();        
        $user = $user->toArray();
        $this->template->profile = $user;
        $this['editForm']->setForms($id, 'users', $user);        
    }
}
