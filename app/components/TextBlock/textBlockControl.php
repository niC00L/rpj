<?php

namespace App\Components\TextBlock;

use Nette\Application\UI\Control,
    Nette\Application\UI\Form;

class TextBlockControl extends \App\AdminModule\Components\baseControl {

    static function defaultValue($db, $id) {
        $values = array(
            'control_id' => $id,
            'title' => 'Titulok. Kliknutím editujte.',
            'text' => 'Textový blok. Kliknutím editujte.',
        );
        $db->table('text_block')->insert($values);
    }

    public function getIds($id) {
        $temp_id = $this->database->table('controls')->where('id', $id)->fetch()->template;
        $template = $this->database->table('site_templates')->where('id', $temp_id)->fetch()->file_name;
        return $template;
    }

    public function renderDefault($id) {
        $this->template->text = $text = $this->database->table('text_block')->where('control_id', $id)->fetch();
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
        dump($values);exit;
    }
}
