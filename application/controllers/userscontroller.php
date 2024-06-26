<?php
include_once(ROOT . DS . 'helpers/jwt.php');

class UsersController extends VanillaController
{

    function beforeAction()
    {

    }

    function index($queryString = "")
    {
        header("Location: " . BASE_PATH . "users/view", true, 302);
        exit();
    }

    function view($idQuery = "")
    {
        global $method;
        global $loginUserId;
        if (empty($loginUserId)) {
            header("Location: " . BASE_PATH . "users/login", true, 302);
            exit();
        }
//        $this->User->where('id',1);
//        $this->User->showHasMany();
//        $user = $this->User->search();
//        $this->set('user',$user);
//
//        $this->User->where('id',1);
//        $user = $this->User->search();
//        $this->sendJson($user);
    }

    function login($queryString = "")
    {
        global $method;
        global $loginUserId;
        if (!empty($loginUserId)) {
            header("Location: " . BASE_PATH . "users/view", true, 302);
            exit();
        }
        $this->headerPath = ROOT . DS . 'application' . DS . 'views' . DS . 'header.php';
        if(is_numeric($loginUserId)){
            header("Location: /users/view");
            exit();
        }
        if ($method == 'GET') {

        } elseif ($method == 'POST') {

        }
    }

    function register($queryString = "")
    {
        global $method;
        global $loginUserId;
        $this->headerPath = ROOT . DS . 'application' . DS . 'views' . DS . 'header.php';
        if (!empty($loginUserId)) {
            header("Location: " . BASE_PATH . "users/view", true, 302);
            exit();
        }
        if ($method == 'GET') {
//            $this->sendJson(" for used email");
        } elseif ($method == 'POST') {
//            $this->sendJson("checking for used email");
        }
    }

    function view_post($queryString = "")
    {
        global $method;
        global $loginUserId;
        if ($method == 'GET') {
            if (empty($loginUserId)) {
                header("Location: " . BASE_PATH . "users/login", true, 302);
                exit();
            }
            else{
                // tim nhung nguoi dang following
                $this->Follow = new Follow();
                $this->Follow->where('follower_id', $loginUserId);
                $followers = $this->Follow->search();



                if(count($followers) >0 ){
                    foreach ($followers as &$follower){
                        $this->User->where('id', $follower["Follow"]["user_id"]);
                        $this->User->showHasOne();
                        $result = $this->User->search();
                        $follower["Follow"]["username"] = $result[0]["User"]["username"];
                        $follower["Follow"]["image"] = $result[0]["Image"]["content"];
                    }
//
                }
                else {
                    $followers = [];
                }

                //lay info user
                $this->User->where('id', $loginUserId);
                $this->User->showHasOne();
                $myUser = $this->User->search();
                $myUser = $myUser[0];
                $this->set( 'myUser', $myUser);
                $this->set( 'username', $myUser["User"]["username"]);
                $this->set( 'followers', $followers);

            }
        } else {
            http_response_code(404);
        }
    }

