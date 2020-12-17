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
        var_dump($this->User); die();
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
        $this->headerPath = ROOT . DS . 'application' . DS . 'views' . DS . 'header.php';
        if ($method == 'GET') {
//            $this->sendJson(" for used email");
        }elseif ($method == 'POST') {
//            $this->sendJson("checking for used email");
        }
    }

    function view_profile($queryString = "") {
        global $method;
        $this->headerPath = ROOT . DS . 'application' . DS . 'views' . DS . "users". DS . 'header.php';

        $this->User->where('id',1);
        $this->User->showHasMany();
        $this->User->showHasOne();
        $user = $this->User->search();
        $this->set('user',$user[0]["User"]);
        $this->set('image_user',$user[0]["Image"]["content"]);
        $posts = $user[0]["Post"];
        // lay image cho cac post trong posts
        if(count($posts) >0 ){
            $this->Post = new Post();
            foreach ($posts as &$post) {
                if($post["Post"]["id"] != null){
                    $this->Post->where('id', $post["Post"]["id"]);
                    $this->Post->showHasOne();
                    $result = $this->Post->search();
                    $post["Post"]["image"] = $result[0]["Image"]["content"];
                }

            }
        }
        $this->set('posts',$posts);

        if ($method == 'GET') {

        }elseif ($method == 'POST') {

        }
    }

    function view_post($queryString = "") {
        global $method;

        $this->headerPath = ROOT . DS . 'application' . DS . 'views' . DS . "users". DS . 'header.php';
        $this->User->where('id',1);
        $this->User->showHasMany();
        $user = $this->User->search();
        // pass data to view
        $this->set('user',$user[0]["User"]);
        $this->set('posts',$user[0]["Post"]);
//        echo json_encode($user);
//        echo json_encode($user[0]["User"]["username"]);
//        echo json_encode($user[0]["Post"][0]["Post"]["id"]);
//        $this->sendJson("Current user " );
        if ($method == 'GET') {

        }elseif ($method == 'POST') {

        }
    }

    function login($queryString = "") {
        global $method;

        $this->headerPath = ROOT . DS . 'application' . DS . 'views' . DS . 'header.php';
        if ($method == 'GET') {

        } elseif ($method == 'POST') {

        }
    }



    function edit($queryString = "") {
        global $method;
        $this->headerPath = ROOT . DS . 'application' . DS . 'views' . DS . 'header.php';
        if ($method == 'GET') {
//            if ($this->curUser) {
//                $this->sendJson("Current user " . $this->curUser);
//            } else {
//                $this->sendJson("Not login");
//            }
//            $this->sendJson("Sent get profile data");
        }elseif ($method == 'POST') {
            $this->sendJson("Sent edit profile data");
        }
    }

    function vLogin($queryString = "") {
        global $method;
        if ($method == "POST") {
            // Validate input
            $validateError = $this->validateLoginInput($this->body["email"], $this->body["password"]);
            if (!empty($validateError)) {
                http_response_code(403);
                $this->sendJson(["validateError"=>$validateError]);
            }

            // check for password
            $this->User->where('email',$this->body["email"]);
            $users = $this->User->search();
            // them truong image_users
            $user[image_user] = null;

            if (!empty($users)) {
                $user = $users[0]["User"];

                if (password_verify($this->body["password"], $user["password"])) {
                    if($user[images_users_id] != null){
                        $this->User->where('email',$this->body["email"]);
                        // them dong nay de lay them image cho Users
                        $this->User->showHasOne();
                        $users = $this->User->search();
                        $image_user = $users[0]["Images_users"];
                        $user[image_user] = $image_user[content];
                    }
                    $jwtHelper = new Jwt();
                    unset($user["password"]);
                    unset($user["created_at"]);
                    unset($user["update_at"]);

                    $this->sendJson([
                        "Authorization"=>$jwtHelper->encode(["id"=>$user["id"]]),
                        "user" => $user,
                    ]);
                    return;
                }
            }

            http_response_code(401);
            $this->sendJson([
                "loginError" => "Wrong email or password"
            ]);
        } else {
            http_response_code(404);
        }
    }

    function validateLoginInput($email, $password) {
        include_once(ROOT . DS . 'helpers/validate.php');

        $validator = new Validator();

        $validateResult = $validator->validateEmail($email);
        if ($validateResult["error"]) {
            $validateError["email"] = $validateResult["error"];
        }

        $validateResult = $validator->validatePassword($password);
        if ($validateResult["error"]) {
            $validateError["password"] = $validateResult["error"];
        }

        return $validateError;
    }

    //API update Users of table users
    function vEdit($queryString = "") {
        global $method;
        if ($method == "POST") {
            $this->User->where('id', $this->body["id"]);
            $users = $this->User->search();
            if (empty($users)) {
                $this->sendJson(["error" => "Not found user"]);

            } else {
                // set fields need to be updated
                if(isset($this->body["id"])){
                    $this->User->id = $this->body["id"];
                    // update fields of User
                    if(isset($this->body["profile_title"]))
                        $this->User->profile_title = $this->body["profile_title"];
                    if(isset($this->body["profile_description"]))
                        $this->User->profile_description = $this->body["profile_description"];
                    if(isset($this->body["username"]))
                        $this->User->username = $this->body["username"];
                    if(isset($this->body["password"])){
                        $this->User->setPassword($this->body["password"]) ;
                    }
                    // update image of User
                    if ($_FILES["image"]["name"] != null) {
                        $image_base64 = base64_encode(file_get_contents($_FILES['image']['tmp_name']));
                        $image = 'data:image/png;base64,'.$image_base64;
                        // call Image table
                        $this->Image = new Image();
                        $this->Image->content = $image;
                        $this->Image->save();
                        // get image_id inserted
                        $image_id = $this->Image->insert_id;
                        $this->Image->insert_id = null;
                        if(is_numeric($image_id) && intval($image_id) >0){
                            //set image of User
                            $this->User->image_id = $image_id;
                        }
                    }
                    $this->User->save();
                    //get User
                    $this->User->where('id',$this->body["id"]);
                    $this->User->showHasOne();
                    $user = $this->User->search();
                    $image_user = $user[0]["Image"];
                    $user[0]["User"]["image_user"] = $image_user["content"];
                    $user = $user[0]["User"];
//                    unset($user["password"]);
                    unset($user["created_at"]);
                    unset($user["updated_at"]);
                    $this->sendJson([
                        "status" => "OK" ,
                        "user" =>  $user
                    ]);
                }

            }
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
    //Test phan trang
//$this->Image = new Image();
//$this->Image->setLimit(7);
//$this->Image->setPage(1);
//$images = $this->Image->search() ;
//$totalPages = $this->Image->totalPages();

}