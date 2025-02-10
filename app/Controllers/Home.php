<?php

namespace App\Controllers;
use App\Libraries\Hash;

class Home extends BaseController
{
    private $db;
    public function __construct()
    {
        helper(['Form_helper','text']);
        $this->db = db_connect();
    }
    public function index()
    {
        $systemModel = new \App\Models\systemModel();
        $system = $systemModel->first();

        $data = ['about'=>$system];
        return view('welcome_message',$data);
    }

    public function signUp()
    {
        $schoolModel = new \App\Models\schoolModel();
        $school = $schoolModel->findAll();
        //system
        $systemModel = new \App\Models\systemModel();
        $system = $systemModel->first();
        $data = ['school'=>$school,'about'=>$system];
        return view('sign-up',$data);
    }

    public function register()
    {
        $validation = $this->validate([
            'csrf_test_name'=>'required',
            'email'=>'required|valid_email|is_unique[tblaccount.Email]',
            'fullname'=>'required|is_unique[tblaccount.Fullname]',
            'password'=>'required|min_length[8]|max_length[12]|regex_match[/[A-Z]/]|regex_match[/[a-z]/]|regex_match[/[0-9]/]',
            'confirm_password'=>'required|matches[password]|min_length[8]|max_length[12]|regex_match[/[A-Z]/]|regex_match[/[a-z]/]|regex_match[/[0-9]/]',
        ]);

        if(!$validation)
        {
            $schoolModel = new \App\Models\schoolModel();
            $school = $schoolModel->findAll();
            return view('sign-up',['validation'=>$this->validator,'school'=>$school]);
        }
        else
        {
            $status = 0;$date = date('Y-m-d');$user_type="GUEST";$role = "User";
            $hash_password = Hash::make($this->request->getPost('password'));
            $token_code = random_string('alnum',64);
            //get the cluster ID
            $schoolModel = new \App\Models\schoolModel();
            $accountModel = new \App\Models\accountModel();
            $school = $schoolModel->WHERE('schoolID',$this->request->getPost('school'))->first();
            $data = ['Email'=>$this->request->getPost('email'), 
                    'Password'=>$hash_password,
                    'Fullname'=>$this->request->getPost('fullname'),
                    'Position'=>'School Representative',
                    'Office'=>'School',
                    'Role'=>$role,
                    'clusterID'=>$school['clusterID'],
                    'schoolID'=>$this->request->getPost('school'),
                    'userType'=>$user_type,
                    'Status'=>$status,
                    'Token'=>$token_code,
                    'DateCreated'=>$date];
            $accountModel->save($data);
            //send email activation link
            $email = \Config\Services::email();
            $email->setTo($this->request->getPost('email'));
            $email->setFrom("vinmogate@gmail.com","ASSIST");
            $imgURL = "assets/img/Logo.png";
            $email->attach($imgURL);
            $cid = $email->setAttachmentCID($imgURL);
            $template = "<center>
            <img src='cid:". $cid ."' width='100'/>
            <table style='padding:20px;background-color:#ffffff;' border='0'><tbody>
            <tr><td><center><h1>Account Activation</h1></center></td></tr>
            <tr><td><center>Hi, ".$this->request->getPost('fullname')."</center></td></tr>
            <tr><td><p><center>Please click the link below to activate your account.</center></p></td><tr>
            <tr><td><center><b>".anchor('activate/'.$this->request->getPost('csrf_test_name'),'Activate Account')."</b></center></td></tr>
            <tr><td><p><center>If you did not sign-up in ASSIST Website,<br/> please ignore this message or contact us @ division.gentri@deped.gov.ph</center></p></td></tr>
            <tr><td>ASSIST IT Support</td></tr></tbody></table></center>";
            $subject = "Account Activation | ASSIST";
            $email->setSubject($subject);
            $email->setMessage($template);
            $email->send();
            session()->setFlashdata('success','Great! Successfully sent activation link');
            return redirect()->to('/success')->withInput();
        }
    }

    public function activateAccount($id)
    {
        $accountModel = new \App\Models\accountModel();
        $account = $accountModel->WHERE('Token',$id)->first();
        $values = ['Status'=>1];
        $accountModel->update($account['accountID'],$values);
        session()->set('loggedUser', $account['accountID']);
        session()->set('fullname', $account['Fullname']);
        session()->set('role',$account['Role']);
        session()->set('user_type',$account['userType']);
        return $this->response->redirect(site_url('user/overview'));
    }

