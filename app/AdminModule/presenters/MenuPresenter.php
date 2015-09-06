<?php

namespace App\AdminModule\Presenters;

use Nette,
    Nette\Application\UI\Form;

class MenuPresenter extends AdminPresenter {

    public function renderEditMenu() {
//        $this->template->setFile(__DIR__ . '/templates/editMenu.latte'); 
        $menu = $this->template->menu = $this->database->table('ctrl_menu')->order('order')->fetchAll();
//        premenne pre autocomplete
        $this->template->posts = $this->database->table('post')->select('address, title')->fetchAll();
        $this->template->post_ctgs = $this->database->table('post_ctg')->select('address, title')->fetchAll();

        $this['menuForm']->setDefaults($menu);
        $this['menuFormDelete']->setDefaults($menu);
    }

    public function renderAddToMenu() {
//        $this->template->setFile('../../templates/Admin/addToMenu.latte');
//        premenne pre autocomplete
        $this->template->posts = $this->database->table('post')->select('address, title')->fetchAll();
        $this->template->post_ctgs = $this->database->table('post_ctg')->select('address, title')->fetchAll();
    }

    protected function createComponentMenuForm() {
        $form = new Form;

        $type = array(
            'Homepage' => 'Domov',
            'Post:show' => 'Clanok',
            'Post:category' => 'Kategoria clankov',
            'Sign' => 'Prihlasenie/Odhlasenie'
        );

        $form->addHidden('id', 'Id:');
        $form->addText('order', 'Poradie:')
                ->setRequired();
        $form->addSelect('type', 'Typ:', $type)
                ->setAttribute('class', 'browser-default')
                ->setPrompt('Zvolte typ')
                ->setRequired();
        $form->addText('address','Clanok:');
        $form->addText('title', 'Titulok:')
                ->setRequired();
        $form->addText('class', 'Class:');
        $form->addSubmit('send', 'Uložit a publikovat')
                ->setAttribute('class', 'btn');

        $form->onSuccess[] = array($this, 'menuFormSucceeded');
        return $form;
    }

    protected function createComponentMenuFormDelete() {
        $form = new Form;
        $form->addHidden('id', 'Id:');
        $form->addSubmit('delete', 'Odstranit')
                ->setAttribute('class', 'btn');
        $form->onSuccess[] = array($this, 'menuFormDeleteSucceeded');
        return $form;
    }

    public function menuFormSucceeded($form, $values) {
        $id = $values['id'];
        unset($values['id']);

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
        $this->redirect('Admin:editMenu');
    }

}
