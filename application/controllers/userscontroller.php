<?php

class UsersController extends VanillaController {

    function beforeAction () {

    }

    function index($queryString="") {
        header("Location: " . BASE_PATH . "users/view",true, 302);
        exit();
    }

    function view($queryString="") {
        global $method;
        if ($method == "GET") {
            $this->set("username", "someone");
        }
    }

    function register($queryString = "") {
        global $method;
        $this->doNotRenderHeader=1;
        if ($method == 'GET') {

        }elseif ($method == 'POST') {
            $this->sendJson("checking for used email");
        }
    }

    function login($queryString = "") {
        global $method;
        $this->doNotRenderHeader=1;
        if ($method == 'GET') {
          
        }elseif ($method == 'POST') {
            $this->sendJson("checking for used email");
        }
    }
    function apilogin($queryString = "") {
        global $method;
        var_dump("asdf");die();
        $this->doNotRenderHeader=1;
        if ($method == 'GET') {
            $this->sendJson("checking for used email");
        }elseif ($method == 'POST') {
            $this->sendJson("checking for used email");
        }
    }
    function edit($queryString = "") {
        global $method;
        $this->doNotRenderHeader=1;
        if ($method == 'GET') {
//            $this->sendJson("Sent get profile data");
        }elseif ($method == 'POST') {
            $this->sendJson("Sent edit profile data");
        }
    }

    function afterAction() {

    }


}