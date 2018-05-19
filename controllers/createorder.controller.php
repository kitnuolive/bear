<?php

class createorderController extends Controller
{

    private $upload;

    public function __construct()
    {
        parent::__construct();
        $this->upload = new uploadController();
    }

    public function index()
    {
        echo 5;
        exit();
    }

    public function createOrder()
    {
//        var_dump($_FILES['file']);
        $result = NULL;
        $error = NULL;
        if (empty($_FILES['file']))
        {
            $error = 'No file data.';
        }
        else
        {
            var_dump($_FILES['file']);
            
            //gen order number
            
            //gen pdf
            
            //save order
            
            //send email
        }

        $return = new stdClass();
        $return->result = $result;
        $return->error = $error;

        echo json_encode($return);

        exit();
    }

}
