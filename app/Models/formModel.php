<?php

namespace App\Models;

use CodeIgniter\Model;

class formModel extends Model
{
    protected $table      = 'tblform';
    protected $primaryKey = 'formID';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['DateCreated','accountID','clusterID','schoolID','Agree','subjectID','Details','File','priorityLevel','Status'];

    public function getTotal()
    {
        return $this->db->table($this->table)->countAllResults();
    }
}