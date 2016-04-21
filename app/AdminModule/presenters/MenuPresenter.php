<?php

namespace App\AdminModule\Presenters;

use Nette,
    Nette\Application\UI\Form,
    Nette\Application\UI\Multiplier;

class MenuPresenter extends AdminPresenter {

    public $address;

    public function startup() {
        parent::startup();

        if ($this->getUser()->getRoles()[0] == 'banned') {
            $this->flashMessage('Máte ban');
            $this->redirect('Admin:default');
        } elseif (!$this->getUser()->isAllowed('components', 'edit')) {
            $this->flashMessage('Nemáte prístup k tejto stránke');
            $this->redirect('Admin:default');
        }

//        premenne pre templaty
        $posts = $this->template->posts = $this->database->table('post')->where('status ? OR status ?', 1, 2)->fetchPairs('address', 'title');
        $post_ctgs = $this->template->post_ctgs = $this->database->table('post_ctg')->where('status ? OR status ?', 1, 2)->fetchPairs('address', 'title');

//        premenne pre select
        $this->address = array_merge($posts, $post_ctgs);
    }

    public function setForms($type, $id, $table, $defaults) {
        
    }

    public function actionEditMenu($id) {
        $this->template->id = $id;
        $menuI = $this->template->menuItems = $this->database->table('ctrl_menu')->order('order')->where('menu_id', $id)->fetchAll();
        $this->template->menu = $menu = $this->database->table('controls')->where('id', $id)->fetch();
        $menuItems = array();
        foreach ($menuI as $values) {
            $menuItem = $values->toArray();
            if ($menuItem['action']) {
                $menuItem['type'] = $menuItem['type'] . '_' . $menuItem['action'];
                unset($menuItem['action']);
            }
            $menuItems[] = $menuItem;
        }
        array_push($menuItems, array('id' => 0, 'menu_id' => $id));
        foreach ($menuItems as $formValue) {
            $this['menuForm'][$formValue['id']]->setDefaults($formValue);
            $this['menuFormDelete'][$formValue['id']]->setDefaults($formValue);
        }
        $this['deleteForm']->setForms($id, 'controls');
        $this['renewForm']->setForms($id, 'controls');
        $ignore = array('component_name', 'namespace', 'position');
        if($menu['position'] == 'layout-menu') {
            array_push($ignore, 'template');
        }
        $this['editForm']->setForms($menu['id'], 'controls', $menu, $ignore);
    }

    protected function createComponentMenuForm() {
        return new Multiplier(function ($itemId) {
            $type = array(
                'Homepage' => 'Domov',
                'Homepage_contact' => 'Kontakt',
                'Post_show' => 'Článok',
                'Post_category' => 'Kategória článkov',
                'Sign' => 'Prihlásenie/Odhlásenie',
                'external' => 'Extérny odkaz',
            );

            $form = new Form;
            $form->addHidden('id', 'Id:');
            $form->addHidden('menu_id', 'MenuId:');
            $form->addText('order', 'Poradie:')
                    ->setRequired();
            $form->addSelect('type', '', $type)
                    ->setPrompt('Zvoľte typ')
                    ->setAttribute('class', 'browser-default')
                    ->setRequired();
            $form->addSelect('address', '', $this->address)
                    ->setAttribute('class', 'browser-default')
                    ->setPrompt('Vyberte');
            $form->addText('ext_address', 'Adresa');
            $form->addText('title', 'Titulok:')
                    ->setRequired();
            $form->addText('class', 'Class:');
            $form->addSubmit('send', 'Uložiť')
                    ->setAttribute('class', 'btn');

            $form->onSuccess[] = array($this, 'menuFormSucceeded');
            return $form;
        });
    }

    protected function createComponentMenuFormDelete() {
        return new Multiplier(function ($itemId) {
            $form = new Form;
            $form->addHidden('id', 'Id:');
            $form->addSubmit('delete', 'Odstrániť')
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
        if (count($type) > 1) {
            $values['action'] = $type[1];
        }

        if ($form->getName() == 0) {
            $this->database->table('ctrl_menu')->insert($values);
        } else {
            $this->database->table('ctrl_menu')->where('id', $id)->update($values);
        }

        $this->flashMessage('Úspešne publikované', 'success');
        $this->redirect('this');
    }

    public function menuFormDeleteSucceeded($form, $values) {
        $id = $values['id'];
        $this->database->table('ctrl_menu')->where('id', $id)->delete();

        $this->flashMessage('Položka odstránená', 'success');
        $this->redirect('this');
    }

}
