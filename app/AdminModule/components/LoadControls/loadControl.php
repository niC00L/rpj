<?php

namespace App\AdminModule\Components\LoadControls;

use Nette\Application\UI\Form,
    Nette\Application\UI\Multiplier;

class loadControl extends \Nette\Application\UI\Control {

    public $database;
    public $global;

    public function __construct(\Nette\Database\Context $database, \App\Model\GlobalSettings $global) {
        parent::__construct();
        $this->database = $database;
        $this->global = $global;
    }

    public function loadControls($position) {
        $components = $this->database->table('controls')->where('status', 2)->where('position', $position)->fetchAll();
        $added = array();
        foreach ($components as $com) {
            if (!in_array($com['component_name'], $added)) {
                $this->addComponent(new $com['namespace']($this->database, $this->global), $com['component_name']);
                array_push($added, $com['component_name']);
            }
        }
        return $components;
    }

    public function render($position) {
        $this->template->controls = $controls = $this->loadControls($position);
        $this->template->position = $position;
        $this->template->setFile(__DIR__ . '/default.latte');
        $this->template->render();
    }

    public function createComponentAddControlForm() {
        $form = new Form;
        $controls = array(
            'banner' => 'Banner',
            'menu' => 'Menu',
            'textBlock' => 'Blok textu',
            'search' => 'Vyhľadávanie',
        );

        $templates = array();
        foreach ($controls as $c => $a) {
            $templates[$c] = $this->database->table('site_templates')->where('type', $c)->fetchPairs('id', 'title');
        }

        $form->addSelect('component_name', 'Vyberte komponentu', $controls)
                ->setRequired('Musíte vybrať komponentu');
        $form->addSelect('template', 'Vyberte šablónu', $templates)
                ->setRequired('Musíte vybrať šablónu');
        $form->addText('title', 'Titulok');
        $form->addTextArea('description', 'Popis')
                ->setAttribute('class', 'materialize-textarea');
        $form->addHidden('position', 'Position');

        $form->addSubmit('submit', 'Pridať')
                ->setAttribute('class', 'btn');

        $form->onSuccess[] = array($this, 'addControlSuccess');
        return $form;
    }

    public function addControlSuccess($form, $values) {
        $namespace = array(
            'banner' => '\App\Components\Banner\BannerControl',
            'menu' => '\App\Components\Menu\MenuControl',
            'textBlock' => '\App\Components\TextBlock\TextBlockControl',
            'search' => '\App\Components\Search\SearchControl',
        );
        $values['namespace'] = $namespace[$values['component_name']];
        $values['status'] = 2;
        $values['editable'] = 1;
        $added = $this->database->table('controls')->insert($values);
        $values['namespace']::defaultValue($this->database, $added['id']);
        $this->presenter->flashMessage('Komponenta pridaná');
        $this->presenter->redirect('this');
        //TODO: Redirect na edit komponenty
    }

    public function createComponentDeleteControl() {
        return new Multiplier(function ($id) {
            $form = new Form;
            $form->addHidden('id', $id);
            $form->addSubmit('delete', 'Zmazať komponentu')
                    ->setAttribute('class', 'btn');

            $form->onSuccess[] = array($this, 'deleteControlSuc');
            return $form;
        });
    }

    public function deleteControlSuc($form, $values) {
        $this->database->table('controls')->where('id', $values['id'])->update(array('status' => 0));
        $this->presenter->flashMessage('Komponenta odstránená');
        $this->presenter->redirect('this');
    }

}
