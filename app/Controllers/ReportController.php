<?php

namespace App\Controllers;
use Dompdf\Dompdf;

class ReportController extends BaseController
{
    private $db;
    public function __construct()
    {
        helper(['Form_helper','text']);
        $this->db = db_connect();
    }

    public function exportReport()
    {
        $dompdf = new Dompdf();
        $template = "";
        $dompdf->loadHtml($template);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("TA-Plan-Report.pdf");
        exit();
    }
}