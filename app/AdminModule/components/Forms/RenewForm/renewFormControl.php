<?php

namespace App\AdminModule\Components\Forms\RenewForm;

use Nette\Application\UI\Form;

class renewFormControl extends \App\AdminModule\Components\baseControl {

    public $table;
    public $id;

    public function setForms($id, $table) {
        $this->id = $id;
        $this->table = $table;
    }

    public function render() {
        $temp = $this->template;
        $temp->setFile(__DIR__ . '/renewFormDefault.latte');
        $temp->render();
    }

    protected function createComponentRenewForm() {
        $form = new Form;
        $form->addSubmit('renew', 'Obnoviť')
                ->setAttribute('class', 'btn');

        $form->onSuccess[] = array($this, 'renewFormSucceeded');
        return $form;
    }

    public function renewFormSucceeded($form, $values) {
        if (\Nette\Utils\Strings::startsWith($this->table, 'post')) {
            $address = $this->database->table($this->table)->where('id', $this->id)->fetch()->address;
            $in_menu = $this->database->table('ctrl_menu')->where('address', $address);
            if ($in_menu) {
                $in_menu->update(array('status' => 0));
            }
        }
        $this->database->table($this->table)->where('id', $this->id)->update(array('status' => 1));

        $this->presenter->flashMessage('Príspevok obnovený.', 'success');
        $this->presenter->redirect('this');
    }

}
