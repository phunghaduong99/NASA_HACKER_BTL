<?php

class TestApi extends VanillaApiController
{
    function GET($args)
    {
        echo json_encode([
            "greeting" => "hello",
            "name" => "NASA hackers",
            "args" => $args
        ]);
    }

    function POST($args)
    {
        var_dump($this->body);
    }

    function PUT($args) {
        echo "put";
    }
}
