<?php

namespace App\Components\Gallery;

use Nette\Application\UI\Control,
    Nette\Application\UI\Form;

class GalleryControl extends \App\AdminModule\Components\baseControl {
    
    static function defaultValue($db, $id) {        
    }
    
    public function setForms($defaults) {
        $this['addGallery']->setDefaults($defaults);
    }
    
    public function getIds($id) {
        $temp_id = $this->database->table('controls')->where('id', $id)->fetch()->template;
        $template = $this->database->table('site_templates')->where('id', $temp_id)->fetch()->file_name;
        return $template;
    }

    public function renderDefault($id) {  
        $this->template->id = $id;        
        $template = $this->getIds($id);
        $this->template->images = $images = $this->database->table('ctrl_gallery')->where('gallery_id', $id)->fetchAll();
        $this->template->setFile(__DIR__ . '/' . $template . '.latte');
        $this->template->render();
    }
    
    public function renderAdd() {
        $this->template->setFile(__DIR__ . '/galleryAdd.latte');
        $this->template->render();
    }
    
    public function createComponentAddGallery() {
        $form = new Form;
        $form->addHidden('post_id');
        $form->addSubmit('submit', 'Pridať galériu')
                ->setAttribute('class', 'btn');
        $form->onSuccess[] = array($this, 'addGallerySuccess');
        return $form;
    }
    
    public function addGallerySuccess($form, $values) {
        $gallery = array(
            'component_name' => 'gallery',
            'namespace' => '\App\Components\Gallery\GalleryControl',
            'status' => 1,
            'editable' => 1,
            'template' => 13,
        );
        $u = $this->database->table('controls')->insert($gallery);
        if ($values['post_id']) {
            $this->database->table('post')->where('id', $values['post_id'])->update(array('gallery_id' => $u['id']));
        }
        $this->presenter->flashMessage('Galéria pridaná');
        $this->presenter->redirect('this');
    }
}
