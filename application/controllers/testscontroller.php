<?php

class TestsController extends VanillaController {

    function beforeAction () {

    }

    function index($queryString="") {
        $this->sendJson([
            "query"=>$queryString
        ]);
    }

    function afterAction() {

    }


}