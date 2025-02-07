<?php

namespace App\Controllers;
use Dompdf\Dompdf;
use Dompdf\Options;


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
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true); // For enabling PHP functions if required
        $dompdf->setOptions($options);
        $template = "";
        //get the deped logo
        $depedPath = 'assets/img/logos/deped.png';
        $type_deped = pathinfo($depedPath, PATHINFO_EXTENSION);
        $img_deped = file_get_contents($depedPath);
        $base64_deped = 'data:image/' . $type_deped . ';base64,' . base64_encode($img_deped);
        //get the deped matatag
        $matatagPath = 'assets/img/logos/deped-matatag.png';
        $type_matatag = pathinfo($matatagPath, PATHINFO_EXTENSION);
        $img_matatag = file_get_contents($matatagPath);
        $base64_matatag = 'data:image/' . $type_matatag . ';base64,' . base64_encode($img_matatag);
        //get the gentri division logo
        $path = 'assets/img/Logo.png';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $img = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($img);
        //code
        $month = "02";
        $year = "2025";
        $mm="";
        switch($month)
        {
            case "01":
                $mm = "January";
                break;
            case "02":
                $mm = "February";
                break;
            case "03":
                $mm = "March";
                break;
            case "04":
                $mm = "April";
                break;
            case "05":
                $mm = "May";
                break;
            case "06":
                $mm = "June";
                break;
            case "07":
                $mm = "July";
                break;
            case "08":
                $mm = "August";
                break;
            case "09":
                $mm = "September";
                break;
            case "10":
                $mm = "October";
                break;
            case "11":
                $mm = "November";
                break;
            case "12":
                $mm = "December";
                break;
        }
        //builder
        $builder = $this->db->table('tblform a');
        $builder->select('a.DateCreated,a.Code,a.Details,b.schoolName,c.clusterName,d.subjectName,e.actionName,e.Recommendation');
        $builder->join('tblschool b','b.schoolID=a.schoolID','LEFT');
        $builder->join('tblcluster c','c.clusterID=a.clusterID','LEFT');
        $builder->join('tblsubject d','d.subjectID=a.subjectID','LEFT');
        $builder->join('tblaction e','e.formID=a.formID','LEFT');
        $builder->WHERE('DATE_FORMAT(a.DateCreated,"%m")',$month)
                ->WHERE('DATE_FORMAT(a.DateCreated,"%Y")',$year)
                ->groupBy('a.formID');
        $data = $builder->get()->getResult();
        foreach($data as $row)
        {
            $template.="
            <head>
                <style>
                @font-face {
                    font-family: 'Bookman Old Style';
                    src: url('vendor/dompdf/dompdf/lib/fonts/BookmanOldStyle.ttf') format('truetype');
                    }
                #table{font-size:12px;}
                #table td, #table th {
                    border: 1px solid #000;
                    padding: 5px;font-size:12px;
                  }
                </style>
            </head>
            <body>
                <table style='width:100%;'>
                <tbody>
                    <tr><td colspan='3'><center><img src=".$base64_deped." width='50'/></center></td></tr>
                    <tr><td colspan='3'><center><b style='font-size:12px;'>Republic of the Philippines</b></center></td></tr>
                    <tr><td colspan='3'><center style='font-size:20px;font-weight:bold;'>Department of Education</center></td></tr>
                    <tr><td colspan='3'><center><b style='font-size:12px;'>REGION IV-A CALABARZON</b></center></td></tr>
                    <tr><td colspan='3'><center><b style='font-size:12px;'>SCHOOL DIVISION OFFICE OF GENERAL TRIAS CITY</b></center></td></tr>
                    <tr><td colspan='3'><hr></td></tr>
                    <tr><td colspan='3'><center style='font-size:20px;font-weight:bold;'>TECHNICAL ASSISTANCE PLAN</center></td></tr>
                    <tr><td colspan='3'><center><i style='font-size:12px;'>For the month of : ".$mm."".$year."</i></center></td></tr>
                </tbody>
                </table>
            <body>
            ";
        }
        $dompdf->loadHtml($template);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("TA-Plan-Report.pdf",array("Attachment"=>1));
    }
}