    function view_profile($queryString = "", $idQuery = "")
    {
        global $method;
        global $loginUserId;

        if (empty($loginUserId)) {
            header("Location: " . BASE_PATH . "users/login", true, 302);
            exit();
        }
        $this->headerPath = ROOT . DS . 'application' . DS . 'views' . DS . "users" . DS . 'header.php';
        $isEdit = false;
        $getId = null;

        $this->User->where('id', $loginUserId);
        $this->User->showHasOne();
        $myUser = $this->User->search();
        $myUser = $myUser[0];
        $this->set( 'username', $myUser["User"]["username"]);
        if ($idQuery == null || is_numeric($idQuery)) {
            if ($idQuery == null) {
                $getId = $loginUserId;
                $this->User->where('id', $loginUserId);
            } else {
                $getId = $idQuery;
                $this->User->where('id', $idQuery);
            }
            $this->User->showHasMany();
            $this->User->showHasOne();
            $user = $this->User->search();
            //tra ve du lieu cua user
            $this->set('user', $user[0]["User"]);

            $this->Follow = new Follow();

            //lay followings cua user
            $this->Follow->where('user_id', $getId);
            $followings = $this->Follow->search();

            if (count($followings) > 0) {
                foreach ($followings as &$following) {
                    if ($following["Follow"]["follower_id"] != null) {
                        $this->User->where('id', $following["Follow"]["follower_id"]);
                        $result = $this->User->search();
                        $following["Follow"]["username"] = $result[0]["User"]["username"];
                    }
                }
            }
            //tra ve followers cua user
            $this->set('followings', $followings);

            //check following
            if ($getId != $loginUserId) {
                $this->Follow->where('user_id', $loginUserId);
                $this->Follow->where('follower_id', $getId);
                $result = $this->Follow->search();
                if (count($result) == 1) {
                    $this->set('isFollowing', 'UnFollow');
                } else {
                    $this->set('isFollowing', 'Follow');
                }

            }

            //lay followers cua user
            $this->Follow->where('follower_id', $getId);
            $followers = $this->Follow->search();
//            var_dump([$this->Follow, $getId]);die();
            $this->set('followings', $followings);
            if (count($followers) > 0) {
                foreach ($followers as &$follower) {
                    if ($follower["Follow"]["user_id"] != null) {
                        $this->User->where('id', $follower["Follow"]["user_id"]);
                        $result = $this->User->search();
                        $follower["Follow"]["username"] = $result[0]["User"]["username"];
                    }
                }
            }
            //tra ve followers cua user
            $this->set('followers', $followers);

//            var_dump($followers);die();

            //image cua user
            $this->set('image_user', $user[0]["Image"]["content"]);
            $posts = $user[0]["Post"];
            // lay image cho cac post trong posts
            if (count($posts) > 0) {
                $this->Post = new Post();
                foreach ($posts as &$post) {
                    if ($post["Post"]["id"] != null) {
                        $this->Post->where('id', $post["Post"]["id"]);
                        $this->Post->showHasOne();
                        $result = $this->Post->search();
                        $post["Post"]["image"] = $result[0]["Image"]["content"];
                    }
                }
            }
            $this->set('posts', $posts);
            //check quyen edit profile
            if ($this->checkLogin() == true && ($idQuery == $loginUserId || $idQuery == null)) {
                $isEdit = true;
            }
            $this->set('isEdit', $isEdit);
        } else {
            header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
        }


        if ($method == 'GET') {

        } elseif ($method == 'POST') {

        }
    }

    function edit($queryString = "")
    {
        global $method;
        global $loginUserId;
        if (empty($loginUserId)) {
            header("Location: " . BASE_PATH . "users/login", true, 302);
            exit();
        }

        $this->headerPath = ROOT . DS . 'application' . DS . 'views' . DS . "users" . DS . 'header.php';
        if ($method == 'GET') {
            $this->User->where('id', $loginUserId);
            $this->User->showHasOne();
            $myUser = $this->User->search();
            $myUser = $myUser[0];

            $cur_user["profile_title"] = $myUser["User"]["profile_title"];
            $cur_user["profile_description"] = $myUser["User"]["profile_description"];
            $cur_user["profile_url"] = $myUser["User"]["profile_url"];

            $this->set( 'username', $myUser["User"]["username"]);
            $this->set( 'cur_user', $cur_user);

        } elseif ($method == 'POST') {
            $this->sendJson("Sent edit profile data");
        }
    }

    function vSearch($queries = [], $idQuery = "")
    {
        global $method;
        global $loginUserId;
        $respone = "";
        $hint = "";
        if ($method == 'GET' &&  $this->checkLogin() == true) {

            if (isset($queries["q"]) ) {
                $q = $queries["q"];
                $this->User->like('username', $q);
                $result = $this->User->search();
                if(count($result) > 0){
                    for($i = 0; $i <count($result) ; $i ++){
                        if ($hint=="") {
                            $hint="<a class='item' href='" ."/users/view_profile/".
                                $result[$i]["User"]["id"] .
                                "' target='_blank'>" .
                                $result[$i]["User"]["username"] . "</a>";
                        } else {
                            $hint= $hint . "<a class='item' href='" ."/users/view_profile/".
                                $result[$i]["User"]["id"] .
                                "' target='_blank'>" .
                                $result[$i]["User"]["username"] . "</a>";
                        }
                    }
                }
            }
            if ($hint=="") {
                $response="no suggestion";
            } else {
                $response=$hint;
            }
            $this->sendJson(["response" => $response]);

        } else {
            http_response_code(404);
        }
    }

