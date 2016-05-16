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
        $menu = $this->database->table('controls')->where('id', $id)->fetch();
        $template = $this->database->table('site_templates')->where('id', $menu['template'])->fetch()->file_name;
        return array($template, $menu);
    }

    public function renderDefault($id) {
		
        $this->template->id = $id;
        $template = $this->getIds($id)[0];
        $this->template->menu = $this->getIds($id)[1];
        $this->template->links = $this->database->table('ctrl_menu')->where('menu_id', $id)->order('order')->fetchAll();
        $this->template->setFile(__DIR__ . '/' . $template . '.latte');
        $this->renderTemplate();
    }

}
