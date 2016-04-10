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
                ->setRequired('Zadajte meno.');

        $form->addPassword('password', 'Password:')
                ->setRequired('Zadajte heslo.');

        $form->addCheckbox('remember', 'Zapamätať prihlásenie');

        $form->addSubmit('send', 'Prihlásiť')
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
        $form->addText('username', 'Prihlasovacie meno')
                ->addCondition(Form::FILLED);
        $form->addText('display_name', 'Meno pre zobrazenie')
                ->addCondition(Form::FILLED);
        $form->addText('email', 'E-mail: *', 35)
                ->setEmptyValue('@')
                ->addRule(Form::FILLED, 'Zadajte e-mail')
                ->addCondition(Form::FILLED)
                ->addRule(Form::EMAIL, 'Neplatný e-mail');
        $form->addPassword('password', 'Heslo: *', 20)
                ->setOption('description', 'Minimálne 6 znakov')
                ->addRule(Form::FILLED, 'Zadajte heslo')
                ->addRule(Form::MIN_LENGTH, 'Heslo musí mať minimálne %d znakov.', 6);
        $form->addPassword('password2', 'Heslo znovu: *', 20)
                ->addConditionOn($form['password'], Form::VALID)
                ->addRule(Form::FILLED, 'Heslo znovu')
                ->addRule(Form::EQUAL, 'Hesla sa nezhodujú.', $form['password']);
        $form->addSubmit('send', 'Registrovať')
                ->setAttribute('class', 'btn');

        $form->onSuccess[] = array($this, 'registerFormSucceeded');
        return $form;
    }

    public function registerFormSucceeded($form, $values) {
        $name = $values['username'];
        $password = $values['password'];
        unset($values['username'], $values['password'], $values['password2']);

        $values['role'] = 'user';
        $values['token'] = Nette\Utils\Random::generate(20, '0-9A-Za-z');

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
