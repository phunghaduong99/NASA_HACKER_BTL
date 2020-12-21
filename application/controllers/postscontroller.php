<?php

class PostsController extends VanillaController
{

    function beforeAction()
    {

    }

    function index($queryString = "")
    {
        header("Location: " . BASE_PATH, true, 302);
        exit();
    }

    function view($queryString = "")
    {
        global $method;
        $this->doNotRenderHeader = 1;
        if ($method == "GET") {
            $this->set("title", "First post");
        }
    }

    function add($queryString = "")
    {
        global $method;
        global $loginUserId;
        if (empty($loginUserId)) {
            header("Location: " . BASE_PATH . "users/login", true, 302);
            exit();
        }
        $this->User = new User();
        $this->User->where('id', $loginUserId);
        $this->User->showHasOne();
        $myUser = $this->User->search();
        $myUser = $myUser[0];
        $this->set( 'username', $myUser["User"]["username"]);

        $this->doNotRenderHeader = 1;
        if ($method == 'GET') {

        }
    }

    function edit($queryString = "")
    {
        global $method;
        $this->doNotRenderHeader = 1;
        if ($method == 'GET') {

        } elseif ($method == 'POST') {
            $this->sendJson("Sent edit post data");
        }
    }

    function vAdd($queryString = "")
    {
        global $method;

        if ($method == "POST") {
            if (isset($this->body["post_description"]) && (isset($this->body["image"]) || $_FILES["image"])) {
                $this->Post->post_description = $this->body["post_description"];
                $this->Post->user_id = "1";
                $this->updateImage();
                $this->Post->save();
                http_response_code(200);
                $this->sendJson([
                    "status" => "OK",
                    "message" => "Them post thanh cong."
                ]);
            } else {
                http_response_code(401);
                $this->sendJson([
                    "message" => "Them post that bai."
                ]);
            }
        } else {
            http_response_code(404);
        }

        $this->doNotRenderHeader = 1;
        if ($method == 'GET') {

        }
    }


    function afterAction()
    {

    }

    function updateImage()
    {
        if ($_FILES["image"]["name"] != null) {
            $image_base64 = base64_encode(file_get_contents($_FILES['image']['tmp_name']));
            $image = 'data:image/png;base64,' . $image_base64;
            // call Image table
            $this->Image = new Image();
            $this->Image->content = $image;
            $this->Image->save();
            // get image_id inserted
            $image_id = $this->Image->insert_id;
            $this->Post->insert_id = null;
            if (is_numeric($image_id) && intval($image_id) > 0) {
                //set image of User
                $this->Post->image_id = $image_id;
            }
        } else if ($this->body["image"] != null) {
            $image_base64 = base64_encode(file_get_contents($this->body["image"]));
            $image = 'data:image/png;base64,' . $image_base64;
            // call Image table
            $this->Image = new Image();
            $this->Image->content = $image;
            $this->Image->save();
            // get image_id inserted
            $image_id = $this->Image->insert_id;
            $this->Post->insert_id = null;
            if (is_numeric($image_id) && intval($image_id) > 0) {
                //set image of User
                $this->Post->image_id = $image_id;
            }
        }
    }

    function vGetPostList($queries = [], $params = [])
    {
        global $method;
        global $loginUserId;
        if (empty($loginUserId)) {
            http_response_code(403);
            return;
        }
        if ($method == 'GET') {
            //lay followings cua user
            $follow = new Follow();
            $follow->where('user_id', $loginUserId);
            $followers = $follow->search();
            if (empty($followers)) {
                http_response_code(403);
                return;
            }
            $postList = [];
            $user = new User();

            // Với từng follower tìm được, lấy thông tin user (post, image)
            foreach ($followers as $item) {
                $userId = $item['Follow']["follower_id"];
                $user->clear();
                $user->where('id', $userId);
                $user->showHasMany();
                if ($queries["limit"] && is_numeric($queries["limit"])) {
                    $user->setHasManyLimit("Post", $queries["limit"]);
                }

                if ($queries["page"] && is_numeric($queries["page"])) {
                    $user->setHasManyPage("Post", $queries["page"]);
                }
                $user->hasManyOrderBy("Post", "updated_at", "DESC");
                $user->showHasOne();
                $result = $user->search();
                $userData = $result[0]['User'];
                $userData["avatar"] = $result[0]["Image"]["content"];
                unset($userData['password']);
                unset($userData["created_at"]);
                unset($userData["update_at"]);

                // Nếu có post
                if (!empty($result[0]["Post"]))
                {
                    $imageModel = new Image();
                    $this->React = new React();
                    foreach ($result[0]["Post"] as $post) {
                        $imageModel->where("id", $post['Post']["image_id"]);
                        $foundImage = $imageModel->search()[0];
                        $post['Post']["image"] = $foundImage["Image"]["content"];

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

                        $postList[] = [
                            "post" => $post['Post'],
                            "user" => $userData
                        ];
                    }
                }


            }

//            var_dump([ $isFollow]); die();
            $this->sendJson(["posts" => $postList]);


        } else {
            http_response_code(404);
        }
    }

}