<?php

class bearAdminController extends Controller
{

    public $user;

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->user = new user();
    }

    public function index()
    {
        $sid = Session::get('asid');
        if (!empty($sid))
        {
            $result = $this->user->chkSession($sid);
            if ($result)
            {
                Router::redirect('/');
            }
        }
    }

    public function login()
    {
        $obj = isset($_POST['data']) ? json_decode($_POST['data']) : $data = null;
//        $obj = new stdClass();
//        $obj->username = 'admin';
//        $obj->password = md5('123456');
//        
//        var_dump($obj);
        if (empty($obj))
        {
            Result::setError('No username or password!');
        }
        else
        {

            $obj->password = md5($obj->password);
            $result = $this->user->adminLogin($obj);
            if ($result)
            {
                $asid = md5($obj->username . time());

                $ip = Setting_utill::get_user_ip_address();

                $this->user->setSession($asid, $result->adminUserId, $ip);
                
                /*set session*/
                
                Session::set('asid', $asid);
                Session::set('adminUserId', $result->adminUserId);
                Session::set('adminUserDisplayName', $result->adminUserDisplayName);
                Session::set('adminUserPic', $result->adminUserPic);
                Session::set('adminUserPermission', $result->adminUserPermission);
                
                /**/
                Result::setResult('pass', true);
            }
            else
            {
                Result::setError('Username or password incorrect!');
            }
        }

        Result::showResult();
    }

    public function logout()
    {
        $asid = Session::get('asid');
//        var_dump($asid);exit();
        $this->user->destroySession($asid);
        Session::destory();
        Router::redirect('/');
    }

}
