<?php

namespace App\Models;

use CodeIgniter\Model;

class reviewModel extends Model
{
    protected $table      = 'tblreview';
    protected $primaryKey = 'reviewID';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['DateReceived', 'accountID','formID','Status','DateApproved'];

}