<?php

class AdminController extends Controller
{

    private $admin;

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->admin = new admin();
    }

    public function index()
    {
        
    }

    public function addUser()
    {
        $result = NULL;
        $error = NULL;
        $post = isset($_POST['data']) ? json_decode($_POST['data']) : $post = null;
        if (empty($post))
        {
            $error = 'No post data.';
        } else
        {
            $username = $post->username;
            $psw = md5($post->password);
            $this->admin->adminAddUser($username, $psw);
            $result = new stdClass();
        }

        $return = new stdClass();
        $return->result = true;
        $return->error = $error;

        echo json_encode($return);

        exit();
    }

}
