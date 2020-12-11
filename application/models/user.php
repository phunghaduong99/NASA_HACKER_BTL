<?php

class User extends VanillaModel {
    var $hasMany = array('Post' => 'Post');

//    function __construct() {
//       echo "model";
//       parent::__construct();
//    }
}