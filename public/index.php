<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));

$url = $_GET['url'];

//$url = (isset($_GET['url'])) ? $_GET['url'] : "items/viewall";

require_once (ROOT . DS . 'library' . DS . 'bootstrap.php');