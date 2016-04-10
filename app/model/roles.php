<?php

namespace App\Model;

class Roles extends \Nette\Object implements \Nette\Security\IAuthorizator {

    private $global;
    public $acls;

    public function __construct(GlobalSettings $global) {
        $this->global = $global->getGlobal();

        $acl = new \Nette\Security\Permission;

        $acl->addResource('post');
        $acl->addResource('gallery');
        $acl->addResource('comment');
        $acl->addResource('category');
        $acl->addResource('profile');
        $acl->addResource('users');
        $acl->addResource('global');
        $acl->addResource('components');

        $acl->addRole('banned');
        $acl->addRole('guest', 'banned');
        $acl->addRole('user', 'guest');
        $acl->addRole('editor', 'user');
        $acl->addRole('admin', 'editor');

        $acl->allow('guest', array('post', 'comment', 'category'), 'view');

        if ($this->global['comment_all'] == 1) {
            $acl->allow('guest', 'comment', 'add');
        } else {
            $acl->allow('user', 'comment', 'add');
        }
        $acl->allow('user', 'profile', array('edit', 'view'));

        $acl->allow('editor', array('post', 'components', 'category', 'comment', 'gallery'), array('edit', 'add', 'delete'));

        $acl->allow('admin', \Nette\Security\Permission::ALL, array('view', 'edit', 'add'));

        $this->acls = $acl;
    }

    public function isAllowed($role, $resource, $privilege) {
        return $this->acls->isAllowed($role, $resource, $privilege);
    }

}
