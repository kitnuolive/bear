<?php

class AdminloginController extends Controller
{

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new admin();
    }

    public function system_index()
    {
        $adminsid = Session::get('adminSid');
        if ($adminsid)
        {
            $session = $this->model->getAdminSession(NULL, $adminsid);
            !empty($session) ? Router::redirect('/adminorder/orderList') : FALSE;
        }
        if ($_POST)
        {

            $data = isset($_POST['data']) ? json_decode($_POST['data']) : $data = null;
            if (empty($data->username) || empty($data->password))
            {
                Result::setError('No username or password.');
                Result::showResult();
            }
            $username = $data->username;
            $psw = $data->password;
            $resultUser = $this->model->adminUser($username, md5($psw));
//            var_dump($resultUser);

            if (empty($resultUser))
            {
                Result::setError('Something\'s gone wrong. Please re-enter your information and try again.');
                Result::showResult();
            } else
            {
                $new_session = md5($username . date("H:i:s"));
                $sid = $new_session;
                $session = $this->model->getAdminSession($resultUser->admin_id);

                $result = new stdClass();
                $result->admin_id = $resultUser->admin_id;
                $result->sid = $sid;
                $result->login_date = date("Y-m-d H:i:s");
                $result->last_update = date("Y-m-d H:i:s");
                $result->expired = FALSE;
                $result->admin_name = $resultUser->admin_user;
//                $result->pic = $resultUser->pic;
//                $result->type = $resultUser->type;

                if (isset($session))
                {
                    //update

                    $this->model->updateSessionAdmin($result);
                } else
                {
                    //add
                    $this->model->addSessionAdmin($result);
                }

                Session::set('adminSid', $sid);
                Session::set('adminId', $result->admin_id);

                Session::set('adminName', $result->admin_name);
//                Result::setResult('pass', TRUE);
//                Result::setResult('adminName', $result->admin_name);
//                Result::showResult();
                $return = new stdClass();
                $return->pass = TRUE;
                $return->adminName = $result->admin_name;

                echo json_encode($return);
                exit();
            }
        }
    }

    public function system_logout()
    {
        unset($_SESSION['adminSid']);
        unset($_SESSION['adminId']);
        unset($_SESSION['adminPic']);
        unset($_SESSION['adminType']);
        Router::redirect('/adminlogin/');
    }

}