    public function successLink()
    {
        return view('success-page');
    }

    public function Auth()
    {
        date_default_timezone_set('Asia/Manila');
        $accountModel = new \App\Models\accountModel();
        //data
        $validation = $this->validate([
            'csrf_test_name'=>'required',
            'email'=>'required|valid_email|is_not_unique[tblaccount.Email]',
            'password'=>'required|min_length[8]|max_length[12]|regex_match[/[A-Z]/]|regex_match[/[a-z]/]|regex_match[/[0-9]/]'
        ]);

        if(!$validation)
        {
            return view('welcome_message',['validation'=>$this->validator]);
        }
        else
        {
            $account = $accountModel->WHERE('Email',$this->request->getPost('email'))
                                    ->WHERE('Status',1)->first();
            $check_password = Hash::check($this->request->getPost('password'), $account['Password']);
            if(!$check_password || empty($check_password))
            {
                session()->setFlashdata('fail','Invalid Password! Please try again');
                return redirect()->to('/')->withInput();
            }
            else
            {
                session()->set('loggedUser', $account['accountID']);
                session()->set('fullname', $account['Fullname']);
                session()->set('role',$account['Role']);
                session()->set('user_type',$account['userType']);
                //create log
                $logModel = new \App\Models\logModel();
                $data = ['accountID'=>session()->get('loggedUser'),'Activity'=>'Logged In','DateCreated'=>date('Y-m-d H:i:s a')];
                $logModel->save($data);
                
                switch($account['Role'])
                {
                    case "Administrator":
                    return redirect()->to('/overview');

                    case "Manager":
                    return redirect()->to('/manager/overview');

                    case "User":
                    return redirect()->to('/user/overview');

                    default:
                    $this->logout();
                    break;
                }
            }
        }
    }

    public function logout()
    {
        //create log
        date_default_timezone_set('Asia/Manila');
        $logModel = new \App\Models\logModel();
        $data = ['accountID'=>session()->get('loggedUser'),'Activity'=>'Logged Out','DateCreated'=>date('Y-m-d H:i:s a')];
        $logModel->save($data);
        if(session()->has('loggedUser'))
        {
            session()->remove('loggedUser');
            session()->destroy();
            return redirect()->to('/?access=out')->with('fail', 'You are logged out!');
        }
    }

    // pages
    /// admin
    public function adminDashboard()
    {
        if(session()->get('role')=="Administrator")
        {
            $title = "Dashboard";
            //system
            $systemModel = new \App\Models\systemModel();
            $system = $systemModel->first();
            $formModel = new \App\Models\formModel();
            //count all the form
            $totalForm = $formModel->countAllResults();
            //count all the pending
            $pendingForm = $formModel->WHERE('Status<>',1)->countAllResults();
            //count all the resolved
            $resolvedForm = $formModel->WHERE('Status',1)->countAllResults();
            //feedback
            $feedbackModel = new \App\Models\feedbackModel();
            $feed = $feedbackModel->countAllResults();
            //get the total feedback
            $positive = [6,7,8,9,10];
            $negative = [1,2,3,4,5];
            $totalFeedback = $feedbackModel->countAllResults();
            $positiveFeedback = $feedbackModel->WHEREIN('Rate',$positive)->countAllResults();
            $negativeFeedback = $feedbackModel->WHEREIN('Rate',$negative)->countAllResults();
            //generate percentage
            if ($totalFeedback != 0) {
                $positivePercent = ($positiveFeedback / $totalFeedback) * 100;
                $negativePercent = ($negativeFeedback/$totalFeedback)*100;
            } else {
                $positivePercent = 0; // or handle it in any other way you prefer
                $negativePercent = 0;
            }
            
            //compute the sum of all rates
            $builder = $this->db->table('tblfeedback');
            $builder->select('sum(Rate)total');
            $sumRate = $builder->get()->getRow();
            if($sumRate->total!=0)
            {
                $totalPercent = ($sumRate->total/($totalFeedback*10))*100;
            }
            else
            {
                $totalPercent = 0;
            }
            //compute the difference between previous and current month
            $currentMonth = date('m'); // Current month (e.g., 02 for February)
            $currentYear = date('Y');  // Current year (e.g., 2025)

            $previousMonth = $currentMonth - 1;
            $previousYear = $currentYear;

            if ($previousMonth == 0) {
                $previousMonth = 12;
                $previousYear -= 1;  // If the current month is January, previous month is December
            }
            $queryCurrentMonth = "SELECT COUNT(formID) AS total FROM tblform
                      WHERE DATE_FORMAT(DateCreated,'%m') = $currentMonth
                      AND DATE_FORMAT(DateCreated,'%Y') = $currentYear";

            $resultCurrentMonth = $this->db->query($queryCurrentMonth);
            $currentResult = $resultCurrentMonth->getRow();

            // Query to get the average feedback for the previous month
            $queryPreviousMonth = "SELECT COUNT(formID) AS total FROM tblform
                                WHERE DATE_FORMAT(DateCreated,'%m') = $previousMonth 
                                AND DATE_FORMAT(DateCreated,'%Y') = $previousYear";

            $resultPreviousMonth = $this->db->query($queryPreviousMonth);
            $previousResult = $resultPreviousMonth->getRow();

            if ($currentResult->total === NULL) {
                $currentResult->total = 0;  // If no feedback for the current month, set to 0
            }
            
            if ($previousResult->total === NULL) {
                $previousResult->total = 0;  // If no feedback for the previous month, set to 0
            }
            
            $absoluteDifference = $currentResult->total - $previousResult->total;

            $data = ['title'=>$title,'total'=>$totalForm,
                    'pending'=>$pendingForm,'resolved'=>$resolvedForm,
                    'feed'=>$feed,'positive'=>$positivePercent,
                    'negative'=>$negativePercent,'totalPercent'=>$totalPercent,
                    'difference'=>$absoluteDifference,'about'=>$system];
            return view('admin/index',$data);
        }
        return redirect()->back();
    }

