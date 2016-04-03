<?php

namespace App\Components\Gallery;

use Nette\Application\UI\Form;

class GalleryControl extends \App\AdminModule\Components\baseControl {    
    public function renderShow($id) {
        $template = $this->template;
        $template->gallery_id = $id;
        $template->setFile(__DIR__ . '/galleryDefault.latte');        
        $template->images = $comments = $this->database->table('imgs')->where('status ? OR status ?', 1, 2)->where(':img_sort.gallery_id', $id)->fetchAll();
        $template->render();
    }
}
