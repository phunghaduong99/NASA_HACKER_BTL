<?php

/** Check if environment is development and display errors **/

function setReporting()
{
    if (DEVELOPMENT_ENVIRONMENT == true) {
        error_reporting(E_ALL);
        ini_set('display_errors', 'On');
    } else {
        error_reporting(E_ALL);
        ini_set('display_errors', 'Off');
        ini_set('log_errors', 'On');
        ini_set('error_log', ROOT . DS . 'tmp' . DS . 'logs' . DS . 'error.log');
    }
}

/** Check for Magic Quotes and remove them **/

function stripSlashesDeep($value)
{
    $value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
    return $value;
}

function removeMagicQuotes()
{
    if (get_magic_quotes_gpc()) {
        $_GET = stripSlashesDeep($_GET);
        $_POST = stripSlashesDeep($_POST);
        $_COOKIE = stripSlashesDeep($_COOKIE);
    }
}

/** Check register globals and remove them **/

function unregisterGlobals()
{
    if (ini_get('register_globals')) {
        $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
        foreach ($array as $value) {
            foreach ($GLOBALS[$value] as $key => $var) {
                if ($var === $GLOBALS[$key]) {
                    unset($GLOBALS[$key]);
                }
            }
        }
    }
}

/** Secondary Call Function **/

function performAction($controller, $action, $queryString = null, $render = 0)
{

    $controllerName = ucfirst($controller) . 'Controller';
    $dispatch = new $controllerName($controller, $action);
    $dispatch->render = $render;
    return call_user_func_array(array($dispatch, $action), $queryString);
}

/** Routing **/

function routeURL($url)
{
    global $routing;

    foreach ($routing as $pattern => $result) {
        if (preg_match($pattern, $url)) {
            return preg_replace($pattern, $result, $url);
        }
    }

    return ($url);
}

/** Main Call Function **/

function callHook()
{
    global $url;
    global $request_uri;
    global $default;
    $queryString = [parse_url($request_uri, PHP_URL_QUERY)];
    parse_str($queryString[0], $queryString);
    $idQuery = null;
    if (!isset($url)) {
        $controller = $default['controller'];
        $action = $default['action'];
    } else {
        $url = rtrim($url, '/');
        $url = routeURL($url);
        $urlArray = explode("/", $url);
        $controller = $urlArray[0];
        array_shift($urlArray);
        if (isset($urlArray[0])) {
            $action = $urlArray[0];
            array_shift($urlArray);
            if(isset($urlArray[0]) ){
                $idQuery = $urlArray[0];
                array_shift($urlArray);
            }
        } else {
            $action = 'index'; // Default Action
        }
    }

    $actionParams = [$queryString, $idQuery];
    $controllerName = ucfirst($controller) . 'Controller';
//	var_dump([$url, $request_uri, $controllerName, $action]);die;
//    var_dump((int)method_exists($controllerName, $action));die;
//	echo "$controllerName, $controller, $action </br>";

    if ((int)method_exists($controllerName, $action)) {

        $dispatch = new $controllerName($controller, $action);
//        var_dump(call_user_func_array($queryString));die;
        call_user_func_array(array($dispatch, "beforeAction"), $actionParams);
        call_user_func_array(array($dispatch, $action), $actionParams);
        call_user_func_array(array($dispatch, "afterAction"), $actionParams);
    } else {
        header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    }
}


/** Autoload any classes that are required **/

function __autoload($className)
{
    if (file_exists(ROOT . DS . 'library' . DS . strtolower($className) . '.class.php')) {
        require_once(ROOT . DS . 'library' . DS . strtolower($className) . '.class.php');
    } else if (file_exists(ROOT . DS . 'application' . DS . 'controllers' . DS . strtolower($className) . '.php')) {
        require_once(ROOT . DS . 'application' . DS . 'controllers' . DS . strtolower($className) . '.php');
    } else if (file_exists(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . '.php')) {
        require_once(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . '.php');
    } else if (file_exists(ROOT . DS . 'application' . DS . 'services' . DS . strtolower($className) . '.php')) {
        require_once(ROOT . DS . 'application' . DS . 'services' . DS . strtolower($className) . '.php');
    } else {
        throw new Exception("Cannot auto load file");
    }
}


/** GZip Output **/

function gzipOutput()
{
    $ua = $_SERVER['HTTP_USER_AGENT'];

    if (0 !== strpos($ua, 'Mozilla/4.0 (compatible; MSIE ')
        || false !== strpos($ua, 'Opera')) {
        return false;
    }

    $version = (float)substr($ua, 30);
    return (
        $version < 6
        || ($version == 6 && false === strpos($ua, 'SV1'))
    );
}

function getSession()
{
    global $loginUserId;
    include_once(ROOT . DS . 'helpers/jwt.php');

    $token = $_COOKIE["Authorization"];
    if ($token) {
        $jwtHelper = new Jwt();
        $tokenValidate = $jwtHelper->validate($token);

        if ($tokenValidate && $tokenValidate->id) {
            $loginUserId = $tokenValidate->id;
        }
    }
}

/** Get Required Files **/

gzipOutput() || ob_start("ob_gzhandler");


$cache = new Cache();
$inflect = new Inflection();
setReporting();
removeMagicQuotes();
unregisterGlobals();
getSession();
callHook();

?>