    public function techAssistance()
    {
        if(session()->get('role')=="Administrator" && session()->get('user_type')=="PSDS")
        {
            $title = "Technical Assistance";
            //system
            $systemModel = new \App\Models\systemModel();
            $system = $systemModel->first();
            //data
            $accountModel = new \App\Models\accountModel();
            $account = $accountModel->WHERE('accountID',session()->get('loggedUser'))->first();
            $builder = $this->db->table('tblform b');
            $builder->select('b.Code,c.Rate,c.Message,c.DateCreated,d.clusterName,e.schoolName');
            $builder->join('tblfeedback c','c.formID=b.formID','INNER');
            $builder->join('tblcluster d','d.clusterID=b.clusterID','LEFT');
            $builder->join('tblschool e','e.schoolID=b.schoolID','LEFT');
            $builder->WHERE('b.clusterID',$account['clusterID']);
            $builder->groupBy('b.formID');
            $feed = $builder->get()->getResult();
            $data = ['title'=>$title,'feedback'=>$feed,'about'=>$system];
            return view('admin/technical-assistance',$data);
        }
        return redirect()->back();
    }

    public function userAccounts()
    {
        if(session()->get('role')=="Administrator")
        {
            $title = "User Accounts";
            //system
            $systemModel = new \App\Models\systemModel();
            $system = $systemModel->first();
            //accounts
            $builder = $this->db->table('tblaccount a');
            $builder->select('a.*,b.clusterName,c.schoolName');
            $builder->join('tblcluster b','b.clusterID=a.clusterID','LEFT');
            $builder->join('tblschool c','c.schoolID=a.schoolID','LEFT');
            $builder->groupBy('a.accountID');
            $account = $builder->get()->getResult();
            $data = ['title'=>$title,'account'=>$account,'about'=>$system];
            return view('admin/manage-account',$data);
        }
        return redirect()->back();
    }

    public function newAccount()
    {
        if(session()->get('role')=="Administrator")
        {
            $title = "New Account";
            //system
            $systemModel = new \App\Models\systemModel();
            $system = $systemModel->first();
            //cluster
            $clusterModel = new \App\Models\clusterModel();
            $cluster = $clusterModel->findAll();
            //school
            $schoolModel = new \App\Models\schoolModel();
            $school = $schoolModel->findAll();
            //subject
            $subjectModel = new \App\Models\subjectModel();
            $subject = $subjectModel->findAll();

            $data = ['title'=>$title,'cluster'=>$cluster,'school'=>$school,'subject'=>$subject,'about'=>$system];
            return view('admin/new-account',$data);
        }
        return redirect()->back();
    }

