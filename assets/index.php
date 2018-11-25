<?php

session_start();
//echo "coming soon";
//exit();
/* Default Value Set */
$_COOKIE['app_versions'] = 1;
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__file__)));
define('VIEW_PATH', ROOT . DS . 'views');
define('VIEW_PATH_MOBILE', ROOT . DS . 'views_m');

require_once(ROOT . DS . 'libs' . DS . 'init.php');
require '' . ROOT . DS . 'libs' . DS . 'app.php';
require_once '' . ROOT . DS . 'libs' . DS . 'Mobile-Detect-master' . DS . 'Mobile_Detect.php';

$url = $_SERVER['REQUEST_URI'];
$server = $_SERVER['HTTP_HOST'];
/**/
$http = "http://";
if (strpos($server, 'localhost') !== FALSE)
{
    $serverUrl = "localhost";
} else if (strpos($server, 'xxx') !== FALSE)
{
    $serverUrl = "xxx";
}
$M = null;
$detect = new Mobile_Detect();
if ($detect->isTablet())
{
    $M = 'tablet';
} // mobile content
else if ($detect->isMobile())
{
    $M = 'mobile';
}
if (!empty($M) && (strtolower($M) == 'mobile' || strtolower($M) == 'tablet'))
{
    if (strpos($server, 'm.' . $serverUrl) === FALSE)
    {
        ($url != '/') ? header("location:http://m." . $serverUrl . $url) : header("location:http://m." . $serverUrl); // redirect destop to mobile
    }
} else if (empty($M))
{
    if (strpos($server, 'm.' . $serverUrl) !== FALSE)
    {
        ($url != '/') ? header("location:http://" . $serverUrl . $url) : header("location:http://" . $serverUrl); // redirect mobile to destop
    }
}

App::run($url, $M);
