<?php

require_once '' . ROOT . DS . 'libs' . DS . 'router.php';
require_once '' . ROOT . DS . 'libs' . DS . 'controller.php';
require_once '' . ROOT . DS . 'libs' . DS . 'view.php';
require_once '' . ROOT . DS . 'libs' . DS . 'drawview.php';
require_once '' . ROOT . DS . 'libs' . DS . 'result.php';
require_once '' . ROOT . DS . 'libs' . DS . 'model.php';
require_once '' . ROOT . DS . 'libs' . DS . 'db.php';
require_once '' . ROOT . DS . 'libs' . DS . 'session.php';
require_once '' . ROOT . DS . 'libs' . DS . 'setting_utill.class.php';
require_once '' . ROOT . DS . 'libs' . DS . 'email.php';

class App
{

    protected static $router;
    public static $db;
    public static $result;

    public static function getRouter()
    {
        return self::$router;
    }

    public static function run($uri, $m)
    {
        $errpage = Config::get('Error_page');
        self::$db = new DB(Config::get('db.host'), Config::get('db.user'), Config::get('db.password'), Config::get('db.db_name'));
        self::$router = new Router($uri);
        $classname = self::$router->getController();
        $layout = self::$router->getRoute();
//        echo 'class ' . $classname . "<P>";
//        echo 'layout ' . $layout . "<P>";
//        exit();

        $controller_class = ucfirst($classname) . 'Controller';
        $controller_method = strtolower(self::$router->getMethodPrefix() . self::$router->getAction());

//        echo $controller_class . "<P>";
//        echo $controller_method . "<P>";

        $controller_object = class_exists($controller_class) ? new $controller_class() : FALSE;
//        $controller_object = new $controller_class();
//        var_dump($controller_object);

        $adminlogin = Config::get('adminlogin');
        $adminpage = Config::get('admin_page');
        if (in_array($classname, $adminpage))
        {

            $asid = Session::get('asid');
//            $asid = 1;
//            echo $asid;
            if (empty($asid))
            {
                echo 404;
                Router::redirect($errpage);
                die();
            }
            $admin = new user();
            $session = $admin->chkSession($asid);
            if ($session)
            {
                $layout = 'adminindex';
            } else
            {
                echo 404;
                Router::redirect($errpage);
                die();
            }
        }
        if ($adminlogin == strtolower(ucfirst($classname)))
        {
            $controller_object->$controller_method();
            $view_object = new View(null, VIEW_PATH . DS . $adminlogin . '.html');
            $content = $view_object->render();
        } else
        {

            if (method_exists($controller_object, $controller_method))
            {
//                echo $controller_method;
                $view_path = $controller_object->$controller_method();
                $view_object = new View($controller_object->getData(), $view_path, $m);
                $content = $view_object->render();
            } else
            {
                echo 404;
                Router::redirect($errpage);
            }
        }

        $layout_path = NULL;
        $layout_default = Config::get('path_default');
//        echo $classname . "<P>";
        if (!in_array($classname, $layout_default))
        {
            $layout_path = VIEW_PATH . DS . $layout . '.html';
//            echo $layout_path . "<P>";
            $layout_view_object = new View(compact('content'), $layout_path);
            echo $layout_view_object->render();
        } else
        {
            echo $content;
        }
    }

}
