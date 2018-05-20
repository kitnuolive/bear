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
        $post = isset($_POST['data']) ? json_decode($_POST['data']) : $post = null;
        if (empty($post))
        {
            $error = 'No post data.';
        }
        else
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

    public function genPdf($data)
    {
        $root = ROOT;

        $html = '<html><img src="' . $root . '/assets' . $data->pic . '"></html>';
        $pdf = new Createpdf();
        $pdfPath = $pdf->genPdf(null, $html);
        return $pdfPath;
    }

}
