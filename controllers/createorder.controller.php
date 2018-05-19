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
//            var_dump($_FILES['file']);
            //gen order number
            $path = $this->upload->uploadFile($_FILES);
            $_FILES['file'] = $_FILES['filePng'];
            $pathpng = $this->upload->uploadFile($_FILES);
            $orderClass = new order();
            $ordernumber = $orderClass->genOrderNumber($path);

            $order_id = $ordernumber->id;
            $order_number = $ordernumber->number;

            $eng = $orderClass->engArr();
            $a = (int) substr($order_number, 0, 2);
            $b = (int) substr($order_number, 2, 2);
            $c = substr($order_number, 4);
            $numberForPDF = $eng[$a] . $eng[$b] . $c;



            //gen pdf
            $data = new stdClass();
            $data->number = $numberForPDF;
            $data->pic = $pathpng;
            $pdf = $this->genPdf($data);

            var_dump($pdf);

            //save order
            //send email
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
