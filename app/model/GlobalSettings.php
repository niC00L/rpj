<?php
namespace App\Model;

class GlobalSettings {
    //Nacitavanie global settings
    public function __construct(\Nette\Database\Context $database) {
        $this->database = $database;
    }
    
    public function getGlobal() {
        return $this->database->table('global_settings')->fetchPairs('setting_name', 'value');
    }
    
    public function getSetting($name) {
        return $this->database->table('global_settings')->where('setting_name', $name)->fetch()->value;
    }
}
