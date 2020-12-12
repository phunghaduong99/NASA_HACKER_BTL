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
//        $this->User->where('id',1);
//        $this->User->showHasMany();
//        $user = $this->User->search();
//        $this->set('user',$user);
//
//        $this->User->where('id',1);
//        $user = $this->User->search();
//        $this->sendJson($user);
    }

    function register($queryString = "") {
        global $method;
        $this->doNotRenderHeader=1;
        if ($method == 'GET') {
            $this->sendJson(" for used email");
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

    function vLogin($queryString = "") {
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

    //API update Users of table users
    function vEdit($queryString = "") {
        global $method;
        if ($method == "POST") {
            // find ID
            $this->User->where('id', $this->body["id"]);
            $users = $this->User->search();
            if (empty($users)) {
                $this->sendJson(["error" => "Not found user"]);

            } else {
                // set fields need to be updated
                $this->User->id = $this->body["id"];
                $this->User->profile_title = $this->body["profile_title"];
//                var_dump([$this->body["profile_title"], $_FILES]);die();
                if ($_FILES["profile_url"]["name"] != null) {
                    $targetDir = "";
                    $name = basename($_FILES["profile_url"]["name"]);
                    $tmp_name = $_FILES["profile_url"]["tmp_name"];

                    $image_base64 = base64_encode(file_get_contents($_FILES['profile_url']['tmp_name']));

                    $image = 'data:image/png;base64,'.$image_base64;

                    $this->User->profile_url = $image;
//                    if(move_uploaded_file($tmp_name, $name)){
//                        $this->User->profile_url = $image;
//                    };
//                    var_dump([move_uploaded_file($tmp_name, $name), $_FILES , $tmp_name, $image]);die();
                    $this->Images_User = new Images_Users();
                    $this->Images_User->user_id = $this->body["id"];
                    $this->Images_User->content = $image;
                    $this->Images_User->save();
//                    var_dump($this->Images_Users);die();
                }
                $this->User->save();

                $this->User->where('id',$this->body["id"]);
                $user = $this->User->search();
                unset($user["password"]);
                unset($user["created_at"]);
                unset($user["update_at"]);
                $this->sendJson([
                    "status" => "OK" ,
                    "user" =>  $user
                ]);
            }
//            var_dump([empty($users),$users ]); die();
        } else {
            header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
        }
    }

    //API register User of table users
    function vRegister($queryString = "") {
        global $method;
        if ($method == "POST") {
            // find ID
            $this->User->where('id',$this->body["id"]);
            $users = $this->User->search();
            if(empty($users)){
                // set fields need to be updated
                $this->User->id =  $this->body["id"];
                $this->User->profile_title =  $this->body["profile_title"];
                $this->User->save();


                $this->User->where('id',$this->body["id"]);
                $user = $this->User->search();
                unset($user["password"]);
                unset($user["created_at"]);
                unset($user["update_at"]);
                $this->sendJson([
                    "status" => "OK" ,
                    "user" =>  $user
                ]);

            }
            else {
                $this->sendJson(["error"=>"User existed!"]);
            }
//            var_dump([empty($users),$users ]); die();
        } else {
            header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
        }
    }

    function afterAction() {

    }


}