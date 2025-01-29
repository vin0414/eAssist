<?php

namespace App\Controllers;
use App\Libraries\Hash;

class Home extends BaseController
{
    private $db;
    public function __construct()
    {
        helper(['url','form']);
        $this->db = db_connect();
    }
    public function index()
    {
        return view('welcome_message');
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
                session()->setFlashdata('fail','Invalid Username or Password!');
                return redirect()->to('/')->withInput();
            }
            else
            {
                session()->set('loggedUser', $account['accountID']);
                session()->set('fullname', $account['Fullname']);
                session()->set('role',$account['Role']);
                
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
            $data = ['title'=>$title];
            return view('admin/index',$data);
        }
        return redirect()->back();
    }

    public function techAssistance()
    {
        if(session()->get('role')=="Administrator")
        {
            $title = "Technical Assistance";
            $data = ['title'=>$title];
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
            $builder->select('a.*,b.clusterName,c.subjectName');
            $builder->join('tblcluster b','b.clusterID=a.clusterID','LEFT');
            $builder->join('tblsubject c','c.subjectID=a.subjectID','LEFT');
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
        if(session()->get('role')=="Administrator")
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
            $data = ['title'=>$title];
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
            $data = ['title'=>$title];
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


    /// user
    public function userDashboard()
    {
        if(session()->get('role')=="User")
        {
            return view('user/index');
        }
        return redirect()->back();
    }
}
