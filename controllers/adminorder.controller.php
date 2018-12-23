<?php

class AdminorderController extends Controller
{

//    private $order;

    public function __construct($data = array())
    {
        parent::__construct($data);
//        $this->ordert = null;
    }

    public function index()
    {
       exit();
    }
    
    public function orderList(){
        $data = isset($_POST['data']) ? json_decode($_POST['data']) : $data = null;
        isset($data) ? $search = $data->search : $search = null;

        $params = App::getRouter()->getParams();
        isset($params[0]) ? $page = (int) $params[0] : $page = 0;

        $orderClass = new order();
        $order = $orderClass->orderList($search, $page);

        echo json_encode($order);
        exit();
    }

}
