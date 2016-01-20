<?php

namespace App\AdminModule\Presenters;

use Nette,
    App\Model,
    Nette\Application\UI\Form,
    Nette\Application\UI\Multiplier,
    Nette\Utils\Strings;

class AdminPresenter extends \App\Presenters\BasePresenter {

    public function renderDefault() {
        
    }

    public function renderGlobal() {
        $defaults = $this->database->table('global_settings')->where('editable', 1)->fetchAll();
        $comments = array();
        $other = array();
        foreach ($defaults as $def) {
            if (Strings::startsWith($def->setting_name, 'comment_')) {
                $comments[$def->id] = $def;
            } else {
                $other[$def->id] = $def;                
            }
        }
        foreach($defaults as $d) {
            $this['globalForm'][$d['id']]->setDefaults($d);
        }
        $this->template->globals = $other;
        $this->template->comments = $comments;
    }

    public function createComponentGlobalForm() {
        return new Multiplier(function ($itemId) {
            $form = new Form;
            $form->addHidden('id');
            if ($itemId >= 8 && $itemId <= 10) {
                $form->addCheckbox('value');
            } else {
                $form->addText('value');
            }
            $form->addSubmit('edit', 'Upravit')
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
