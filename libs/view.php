<?php

class View
{

    protected $data;
    protected $path;

    protected static function getDefaultViewPath($m)
    {
        $router = App::getRouter();
        if (!$router)
        {
            return false;
        }

        $controller_dir = $router->getController();
        $template_name = $router->getMethodPrefix() . $router->getAction() . '.html';
        if (!empty($m))// mobile
        {
            if (is_file(VIEW_PATH_MOBILE . DS . $controller_dir . DS . $template_name))
            {
                return VIEW_PATH_MOBILE . DS . $controller_dir . DS . $template_name;
            } else
            {
                return VIEW_PATH . DS . $controller_dir . DS . $template_name;
            }
        }
        return VIEW_PATH . DS . $controller_dir . DS . $template_name;
    }

    public function __construct($data = array(), $path = null, $m = NULL)
    {

//        var_dump($data);
//        echo "<P>";
//        var_dump($path);
//        echo "<P>";
        if (!$path)
        {
            $path = self::getDefaultViewPath($m);
        }
        if (!file_exists($path))
        {
            Router::redirect(Config::get('Error_page'));
        }
//        var_dump($path);
        $this->path = $path;
        $this->data = $data;
    }

    public function render()
    {
        $data = $this->data;
        //var_dump($this->path);
        ob_start();
        include($this->path);
        $content = ob_get_clean();
        return $content;
    }

}
