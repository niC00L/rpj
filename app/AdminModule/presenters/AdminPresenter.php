<?php

namespace App\AdminModule\Presenters;

use Nette,
    App\Model,
    Nette\Application\UI\Form,
    Nette\Application\UI\Multiplier;

class AdminPresenter extends \App\Presenters\BasePresenter {

    public function renderDefault() {
        
    }

    public function renderGlobal() {
        $defaults = $this->template->globals = $this->database->table('global_settings')->where('editable', 1)->fetchAll();
        foreach ($defaults as $def) {
            $this['globalForm'][$def['id']]->setDefaults($def);
        }
    }

    public function createComponentGlobalForm() {
        return new Multiplier(function ($itemId) {
            $form = new Form;
            $form->addHidden('id');
            $form->addText('value');
            $form->addSubmit('delete', 'Upravit')
                    ->setAttribute('class', 'btn');
            $form->onSuccess[] = array($this, 'menuFormDeleteSucceeded');
            return $form;
        });
    }
    
    public function menuFormDeleteSucceeded($form, $values) {
        $this->database->table('global_settings')->where('id', $values['id'])->update($values);

        $this->flashMessage('Polozka upravena.', 'success');
        $this->redirect('Admin:global');
    }
}