    public function editAccount($id)
    {
        if(session()->get('role')=="Administrator")
        {
            $title = "Edit Account";
            //system
            $systemModel = new \App\Models\systemModel();
            $system = $systemModel->first();
            //cluster
            $clusterModel = new \App\Models\clusterModel();
            $cluster = $clusterModel->findAll();
            //school
            $schoolModel = new \App\Models\schoolModel();
            $school = $schoolModel->findAll();
            //subject
            $subjectModel = new \App\Models\subjectModel();
            $subject = $subjectModel->findAll();
            //account
            $accountModel = new \App\Models\accountModel();
            $account = $accountModel->where('Token',$id)->first();

            $data = ['title'=>$title,'cluster'=>$cluster,'school'=>$school,'subject'=>$subject,'account'=>$account,'about'=>$system];
            return view('admin/edit-account',$data);
        }
        return redirect()->back();
    }

    public function clusterAndSchools()
    {
        if(session()->get('role')=="Administrator")
        {
            $title = "Cluster & Schools";
            //system
            $systemModel = new \App\Models\systemModel();
            $system = $systemModel->first();
            //cluster
            $clusterModel = new \App\Models\clusterModel();
            $cluster = $clusterModel->findAll();
            $data = ['title'=>$title,'cluster'=>$cluster,'about'=>$system];
            return view('admin/manage-schools',$data);
        }
        return redirect()->back();
    }

    public function editSchool($id)
    {
        if(session()->get('role')=="Administrator")
        {
            $title = "Edit School";
            //system
            $systemModel = new \App\Models\systemModel();
            $system = $systemModel->first();
            $data = ['title'=>$title,'about'=>$system];
            return view('admin/edit-school',$data);
        }
        return redirect()->back();
    }

    public function reports()
    {
        if(session()->get('role')=="Administrator" && session()->get('user_type')=="PSDS")
        {
            $title = "Reports";
            //system
            $systemModel = new \App\Models\systemModel();
            $system = $systemModel->first();
            $data = ['title'=>$title,'about'=>$system];
            return view('admin/report',$data);
        }
        return redirect()->back();
    }

    public function myAccount()
    {
        if(session()->get('role')=="Administrator")
        {
            $title = "My Account";
            //system
            $systemModel = new \App\Models\systemModel();
            $system = $systemModel->first();
            //accounts
            $accountModel = new \App\Models\accountModel();
            $user = session()->get('loggedUser');
            $account = $accountModel->WHERE('accountID',$user)->first();
            $data = ['title'=>$title,'account'=>$account,'about'=>$system];
            return view('admin/account',$data);
        }
        return redirect()->back();
    }

    public function systemInfo()
    {
        $title = "System and Logs";
        //system
        $systemModel = new \App\Models\systemModel();
        $system = $systemModel->first();
        //logs
        $builder = $this->db->table('tblrecord a');
        $builder->select('a.*,b.Fullname');
        $builder->join('tblaccount b','b.accountID=a.accountID','LEFT');
        $log = $builder->get()->getResult();

        $data = ['title'=>$title,'log'=>$log,'system'=>$system,'about'=>$system];
        return view('admin/system',$data);
    }

