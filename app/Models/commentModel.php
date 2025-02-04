<?php

namespace App\Models;

use CodeIgniter\Model;

class commentModel extends Model
{
    protected $table      = 'tblcomment';
    protected $primaryKey = 'commentID';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['formID','accountID','Message','DateCreated'];
}