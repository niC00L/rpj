<?php

namespace App\AdminModule\Presenters;

use Nette,
    Nette\Application\UI\Form,
    Nette\Application\UI\Multiplier;

class MenuPresenter extends AdminPresenter {

    public $address;

    public function startup() {
        parent::startup();

//        premenne pre templaty
        $posts = $this->template->posts = $this->database->table('post')->where('status ? OR status ?', 1, 2)->fetchPairs('address', 'title');
        $post_ctgs = $this->template->post_ctgs = $this->database->table('post_ctg')->where('status ? OR status ?', 1, 2)->fetchPairs('address', 'title');

//        premenne pre select
        $this->address = array_merge($posts, $post_ctgs);
    }

    public function renderEditMenu() {
        $menu = $this->template->menu = $this->database->table('ctrl_menu')->order('order')->fetchAll();
        $menuItems = array();
        foreach ($menu as $values) {
            $menuItem = $values->toArray();
            if ($menuItem['action']) {
                $menuItem['type'] = $menuItem['type'] . '_' . $menuItem['action'];
                unset($menuItem['action']);
            }
            $menuItems[] = $menuItem;
        }
        foreach ($menuItems as $formValue) {
            $this['menuForm'][$formValue['id']]->setDefaults($formValue);
            $this['menuFormDelete'][$formValue['id']]->setDefaults($formValue);
        }
    }

    public function renderAddToMenu() {
        
    }

    protected function createComponentMenuForm() {
        return new Multiplier(function ($itemId) {
            $type = array(
                'Homepage' => 'Domov',
                'Post_show' => 'Clanok',
                'Post_category' => 'Kategoria clankov',
                'Sign' => 'Prihlasenie/Odhlasenie'
            );

            $form = new Form;
            $form->addHidden('id', 'Id:');
            $form->addText('order', 'Poradie:')
                    ->setRequired();
            $form->addSelect('type', 'Typ:', $type)
                    ->setAttribute('class', 'browser-default')
                    ->setPrompt('Zvolte typ')
                    ->setRequired();
            $form->addSelect('address', 'Clanok:', $this->address)
                    ->setPrompt('Vyberte')
                    ->setAttribute('class', 'browser-default');
            $form->addText('title', 'Titulok:')
                    ->setRequired();
            $form->addText('class', 'Class:');
            $form->addSubmit('send', 'Uložit a publikovat')
                    ->setAttribute('class', 'btn');

            $form->onSuccess[] = array($this, 'menuFormSucceeded');
            return $form;
        });
    }

    protected function createComponentMenuFormDelete() {
        return new Multiplier(function ($itemId) {
            $form = new Form;
            $form->addHidden('id', 'Id:');
            $form->addSubmit('delete', 'Odstranit')
                    ->setAttribute('class', 'btn');
            $form->onSuccess[] = array($this, 'menuFormDeleteSucceeded');
            return $form;
        });
    }

    public function menuFormSucceeded($form, $values) {
        $id = $values['id'];
        unset($values['id']);

        $type = explode("_", $values['type']);
        unset($values['type']);
        $values['type'] = $type[0];
        if (count($type)>1) {
            $values['action'] = $type[1];
        }

        if ($this->getAction() == 'editMenu') {
            $this->database->table('ctrl_menu')->where('id', $id)->update($values);
        } elseif ($this->getAction() == 'addToMenu') {
            $this->database->table('ctrl_menu')->insert($values);
        }

        $this->flashMessage('Uspesne publikovane.', 'success');
        $this->redirect('this', ['address' => $values['address']]);
    }

    public function menuFormDeleteSucceeded($form, $values) {
        $id = $values['id'];
        $this->database->table('ctrl_menu')->where('id', $id)->delete();

        $this->flashMessage('Polozka odstranena.', 'success');
        $this->redirect('Menu:editMenu');
    }

}
