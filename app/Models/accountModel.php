<?php

namespace App\Models;

use CodeIgniter\Model;

class accountModel extends Model
{
    protected $table      = 'tblaccount';
    protected $primaryKey = 'accountID';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['Email', 'Password','Fullname','Position','Office','Role','clusterID','schoolID','subjectID','userType','Status','Token','DateCreated'];
}