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

    public function exportPlan()
    {
        $dompdf = new Dompdf();
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true); // For enabling PHP functions if required
        $options->set("isImageEnabled", true);
        $dompdf->setOptions($options);
        $name = session()->get('fullname');
        //get the first administrator
        $accountModel = new \App\Models\accountModel();
        $account = $accountModel->WHERE('Position','Chief Education Supervisor')->first();
        $template = "";
        //get the deped
        $depedPath = 'assets/img/logos/deped_logo.webp';
        $type_deped = pathinfo($depedPath, PATHINFO_EXTENSION);
        $imageData = file_get_contents($depedPath);
        $base64_deped = 'data:image/' . $type_deped . ';base64,' . base64_encode($imageData);
        //get the gentri division logo
        $path = 'assets/img/logos/footer.png';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $img = file_get_contents($path);
        $base64_footer = 'data:image/' . $type . ';base64,' . base64_encode($img);
        //header
        $paths = 'assets/img/logos/header.png';
        $types = pathinfo($paths, PATHINFO_EXTENSION);
        $imgs = file_get_contents($paths);
        $base64_header = 'data:image/' . $types . ';base64,' . base64_encode($imgs);
        //code
        $month = $this->request->getGet('month');
        $year = $this->request->getGet('year');
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
        
        $template.='
        <!DOCTYPE html>
        <html>  
        <head>
            <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
            <style>
            #table{font-size:12px;}
            #table {
            border-collapse: collapse;
            width: 100%;
            }
            
            #table td, #table th {
            border: 1px solid #000;
            padding: 5px;font-size:12px;
            }
            
            #table tr:hover {background-color: #000;}
            
            #table th {
            padding-top: 10px;
            padding-bottom: 10px;
            text-align: center;
            color: #000000;
            }
            .header {
                text-align: center;
                margin-bottom: 0px;
            }

            .header img {
                width: 100px;
                height: auto;
                margin-bottom: 10px;
            }
            .footer
            {
                position:fixed;bottom:0;width:100%;font-size:10px;
            }
            </style>
        </head>
        <body>
            <table style="width:100%;">
            <tbody>
                <tr><td colspan="3"><div class="header"></td></tr>
                <tr><td colspan="3"><center><img src='.$base64_deped.' width="75px"/></center></td></tr>
                <tr><td colspan="3"><center><img src='.$base64_header.'/></center></td></tr>
                <tr><td colspan="3"><hr></td></tr>
                <tr><td colspan="3"></div></td></tr>
                <tr><td colspan="3"><center style="font-size:18px;font-weight:bold;">TECHNICAL ASSISTANCE PLAN</center></td></tr>
                <tr><td colspan="3"><center><i style="font-size:12px;">For the month of : '.$mm.' '.$year.'</i></center></td></tr>
                <tr><td colspan="3"><br/></td></tr>
            </tbody>';
        $template.='<tr>
            <td colspan="3">
                <table id="table" style="width:100%;">
                    <thead>
                    <th>T.A. ID</th>
                    <th>CLUSTER</th>
                    <th>SCHOOL NAME</th>
                    <th>AREA OF CONCERN</th>
                    <th>DETAILS OF TECHNICAL ASSISTANCE NEEDED</th>
                    <th>TECHNICAL ASSISTANCE PROVIDED</th>
                    <th>RECOMMENDATION</th>
                    </thead>
                    <tbody>';
        //builder
        $builder = $this->db->table('tblform a');
        $builder->select('a.DateCreated,a.Code,a.Details,b.schoolName,c.clusterName,d.subjectName,e.actionName,e.Recommendation');
        $builder->join('tblschool b','b.schoolID=a.schoolID','LEFT');
        $builder->join('tblcluster c','c.clusterID=a.clusterID','LEFT');
        $builder->join('tblsubject d','d.subjectID=a.subjectID','LEFT');
        $builder->join('tblaction e','e.formID=a.formID','LEFT');
        $builder->WHERE('DATE_FORMAT(e.ImplementationDate,"%m")',$month)
                ->WHERE('DATE_FORMAT(e.ImplementationDate,"%Y")',$year)
                ->groupBy('a.formID');
        $data = $builder->get()->getResult();
        foreach($data as $row)
        {
            $template.='<tr>
                            <td>'.$row->Code.'</td>
                            <td>'.$row->clusterName.'</td>
                            <td>'.$row->schoolName.'</td>
                            <td>'.$row->subjectName.'</td>
                            <td>'.$row->Details.'</td>
                            <td>'.$row->actionName.'</td>
                            <td>'.$row->Recommendation.'</td>
                        </tr>';   
        }
        $template.='</tbody>
                </table>
            </td>
        </tr>';
        $template.='<tr><td colspan="3"><br/></td></tr>';
        $template.='<tr><td colspan="3"><br/></td></tr>';
        $template.='<tr><td colspan="3"><br/></td></tr>';
        //prepared and approved
        $template.='<tr><td colspan="2"><span style="font-size:12px;">Prepared By</span></td><td><span style="font-size:12px;">Approved By</span></td></tr>';
        $template.='<tr><td colspan="3"><br/></td></tr>';
        $template.='<tr><td colspan="2"><i style="font-size:12px;">'.strtoupper($name).'</i></td><td><i style="font-size:12px;">'.strtoupper($account['Fullname']).'</i></td></tr>';
        $template.='<tr><td colspan="2"><i style="font-size:12px;">EPS/PSDS/UNIT/SECTION HEAD</i></td><td><i style="font-size:12px;">CHIEF EDUCATION SUPERVISOR</i></td></tr>';
        $template.='<tr><td colspan="3">
                        <div class="footer">
                        <hr><br/>
                        <img src='.$base64_footer.'/>
                        </div>
                    </td></tr>';
        $template.='</table>
        <body>
        ';
        $dompdf->loadHtml($template);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("TA-Plan-".$mm."-".$year.".pdf",array("Attachment"=>1));
    }

    public function exportReport()
    {
        $dompdf = new Dompdf();
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true); // For enabling PHP functions if required
        $options->set("isImageEnabled", true);
        $dompdf->setOptions($options);
        $name = session()->get('fullname');
        //get the first administrator
        $accountModel = new \App\Models\accountModel();
        $account = $accountModel->WHERE('Position','Chief Education Supervisor')->first();
        $template = "";
        //get the deped
        $depedPath = 'assets/img/logos/deped_logo.webp';
        $type_deped = pathinfo($depedPath, PATHINFO_EXTENSION);
        $imageData = file_get_contents($depedPath);
        $base64_deped = 'data:image/' . $type_deped . ';base64,' . base64_encode($imageData);
        //get the gentri division logo
        $path = 'assets/img/logos/footer.png';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $img = file_get_contents($path);
        $base64_footer = 'data:image/' . $type . ';base64,' . base64_encode($img);
        //header
        $paths = 'assets/img/logos/header.png';
        $types = pathinfo($paths, PATHINFO_EXTENSION);
        $imgs = file_get_contents($paths);
        $base64_header = 'data:image/' . $types . ';base64,' . base64_encode($imgs);
        //code
        $month = $this->request->getGet('month');
        $year = $this->request->getGet('year');
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
        
        $template.='
        <!DOCTYPE html>
        <html>  
        <head>
            <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
            <style>
            #table{font-size:12px;}
            #table {
            border-collapse: collapse;
            width: 100%;
            }
            
            #table td, #table th {
            border: 1px solid #000;
            padding: 5px;font-size:12px;
            }
            
            #table tr:hover {background-color: #000;}
            
            #table th {
            padding-top: 10px;
            padding-bottom: 10px;
            text-align: center;
            color: #000000;
            }
            .header {
                text-align: center;
                margin-bottom: 0px;
            }

            .header img {
                width: 100px;
                height: auto;
                margin-bottom: 10px;
            }
            .footer
            {
                position:fixed;bottom:0;width:100%;font-size:10px;
            }
            </style>
        </head>
        <body>
            <table style="width:100%;">
            <tbody>
                <tr><td colspan="3"><div class="header"></td></tr>
                <tr><td colspan="3"><center><img src='.$base64_deped.' width="75px"/></center></td></tr>
                <tr><td colspan="3"><center><img src='.$base64_header.'/></center></td></tr>
                <tr><td colspan="3"><hr></td></tr>
                <tr><td colspan="3"></div></td></tr>
                <tr><td colspan="3"><center style="font-size:18px;font-weight:bold;">TECHNICAL ASSISTANCE REPORT</center></td></tr>
                <tr><td colspan="3"><center><i style="font-size:12px;">For the month of : '.$mm.' '.$year.'</i></center></td></tr>
                <tr><td colspan="3"><br/></td></tr>
            </tbody>';
        $template.='<tr>
            <td colspan="3">
                <table id="table" style="width:100%;">
                    <thead>
                    <th>T.A. ID</th>
                    <th>CLUSTER</th>
                    <th>SCHOOL NAME</th>
                    <th>AREA OF CONCERN</th>
                    <th>DETAILS OF TECHNICAL ASSISTANCE NEEDED</th>
                    <th>DATE OF IMPLEMENTATION</th>
                    </thead>
                    <tbody>';
        //builder
        $builder = $this->db->table('tblform a');
        $builder->select('a.DateCreated,a.Code,a.Details,b.schoolName,c.clusterName,d.subjectName,e.ImplementationDate');
        $builder->join('tblschool b','b.schoolID=a.schoolID','LEFT');
        $builder->join('tblcluster c','c.clusterID=a.clusterID','LEFT');
        $builder->join('tblsubject d','d.subjectID=a.subjectID','LEFT');
        $builder->join('tblaction e','e.formID=a.formID','LEFT');
        $builder->WHERE('DATE_FORMAT(e.ImplementationDate,"%m")',$month)
                ->WHERE('DATE_FORMAT(e.ImplementationDate,"%Y")',$year)
                ->groupBy('a.formID');
        $data = $builder->get()->getResult();
        foreach($data as $row)
        {
            $template.='<tr>
                            <td>'.$row->Code.'</td>
                            <td>'.$row->clusterName.'</td>
                            <td>'.$row->schoolName.'</td>
                            <td>'.$row->subjectName.'</td>
                            <td>'.$row->Details.'</td>
                            <td>'.$row->ImplementationDate.'</td>
                        </tr>';   
        }
        $template.='</tbody>
                </table>
            </td>
        </tr>';
        $template.='<tr><td colspan="3"><br/></td></tr>';
        $template.='<tr><td colspan="3"><br/></td></tr>';
        $template.='<tr><td colspan="3"><br/></td></tr>';
        //prepared and approved
        $template.='<tr><td colspan="2"><span style="font-size:12px;">Prepared By</span></td><td><span style="font-size:12px;">Approved By</span></td></tr>';
        $template.='<tr><td colspan="3"><br/></td></tr>';
        $template.='<tr><td colspan="2"><i style="font-size:12px;">'.strtoupper($name).'</i></td><td><i style="font-size:12px;">'.strtoupper($account['Fullname']).'</i></td></tr>';
        $template.='<tr><td colspan="2"><i style="font-size:12px;">EPS/PSDS/UNIT/SECTION HEAD</i></td><td><i style="font-size:12px;">CHIEF EDUCATION SUPERVISOR</i></td></tr>';
        $template.='<tr><td colspan="3">
                        <div class="footer">
                        <hr><br/>
                        <img src='.$base64_footer.'/>
                        </div>
                    </td></tr>';
        $template.='</table>
        <body>
        ';
        $dompdf->loadHtml($template);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("TA-Plan-Report-".$mm."-".$year.".pdf",array("Attachment"=>1));
    }

    public function printTA()
    {
        $dompdf = new Dompdf();
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true); // For enabling PHP functions if required
        $options->set("isImageEnabled", true);
        $dompdf->setOptions($options);
        $name = session()->get('fullname');
        $assignModel = new \App\Models\assignModel();
        $assign = $assignModel->first();
        $template = "";
        //get the deped
        $depedPath = 'assets/img/logos/deped_logo.webp';
        $type_deped = pathinfo($depedPath, PATHINFO_EXTENSION);
        $imageData = file_get_contents($depedPath);
        $base64_deped = 'data:image/' . $type_deped . ';base64,' . base64_encode($imageData);
        //get the gentri division logo
        $path = 'assets/img/logos/footer.png';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $img = file_get_contents($path);
        $base64_footer = 'data:image/' . $type . ';base64,' . base64_encode($img);
        //header
        $paths = 'assets/img/logos/header.png';
        $types = pathinfo($paths, PATHINFO_EXTENSION);
        $imgs = file_get_contents($paths);
        $base64_header = 'data:image/' . $types . ';base64,' . base64_encode($imgs);
        //code
        $month = $this->request->getGet('month');
        $year = $this->request->getGet('year');
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
        
        $template.='
        <!DOCTYPE html>
        <html>  
        <head>
            <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
            <style>
            #table{font-size:12px;}
            #table {
            border-collapse: collapse;
            width: 100%;
            }
            
            #table td, #table th {
            border: 1px solid #000;
            padding: 5px;font-size:12px;
            }
            
            #table tr:hover {background-color: #000;}
            
            #table th {
            padding-top: 10px;
            padding-bottom: 10px;
            text-align: center;
            color: #000000;
            }
            .header {
                text-align: center;
                margin-bottom: 0px;
            }

            .header img {
                width: 100px;
                height: auto;
                margin-bottom: 10px;
            }
            .footer
            {
                position:fixed;bottom:0;width:100%;font-size:10px;
            }
            </style>
        </head>
        <body>
            <table style="width:100%;">
            <tbody>
                <tr><td colspan="3"><div class="header"></td></tr>
                <tr><td colspan="3"><center><img src='.$base64_deped.' width="75px"/></center></td></tr>
                <tr><td colspan="3"><center><img src='.$base64_header.'/></center></td></tr>
                <tr><td colspan="3"><hr></td></tr>
                <tr><td colspan="3"></div></td></tr>
                <tr><td colspan="3"><center style="font-size:18px;font-weight:bold;">OFFICE CONSOLIDATED TECHNICAL ASSISTANCE REPORT</center></td></tr>
                <tr><td colspan="3"><center><i style="font-size:12px;">For the month of : '.$mm.' '.$year.'</i></center></td></tr>
                <tr><td colspan="3"><br/></td></tr>
            </tbody>';
        $template.='<tr>
            <td colspan="3">
                <table id="table" style="width:100%;">
                    <thead>
                    <th>T.A. ID</th>
                    <th>CLUSTER</th>
                    <th>SCHOOL NAME</th>
                    <th>AREA OF CONCERN</th>
                    <th>DETAILS OF TECHNICAL ASSISTANCE NEEDED</th>
                    <th>TECHNICAL ASSISTANCE PROVIDED</th>
                    <th>RECOMMENDATION</th>
                    <th>RATING</th>
                    <th>FEEDBACK</th>
                    </thead>
                    <tbody>';
        //builder
        $builder = $this->db->table('tblform a');
        $builder->select('a.DateCreated,a.Code,a.Details,b.schoolName,c.clusterName,d.subjectName,e.actionName,e.Recommendation,f.Rate,f.Message');
        $builder->join('tblschool b','b.schoolID=a.schoolID','LEFT');
        $builder->join('tblcluster c','c.clusterID=a.clusterID','LEFT');
        $builder->join('tblsubject d','d.subjectID=a.subjectID','LEFT');
        $builder->join('tblaction e','e.formID=a.formID','LEFT');
        $builder->join('tblfeedback f','f.formID=a.formID','INNER');
        $builder->WHERE('DATE_FORMAT(e.ImplementationDate,"%m")',$month)
                ->WHERE('DATE_FORMAT(e.ImplementationDate,"%Y")',$year)
                ->groupBy('a.formID');
        $data = $builder->get()->getResult();
        foreach($data as $row)
        {
            $template.='<tr>
                            <td>'.$row->Code.'</td>
                            <td>'.$row->clusterName.'</td>
                            <td>'.$row->schoolName.'</td>
                            <td>'.$row->subjectName.'</td>
                            <td>'.$row->Details.'</td>
                            <td>'.$row->actionName.'</td>
                            <td>'.$row->Recommendation.'</td>
                            <td>'.$row->Rate.'</td>
                            <td>'.$row->Message.'</td>
                        </tr>';   
        }
        $template.='</tbody>
                </table>
            </td>
        </tr>';
        $template.='<tr><td colspan="3"><br/></td></tr>';
        $template.='<tr><td colspan="3"><br/></td></tr>';
        $template.='<tr><td colspan="3"><br/></td></tr>';
        //prepared and approved
        $template.='<tr><td colspan="2"><span style="font-size:12px;">Prepared By</span></td><td><span style="font-size:12px;">Approved By</span></td></tr>';
        $template.='<tr><td colspan="3"><br/></td></tr>';
        $template.='<tr><td colspan="2"><i style="font-size:12px;">'.strtoupper($name).'</i></td><td><i style="font-size:12px;">'.strtoupper($assign['Fullname']).'</i></td></tr>';
        $template.='<tr><td colspan="2"><i style="font-size:12px;">CHIEF EDUCATION SUPERVISOR</i></td><td><i style="font-size:12px;">SCHOOLS DIVISION SUPERINTENDENT</i></td></tr>';
        $template.='<tr><td colspan="3">
                        <div class="footer">
                        <hr><br/>
                        <img src='.$base64_footer.'/>
                        </div>
                    </td></tr>';
        $template.='</table>
        <body>
        ';
        $dompdf->loadHtml($template);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("Office-Consolidated-TA-Report-".$mm."-"."$year".".pdf",array("Attachment"=>1));
    }

    public function printPlan()
    {
        $dompdf = new Dompdf();
        require '../vendor/autoload.php';
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true); // For enabling PHP functions if required
        $options->set("isImageEnabled", true);
        $dompdf->setOptions($options);
        $name = session()->get('fullname');
        $assignModel = new \App\Models\assignModel();
        $assign = $assignModel->first();
        $template = "";
        //get the deped
        $depedPath = 'assets/img/logos/deped_logo.webp';
        $type_deped = pathinfo($depedPath, PATHINFO_EXTENSION);
        $imageData = file_get_contents($depedPath);
        $base64_deped = 'data:image/' . $type_deped . ';base64,' . base64_encode($imageData);
        //get the gentri division logo
        $path = 'assets/img/logos/footer.png';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $img = file_get_contents($path);
        $base64_footer = 'data:image/' . $type . ';base64,' . base64_encode($img);
        //header
        $paths = 'assets/img/logos/header.png';
        $types = pathinfo($paths, PATHINFO_EXTENSION);
        $imgs = file_get_contents($paths);
        $base64_header = 'data:image/' . $types . ';base64,' . base64_encode($imgs);
        //code
        $month = $this->request->getGet('month');
        $year = $this->request->getGet('year');
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
        
        $template.='
        <!DOCTYPE html>
        <html>  
        <head>
            <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
            <style>
            #table{font-size:12px;}
            #table {
            border-collapse: collapse;
            width: 100%;
            }
            
            #table td, #table th {
            border: 1px solid #000;
            padding: 5px;font-size:12px;
            }
            
            #table tr:hover {background-color: #000;}
            
            #table th {
            padding-top: 10px;
            padding-bottom: 10px;
            text-align: center;
            color: #000000;
            }
            .header {
                text-align: center;
                margin-bottom: 0px;
            }

            .header img {
                width: 100px;
                height: auto;
                margin-bottom: 10px;
            }
            .footer
            {
                position:fixed;bottom:0;width:100%;font-size:10px;
            }
            </style>
        </head>
        <body>
            <table style="width:100%;">
            <tbody>
                <tr><td colspan="3"><div class="header"></td></tr>
                <tr><td colspan="3"><center><img src='.$base64_deped.' width="75px"/></center></td></tr>
                <tr><td colspan="3"><center><img src='.$base64_header.'/></center></td></tr>
                <tr><td colspan="3"><hr></td></tr>
                <tr><td colspan="3"></div></td></tr>
                <tr><td colspan="3"><center style="font-size:18px;font-weight:bold;">OFFICE CONSOLIDATED TECHNICAL ASSISTANCE PLAN</center></td></tr>
                <tr><td colspan="3"><center><i style="font-size:12px;">For the month of : '.$mm.' '.$year.'</i></center></td></tr>
                <tr><td colspan="3"><br/></td></tr>
            </tbody>';
        $template.='<tr>
            <td colspan="3">
                <table id="table" style="width:100%;">
                    <thead>
                    <th>T.A. ID</th>
                    <th>CLUSTER</th>
                    <th>SCHOOL NAME</th>
                    <th>AREA OF CONCERN</th>
                    <th>DETAILS OF TECHNICAL ASSISTANCE NEEDED</th>
                    <th>DATE OF IMPLEMENTATION</th>
                    </thead>
                    <tbody>';
        //builder
        $builder = $this->db->table('tblform a');
        $builder->select('a.DateCreated,a.Code,a.Details,b.schoolName,c.clusterName,d.subjectName,e.ImplementationDate');
        $builder->join('tblschool b','b.schoolID=a.schoolID','LEFT');
        $builder->join('tblcluster c','c.clusterID=a.clusterID','LEFT');
        $builder->join('tblsubject d','d.subjectID=a.subjectID','LEFT');
        $builder->join('tblaction e','e.formID=a.formID','LEFT');
        $builder->WHERE('DATE_FORMAT(e.ImplementationDate,"%m")',$month)
                ->WHERE('DATE_FORMAT(e.ImplementationDate,"%Y")',$year)
                ->groupBy('a.formID');
        $data = $builder->get()->getResult();
        foreach($data as $row)
        {
            $template.='<tr>
                            <td>'.$row->Code.'</td>
                            <td>'.$row->clusterName.'</td>
                            <td>'.$row->schoolName.'</td>
                            <td>'.$row->subjectName.'</td>
                            <td>'.$row->Details.'</td>
                            <td>'.$row->ImplementationDate.'</td>
                        </tr>';   
        }
        $template.='</tbody>
                </table>
            </td>
        </tr>';
        $template.='<tr><td colspan="3"><br/></td></tr>';
        $template.='<tr><td colspan="3"><br/></td></tr>';
        $template.='<tr><td colspan="3"><br/></td></tr>';
        //prepared and approved
        $template.='<tr><td colspan="2"><span style="font-size:12px;">Prepared By</span></td><td><span style="font-size:12px;">Approved By</span></td></tr>';
        $template.='<tr><td colspan="3"><br/></td></tr>';
        $template.='<tr><td colspan="2"><i style="font-size:12px;">'.strtoupper($name).'</i></td><td><i style="font-size:12px;">'.strtoupper($assign['Fullname']).'</i></td></tr>';
        $template.='<tr><td colspan="2"><i style="font-size:12px;">CHIEF EDUCATION SUPERVISOR</i></td><td><i style="font-size:12px;">SCHOOLS DIVISION SUPERINTENDENT</i></td></tr>';
        $template.='<tr><td colspan="3">
                        <div class="footer">
                        <hr><br/>
                        <img src='.$base64_footer.'/>
                        </div>
                    </td></tr>';
        $template.='</table>
        </body>
        </html>';
        $dompdf->loadHtml($template);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("Office-Consolidated-TA-Plan-".$mm."-"."$year".".pdf",array("Attachment"=>1));
    }
}