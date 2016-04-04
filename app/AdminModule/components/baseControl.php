<?php

namespace App\AdminModule\Components;

//use Nette\Application\UI\Control;

class baseControl extends \Nette\Application\UI\Control {

    /** @var Nette\Database\Context */
    public $database;
    public $global;

    public function __construct(\Nette\Database\Context $database, \App\Model\GlobalSettings $global) {
        parent::__construct();
        $this->database = $database;
        $this->global = $global->getGlobal();
    }
    
}
