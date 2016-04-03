<?php

namespace App\Model;

class roles extends \Nette\Object implements \Nette\Security\IAuthorizator {

    private $global;

    private function __construct(GlobalSettings $global) {
        $this->global = $global->getGlobal();

        $acl = new Nette\Security\Permission;

        $acl->addRole('banned');
        $acl->addRole('guest', 'banned');
        $acl->addRole('user', 'guest'); // registered dědí od guest
        $acl->addRole('editor', 'user'); // a od něj dědí administrator
        $acl->addRole('administrator', 'editor'); // a od něj dědí administrator

        $acl->allow('guest', array('article', 'comment', 'category'), 'view');

        if ($this->global['comment_all']==1) {
            $acl->allow('guest', 'comment', 'add');
        } else {
            $acl->allow('user', 'comment', 'add');
        }
        $acl->allow('user', 'profile', 'edit');

        $acl->allow('editor', array('article', 'gallery', 'category'), array('edit', 'add', 'delete'));

        $acl->allow('administrator', Permission::ALL, array('view', 'edit', 'add'));
    }

    public function isAllowed($role, $resource, $privilege) {
        return True;
    }

}