    /// manager
    public function managerDashboard()
    {
        if(session()->get('role')=="Manager")
        {
            $title = "Dashboard";
            //system
            $systemModel = new \App\Models\systemModel();
            $system = $systemModel->first();
            $formModel = new \App\Models\formModel();
            //count all the form
            $totalForm = $formModel->countAllResults();
            //count all the pending
            $pendingForm = $formModel->WHERE('Status<>',1)->countAllResults();
            //count all the resolved
            $resolvedForm = $formModel->WHERE('Status',1)->countAllResults();
            //feedback
            $feedbackModel = new \App\Models\feedbackModel();
            $feed = $feedbackModel->countAllResults();
            //get the total feedback
            $positive = [6,7,8,9,10];
            $negative = [1,2,3,4,5];
            $totalFeedback = $feedbackModel->countAllResults();
            $positiveFeedback = $feedbackModel->WHEREIN('Rate',$positive)->countAllResults();
            $negativeFeedback = $feedbackModel->WHEREIN('Rate',$negative)->countAllResults();
            //generate percentage
            if ($totalFeedback != 0) {
                $positivePercent = ($positiveFeedback / $totalFeedback) * 100;
                $negativePercent = ($negativeFeedback/$totalFeedback)*100;
            } else {
                $positivePercent = 0; // or handle it in any other way you prefer
                $negativePercent = 0;
            }
            
            //compute the sum of all rates
            $builder = $this->db->table('tblfeedback');
            $builder->select('sum(Rate)total');
            $sumRate = $builder->get()->getRow();
            if($sumRate->total!=0)
            {
                $totalPercent = ($sumRate->total/($totalFeedback*10))*100;
            }
            else
            {
                $totalPercent = 0;
            }
            //compute the difference between previous and current month
            $currentMonth = date('m'); // Current month (e.g., 02 for February)
            $currentYear = date('Y');  // Current year (e.g., 2025)

            $previousMonth = $currentMonth - 1;
            $previousYear = $currentYear;

            if ($previousMonth == 0) {
                $previousMonth = 12;
                $previousYear -= 1;  // If the current month is January, previous month is December
            }
            $queryCurrentMonth = "SELECT COUNT(formID) AS total FROM tblform
                      WHERE DATE_FORMAT(DateCreated,'%m') = $currentMonth
                      AND DATE_FORMAT(DateCreated,'%Y') = $currentYear";

            $resultCurrentMonth = $this->db->query($queryCurrentMonth);
            $currentResult = $resultCurrentMonth->getRow();

            // Query to get the average feedback for the previous month
            $queryPreviousMonth = "SELECT COUNT(formID) AS total FROM tblform
                                WHERE DATE_FORMAT(DateCreated,'%m') = $previousMonth 
                                AND DATE_FORMAT(DateCreated,'%Y') = $previousYear";

            $resultPreviousMonth = $this->db->query($queryPreviousMonth);
            $previousResult = $resultPreviousMonth->getRow();

            if ($currentResult->total === NULL) {
                $currentResult->total = 0;  // If no feedback for the current month, set to 0
            }
            
            if ($previousResult->total === NULL) {
                $previousResult->total = 0;  // If no feedback for the previous month, set to 0
            }
            
            $absoluteDifference = $currentResult->total - $previousResult->total;

            $data = ['title'=>$title,'total'=>$totalForm,
                    'pending'=>$pendingForm,'resolved'=>$resolvedForm,
                    'feed'=>$feed,'positive'=>$positivePercent,
                    'negative'=>$negativePercent,'totalPercent'=>$totalPercent,
                    'difference'=>$absoluteDifference,'about'=>$system];
            return view('manager/index',$data);
        }
        return redirect()->back();
    }

    public function managerTechnicalAssistance()
    {
        if(session()->get('role')=="Manager")
        {
            $title = "Technical Assistance";
            //system
            $systemModel = new \App\Models\systemModel();
            $system = $systemModel->first();
            $data = ['title'=>$title,'about'=>$system];
            return view('manager/technical-assistance',$data);
        }
        return redirect()->back();
    }

    public function managerReport()
    {
        if(session()->get('role')=="Manager")
        {
            $title = "Reports";
            //system
            $systemModel = new \App\Models\systemModel();
            $system = $systemModel->first();
            $data = ['title'=>$title,'about'=>$system];
            return view('manager/report',$data);
        }
        return redirect()->back();
    }

    public function managerAccount()
    {
        if(session()->get('role')=="Manager")
        {
            $title = "My Account";
            //system
            $systemModel = new \App\Models\systemModel();
            $system = $systemModel->first();
            $accountModel = new \App\Models\accountModel();
            $user = session()->get('loggedUser');
            $account = $accountModel->WHERE('accountID',$user)->first();
            $data = ['title'=>$title,'account'=>$account,'about'=>$system];
            return view('manager/account',$data);
        }
        return redirect()->back();
    }


