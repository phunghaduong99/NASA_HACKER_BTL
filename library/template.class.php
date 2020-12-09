<?php
class Template {

    protected $variables = array();
    protected $_controller;
    protected $_action;

    function __construct($controller,$action) {
        $this->_controller = $controller;
        $this->_action = $action;
    }

    /** Set Variables **/

    function set($name,$value) {
        $this->variables[$name] = $value;
    }

    /** Display Template **/

    function render($doNotRenderHeader = 0) {

        $html = new HTML;
        extract($this->variables);

        if ($doNotRenderHeader == 0) {
            $path1 = file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'header.php');
            if ($path1) {
                include (ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'header.php');
            } else {
                include (ROOT . DS . 'application' . DS . 'views' . DS . 'header.php');
            }
        }
        $path2 = file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . $this->_action . '.php');
        if ($path2) {
            include (ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . $this->_action . '.php');
        }

        if ($doNotRenderHeader == 0) {
            $path3 = file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'footer.php');
            if ($path3) {
                include (ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'footer.php');
            } else {
                include (ROOT . DS . 'application' . DS . 'views' . DS . 'footer.php');
            }
        }
    }

}
