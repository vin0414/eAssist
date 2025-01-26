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
                
            }
        }
    }
}
