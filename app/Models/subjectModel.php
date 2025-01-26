<?php

namespace App\Models;

use CodeIgniter\Model;

class subjectModel extends Model
{
    protected $table      = 'tblsubject';
    protected $primaryKey = 'subjectID';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['subjectName','Status','DateCreated'];
}