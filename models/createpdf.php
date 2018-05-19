<?php

use Dompdf\Dompdf;
use Dompdf\Options;

class Createpdf
{

    public function genPdf($lan, $body)
    {
        require_once '' . ROOT . DS . 'libs' . DS . 'dompdf' . DS . 'autoload.inc.php';
        $dompdf = new Dompdf();
        if (empty($lan))
        {
            $lan = 'portrait';
        }
        $s = 'a4';
        $dompdf->loadHtml($body);
        $dompdf->setPaper($s, $lan);
        $dompdf->render();
        $pdf = $dompdf->output();
        $tmp = time() . rand(1, 99);
        $filename = ROOT . DS . "assets/upload/user/" . $tmp;
        file_put_contents($filename . '.pdf', $pdf);
        $file_return = "/upload/user/" . $tmp . ".pdf";
        return $file_return;
    }

}