    function vFollow($queries = [], $idQuery = "")
    {
        global $method;
        global $loginUserId;
        if ($method == 'GET' && is_numeric($idQuery) && $idQuery != $loginUserId && $this->checkLogin() == true) {
            $isFollow = null;
            $this->Follow = new Follow();
            //lay followings cua user
            $this->Follow->where('user_id', $loginUserId);
            $this->Follow->where('follower_id', $idQuery);
            $result = $this->Follow->search();

            if (count($result) == 1) {
                $this->Follow->id = $result[0]["Follow"]["id"];
                $this->Follow->delete();

                $isFollow = "Follow";
            } else {
                $this->Follow->user_id = $loginUserId;
                $this->Follow->follower_id = $idQuery;
                $this->Follow->save();
                $isFollow = "UnFollow";
            }
//            var_dump([ $isFollow]); die();
            // Dem so follower cua User_id
            $this->Follow->where('follower_id', $idQuery);
            $followers = $this->Follow->search();


            $this->sendJson(["follow" => $isFollow,
                 "count"=> count($followers)]);


        } else {
            http_response_code(404);
        }
    }

    function vGetPosts($queries = [], $idQuery = "")
    {
        // Luon co $idQuery
        global $method;
        global $loginUserId;
        if ($method == "GET") {
            if (is_numeric($idQuery)) {
                $this->User->where('id', $idQuery);
                if ($queries["limit"] && is_numeric($queries["limit"])) {
                    $this->User->setHasManyLimit("Post", $queries["limit"]);
                }

                if ($queries["page"] && is_numeric($queries["page"])) {
                    $this->User->setHasManyPage("Post", $queries["page"]);
                }
                $this->User->hasManyOrderBy("Post", "updated_at", "DESC");
                $this->User->showHasOne();
                $this->User->showHasMany();
                $users = $this->User->search();
                if (empty($users)) {
                    http_response_code(403);
                    $this->sendJson(["error" => "User with id: " . $idQuery . " does not exist"]);
                }
                $user = $users[0];
//                var_dump($user["Image"]["content"]);die();
                $posts = $user["Post"];
                // lay image cho cac post trong posts
                if (count($posts) > 0) {
                    $this->Post = new Post();
                    $this->React = new React();
                    foreach ($posts as &$post) {
                        if ($post["Post"]["id"] != null) {
                            $this->Post->where('id', $post["Post"]["id"]);
                            $this->Post->showHasOne();
                            $result = $this->Post->search();
                            $post["Post"]["image"] = $result[0]["Image"]["content"];

                            //Lay ra  number react
                            $this->Post->where('id', $post["Post"]["id"]);
                            $this->Post->showHasMany();
                            $result = $this->Post->search();
                            if(count($result[0]["React"]) >0 ){
                                $post["Post"]["number_react"] = count($result[0]["React"]);
                            }
                            else {
                                $post["Post"]["number_react"] = 0;
                            }

                            // Check userLogin react?
                            $isReact = false;
                            $this->React->where('post_id', $post["Post"]["id"]);
                            $this->React->where('user_id', $loginUserId);
                            $result = $this->React->search();
                            if(count($result[0]) == 1 ){
                                $isReact = true;
                            }
                            else {
                                $isReact = false;
                            }
//                            var_dump($result); die();
                            $post["Post"]["isReact"] = $isReact;
                        }
                        $post = $post["Post"];
                    }
                } else {
                    $posts = [];
                }
//                var_dump($posts);die();
                $this->sendJson(["posts" => $posts]);

            } else {
                http_response_code(403);
                $this->sendJson(["error" => "Invalid request"]);
            }
        } else {
            http_response_code(404);
        }
    }

