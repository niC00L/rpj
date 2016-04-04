<?php

namespace App\Model;


class GetUser extends \App\Presenters\BasePresenter {
    
    public function getInfo($id){
        $userInfo = $this->database->table('users')->where('id', $id)->fetchAll();
        return $userInfo;
    }
}