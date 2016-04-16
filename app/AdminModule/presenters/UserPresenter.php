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
        //gridForm has $table, $columns, $filter;
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
        $users = $this->database->table('users')->fetchAll();
        $this->template->users = $users;
        $this['gridForm']->setForms('users', array('username', 'role'), 'id <>'.$this->getUser()->getId());
//        foreach ($users as $u) {
//            $this['usersEditor'][$u['id']]->setDefaults($u);
//        }
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

//        $this->template->comments = $comments;
//        $this->template->posts = $posts;
        $this->template->profile = $user;
        $this['editForm']->setForms($id, 'users', $user);
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
        $old = $old = $this->database->table('users')->where('id', $id)->select('password');
        $verify = Passwords::verify($form['old'], $old);
        if ($verify) {
            $this->database->table('users')->update(array('password' => $values['new']));
            $this->flashMessage('Heslo bolo zmenené');
            $this->redirect('this');
        } else {
            $this->flashMessage('Staré heslo sa nezhoduje');
            $this->redirect('this');
        }
    }

}
