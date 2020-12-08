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

    function new($queryString = "") {
        global $method;
        $this->doNotRenderHeader=1;
        if ($method == 'GET') {

        }
    }

    function afterAction() {

    }


}