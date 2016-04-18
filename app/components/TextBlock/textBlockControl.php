<?php

namespace App\Components\TextBlock;

use Nette\Application\UI\Control,
    Nette\Application\UI\Form;

class TextBlockControl extends \App\AdminModule\Components\baseControl {
    
//    public function __construct(\Nette\Database\Context $database, \App\Model\GlobalSettings $global) {
//        parent::__construct($database, $global);
//        $this->addComponent(new \App\AdminModule\Components\Forms\EditForm\editFormControl($this->database, $this->global), 'editForm');
//    }

    static function defaultValue($db, $id) {
        $values = array(
            'control_id' => $id,
            'title' => 'Titulok. Kliknutím editujte.',
            'text' => 'Textový blok. Kliknutím editujte.',
        );
        $db->table('ctrl_text_block')->insert($values);
    }

    public function getIds($id) {
        $temp_id = $this->database->table('controls')->where('id', $id)->fetch()->template;
        $template = $this->database->table('site_templates')->where('id', $temp_id)->fetch()->file_name;
        return $template;
    }
    
//    public function setForms($id, $table, $defaults) {
//    }

    public function renderDefault($id) {
        $this->template->text = $text = $this->database->table('ctrl_text_block')->where('control_id', $id)->fetch();
//        $this['editForm']->setForms($id, 'ctrl_text_block', $text);
        $this['editForm']->setDefaults($text);
        $this->template->setFile(__DIR__ . '/' . $this->getIds($id) . '.latte');
        $this->template->render();
    }

    public function createComponentEditForm() {
        $form = new Form;
        $form->addHidden('id');
        $form->addText('title', 'Titulok');
        $form->addTextArea('text', 'Text')
                ->setAttribute('class', 'materialize-textarea');
        $form->addSubmit('submit', 'Odoslať')
                ->setAttribute('class', 'btn');
        $form->onSuccess[] = array($this, 'editSuccess');
        return $form;
    }
    
    public function editSuccess($form, $values) {
        $this->database->table('ctrl_text_block')->where('id', $values['id'])->update($values);
        $this->presenter->flashMessage('Úspešne upravené');
        $this->presenter->redirect('this');
    }
}
