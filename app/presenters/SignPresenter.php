<?php

namespace App\Presenters;

use Nette,
    App\Forms\SignFormFactory,
    Nette\Application\UI\Form,
    Nette\Security\Passwords;

//    App\Model\UserManager;

class SignPresenter extends BasePresenter {

//    aby sa nezapisovala stranka do session
    public function beforeRender() {
        
    }

    /**
     * @var \App\Model\UserManager
     * @inject
     */
    public $userManager;

    /** @var SignFormFactory @inject */
    public $factory;
    private $users;

    /**
     * Sign-in form factory.
     * @return Nette\Application\UI\Form
     */
    public function createComponentSignInForm() {
        $form = new Form();
        $form->addText('username', 'Username:')
                ->setRequired('Please enter your username.');

        $form->addPassword('password', 'Password:')
                ->setRequired('Please enter your password.');

        $form->addCheckbox('remember', 'Keep me signed in');

        $form->addSubmit('send', 'Sign in')
                ->setAttribute('class', 'btn');

        // call method signInFormSucceeded() on success                        
        $form->onSuccess[] = $this->signInFormSucceeded;

        return $form;
    }

    public function signInFormSucceeded($form, $values) {
        $user = $this->getUser();

        if ($values->remember) {
            $user->setExpiration('14 days', FALSE);
        } else {
            $user->setExpiration('20 minutes', TRUE);
        }

        try {
            $user->login($values->username, $values->password);
            if ($this->mySession->backlink) {
                $this->restoreRequest($this->mySession->backlink);
            } else {
                $this->redirect(':Admin:default');
            }
            $this->flashMessage('Boli ste uspesne prihlaseny.');
        } catch (\Nette\Security\AuthenticationException $e) {
            $this->flashMessage($e->getMessage());
        }
    }

    public function createComponentRegisterForm() {
        $form = new Form;
        $form->addText('username', 'Username')
                ->addCondition(Form::FILLED);
        $form->addText('display_name', 'Display name')
                ->addCondition(Form::FILLED);
        $form->addText('email', 'E-mail: *', 35)
                ->setEmptyValue('@')
                ->addRule(Form::FILLED, 'Enter your e-mail')
                ->addCondition(Form::FILLED)
                ->addRule(Form::EMAIL, 'E-mail not valid');
        $form->addPassword('password', 'Password: *', 20)
                ->setOption('description', 'Min 6 characters')
                ->addRule(Form::FILLED, 'Enter your password')
                ->addRule(Form::MIN_LENGTH, 'Password must contain at least %d characters.', 6);
        $form->addPassword('password2', 'Retype password: *', 20)
                ->addConditionOn($form['password'], Form::VALID)
                ->addRule(Form::FILLED, 'Heslo znovu')
                ->addRule(Form::EQUAL, 'Passwords do not match.', $form['password']);
        $form->addSubmit('send', 'Register')
                ->setAttribute('class', 'btn');

        $form->onSuccess[] = array($this, 'registerFormSucceeded');
        return $form;
    }

    public function registerFormSucceeded($form, $values) {
        $name = $values['username'];
        $password = $values['password'];
        unset($values['username'], $values['password'], $values['password2']);

        $values['rights'] = 'user';
        $values['token'] = '42';

        $this->userManager->add($name, $password);
        $add_user = $this->database->table('users')->where('username', $name)->update($values);
        if ($add_user) {
            $this->flashMessage('Successfully registered! You can sign in now');
            $this->redirect('Sign:in');
        }
    }
    
    public function renderIn(){
        if ($this->user->isLoggedIn()){
            $this->flashMessage('You are already logged in');
            $this->redirect('Admin:Admin:default');
        }
    }
    
    public function renderRegister(){
        $this->template->users = $this->database->table('users')->fetchAll();
        #$this->template->mails = $this->database->table('users')->select('email')->fetchAll();
        if ($this->user->isLoggedIn()){
            $this->flashMessage('You are already logged in');
            $this->redirect('Admin:Admin:default');
        }
    }
    
    public function actionOut() {
        $this->getUser()->logout();
        $this->flashMessage('You have been signed out.');
        if ($this->mySession->backlink) {
            $this->restoreRequest($this->mySession->backlink);
        } else {
            $this->redirect('Homepage:default');
        }
    }

}
