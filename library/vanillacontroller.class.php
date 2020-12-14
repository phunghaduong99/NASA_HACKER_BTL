<?php

class VanillaController {

    protected $_controller;
    protected $_action;
    protected $_template;
    protected $body;
    protected $curUser;
    protected $headerPath;
    protected $footerPath;

    public $doNotRenderHeader;
    public $render;

    function __construct($controller, $action) {
        global $loginUserId;
        global $inflect;

        $this->_controller = ucfirst($controller);
        $this->_action = $action;
        $model = ucfirst($inflect->singularize($controller));

        $this->doNotRenderHeader = 0;
        $this->render = 1;
        $this->body = $this->getBodyData();
        $this->$model = new $model();
        $this->curUser = $loginUserId;

        $this->_template = new Template($controller,$action);

    }

    function set($name,$value) {
        $this->_template->set($name,$value);
    }

    function sendJson($data) {
        header('Content-type: application/json');
        $this->render = 0;

        if (is_array($data)) {
            $data = json_encode($data);
        }
        echo $data;
    }

    public function getBodyData()
    {
        $data = [];
        if (!empty($_POST)) {
            // when using application/x-www-form-urlencoded or multipart/form-data as the HTTP Content-Type in the request
            // NOTE: if this is the case and $_POST is empty, check the variables_order in php.ini! - it must contain the letter P
            $data = $_POST;
        } else {
            // when using application/json as the HTTP Content-Type in the request
            $post = json_decode(file_get_contents('php://input'), true);
            if(json_last_error() == JSON_ERROR_NONE)
            {
                $data = $post;
            }
        }

        return $data;
    }

    function __destruct() {
        if ($this->render) {
            if ($this->headerPath) {
                $this->_template->setHeaderPath($this->headerPath);
            }
            if ($this->footerPath) {
                $this->_template->setFooterPath($this->footerPath);
            }
            $this->_template->render($this->doNotRenderHeader);
        }
    }

}