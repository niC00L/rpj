<?php

namespace App\Presenters;

use Nette,
    App\Model;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter {

    /** @var Nette\Database\Context */
    public $database;
    public $global;

    public function __construct(\Nette\Database\Context $database, \App\Model\GlobalSettings $global) {
        parent::__construct();
        $this->database = $database;
        $this->global = $global->getGlobal();
    }

    /** @var Nette\Http\SessionSection */
    public $mySession;

    protected function startup() {
        parent::startup();
        // zaciatok session
        $this->mySession = $this->session->getSection("mySession");
        $this->template->global = $this->global;       
        
        $components = $this->database->table('controls')->fetchAll();
        foreach ($components as $com) {
            $this->addComponent(new $com['namescape']($this->database), $com['component_name']);
        }
    }

    public function beforeRender() {
        parent::beforeRender();

        // ulozenie request
        $this->mySession->backlink = $this->storeRequest();
    }

}
