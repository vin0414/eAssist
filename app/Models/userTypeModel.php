<?php

namespace App\Models;

use CodeIgniter\Model;

class userTypeModel extends Model
{
    protected $table      = 'tbluser_type';
    protected $primaryKey = 'userID';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['userType','DateCreated'];
}