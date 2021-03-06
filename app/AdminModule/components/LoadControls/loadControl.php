<?php

namespace App\AdminModule\Components\LoadControls;

use Nette\Application\UI\Form,
    Nette\Application\UI\Multiplier;

class loadControl extends \Nette\Application\UI\Control {

    public $database;
    public $global;
    protected $position;

    public function __construct(\Nette\Database\Context $database, \App\Model\GlobalSettings $global, $position) {
        parent::__construct();
        $this->database = $database;
        $this->global = $global;
        $this->position = $position;

        $components = $this->database->table('controls')->where('status', 1)->order('order')->where('position', $position)->fetchAll();
        $added = array();
        foreach ($components as $com) {
            if (!in_array($com['component_name'], $added)) {
                $this->addComponent(new $com['namespace']($this->database, $this->global), $com['component_name']);
                array_push($added, $com['component_name']);
            }
        }
    }

    public function loadControls($position) {
        $components = $this->database->table('controls')->where('status', 1)->order('order')->where('position', $this->position)->fetchAll();
//        $added = array();
//        foreach ($components as $com) {
//            if (!in_array($com['component_name'], $added)) {
//                $this->addComponent(new $com['namespace']($this->database, $this->global), $com['component_name']);
//                array_push($added, $com['component_name']);
//            }
//        }
        return $components;
    }

    public function render() {
        $this->template->controls = $controls = $this->loadControls($this->position);
        $this->template->position = $this->position;
        $this->template->setFile(__DIR__ . '/default.latte');
        $this->template->global = $this->global->getGlobal();
		$this->template->render();
    }

    public function createComponentAddControlForm() {
        $form = new Form;
        $controls = array(
            'banner' => 'Banner',
            'menu' => 'Menu',
            'textBlock' => 'Blok textu',
//            'search' => 'Vyhľadávanie',
        );

        $templates = array();
        foreach ($controls as $c => $a) {
            $templates[$c] = $this->database->table('site_templates')->where('type', $c)->where('status', 1)->fetchPairs('id', 'title');
        }

        $form->addSelect('component_name', 'Komponenta', $controls)
                ->setAttribute('class', 'browser-default')
                ->setRequired('Musíte vybrať komponentu');
        $form->addSelect('template', 'Šablóna', $templates)
                ->setAttribute('class', 'browser-default')
                ->setRequired('Musíte vybrať šablónu');
		$form->addText('order', 'Poradie')
				->addRule(Form::INTEGER, 'Poradie musí byť číslo');
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
        $values['status'] = 1;
        $values['editable'] = 1;
        $added = $this->database->table('controls')->insert($values);
        if ($values['component_name'] != 'search') {
            $values['namespace']::defaultValue($this->database, $added['id']);
        }
        $this->presenter->flashMessage('Komponenta pridaná');
        $this->presenter->redirect('this');
        //TODO: Redirect na edit komponenty
    }

    public function createComponentDeleteControl() {
        return new Multiplier(function ($id) {
            $form = new Form;
            $form->addHidden('id', $id);
            $form->addSubmit('delete', 'Zmazať komponentu')
                    ->setAttribute('class', 'btn control-action');

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
