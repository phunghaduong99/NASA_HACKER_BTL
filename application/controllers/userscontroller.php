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
        $this->User->where('id',1);
        $this->User->showHasMany();
        $user = $this->User->search();
        $this->set('user',$user);
//
//        $this->User->where('id',1);
//        $user = $this->User->search();
        $this->sendJson($user);
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

        } elseif ($method == 'POST') {

        }
    }

    function apiLogin($queryString = "") {
        global $method;
        if ($method == "POST") {
            // Validate input
            include_once(ROOT . DS . 'helpers/validate.php');
            $validator = new Validator();
            $validateError = [];

            $validateResult = $validator->validateEmail($this->body["email"]);
            if ($validateResult["error"]) {
                $validateError["email"] = $validateResult["error"];
            }

            $validateResult = $validator->validatePassword($this->body["password"]);
            if ($validateResult["error"]) {
                $validateError["password"] = $validateResult["error"];
            }
            if (!empty($validateError)) {
                $this->sendJson(["validateError"=>$validateError]);
            }

            // check for password
            $this->User->where('email',$this->body["email"]);
            $users = $this->User->search();
            if (!empty($users)) {
                $user = $users[0]["User"];
                if ($user["password"] == $this->body["password"]) {
                    $jwtHelper = new Jwt();
                    unset($user["password"]);
                    unset($user["created_at"]);
                    unset($user["update_at"]);
                    $this->sendJson([
                        "Authorization"=>$jwtHelper->encode(["email"=>$this->body["email"]]),
                        "user" => $user
                    ]);
                    return;
                }
            }
            $this->sendJson([
                "loginError" => "Wrong email or password"
            ]);
        } else {
            header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
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