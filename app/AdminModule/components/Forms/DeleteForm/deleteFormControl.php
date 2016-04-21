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
        $form->addSubmit('delete', 'Zmazať')
                ->setAttribute('class', 'btn');

        $form->onSuccess[] = array($this, 'deleteFormSucceeded');
        return $form;
    }

    public function deleteFormSucceeded($form, $values) {
        if (\Nette\Utils\Strings::startsWith($this->table, 'post')) {
            $address = $this->database->table($this->table)->where('id', $this->id)->fetch()->address;
            $this->database->table('ctrl_menu')->where('address', $address)->update(array('status' => 0));
        }
        $this->database->table($this->table)->where('id', $this->id)->update(array('status' => 0));        

        $this->presenter->flashMessage('Príspevok odstránený.', 'success');
        $this->presenter->redirect('this');
    }

}
