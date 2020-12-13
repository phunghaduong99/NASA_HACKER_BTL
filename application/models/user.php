<?php

class User extends VanillaModel {
    var $hasMany = array('Post' => 'Post');
    var $hasOne = array('Images_users' => 'Images_users');
//    function __construct() {
//       echo "model";
//       parent::__construct();
//    }
}