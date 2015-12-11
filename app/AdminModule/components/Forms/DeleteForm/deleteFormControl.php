<?php

namespace App\AdminModule\Components\Forms\DeleteForm;

use Nette\Application\UI\Form;

class deleteFormControl extends \App\AdminModule\Components\baseControl {

    public $table;
    public $id;

    public function setForms($id, $table) {
        $this->id = $id;
        $this->table = $table;
    }
    
    public function render() {
        $temp = $this->template;
        $temp->setFile(__DIR__ . '/deleteFormDefault.latte');
        $temp->render();
    }    

    protected function createComponentDeleteForm() {
        $form = new Form;
        $form->addSubmit('delete', 'Delete')
                ->setAttribute('class', 'btn');

        $form->onSuccess[] = array($this, 'deleteFormSucceeded');
        return $form;
    }

    public function deleteFormSucceeded($form, $values) {

        $address = $this->database->table($this->table)->where('id', $this->id)->fetch()->address;
        $this->database->table($this->table)->where('id', $this->id)->update(array('status' => 0));

        $this->database->table('ctrl_menu')->where('address', $address)->update(array('status' => 0));

        $this->presenter->flashMessage('Prispevok odstraneny.', 'success');
        $this->presenter->redirect('Homepage:');
    }

}
