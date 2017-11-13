<?php

date_default_timezone_set('Asia/Bangkok');
require '' . ROOT . DS . 'config' . DS . 'config.php';
require '' . ROOT . DS . 'config' . DS . 'configDb.php';

function __autoload($class_name)
{
    $controllers_path = ROOT . DS . 'controllers' . DS . str_replace('controller', '', strtolower($class_name)) . '.controller.php';
    $model_path = ROOT . DS . 'models' . DS . strtolower($class_name) . '.php';
//    echo $controllers_path . "<P>";
//    echo $model_path . "<P>";

    if (file_exists($controllers_path))
    {
        require_once($controllers_path);
    }
    if (file_exists($model_path))
    {
        require_once($model_path);
    }
}
