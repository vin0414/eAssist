<?php

namespace App\Models;

use CodeIgniter\Model;

class feedbackModel extends Model
{
    protected $table      = 'tblfeedback';
    protected $primaryKey = 'feedID';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['schoolID','accountID','formID','Code','Rate','Message','DateCreated'];
}