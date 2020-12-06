<?php

class VanillaApiController
{
    protected $body;
    function __construct() {
        $this->body = $this->getBodyData();
    }

    function GET($args) {
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
    }

    function POST($args) {
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
    }

    function PUT($args) {
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
    }

    function DELETE($args) {
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
    }


    private function getBodyData()
    {
        if(!empty($_POST))
        {
            // when using application/x-www-form-urlencoded or multipart/form-data as the HTTP Content-Type in the request
            // NOTE: if this is the case and $_POST is empty, check the variables_order in php.ini! - it must contain the letter P
            return $_POST;
        }

        // when using application/json as the HTTP Content-Type in the request
        $post = json_decode(file_get_contents('php://input'), true);
        if(json_last_error() == JSON_ERROR_NONE)
        {
            return $post;
        }

        return [];
    }
}