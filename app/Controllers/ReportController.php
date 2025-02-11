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
        //$base64_deped = 'data:image/' . $type_deped . ';base64,' . base64_encode($imageData);
        $base64_deped = '';
        //get the deped matatag
        $matatagPath = 'assets/img/logos/deped-matatag.png';
        $type_matatag = pathinfo($matatagPath, PATHINFO_EXTENSION);
        $img_matatag = file_get_contents($matatagPath);
        //$base64_matatag = 'data:image/' . $type_matatag . ';base64,' . base64_encode($img_matatag);
        $base64_matatag = '';
        //get the gentri division logo
        $path = 'assets/img/Logo.png';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $img = file_get_contents($path);
        //$base64 = 'data:image/' . $type . ';base64,' . base64_encode($img);
        $base64 = '';
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
        <head>
            <style>
            #table{font-size:10px;}
            #table {
            font-family: Bookman Old Style;
            border-collapse: collapse;
            width: 100%;
            }
            
            #table td, #table th {
            border: 1px solid #000;
            padding: 5px;font-size:10px;
            }
            
            #table tr:hover {background-color: #000;}
            
            #table th {
            padding-top: 10px;
            padding-bottom: 10px;
            text-align: center;
            color: #000000;
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
                <tr><td colspan="3"><center><img src='.$base64_deped.' width="75px"/></center></td></tr>
                <tr><td colspan="3"><center><b style="font-size:10px;">Republic of the Philippines</b></center></td></tr>
                <tr><td colspan="3"><center style="font-size:18px;font-weight:bold;">Department of Education</center></td></tr>
                <tr><td colspan="3"><center><b style="font-size:10px;">REGION IV-A CALABARZON</b></center></td></tr>
                <tr><td colspan="3"><center><b style="font-size:10px;">SCHOOL DIVISION OFFICE OF GENERAL TRIAS CITY</b></center></td></tr>
                <tr><td colspan="3"><hr></td></tr>
                <tr><td colspan="3"><center style="font-size:18px;font-weight:bold;">TECHNICAL ASSISTANCE PLAN</center></td></tr>
                <tr><td colspan="3"><center><i style="font-size:10px;">For the month of : '.$mm.' '.$year.'</i></center></td></tr>
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
        $template.='<tr><td colspan="2"><span style="font-size:10px;">Prepared By</span></td><td><span style="font-size:10px;">Approved By</span></td></tr>';
        $template.='<tr><td colspan="3"><br/></td></tr>';
        $template.='<tr><td colspan="2"><i style="font-size:10px;">'.strtoupper($name).'</i></td><td><i style="font-size:10px;">'.strtoupper($account['Fullname']).'</i></td></tr>';
        $template.='<tr><td colspan="2"><i style="font-size:10px;">EPS/PSDS/UNIT/SECTION HEAD</i></td><td><i style="font-size:10px;">ADMINISTRATIVE OFFICER V/ CHIEF EDUCATION SUPERVISOR</i></td></tr>';
        $template.='<tr><td colspan="3">
                        <div class="footer">
                        <hr><br/>
                        <table>
                        <tr>
                        <td><img src='.$base64_matatag.' width="140px"/><img src='.$base64.' width="70px"/></td>
                        <td style="vertical-align:top;">Address : Brgy. Santa Clara, General Trias City, Cavite<br/>Telephone No.: (046) 419-8720<br/>Email Address: division.gentri@deped.gov.ph<br/>Website: www.depedgentri.com</td>
                        </tr>
                        </table> 
                        </div>
                    </td></tr>';
        $template.='</table>
        <body>
        ';
        $dompdf->loadHtml($template);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("TA-Plan.pdf",array("Attachment"=>1));
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
        //$base64_deped = 'data:image/' . $type_deped . ';base64,' . base64_encode($imageData);
        $base64_deped = '';
        //get the deped matatag
        $matatagPath = 'assets/img/logos/deped-matatag.png';
        $type_matatag = pathinfo($matatagPath, PATHINFO_EXTENSION);
        $img_matatag = file_get_contents($matatagPath);
        //$base64_matatag = 'data:image/' . $type_matatag . ';base64,' . base64_encode($img_matatag);
        $base64_matatag = '';
        //get the gentri division logo
        $path = 'assets/img/Logo.png';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $img = file_get_contents($path);
        //$base64 = 'data:image/' . $type . ';base64,' . base64_encode($img);
        $base64 = '';
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
        <head>
            <style>
            #table{font-size:10px;}
            #table {
            font-family: Bookman Old Style;
            border-collapse: collapse;
            width: 100%;
            }
            
            #table td, #table th {
            border: 1px solid #000;
            padding: 5px;font-size:10px;
            }
            
            #table tr:hover {background-color: #000;}
            
            #table th {
            padding-top: 10px;
            padding-bottom: 10px;
            text-align: center;
            color: #000000;
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
                <tr><td colspan="3"><center><img src='.$base64_deped.' width="75px"/></center></td></tr>
                <tr><td colspan="3"><center><b style="font-size:10px;">Republic of the Philippines</b></center></td></tr>
                <tr><td colspan="3"><center style="font-size:18px;font-weight:bold;">Department of Education</center></td></tr>
                <tr><td colspan="3"><center><b style="font-size:10px;">REGION IV-A CALABARZON</b></center></td></tr>
                <tr><td colspan="3"><center><b style="font-size:10px;">SCHOOL DIVISION OFFICE OF GENERAL TRIAS CITY</b></center></td></tr>
                <tr><td colspan="3"><hr></td></tr>
                <tr><td colspan="3"><center style="font-size:18px;font-weight:bold;">TECHNICAL ASSISTANCE REPORT</center></td></tr>
                <tr><td colspan="3"><center><i style="font-size:10px;">For the month of : '.$mm.' '.$year.'</i></center></td></tr>
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
        $template.='<tr><td colspan="2"><span style="font-size:10px;">Prepared By</span></td><td><span style="font-size:10px;">Approved By</span></td></tr>';
        $template.='<tr><td colspan="3"><br/></td></tr>';
        $template.='<tr><td colspan="2"><i style="font-size:10px;">'.strtoupper($name).'</i></td><td><i style="font-size:10px;">'.strtoupper($account['Fullname']).'</i></td></tr>';
        $template.='<tr><td colspan="2"><i style="font-size:10px;">EPS/PSDS/UNIT/SECTION HEAD</i></td><td><i style="font-size:10px;">ADMINISTRATIVE OFFICER V/ CHIEF EDUCATION SUPERVISOR</i></td></tr>';
        $template.='<tr><td colspan="3">
                        <div class="footer">
                        <hr><br/>
                        <table>
                        <tr>
                        <td><img src='.$base64_matatag.' width="140px"/><img src='.$base64.' width="70px"/></td>
                        <td style="vertical-align:top;">Address : Brgy. Santa Clara, General Trias City, Cavite<br/>Telephone No.: (046) 419-8720<br/>Email Address: division.gentri@deped.gov.ph<br/>Website: www.depedgentri.com</td>
                        </tr>
                        </table> 
                        </div>
                    </td></tr>';
        $template.='</table>
        <body>
        ';
        $dompdf->loadHtml($template);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("TA-Plan-Report.pdf",array("Attachment"=>1));
    }
}