<?php

class Post extends VanillaModel {
	var $hasOne = array('User' => 'User',
        'Image' => 'Image');
    var $hasMany = array('React' => 'React');
}