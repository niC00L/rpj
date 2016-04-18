<?php

namespace App\Presenters;

use Nette,
    App\Model;

class HomepagePresenter extends BasePresenter {

    public function actionDefault() {
        $this->addComponent(new \App\AdminModule\Components\LoadControls\loadControl($this->database, $this->global, 'homepage-default-content'), 'HomepageContent');
        $this->template->pages = $this->database->table('post')
                ->order('create_date DESC');
    }
    
    public function actionContact() {
        $this->addComponent(new \App\AdminModule\Components\LoadControls\loadControl($this->database, $this->global, 'homepage-contact-content'), 'ContactContent');
    }
}
