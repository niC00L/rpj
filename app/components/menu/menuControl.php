<?php

namespace App\Components\Menu;

use Nette\Application\UI\Control;

class MenuControl extends \App\AdminModule\Components\baseControl {

    static function defaultValue($db, $id) {
        $values = array(
            'order' => 1,
            'menu_id' => $id,
            'title' => 'Domovská stránka',
            'type' => 'Homepage',
        );
        $db->table('ctrl_menu')->insert($values);
    }

    public function getIds($id) {
        $temp_id = $this->database->table('controls')->where('id', $id)->fetch()->template;
        $template = $this->database->table('site_templates')->where('id', $temp_id)->fetch()->file_name;
        return $template;
    }

    public function renderDefault($id) {
        $this->template->id = $id;
        $template = $this->getIds($id);
        $this->template->links = $this->database->table('ctrl_menu')->where('menu_id', $id)->order('order')->fetchAll();
        $this->template->setFile(__DIR__ . '/' . $template . '.latte');
        $this->template->render();
    }

}
