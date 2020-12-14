<?php

class Image extends VanillaModel {
    var $hasOne = array('User' => 'User');
}