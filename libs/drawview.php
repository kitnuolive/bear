<?php

class DrawView
{

    protected $data;
    protected $viewpath;

    public function __construct($data = array(), $page, $method, $noview = null)
    {
        if (!empty($noview))
        {
            $this->viewpath = VIEW_PATH . '/' . $page . '/' . $method . '.html';
            $this->data = $data;
        }
        else
        {
            $this->viewpath = VIEW_PATH . '/' . $page . '/' . 'view' . '/' . $method . '.html';
            $this->data = $data;
        }
    }

    public function drawHtml($return = null, $include = null)
    {
        $dataview = $this->data;
        if (!empty($include))
        {
            return include($this->viewpath);
        }
        ob_start();
        include($this->viewpath);
        $a = ob_get_clean();
        if (!empty($return))
        {
            return $a;
        }
        echo $a;
        die;
    }

}
