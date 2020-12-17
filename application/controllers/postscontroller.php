<?php

class PostsController extends VanillaController {

    function beforeAction () {

    }

    function index($queryString="") {
        header("Location: " . BASE_PATH,true, 302);
        exit();
    }

    function view($queryString="") {
        global $method;
        $this->doNotRenderHeader=1;
        if ($method == "GET") {
            $this->set("title", "First post");
        }
    }

    function edit($queryString = "") {
        global $method;
        $this->doNotRenderHeader=1;
        if ($method == 'GET') {

        }elseif ($method == 'POST') {
            $this->sendJson("Sent edit post data");
        }
    }

    function vAdd($queryString = "") {
        global $method;

        if ($method == "POST") {
            if(isset($this->body["post_description"]) && (isset($this->body["image"]) || $_FILES["image"] ) ){
                $this->Post->post_description = $this->body["post_description"];
                $this->Post->user_id = "1";
                $this->updateImage();
                $this->Post->save();
                http_response_code(200);
                $this->sendJson([
                    "status" => "OK" ,
                    "message" =>  "Them post thanh cong."
                ]);
            }else{
                http_response_code(401);
                $this->sendJson([
                    "message" =>  "Them post that bai."
                ]);
            }
        }
        else {
            http_response_code(404);
        }

        $this->doNotRenderHeader=1;
        if ($method == 'GET') {

        }
    }

    function add($queryString = "") {
        global $method;
        $this->doNotRenderHeader=1;
        if ($method == 'GET') {

        }
    }

    function afterAction() {

    }

    function updateImage(){
        if ($_FILES["image"]["name"] != null) {
            $image_base64 = base64_encode(file_get_contents($_FILES['image']['tmp_name']));
            $image = 'data:image/png;base64,'.$image_base64;
            // call Image table
            $this->Image = new Image();
            $this->Image->content = $image;
            $this->Image->save();
            // get image_id inserted
            $image_id = $this->Image->insert_id;
            $this->Post->insert_id = null;
            if(is_numeric($image_id) && intval($image_id) >0){
                //set image of User
                $this->Post->image_id = $image_id;
            }
        }
        else if($this->body["image"] != null) {
            $image_base64 = base64_encode(file_get_contents($this->body["image"]));
            $image = 'data:image/png;base64,'.$image_base64;
            // call Image table
            $this->Image = new Image();
            $this->Image->content = $image;
            $this->Image->save();
            // get image_id inserted
            $image_id = $this->Image->insert_id;
            $this->Post->insert_id = null;
            if(is_numeric($image_id) && intval($image_id) >0){
                //set image of User
                $this->Post->image_id = $image_id;
            }
        }
    }

}