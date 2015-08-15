<?php

namespace App\Presenters\Components\Menu;

use Nette\Application\UI\Control;

class MenuControl extends Control {

	/** @var Nette\Database\Context */
	private $database;
	
	public function __construct(\Nette\Database\Context $database) {
		parent::__construct();
		$this->database = $database;
	}
	
	public function render() {
		$template = $this->template;
		$template->setFile(__DIR__ . '/menuDefault.latte');
		$pages = $this->database->table('pages');
		$categories = $this->database->table('pages_category');
		$template->render();
	}

}
