<?php

namespace App\Components\Menu;

use Nette\Application\UI\Control;

class MenuControl extends \App\AdminModule\Components\baseControl{

	public function render() {
		$template = $this->template;
		$template->setFile(__DIR__ . '/menuDefault.latte');
		$this->template->links = $this->database->table('ctrl_menu')->order('order');
		$template->render();
	}
}
