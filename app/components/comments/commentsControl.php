<?php

namespace App\Components\Comments;

use Nette\Application\UI\Control,
    Nette\Application\UI\Form;

class CommentsControl extends \App\AdminModule\Components\baseControl {

    private $id;
    private $table;

    public function renderShow($id, $table) {                        
        $this->id = $id;
        $this->table = $table;
        $template = $this->template;
        $template->setFile(__DIR__ . '/commentsDefault.latte');
        $template->comments = $comments = $this->database->table('comments')->where($table.'_id', $id)->fetchAll();
        $template->render();
    }
    
    public function createComponentAddComment() {
        $form = new Form;
        
        
        return $form;
    }

}
