<?php

namespace App\Models;

use CodeIgniter\Model;

class officeModel extends Model
{
    protected $table      = 'tbloffice';
    protected $primaryKey = 'officeID';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['officeName','DateCreated'];
}