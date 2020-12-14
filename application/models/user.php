<?php

class User extends VanillaModel {
    var $hasMany = array('Post' => 'Post');
    var $hasOne = array('Image' => 'Image');
//    function __construct() {
//       echo "model";
//       parent::__construct();
//    }
}