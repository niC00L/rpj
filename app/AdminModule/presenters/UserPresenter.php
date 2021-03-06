<?php

namespace App\AdminModule\Presenters;

use Nette,
    App\Model,
    Nette\Application\UI\Form,
    Nette\Application\UI\Multiplier,
    Nette\Utils\Strings,
    Nette\Utils\Image;

class UserPresenter extends AdminPresenter {

    public $id;

    public function setForms($id, $table, $defaults) {
        
    }

    public function startup() {
        parent::startup();
        if ($this->getUser()->getRoles()[0] == 'banned') {
            $this->flashMessage('Máte ban');
            $this->redirect('Admin:default');
        }
    }

    public function actionDefault() {
        if (!$this->getUser()->isAllowed('users', 'edit')) {
            $this->flashMessage('Nemáte právo na prístup k tejto stránke');
            $this->redirect('Admin:default');
        }
        $users = $this->database->table('users')->where('id <>', $this->getUser()->getId())->fetchAll();
        $this->template->users = $users;
//        $this['gridForm']->setForms(NULL, 'users', NULL);
        foreach ($users as $u) {
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
                    ->setAttribute('class','browser-default');
            $form->addSubmit('edit', 'Upraviť')
                    ->setAttribute('class', 'btn');

            $form->onSuccess[] = array($this, 'usersEditorSuc');
            return $form;
        });
    }

    public function usersEditorSuc($form, $values) {
        $id = $values['id'];
        unset($values['id']);

        $this->database->table('users')->where('id', $id)->update($values);
        $this->flashMessage('Úspešne upravené');
        $this->redirect('this');
    }

    public function actionProfile($id) {
        if (!$this->getUser()->isAllowed('profile', 'view')) {
            $this->flashMessage('Nemáte právo na prístup k tejto stránke');
            $this->redirect(':Homepage:default');
        }
        $posts = $this->database->table('post')->where('author', $id);
        $comments = $this->database->table('comments')->where('user_id', $id);
        $user = $this->database->table('users')->where('id', $id)->fetch();
        $user = $user->toArray();
        $this->template->profile = $user;
        $this['editForm']->setForms($id, 'users', $user, array('role', 'username', 'password', 'token'));
    }

    public function createComponentChangePasswordForm() {
        $form = new Form();
        $form->addPassword('old', 'Staré heslo')
                ->setRequired('Napíšte staré heslo');
        $form->addPassword('new', 'Nové heslo', 20)
                ->setOption('description', 'Minimálne 6 znakov')
                ->addRule(Form::FILLED, 'Zadajte heslo')
                ->addRule(Form::MIN_LENGTH, 'Heslo musí mať minimálne %d znakov.', 6);
        $form->addPassword('new2', 'Nové heslo znovu', 20)
                ->addConditionOn($form['new'], Form::VALID)
                ->addRule(Form::FILLED, 'Heslo znovu')
                ->addRule(Form::EQUAL, 'Hesla sa nezhodujú.', $form['new']);        
        
        $form->addSubmit('submit', 'Zmeniť')
                ->setAttribute('class', 'btn');

        $form->onSuccess[] = array($this, 'changePasswordSuccess');
        return $form;
    }

    public function changePasswordSuccess($form, $values) {
        $id = $this->getParameter('id');
        $old = $this->database->table('users')->where('id', $id)->fetch()->password;
        $verify = \Nette\Security\Passwords::verify($values['old'], $old);
        if ($verify) {
            $this->database->table('users')->where('id', $id)->update(array('password' => \Nette\Security\Passwords::hash($values['new'])));
            $this->flashMessage('Heslo bolo zmenené');
            $this->redirect('this');
        }
        else {
            $this->flashMessage('Staré heslo sa nezhoduje');
            $this->redirect('this');
        }
    }

}
