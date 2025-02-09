<?php

namespace App\Models;

use CodeIgniter\Model;

class systemModel extends Model
{
    protected $table      = 'tblsystem';
    protected $primaryKey = 'systemID';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['systemTitle','systemDetails','systemLogo','DateCreated'];
}