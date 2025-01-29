<?php

namespace App\Models;

use CodeIgniter\Model;

class passwordModel extends Model
{
    protected $table      = 'tblpassword';
    protected $primaryKey = 'passwordID';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['Password','DateCreated'];
}