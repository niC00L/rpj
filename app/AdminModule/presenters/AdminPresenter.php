<?php

namespace App\AdminModule\Presenters;

use Nette,
    App\Model,
    Nette\Application\UI\Form,
    Nette\Application\UI\Multiplier,
    Nette\Utils\Strings;

class AdminPresenter extends \App\Presenters\BasePresenter {

    public $userData;
    
    public function startup() {
        parent::startup();
        if (!$this->getUser()->isLoggedIn()) {
            $this->flashMessage('Na prístup k tejto stránke sa musíte prihlásiť');
            $this->redirect(':Sign:in');
        }
        $this->userData = $this->database->table('users')->where('id', $this->getUser()->getId())->fetch();
        $this->template->userData = $this->userData;
    }

    public function renderDefault() {
        
    }

//    public function setForms($table, $columns, $filter) {
//        
//    }

    public function renderGlobal() {
        if (!$this->getUser()->isAllowed('global', 'edit')) {
            $this->flashMessage('Nemáte právo na prístup k tejto stránke');
            $this->redirect('Admin:default');
        }
        $def = array(
            'description' => array('description', 'Nastavenie'),
            'value' => array('value', 'Hodnota'),
        );
//        $this['gridForm']->setForms('global_settings', $def, 'id <> 0');

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
        foreach ($defaults as $d) {
            $this['globalForm'][$d['id']]->setDefaults($d);
        }
        $this->template->globals = $other;
        $this->template->comments = $comments;
    }

    public function createComponentGlobalForm() {
        return new Multiplier(function ($itemId) {
            $form = new Form;
            $form->addHidden('id');
            if ($itemId >= 8 && $itemId <= 11) {
                $form->addCheckbox('value');
            } else {
                $form->addText('value');
            }
            $form->addSubmit('edit', 'Upraviť')
                    ->setAttribute('class', 'btn');
            $form->onSuccess[] = array($this, 'menuFormDeleteSucceeded');
            return $form;
        });
    }

    public function menuFormDeleteSucceeded($form, $values) {
        $this->database->table('global_settings')->where('id', $values['id'])->update($values);

        $this->flashMessage('Položka upravená.', 'success');
        $this->redirect('Admin:global');
    }

}
