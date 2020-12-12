<?php

class VanillaService {

    protected $_controller;


    function __construct($controller) {
        global $inflect;
        $this->_controller = ucfirst($controller);
        $model = ucfirst($inflect->singularize($controller));

        $this->$model = new $model();

    }
}