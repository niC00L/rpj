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
        $this->global = $global;
    }

    /** @var Nette\Http\SessionSection */
    public $mySession;

    protected function startup() {
        parent::startup();
        $this->addComponent(new \App\AdminModule\Components\LoadControls\loadControl($this->database, $this->global, 'layout-footer'), 'LayoutFooter');
        
        // zaciatok session
        $this->mySession = $this->session->getSection("mySession");
        $this->template->global = $this->global->getGlobal();

        $components = $this->database->table('controls')->where('status', 2)->fetchAll();
        $added = array();
        foreach ($components as $com) {
            if (!in_array($com['component_name'], $added)) {
                $this->addComponent(new $com['namespace']($this->database, $this->global), $com['component_name']);
                array_push($added, $com['component_name']);
            }
        }
    }

    public function beforeRender() {
        parent::beforeRender();

        // ulozenie request
        $this->mySession->backlink = $this->storeRequest();
    }

}
