<?php

class User extends VanillaModel {
    var $hasMany = array('Post' => 'Post', 'Follow' => 'Follow');
    var $hasOne = array('Image' => 'Image');

//    function __construct() {
//       echo "model";
//       parent::__construct();
//    }
    public function setPassword($password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $this->password = $hashedPassword;
    }
}