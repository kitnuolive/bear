<?php

class FrameController extends Controller
{

    private $frameClass;

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->frameClass = new Frame();
    }

    public function frameCategory()
    {
        $category = $this->frameClass->frameCategory(NULL, NULL);

        echo json_encode($category);
        exit();
    }

    public function frameList()
    {

        $data = isset($_POST['data']) ? json_decode($_POST['data']) : $data = null;
        isset($data) ? $search = $data->search : $search = null;

        $params = App::getRouter()->getParams();
        isset($params[0]) ? $page = (int) $params[0] : $page = 0;

        $category = $this->frameClass->frameList($search, $page);

        echo json_encode($category);
        exit();
    }

    public function stickerCategory()
    {
        $category = $this->frameClass->stickerCategory(NULL, NULL);

        echo json_encode($category);
        exit();
    }

    public function stickerList()
    {

        $data = isset($_POST['data']) ? json_decode($_POST['data']) : $data = null;
        isset($data) ? $search = $data->search : $search = null;

        $params = App::getRouter()->getParams();
        isset($params[0]) ? $page = (int) $params[0] : $page = 0;

        $category = $this->frameClass->stickerList($search, $page);

        echo json_encode($category);
        exit();
    }

}
