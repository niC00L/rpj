<?php

namespace App\Presenters;

use Nette,
	Nette\Application\UI\Form;

class PostPresenter extends BasePresenter {	

	public function renderShow($address) {
		$this->template->setFile( __DIR__ . '/templates/Post/showPost.latte'); 
		$post = $this->template->page = $this->database->table('pages')->where('address',$address)->fetch();
		$this['postForm']->setDefaults($post->toArray());
	}

	public function renderCategory() {
		$this->template->setFile( __DIR__ . '/templates/Post/showCategory.latte'); 
		$post = $this->template->page = $this->database->table('pages_category')->where('address',$address)->fetch();
	}

	protected function createComponentPostForm() {
		$form = new Form;
		$form->addText('address', 'Adresa:')
				->setRequired();
		$form->addText('title', 'Titulok:')
				->setRequired();
		$form->addTextArea('text', 'Obsah:')
				->setAttribute('class', 'materialize-textarea')
				->setRequired();

		$form->addSubmit('send', 'Uložit a publikovat')
				->setAttribute('class', 'waves-effect btn');

		$form->onSuccess[] = array($this, 'postFormSucceeded');

		return $form;
	}

	public function postFormSucceeded($form, $values) {
		$postId = $this->getParameter('postId');

		if ($postId) {
			$post = $this->database->table('pages')->get($postId);
			$post->update($values);
		} else {
			$post = $this->database->table('pages')->insert($values);
		}

		$this->flashMessage('Příspěvek byl úspěšně publikován.', 'success');
		$this->restoreRequest($this->mySession->backlink);
	}

}
