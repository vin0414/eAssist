<?php

namespace App\Controllers;
use App\Libraries\Hash;

class FetchController extends BaseController
{
    private $db;
    public function __construct()
    {
        helper(['url','form']);
        $this->db = db_connect();
    }

    public function fetchCluster()
    {

    }

    public function fetchSubject()
    {
        
    }
}