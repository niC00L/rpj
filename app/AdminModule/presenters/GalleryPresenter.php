<?php

namespace App\AdminModule\Presenters;

use Nette;
use App\Model,
    Nette\Application\UI\Form;

class GalleryPresenter extends AdminPresenter {
    private $gallery_id;
    
    public function startup() {
        parent::startup();
        if ($this->getUser()->getRoles()[0] == 'banned') {
            $this->flashMessage('Máte ban');
            $this->redirect('Admin:default');
        } elseif (!$this->getUser()->isAllowed('gallery', 'edit')) {
            $this->flashMessage('Nemáte prístup k tejto stránke');
            $this->redirect('Admin:default');
        }
    }
    
    public function setForms($type, $id, $table, $defaults) {
    }

    public function actionDefault($id) {
        $this->template->imgs = $this->database->table('imgs')->where('gallery_id', $id)->fetchAll();
    }
    
    public function actionBanner($id) {
        $this->template->id = $id;
        $this->template->banner = $banner = $this->database->table('controls')->where('id', $id)->fetch();
        $imgs = $this->template->imgs = $this->database->table('ctrl_banner')->where('banner_id', $id)->fetchAll();
        $this['multiplierForm']->setForms('banner', $id, 'ctrl_banner', $imgs);
        $this['deleteForm']->setForms($id, 'controls');
        $this['renewForm']->setForms($id, 'controls');
        $this['editForm']->setForms($banner['id'], 'controls', $banner, array('component_name', 'namespace', 'position'));
    }
    
    public function actionGallery($id) {
        $this->template->id = $id;
        $this->template->gallery = $gallery = $this->database->table('controls')->where('id', $id)->fetch();
        $imgs = $this->template->imgs = $this->database->table('ctrl_gallery')->where('gallery_id', $id)->fetchAll();
        $this['multiplierForm']->setForms('gallery', $id, 'ctrl_gallery', $imgs);
        $this['deleteForm']->setForms($id, 'controls');
        $this['renewForm']->setForms($id, 'controls');
        $this['editForm']->setForms($gallery['id'], 'controls', $gallery, array('component_name', 'namespace', 'position'));
    }

    public function actionAdd($id) {
        $this->gallery_id = $id;
    }

    public function createComponentAddForm() {
        $form = new Form;

        $form->addUpload('image_name');
        $form->addText('title', 'Title:');
        $form->addTextarea('description', 'Popis:')
                ->setAttribute('class', 'materialize-textarea');
        $form->addText('alt', 'Alt:')
                ->setRequired('Toto pole je povinné');
        $form->addSubmit('send', 'Ulozit')
                ->setAttribute('class', 'btn');

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
