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

    function add($queryString = "")
    {
        global $method;
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
            $this->user = new User();
            //lay followings cua user
            $this->user->where('id', $loginUserId);
            $this->user->showHasMany();
            if ($queries["limit"] && is_numeric($queries["limit"])) {
                $this->user->setHasManyLimit("Follow", $queries["limit"]);
            }

            if ($queries["page"] && is_numeric($queries["page"])) {
                $this->user->setHasManyPage("Follow", $queries["page"]);
            }
            $foundUsers = $this->user->search();
            if (empty($foundUsers)) {
                http_response_code(404);
                return;
            }
            $followers = $foundUsers[0]["Follow"];
            $postList = [];
            foreach ($followers as $item) {
                $userId = $item['Follow']["follower_id"];
                $this->user->where('id', $userId);
                $this->user->showHasMany();
                $this->user->showHasOne();
                $result = $this->user->search();
                $userData = $result[0]['User'];
                $userData["avatar"] = $result[0]["Image"]["content"];
                unset($userData['password']);
                unset($userData["created_at"]);
                unset($userData["update_at"]);
                if ($result[0]["Post"]) {
                    $imageModel = new Image();
                    $imageModel->where("id", $result[0]["Post"][0]['Post']["image_id"]);
                    $foundImage = $imageModel->search()[0];
                    $result[0]["Post"][0]['Post']["image"] = $foundImage["Image"]["content"];
                    $postList[] = [
                        "post" => $result[0]["Post"][0]['Post'],
                        "user" => $userData
                    ];
                }
            }

//            var_dump([ $isFollow]); die();
            $this->sendJson(["posts" => $postList]);


        } else {
            http_response_code(404);
        }
    }

}