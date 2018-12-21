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

    public function updateOrder()
    {
        $result = NULL;
        $error = NULL;
//        $data = 'data:image/png;base64,AAAFBfj42Pj4';
        $post = isset($_POST['data']) ? json_decode($_POST['data']) : $post = null;
//        print_r($_POST);
//        print_r($_FILES);
        if (empty($post))
        {
            $error = 'No post data.';
        } else
        {
            $data = $post->png;
            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);
            $file = md5(time()) . ".png";
            file_put_contents(ROOT . DS . "assets/upload/order/{$file}", $data);
            $orderClass = new order();
            $objOrder = new stdClass();
            $objOrder->bear_order_id = $post->bear_order_id;
            $objOrder->bear_order_path = "/upload/order/{$file}";
            $order_update = $orderClass->orderUpdate($objOrder);
//            print_r($order_update);
            $result = new stdClass();
            $result->bear_order_id = $order_update->bear_order_id;
            $return = new stdClass();
            $return->result = $result;
            $return->error = $error;
            echo json_encode($return);

            exit();
        }
        exit();
    }

    public function genOrderNumber()
    {

        $result = NULL;
        $error = NULL;
//        $_POST['data'] = '{"bear_order_path_svg":"path","frame_category_id":"1","frame_list_id":"1","sticker_list_id":"1","frame_category_code":"ev"}';
        $post = isset($_POST['data']) ? json_decode($_POST['data']) : $post = null;
//        print_r($_POST);
//        print_r($_FILES);
        if (empty($post))
        {
            $error = 'No post data.';
        } else
        {
            $path = $this->upload->uploadFile($_FILES);
            $orderClass = new order();
            $date = date("Y-m-d H:i:s");
            //save order
            $objOrder = new stdClass();
            $objOrder->bear_order_number = $post->frame_category_code . time();
            $objOrder->bear_order_status = 1;
            $objOrder->bear_order_path_svg = $path;
            $objOrder->frame_list_id = $post->frame_list_id;
            $objOrder->sticker_list_id = $post->sticker_list_id;
            $objOrder->frame_category_id = $post->frame_category_id;
            $objOrder->create_date = $date;

            $order_update = $orderClass->orderUpdate($objOrder);
//            print_r($order_update);
            $result = new stdClass();
            $result->bear_order_id = $order_update->bear_order_id;
            $result->bear_order_number = $objOrder->bear_order_number;
            $return = new stdClass();
            $return->result = $result;
            $return->error = $error;
            echo json_encode($return);

            exit();
        }
        exit();
    }

    public function createOrder()
    {
//        var_dump($_FILES['file']);
        $result = NULL;
        $error = NULL;
        $post = isset($_POST['data']) ? json_decode($_POST['data']) : $post = null;
        if (empty($post))
        {
            $error = 'No post data.';
        } else
        {

//            var_dump($_FILES['file']);
            //gen order number
//            $path = $this->upload->uploadFile($_FILES);
            $path = 'order path';
            $orderClass = new order();
            $ordernumber = $orderClass->genOrderNumber();

            $order_id = $ordernumber->id;
            $order_number = $ordernumber->number;

            $eng = $orderClass->engArr();
            $a = (int) substr($order_number, 0, 2);
            $b = (int) substr($order_number, 2, 2);
            $c = substr($order_number, 4);
            $orderNumberUser = $eng[$a] . $eng[$b] . $c;

            //save account
            //select account
            $accountClass = new account();
            $obj = new stdClass();
            $obj->user_account_email = $post->user_account_email;
            $accList = $accountClass->accountList($obj);
            $user_account_id = NULL;
            if (!empty($accList->view[0]->user_account_id))
            {
                $user_account_id = $accList->view[0]->user_account_id;
            }

            $objAcc = new stdClass();
            $objAcc->user_account_id = $user_account_id;
            $objAcc->user_account_email = $post->user_account_email;
            $objAcc->user_account_tel = $post->user_account_tel;
            $objAcc->user_account_name = $post->user_account_email;
            $date = date("Y-m-d H:i:s");
            if (empty($user_account_id))
            {
                $objAcc->create_date = $date;
            }
            $objAcc->last_update = $date;

            $user_account_id = $accountClass->accountUpdate($objAcc);

            //save order
            $objOrder = new stdClass();
            $objOrder->bear_order_id = $order_id;
            $objOrder->bear_order_status = 1;
            $objOrder->user_account_id = $user_account_id->user_account_id;
            $objOrder->bear_order_path = $path;
            $objOrder->create_date = $date;

            $orderClass->orderUpdate($objOrder);
            $result = new stdClass();
            $result->orderNumber = $orderNumberUser;
        }

        $return = new stdClass();
        $return->result = $result;
        $return->error = $error;

        echo json_encode($return);

        exit();
    }

    public function sendEmail()
    {
        //mail
        require_once ROOT . DS . 'models' . DS . strtolower('mailer') . '.php';
        $mailer = new mailer();
        $mail[] = 'kitnumaster@gmail.com';
        $mailMsg = 'test email>';
        $file[] = null;
        $resultmail = $mailer->sendMail('test', $mail, null, null, $mailMsg, null);
        Result::setResult('result', $resultmail);
        Result::showResult();
    }

    public function genPdf($data)
    {
        $root = ROOT;

        $html = '<html><img src="' . $root . '/assets' . $data->pic . '"></html>';
        $pdf = new Createpdf();
        $pdfPath = $pdf->genPdf(null, $html);
        return $pdfPath;
    }

}