    /// user
    public function userDashboard()
    {
        if(session()->get('role')=="User")
        {
            $title = "Dashboard";
            //system
            $systemModel = new \App\Models\systemModel();
            $system = $systemModel->first();
            $formModel = new \App\Models\formModel();
            //count all the form
            $totalForm = $formModel->countAllResults();
            //count all the pending
            $pendingForm = $formModel->WHERE('Status<>',1)->countAllResults();
            //count all the resolved
            $resolvedForm = $formModel->WHERE('Status',1)->countAllResults();
            //feedback
            $feedbackModel = new \App\Models\feedbackModel();
            $feed = $feedbackModel->countAllResults();
            //get the total feedback
            $positive = [6,7,8,9,10];
            $negative = [1,2,3,4,5];
            $totalFeedback = $feedbackModel->countAllResults();
            $positiveFeedback = $feedbackModel->WHEREIN('Rate',$positive)->countAllResults();
            $negativeFeedback = $feedbackModel->WHEREIN('Rate',$negative)->countAllResults();
            //generate percentage
            if ($totalFeedback != 0) {
                $positivePercent = ($positiveFeedback / $totalFeedback) * 100;
                $negativePercent = ($negativeFeedback/$totalFeedback)*100;
            } else {
                $positivePercent = 0; // or handle it in any other way you prefer
                $negativePercent = 0;
            }
            
            //compute the sum of all rates
            $builder = $this->db->table('tblfeedback');
            $builder->select('sum(Rate)total');
            $sumRate = $builder->get()->getRow();
            if($sumRate->total!=0)
            {
                $totalPercent = ($sumRate->total/($totalFeedback*10))*100;
            }
            else
            {
                $totalPercent = 0;
            }
            //compute the difference between previous and current month
            $currentMonth = date('m'); // Current month (e.g., 02 for February)
            $currentYear = date('Y');  // Current year (e.g., 2025)

            $previousMonth = $currentMonth - 1;
            $previousYear = $currentYear;

            if ($previousMonth == 0) {
                $previousMonth = 12;
                $previousYear -= 1;  // If the current month is January, previous month is December
            }
            $queryCurrentMonth = "SELECT COUNT(formID) AS total FROM tblform
                      WHERE DATE_FORMAT(DateCreated,'%m') = $currentMonth
                      AND DATE_FORMAT(DateCreated,'%Y') = $currentYear";

            $resultCurrentMonth = $this->db->query($queryCurrentMonth);
            $currentResult = $resultCurrentMonth->getRow();

            // Query to get the average feedback for the previous month
            $queryPreviousMonth = "SELECT COUNT(formID) AS total FROM tblform
                                WHERE DATE_FORMAT(DateCreated,'%m') = $previousMonth 
                                AND DATE_FORMAT(DateCreated,'%Y') = $previousYear";

            $resultPreviousMonth = $this->db->query($queryPreviousMonth);
            $previousResult = $resultPreviousMonth->getRow();

            if ($currentResult->total === NULL) {
                $currentResult->total = 0;  // If no feedback for the current month, set to 0
            }
            
            if ($previousResult->total === NULL) {
                $previousResult->total = 0;  // If no feedback for the previous month, set to 0
            }
            
            $absoluteDifference = $currentResult->total - $previousResult->total;


            $data = ['title'=>$title,'total'=>$totalForm,
                    'pending'=>$pendingForm,'resolved'=>$resolvedForm,
                    'feed'=>$feed,'positive'=>$positivePercent,
                    'negative'=>$negativePercent,'totalPercent'=>$totalPercent,
                    'difference'=>$absoluteDifference,'about'=>$system];
            return view('user/index',$data);
        }
        return redirect()->back();
    }

    public function userTechnicalAssistance()
    {
        if(session()->get('role')=="User")
        {
            $title = "Technical Assistance";
            //system
            $systemModel = new \App\Models\systemModel();
            $system = $systemModel->first();
            //area of concerns
            $subjectModel = new \App\Models\subjectModel();
            $subject = $subjectModel->findAll();
            //users
            $type_users = ['EPS'];
            $accountModel = new \App\Models\accountModel();
            $account = $accountModel->WHEREIN('userType',$type_users)->findAll();

            $data = ['title'=>$title,'subject'=>$subject,'account'=>$account,'about'=>$system];
            return view('user/technical-assistance',$data);
        }
        return redirect()->back();
    }

    public function userFeedback()
    {
        if(session()->get('role')=="User")
        {
            $title = "Feedback";
            //system
            $systemModel = new \App\Models\systemModel();
            $system = $systemModel->first();
            //feedback
            $user = session()->get('loggedUser');
            $feedbackModel = new \App\Models\feedbackModel();
            $feed = $feedbackModel->WHERE('accountID',$user)->findAll();
            $data = ['title'=>$title,'feed'=>$feed,'about'=>$system];
            return view('user/feedback',$data);
        }
        return redirect()->back();
    }

