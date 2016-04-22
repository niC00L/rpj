<?php

namespace App\AdminModule\Presenters;

use Nette,
    App\Model;

class ControlsPresenter extends AdminPresenter {

    public function startup() {
        parent::startup();
        if ($this->getUser()->getRoles()[0] == 'banned') {
            $this->flashMessage('Máte ban');
            $this->redirect('Admin:default');
        } elseif (!$this->getUser()->isAllowed('post', 'edit')) {
            $this->flashMessage('Nemáte prístup k tejto stránke');
            $this->redirect('Admin:default');
        }
    }

    static function getControls($db, $status) {
        $controls = $db->table('controls')->where('status', $status)->where('editable', 1)->fetchAll();
        if ($status != 0) {
            $main = $db->table('controls')->where('position', 'layout-menu')->fetch();
            array_push($controls, $main);
        }
        $links = array();
        foreach ($controls as $item) {
            if (!in_array($item['position'], $links)) {
                $link = explode('-', $item['position']);
                if ($link[0] == 'layout') {
                    $links[$item['position']] = '#';
                } elseif ($link[0] == 'post') {
                    if ($link[1] == 'show') {
                        $link[2] = $db->table('post')->where('status', 1)->fetch()->address;
                    } else {
                        $link[2] = $db->table('post_ctg')->where('status', 1)->fetch()->address;
                    }
                    $links[$item['position']] = array(\Nette\Utils\Strings::capitalize($link[0]), \Nette\Utils\Strings::capitalize($link[1]), $link[2]);
                } else {
                    $links[$item['position']] = array(\Nette\Utils\Strings::capitalize($link[0]), \Nette\Utils\Strings::capitalize($link[1]));
                }
            }
        }
        return array($controls, $links);
    }

    public function actionDefault() {
        $controls = $this::getControls($this->database, 1)[0];
        $links = $this::getControls($this->database, 1)[1];
        $this->template->controls = $controls;
        $this->template->links = $links;
    }

}
