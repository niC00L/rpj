<?php
	namespace App\Presenters;

	use Nette,
		App\Model;

	
	class HomepagePresenter extends BasePresenter {

		public function renderDefault() {
		$this->template->pages = $this->database->table('pages')
			->order('create_date DESC');
		}
	}

