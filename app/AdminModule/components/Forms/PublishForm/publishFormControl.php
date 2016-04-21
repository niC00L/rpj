<?php

namespace App\AdminModule\Components\Forms\PublishForm;

use Nette\Application\UI\Form;

class publishFormControl extends \App\AdminModule\Components\baseControl {

    public $table;
    public $id;
    
    public function setForms($id, $table) {
        $this->id = $id;
        $this->table = $table;
    }
    
    public function render() {
        $temp = $this->template;
        $temp->setFile(__DIR__ . '/publishFormDefault.latte');
        $temp->render();
    }    

    protected function createComponentPublishForm() {
        $form = new Form;
        $form->addSubmit('publish', 'Publikovať')
                ->setAttribute('class', 'btn');

        $form->onSuccess[] = array($this, 'publishFormSucceeded');
        return $form;
    }

    public function renewFormSucceeded($form, $values) {

        $address = $this->database->table($this->table)->where('id', $this->id)->fetch()->address;
        $this->database->table($this->table)->where('id', $this->id)->update(array('status' => 1));
        
        $in_menu = $this->database->table('ctrl_menu')->where('address', $address);
        
        if($in_menu) {
            $in_menu->update(array('status' => 0));
        }

        $this->presenter->flashMessage('Príspevok publikovaný.', 'success');
        $this->presenter->redirect('this', ['address' => $address]);
    }    
}