    public function userAccount()
    {
        if(session()->get('role')=="User")
        {
            $title = "My Account";
            //system
            $systemModel = new \App\Models\systemModel();
            $system = $systemModel->first();
            //data
            $accountModel = new \App\Models\accountModel();
            $user = session()->get('loggedUser');
            $account = $accountModel->WHERE('accountID',$user)->first();
            $data = ['title'=>$title,'account'=>$account,'about'=>$system];
            return view('user/account',$data);
        }
        return redirect()->back();
    }

    public function changePassword()
    {
        $accountModel = new \App\Models\accountModel();
        $user = session()->get('loggedUser');
        $validation = $this->validate([
            'current_password'=>'required|min_length[8]|max_length[12]|regex_match[/[A-Z]/]|regex_match[/[a-z]/]|regex_match[/[0-9]/]',
            'new_password'=>'required|min_length[8]|max_length[12]|regex_match[/[A-Z]/]|regex_match[/[a-z]/]|regex_match[/[0-9]/]',
            'confirm_password'=>'required|matches[new_password]|min_length[8]|max_length[12]|regex_match[/[A-Z]/]|regex_match[/[a-z]/]|regex_match[/[0-9]/]',
        ]);

        if(!$validation)
        {
            return $this->response->SetJSON(['error' => $this->validator->getErrors()]);
        }
        else
        {
            //variables
            $oldpassword = $this->request->getPost('current_password');
            $newpassword = $this->request->getPost('new_password');

            $account = $accountModel->WHERE('accountID',$user)->first();
            $checkPassword = Hash::check($oldpassword,$account['Password']);
            if(!$checkPassword||empty($checkPassword))
            {
                $error = ['current_password'=>'Password mismatched. Please try again'];
                return $this->response->SetJSON(['error' => $error]);
            }
            else
            {
                if(($oldpassword==$newpassword))
                {
                    $error = ['new_password'=>'The new password cannot be the same as the current password.'];
                    return $this->response->SetJSON(['error' => $error]);
                }
                else
                {
                    $HashPassword = Hash::make($newpassword);
                    $data = ['Password'=>$HashPassword];
                    $accountModel->update($user,$data);
                    return $this->response->setJSON(['success' => 'Successfully submitted']);
                }
            }
        }
    }

    public function viewFeedback()
    {
        if(session()->get('role')=="Administrator" && session()->get('user_type')=="PSDS")
        {
            $title = "Feedback and Rates";
            //system
            $systemModel = new \App\Models\systemModel();
            $system = $systemModel->first();
            //feedback
            $builder = $this->db->table('tblfeedback a');
            $builder->select('a.*,b.Fullname,c.schoolName');
            $builder->join('tblaccount b','b.accountID=a.accountID','LEFT');
            $builder->join('tblschool c','c.schoolID=a.schoolID','LEFT');
            $builder->groupBy('a.feedID');
            $feed = $builder->get()->getResult();

            $data = ['title'=>$title,'feed'=>$feed,'about'=>$system];
            return view('feedback',$data);
        }
        return redirect()->back();
    }

    public function saveLogo()
    {
        $systemModel = new \App\Models\systemModel();
        $system = $systemModel->first();
        //file
        $file = $this->request->getFile('file');
        $originalName = $file->getClientName();
        //update image
        if(!empty($originalName))
        {
            $file->move('assets/img/logos/',$originalName);
        }
        //save and update the form
        if(empty($system))
        {
            $data = ['systemTitle'=>$this->request->getPost('app_name'),
                    'systemDetails'=>$this->request->getPost('app_details'),
                    'systemLogo'=>$originalName,
                    'DateCreated'=>date('Y-m-d')];
            $systemModel->save($data);
        }
        else
        {
            $data = ['systemTitle'=>$this->request->getPost('app_name'),
            'systemDetails'=>$this->request->getPost('app_details'),
            'systemLogo'=>$originalName,
            'DateCreated'=>date('Y-m-d')];
            $systemModel->update($system['systemID'],$data);
        }
        session()->setFlashdata('success','Great! Successfully applied changes');
        return redirect()->to('/about')->withInput();
    }
}