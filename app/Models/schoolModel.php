<?php

namespace App\Models;

use CodeIgniter\Model;

class schoolModel extends Model
{
    protected $table      = 'tblschool';
    protected $primaryKey = 'schoolID';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['schoolName','address','clusterID','Status','DateCreated'];
}