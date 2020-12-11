<?php

class Post extends VanillaModel {
	var $hasOne = array('User' => 'User');
}