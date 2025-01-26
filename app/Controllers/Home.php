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
                    return redirect()->to('/admin');

                    case "Manager":
                    return redirect()->to('/manager');

                    case "User":
                    return redirect()->to('/user');

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
            return view('admin/index');
        }
        return redirect()->back();
    }

    /// manager
    public function managerDashboard()
    {
        if(session()->get('role')=="Manager")
        {
            return view('manager/index');
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
