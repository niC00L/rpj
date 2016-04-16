<?php

namespace App\Components\Banner;

use Nette\Application\UI\Control,
    Nette\Application\UI\Form;

class BannerControl extends \App\AdminModule\Components\baseControl {
    
    public function getIds($id) {
        $temp_id = $this->database->table('controls')->where('id', $id)->fetch()->template;
        $template = $this->database->table('site_templates')->where('id', $temp_id)->fetch()->file_name;
        return $template;
    }

    public function renderDefault($id) {  
        $this->template->id = $id;
        $template = $this->getIds($id);
        $this->template->images = $images = $this->database->table('banners')->where('banner_id', $id)->fetchAll();
        $this->template->setFile(__DIR__ . '/' . $template . '.latte');
        $this->template->render();
    }
    
    public function renderEdit($position) {
        
    }

    public function createComponentAddImageForm() {        
        $form = new Form;

        $form->onSuccess[] = array($this, 'addImageFormSuc');
        return $form;
    }

    public function addCommentFormSuc($form, $values) {
        $values[$this->table . '_id'] = $this->id;
        if ($this->presenter->user->isLoggedIn()) {
            $values['user_id'] = $this->presenter->getUser()->getId();
        }
        $this->database->table('comments')->insert($values);

        $this->presenter->flashMessage('Obrázok pridaný.', 'success');
        $this->presenter->redirect('this');
    }

}
