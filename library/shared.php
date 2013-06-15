<?php

/* Check if environment is development and display errors */

function setReporting() {
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

/* Check for Magic Quotes and remove them */

// Recursive function that traverses from array down to the element level. At element level, remove slashes from element
function stripSlashesDeep($value) {
  $value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
  return $value;
}

function removeMagicQuotes() {
  if ( get_magic_quotes_gpc() ) {
    $_GET = stripSlashesDeep($_GET);
    $_POST = stripSlashesDeep($_POST);
    $_COOKIE = stripSlashesDeep($_COOKIE);
  }
}

/* Check registered globals and remove them */
function unregisterGlobals() {
  if (ini_get('register_globals')) {
    $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
    foreach ($array as $value) {
      foreach($GLOBALS[$value] as $key => $var) {
        if ($var === $GLOBALS[$key]) {
          unset($GLOBALS[$key];
        }
      }
    }
  }
}

/* Main function that parses a given url and gets the controller, action, and querystring. */
function callHook() {
  global $url;

  # Break the URL into an array
  $urlArray = array();
  $urlArray = explode('/', $url);

  $controller = $urlArray[0];
  array_shift($urlArray);
  $action = $urlArray[0];
  array_shift($urlArray);
  $queryString = $urlArray;

  $controllerName - $controller;
  $controller = ucwords($controller);
  $model = rtrim($controller, 's');
  $controller .= 'Controller';
  $dispatch = new $controller($model, $controllerName, $action);

  # Cool code, for call_user_func, we call the action method of the dispatch class and pass through an array of parameters
  if((int)method_exists($controller, $action)) {
    call_user_func_array(array($dispatch,$action), $queryString);
  } else {
    /* Error generation code goes here */
  }
}

function __autoload($className) {
  if (file_exists(ROOT . DS . 'library' . DS . strtolower($className) . '.class.php')){
    require_once(ROOT . DS . 'library' . DS . strtolower($className) . '.class.php');
  } elseif (file_exists(ROOT . DS . 'application' . DS . 'controllers' . DS . strtolower($className) . '.php')) {
    require_once(ROOT . DS . 'application' . DS . 'controllers' . DS . strtolower($className) . '.php');
  } elseif (file_exists(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . '.php')) {
    require_once(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . '.php');
  } else {
    /* Error Generation Code here */
  }
}
















