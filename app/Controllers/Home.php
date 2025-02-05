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
        return view('welcome_message');
    }

    public function signUp()
    {
        $schoolModel = new \App\Models\schoolModel();
        $school = $schoolModel->findAll();
        $data = ['school'=>$school];
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

            $data = ['title'=>$title,'total'=>$totalForm,'pending'=>$pendingForm,'resolved'=>$resolvedForm,'feed'=>$feed];
            return view('admin/index',$data);
        }
        return redirect()->back();
    }

    public function techAssistance()
    {
        if(session()->get('role')=="Administrator" && session()->get('user_type')=="PSDS")
        {
            $title = "Technical Assistance";
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
            $data = ['title'=>$title,'feedback'=>$feed];
            return view('admin/technical-assistance',$data);
        }
        return redirect()->back();
    }

    public function userAccounts()
    {
        if(session()->get('role')=="Administrator")
        {
            $title = "User Accounts";
            $builder = $this->db->table('tblaccount a');
            $builder->select('a.*,b.clusterName,c.schoolName');
            $builder->join('tblcluster b','b.clusterID=a.clusterID','LEFT');
            $builder->join('tblschool c','c.schoolID=a.schoolID','LEFT');
            $builder->groupBy('a.accountID');
            $account = $builder->get()->getResult();
            $data = ['title'=>$title,'account'=>$account];
            return view('admin/manage-account',$data);
        }
        return redirect()->back();
    }

    public function newAccount()
    {
        if(session()->get('role')=="Administrator")
        {
            $title = "New Account";
            //cluster
            $clusterModel = new \App\Models\clusterModel();
            $cluster = $clusterModel->findAll();
            //school
            $schoolModel = new \App\Models\schoolModel();
            $school = $schoolModel->findAll();
            //subject
            $subjectModel = new \App\Models\subjectModel();
            $subject = $subjectModel->findAll();

            $data = ['title'=>$title,'cluster'=>$cluster,'school'=>$school,'subject'=>$subject];
            return view('admin/new-account',$data);
        }
        return redirect()->back();
    }

    public function editAccount($id)
    {
        if(session()->get('role')=="Administrator")
        {
            $title = "Edit Account";
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

            $data = ['title'=>$title,'cluster'=>$cluster,'school'=>$school,'subject'=>$subject,'account'=>$account];
            return view('admin/edit-account',$data);
        }
        return redirect()->back();
    }

    public function clusterAndSchools()
    {
        if(session()->get('role')=="Administrator")
        {
            $title = "Cluster & Schools";
            //cluster
            $clusterModel = new \App\Models\clusterModel();
            $cluster = $clusterModel->findAll();
            $data = ['title'=>$title,'cluster'=>$cluster];
            return view('admin/manage-schools',$data);
        }
        return redirect()->back();
    }

    public function editSchool($id)
    {
        if(session()->get('role')=="Administrator")
        {
            $title = "Edit School";

            $data = ['title'=>$title];
            return view('admin/edit-school',$data);
        }
        return redirect()->back();
    }

    public function reports()
    {
        if(session()->get('role')=="Administrator" && session()->get('user_type')=="PSDS")
        {
            $title = "Reports";
            $data = ['title'=>$title];
            return view('admin/report',$data);
        }
        return redirect()->back();
    }

    public function myAccount()
    {
        if(session()->get('role')=="Administrator")
        {
            $title = "My Account";
            $accountModel = new \App\Models\accountModel();
            $user = session()->get('loggedUser');
            $account = $accountModel->WHERE('accountID',$user)->first();
            $data = ['title'=>$title,'account'=>$account];
            return view('admin/account',$data);
        }
        return redirect()->back();
    }

    /// manager
    public function managerDashboard()
    {
        if(session()->get('role')=="Manager")
        {
            $title = "Dashboard";
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

            $data = ['title'=>$title,'total'=>$totalForm,'pending'=>$pendingForm,'resolved'=>$resolvedForm,'feed'=>$feed];
            return view('manager/index',$data);
        }
        return redirect()->back();
    }

    public function managerTechnicalAssistance()
    {
        if(session()->get('role')=="Manager")
        {
            $title = "Technical Assistance";
            $data = ['title'=>$title];
            return view('manager/technical-assistance',$data);
        }
        return redirect()->back();
    }

    public function managerReport()
    {
        if(session()->get('role')=="Manager")
        {
            $title = "Reports";
            $data = ['title'=>$title];
            return view('manager/report',$data);
        }
        return redirect()->back();
    }

    public function managerAccount()
    {
        if(session()->get('role')=="Manager")
        {
            $title = "My Account";
            $accountModel = new \App\Models\accountModel();
            $user = session()->get('loggedUser');
            $account = $accountModel->WHERE('accountID',$user)->first();
            $data = ['title'=>$title,'account'=>$account];
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

            $data = ['title'=>$title,'total'=>$totalForm,'pending'=>$pendingForm,'resolved'=>$resolvedForm,'feed'=>$feed];
            return view('user/index',$data);
        }
        return redirect()->back();
    }

    public function userTechnicalAssistance()
    {
        if(session()->get('role')=="User")
        {
            $title = "Technical Assistance";
            $user = session()->get('loggedUser');
            //area of concerns
            $subjectModel = new \App\Models\subjectModel();
            $subject = $subjectModel->findAll();
            //users
            $type_users = ['EPS'];
            $accountModel = new \App\Models\accountModel();
            $account = $accountModel->WHEREIN('userType',$type_users)->findAll();

            $data = ['title'=>$title,'subject'=>$subject,'account'=>$account];
            return view('user/technical-assistance',$data);
        }
        return redirect()->back();
    }

    public function userFeedback()
    {
        if(session()->get('role')=="User")
        {
            $title = "Feedback";
            //feedback
            $user = session()->get('loggedUser');
            $feedbackModel = new \App\Models\feedbackModel();
            $feed = $feedbackModel->WHERE('accountID',$user)->findAll();
            $data = ['title'=>$title,'feed'=>$feed];
            return view('user/feedback',$data);
        }
        return redirect()->back();
    }

    public function userAccount()
    {
        if(session()->get('role')=="User")
        {
            $title = "My Account";
            //data
            $accountModel = new \App\Models\accountModel();
            $user = session()->get('loggedUser');
            $account = $accountModel->WHERE('accountID',$user)->first();
            $data = ['title'=>$title,'account'=>$account];
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
}
