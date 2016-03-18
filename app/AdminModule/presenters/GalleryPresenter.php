<?php

namespace App\AdminModule\Presenters;

use Nette;
use App\Model,
    Nette\Application\UI\Form;

class GalleryPresenter extends AdminPresenter {

    public function renderDefault() {
        $this->template->imgs = $this->database->table('imgs')->fetchAll();
    }

    public function renderAdd() {
        
    }

    public function createComponentAddForm() {
        $form = new Form;

        $form->addUpload('image_name', 'Obrazok:')
                ->setAttribute('class', 'form-control');
        $form->addText('title', 'Title:')
                ->setAttribute('class', 'form-control');
        $form->addTextarea('description', 'Popis:')
                ->setAttribute('class', 'form-control');
        $form->addText('alt', 'Alt:')
                ->setAttribute('class', 'form-control');
        $form->addSubmit('send', 'Ulozit')
                ->setAttribute('class', 'btn btn-primary');

        $form->onSuccess[] = array($this, 'addFormSuc');
        return $form;
    }

    public function addFormSuc($form, $values) {
        $image = $values['image_name'];
        unset($values['image_name']);
        $values['image_name'] = $image->name;
               
        $image->move('images/gallery/'.$image->name);
        $this->database->table('imgs')->insert($values);
        
        $this->flashMessage('Uspesne pridane');
        $this->redirect('Gallery:add');
    }

}
