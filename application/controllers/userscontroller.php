<?php
include_once(ROOT . DS . 'helpers/jwt.php');

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
            // Validate input
            // check for password
            if ($this->body["email"] == "ok") {
                $jwtHelper = new Jwt();
                setrawcookie("Authorization", $jwtHelper->encode(["email"=>$this->body["email"]]));
            } else {
                $this->sendJson("Wrong email or password");
            }
        }
    }

    function edit($queryString = "") {
        global $method;
        $this->doNotRenderHeader=1;
        if ($method == 'GET') {
            if ($this->curUser) {
                $this->sendJson("Current user " . $this->curUser);
            } else {
                $this->sendJson("Not login");
            }
//            $this->sendJson("Sent get profile data");
        }elseif ($method == 'POST') {
            $this->sendJson("Sent edit profile data");
        }
    }

    function afterAction() {

    }


}