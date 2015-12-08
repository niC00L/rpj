<?php

namespace App\AdminModule\Components;

//use Nette\Application\UI\Control;

class baseControl extends \Nette\Application\UI\Control {

    /** @var Nette\Database\Context */
    public $database;

    public function __construct(\Nette\Database\Context $database) {
        parent::__construct();
        $this->database = $database;
    }

}
