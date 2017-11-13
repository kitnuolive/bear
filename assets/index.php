<?php

session_start();
//echo "coming soon";
//exit();
/* Default Value Set */
$_COOKIE['app_versions'] = 1;
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__file__)));
define('VIEW_PATH', ROOT . DS . 'views');

require_once(ROOT . DS . 'libs' . DS . 'init.php');
require '' . ROOT . DS . 'libs' . DS . 'app.php';

$url = $_SERVER['REQUEST_URI'];
App::run($url);
