<?php

namespace App\Presenters;

use Nette,
    App\Forms\SignFormFactory,
    Nette\Application\UI\Form,
    Nette\Security\Passwords;

class SignPresenter extends BasePresenter {

    /** @var SignFormFactory @inject */
    public $factory;

//    aby sa nezapisovala stranka do session
    public function beforeRender() {
        
    }

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

        $form->addSubmit('send', 'Sign in');

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
            $this->restoreRequest($this->mySession->backlink);
            $this->flashMessage('Boli ste uspesne prihlaseny.');
        } catch (\Nette\Security\AuthenticationException $e) {
            $this->flashMessage($e->getMessage());
        }
    }

    public function actionOut() {
        $this->getUser()->logout();
        $this->flashMessage('You have been signed out.');
        $this->restoreRequest($this->mySession->backlink);
    }

}
