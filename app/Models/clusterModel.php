<?php

namespace App\Models;

use CodeIgniter\Model;

class clusterModel extends Model
{
    protected $table      = 'tblcluster';
    protected $primaryKey = 'clusterID';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['clusterName','Status','DateCreated'];
}