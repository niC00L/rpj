<?php

namespace App\Presenters;

use Nette,
	Nette\Application\UI\Form;

class PostPresenter extends BasePresenter {

	public function renderShow($address) {
		$this->template->setFile(__DIR__ . '/templates/Post/showPost.latte');
		$post = $this->template->post = $this->database->table('page')->where('address', $address)->fetch();
		$this['postForm']->setDefaults($post->toArray());
	}

	public function renderCategory($address) {
		$this->template->setFile(__DIR__ . '/templates/Post/showCategory.latte');
		$category = $this->template->category = $this->database->table('page_ctg')->where('address', $address)->fetch();
		$ctg_id = $postId = $this->database->table('page_ctg')->fetch()->id;
		$posts = $this->template->posts = $this->database->table('page')->where('ctg_id', $ctg_id)->fetchAll();
		$this['postForm']->setDefaults($category->toArray());
	}

	protected function createComponentPostForm() {
		$form = new Form;
		$form->addText('address', 'Adresa:')
				->setRequired();
		$form->addText('title', 'Titulok:')
				->setRequired();
		$form->addTextArea('description', 'Obsah:')
				->setAttribute('class', 'materialize-textarea');

		if ($this->getAction() == 'show') {
			$form->addTextArea('text', 'Obsah:')
					->setAttribute('class', 'materialize-textarea');
		}
		$form->addSubmit('send', 'Uložit a publikovat')
				->setAttribute('class', 'waves-effect btn');

		$form->onSuccess[] = array($this, 'postFormSucceeded');

		return $form;
	}

	public function postFormSucceeded($form, $values) {
		$address = $this->getParameter('address');
		if ($this->getAction() == 'show') {
			$table = 'page';
		} elseif ($this->getAction() == 'category') {
			$table = 'page_ctg';
		}
		$id = $this->database->table($table)->where('address', $address)->fetch()->id;
		if ($id) {
			$post = $this->database->table($table)->get($id);
			$post->update($values);
		} else {
			$post = $this->database->table($table)->insert($values);
		}
		$this->flashMessage('Uspesne publikovane.', 'success');
		$this->redirect('this', ['address' => $values['address']]);
	}

}
