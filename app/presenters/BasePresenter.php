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

    public function __construct(\Nette\Database\Context $database) {
        parent::__construct();
        $this->database = $database;
    }

    /** @var Nette\Http\SessionSection */
    public $mySession;
    public $global;

    protected function startup() {
        parent::startup();
        // zaciatok session
        $this->mySession = $this->session->getSection("mySession");
        $this->template->global = $this->database->table('global_settings')->fetchPairs('setting_name', 'value');
        
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
