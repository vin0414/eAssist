<?php

namespace App\Models;

use CodeIgniter\Model;

class actionModel extends Model
{
    protected $table      = 'tblaction';
    protected $primaryKey = 'actionID';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['DateCreated','formID','accountID','actionName','Recommendation','implementationDate','requestorID'];
}