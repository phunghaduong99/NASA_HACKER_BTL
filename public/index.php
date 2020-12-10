<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));

$method = $_SERVER['REQUEST_METHOD'];
//echo $method;

$request_uri = $_SERVER['REQUEST_URI'];
//echo $request_uri;

$url = $_GET['url'];
//echo $url;
//$url = 'products/view/1';

$loginUserId = null;

require_once (ROOT . DS . 'library' . DS . 'bootstrap.php');