<?php

namespace App\AdminModule\Presenters;

use Nette;
use App\Model,
    Nette\Application\UI\Form;

class GalleryPresenter extends AdminPresenter {
    private $gallery_id;
    
    public function startup() {
        if ($this->getUser()->getRoles()[0] == 'banned') {
            $this->flashMessage('Máte ban');
            $this->redirect('Admin:default');
        } elseif (!$this->getUser()->isAllowed('gallery', 'edit')) {
            $this->flashMessage('Nemáte prístup k tejto stránke');
            $this->redirect('Admin:default');
        }
    }

    public function renderDefault() {
        $this->template->imgs = $this->database->table('imgs')->fetchAll();
    }

    public function actionAdd($id) {
        $this->gallery_id = $id;
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
               
        $image->move('images/gallery/'.$this->gallery_id.'/'.$image->name);
        $image = $this->database->table('imgs')->insert($values);
        
        $this->database->table('img_sort')->insert(array(
            'gallery_id' => $this->gallery_id,
            'img_id' => $image['id']
        ));
        
        $this->flashMessage('Uspesne pridane');
        $this->redirect('Gallery:add');
    }
}
