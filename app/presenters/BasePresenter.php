<?php

namespace App\Presenters;

use Nette,
	App\Model;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter {

	/** @var Nette\Database\Context */
	protected $database;

	public function __construct(\Nette\Database\Context $database) {
		parent::__construct();
		$this->database = $database;
	}
	
	/** @var Nette\Http\SessionSection */
	public $mySession;
	
	protected function startup() {
		parent::startup();
		// zaciatok session
		$this->mySession = $this->session->getSection("mySession");
	}

	public function createComponentMenu() {
		return new \App\Components\Menu\MenuControl($this->database);
	}

	public function beforeRender() {
		parent::beforeRender();

		// ulozenie request
		$this->mySession->backlink = $this->storeRequest();
	}

}
