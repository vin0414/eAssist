<?php

namespace App\Models;

use CodeIgniter\Model;

class logModel extends Model
{
    protected $table      = 'tblrecord';
    protected $primaryKey = 'recordID';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['accountID','Activity','DateCreated'];
}