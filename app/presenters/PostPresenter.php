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
		$ctg_id = $this->database->table('page_ctg')->where('address', $address)->fetch()->id;

		$page_id = $this->database->table('page_ctg_sort')->group('page_id')->having($ctg_id)->where('ctg_id', $ctg_id)->fetchAll();
		$posts = $this->template->posts = $this->database->table('page')->where('id', $page_id)->fetchAll();

		$this['postForm']->setDefaults($category->toArray());
	}

	protected function createComponentPostForm() {
		
//		ak sa zobrazuje clanok vyberu sa data pre checkboxy s kategoriami
		if ($this->getAction() == 'show') {
			$address = $this->getParameter('address');
			$id = $this->database->table('page')->where('address', $address)->fetch()->id;

			$ctgs_in = $this->database->table('page_ctg_sort')->where('page_id', $id);

			$ctgs = $this->database->table('page_ctg')->fetchAll();
		}
		
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

//			vykreslia sa checkboxy
			foreach ($ctgs as $ctg) {
				$form->addCheckbox('category_' . $ctg['id'], $ctg['title']);
			}
			
//			pouzitym sa prida hodnota true
			foreach ($ctgs_in as $ctg_in) {
				$form->setValues(['category_' . $ctg_in->ctg_id => true]);
			}
		}

		$form->addSubmit('send', 'Uložit a publikovat')
				->setAttribute('class', 'btn');

		$form->onSuccess[] = array($this, 'postFormSucceeded');
		return $form;
	}

	public function postFormSucceeded($form, $values) {
		
		if ($this->getAction() == 'show') {
			$table = 'page';
		} elseif ($this->getAction() == 'category') {
			$table = 'page_ctg';
		}

		$address = $this->getParameter('address');
		$id = $this->database->table($table)->where('address', $address)->fetch()->id;

//		Vyradi checkboxy z values aby sa nezapisovali do tabulky pages
		$ctg_sort = array();
		foreach ($values as $key => $value) {
			if (Nette\Utils\Strings::startsWith($key, 'category_')) {
//				ak je checkbox zaskrtnuty vlozi jeho hodnotu do pola $ctg_sort
				if ($values[$key] == true) {
//				do pola sa vlozia len cisla
					array_push($ctg_sort, substr($key, -1));
				}
				unset($values[$key]);
			}
		}

//		zapisanie clankov a kategorii
		$this->database->table('page_ctg_sort')->where('page_id', $id)->delete();
		foreach ($ctg_sort as $ctg_id) {
			$this->database->table('page_ctg_sort')->insert(array(
				'page_id' => $id,
				'ctg_id' => $ctg_id
			));
		}
		
//		zapisanie/pridanie obsahu stranky
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
