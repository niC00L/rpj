<?php

namespace App\Presenters;

use Nette,
	App\Forms\SignFormFactory,
	Nette\Application\UI\Form;

/**
 * Sign in/out presenters.
 */
class SignPresenter extends BasePresenter {

	/** @var SignFormFactory @inject */
	public $factory;

	/**
	 * Sign-in form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentSignInForm() {
		$form = new Form;
		$form->addText('username', 'Uživatelské jméno:')
				->setRequired('Prosím vyplňte své uživatelské jméno.');

		$form->addPassword('password', 'Heslo:')
				->setRequired('Prosím vyplňte své heslo.');

		$form->addCheckbox('remember', 'Zůstat přihlášen');

		$form->addSubmit('send', 'Přihlásit');

		$form->onSuccess[] = array($this, 'signInFormSucceeded');
		return $form;
	}

//Aby sa do session neulozila stranka prihlasenia
	public function beforeRender() {
		
	}

	public function signInFormSucceeded($form) {
//		if (!$this->getUser()->isLoggedIn()) {
//			$this->error('Pro vytvoření, nebo editování příspěvku se musíte přihlásit.');
//		}
		$values = $form->values;

		try {
			$this->getUser()->login($values->username, $values->password);
			$this->restoreRequest($this->mySession->backlink);
		} catch (Nette\Security\AuthenticationException $e) {
			$form->addError('Nesprávné přihlašovací jméno nebo heslo.');
		}
	}

	public function actionOut() {
		$this->getUser()->logout();
		$this->flashMessage('You have been signed out.');
		$this->restoreRequest($this->mySession->backlink);
	}

}