    function vReact($queries = [], $idQuery = ""){
        global $method;
        global $loginUserId ;
        // idQuery la postId
        //&& !$this->checkLogin() == true

        if($method == 'GET' && is_numeric($idQuery) ){
            $isReact = null;
            $this->React = new React();
            //Check user co React post_id
            $this->React->where('post_id', $idQuery);
            $this->React->where('user_id', $loginUserId);
            $result = $this->React->search();


            if (count($result) == 1) {
                $this->React->where('post_id', $idQuery);
                $this->React->where('user_id', $loginUserId);
                $this->React->delete();

                $isReact = false;
            } else {
                $this->React->post_id =  $idQuery;
                $this->React->user_id = $loginUserId;
                $this->React->save();
                $isReact = true;
            }
//            var_dump([ $isReact]); die();
            $count = 0;
            $this->Post = new Post();
            //Lay ra  number react
            $this->Post->where('id', $idQuery);
            $this->Post->showHasMany();
            $result = $this->Post->search();
            if(count($result[0]["React"]) >0 ){
                $count = count($result[0]["React"]);
            }
            else {
                $count = 0;
            }

            $this->sendJson(["isReact" => $isReact,
                "count" => $count]);


        }else {
            http_response_code(404);
        }
    }

    function vLogin($queryString = "")
    {
        global $method;
        if ($method == "POST") {
            // Validate input
            $validateError = $this->validateLoginInput($this->body["email"], $this->body["password"]);
            if (!empty($validateError)) {
                http_response_code(403);
                $this->sendJson(["validateError" => $validateError]);
            }

            // check for password
            $this->User->where('email', $this->body["email"]);
            $users = $this->User->search();
            // them truong image_users
            $user[image_user] = null;

            if (!empty($users)) {
                $user = $users[0]["User"];

                if (password_verify($this->body["password"], $user["password"])) {
                    if ($user[images_users_id] != null) {
                        $this->User->where('email', $this->body["email"]);
                        // them dong nay de lay them image cho Users
                        $this->User->showHasOne();
                        $users = $this->User->search();
                        $image_user = $users[0]["Images_users"];
                        $user[image_user] = $image_user[content];
                    }
                    $jwtHelper = new Jwt();
                    $this->sendJson([
                        "Authorization" => $jwtHelper->encode(["id" => $user["id"]])
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



    //API update Users of table users
    function vEdit($queryString = "")
    {
        global $method;
        global $loginUserId;
        if (empty($loginUserId)) {
            http_response_code(403);
            die;
        }
        if ($method == "POST") {
            $this->User->where('id', $loginUserId);
            $users = $this->User->search();
            if (empty($users)) {
                $this->sendJson(["error" => "Not found user"]);
            } else {
                // set fields need to be updated
                if ($loginUserId) {
                    $this->User->id = $loginUserId;
                    // update fields of User
                    if (isset($this->body["profile_title"]))
                        $this->User->profile_title = $this->body["profile_title"];
                    if (isset($this->body["profile_description"]))
                        $this->User->profile_description = $this->body["profile_description"];
                    if (isset($this->body["username"]))
                        $this->User->username = $this->body["username"];
                    if (isset($this->body["password"])) {
                        $this->User->setPassword($this->body["password"]);
                    }
                    // update image of User
                    if ($_FILES["image"]["name"] != null) {
                        $image_base64 = base64_encode(file_get_contents($_FILES['image']['tmp_name']));
                        $image = 'data:image/png;base64,' . $image_base64;
                        // call Image table
                        $this->Image = new Image();
                        $this->Image->content = $image;
                        $this->Image->save();
                        // get image_id inserted
                        $image_id = $this->Image->insert_id;
                        $this->Image->insert_id = null;
                        if (is_numeric($image_id) && intval($image_id) > 0) {
                            //set image of User
                            $this->User->image_id = $image_id;
                        }
                    }
                    $this->User->save();
                    //get User
                    $this->User->where('id', $loginUserId);
                    $this->User->showHasOne();
                    $user = $this->User->search();
                    $image_user = $user[0]["Image"];
                    $user[0]["User"]["image_user"] = $image_user["content"];
                    $user = $user[0]["User"];
//                    unset($user["password"]);
                    unset($user["created_at"]);
                    unset($user["updated_at"]);
                    $this->sendJson([
                        "status" => "OK",
                        "user" => $user
                    ]);
                }
                $this->User->save();
                //get User
                $this->User->where('id', $this->body["id"]);
                $this->User->showHasOne();
                $user = $this->User->search();
                $image_user = $user[0]["Image"];
                $user[0]["User"]["image_user"] = $image_user["content"];
                $user = $user[0]["User"];
//                    unset($user["password"]);
                unset($user["created_at"]);
                unset($user["updated_at"]);
                $this->sendJson([
                    "status" => "OK",
                    "user" => $user
                ]);

            }
        } else {
            header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
        }
    }

    function validateLoginInput($email, $password)
    {
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

    //API register User of table users
    function vRegister($queries = [], $idQuery = "")
    {
        global $method;
        if ($method == "POST") {
            // Validate input
            $validateError = $this->validateRegisterInput(
                $this->body["email"],
                $this->body["username"],
                $this->body["password"]
            );
            if (!empty($validateError)) {
                http_response_code(403);
                $this->sendJson(["validateError" => $validateError]);
            }

            // check for password
            $newUser = new User();
            $newUser->email = $this->body["email"];
            $newUser->username = $this->body["username"];
            $newUser->setPassword($this->body["password"]);
            $newUser->_describe = ["email", "username", "password"];
            $result = $newUser->save();
            if ($result<0){
                http_response_code(403);
                die;
            }
            $newUser->clear();
            $newUser->where("email", $this->body["email"]);
            $foundUser = $newUser->search();
            if (empty($foundUser)) {
                http_response_code(403);
                die;
            }
            $userId = $foundUser[0]['User']['id'];

            $jwtHelper = new Jwt();
            $this->sendJson([
                "Authorization" => $jwtHelper->encode(["id" => $userId])
            ]);
        } else {
            http_response_code(404);
        }
    }

    function validateRegisterInput($email, $username, $password){
        include_once(ROOT . DS . 'helpers/validate.php');

        $validator = new Validator();

        $validateResult = $validator->validateEmail($email);
        if ($validateResult["error"]) {
            $validateError["email"] = $validateResult["error"];
        } else {
            $user = new User();
            $user->where("email", $email);
            $foundUsers = $user->search();
            if (!empty($foundUsers)) {
                $validateError["email"] = ["Email has already been used."];
            }
        }

        $validateResult = $validator->validatePassword($password);
        if ($validateResult["error"]) {
            $validateError["password"] = $validateResult["error"];
        }

        $validateResult = $validator->validateUsername($username);
        if ($validateResult["error"]) {
            $validateError["username"] = $validateResult["error"];
        }

        return $validateError;
    }

    function checkLogin()
    {
        global $loginUserId;
        if ($loginUserId != null) {
            if (is_numeric($loginUserId) && $loginUserId > 0) {
                return true;
            }
        }
        return false;
    }

    function searchUserByUsername($queries=[], $params=[]) {
        global $method;
        global $loginUserId;
        if (empty($loginUserId)) {
            http_response_code(401);
            return;
        }

        if (empty($queries["string"])) {
            $this->sendJson([]);
            return;
        }

        $user = new User();
        $user->like("username", $queries["string"]);

        if ($queries["limit"] && is_numeric($queries["limit"])) {
            $user->setHasManyLimit("Post", $queries["limit"]);
        } else {
            $user->setHasManyLimit("Post", 5);
        }

        if ($queries["page"] && is_numeric($queries["page"])) {
            $user->setHasManyPage("Post", $queries["page"]);
        }
        $result = $user->search();
        $foundUsers = [];

        foreach ($result as $item) {
            $item["Post"]["password"] = null;
            $item["Post"]["password"] = null;
            $item["Post"]["password"] = null;
        }
    }

    function afterAction()
    {

    }
    //Test phan trang
//$this->Image = new Image();
//$this->Image->setLimit(7);
//$this->Image->setPage(1);
//$images = $this->Image->search() ;
//$totalPages = $this->Image->totalPages();

}