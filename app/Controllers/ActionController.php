<?php

namespace App\Controllers;
use App\Libraries\Hash;

class ActionController extends BaseController
{
    private $db;
    public function __construct()
    {
        helper(['url','form']);
        $this->db = db_connect();
    }

    //cluster
    public function fetchCluster()
    {
        $clusterModel = new \App\Models\clusterModel();
        $cluster = $clusterModel->findAll();
        foreach($cluster as $row)
        {
            ?>
            <tr>
                <td>
                    <div class="d-flex px-2 py-1">
                        <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm"><?php echo $row['clusterName'] ?></h6>
                        <p class="text-xs text-secondary mb-0">Date Created : <?php echo $row['DateCreated'] ?></p>
                        </div>
                    </div>
                </td>
                <td class="align-middle">
                    <button type="button" class="btn btn-sm text-xs editCluster" value="<?php echo $row['clusterID'] ?>"><i class="fa-regular fa-pen-to-square"></i>&nbsp;Rename</button>
                </td>
            </tr>
            <?php
        }
    }

    public function saveCluster()
    {
        $clusterModel = new \App\Models\clusterModel();

        $validation = $this->validate([
            'csrf_test_name'=>'required',
            'cluster_name'=>'required|is_unique[tblcluster.clusterName]',
        ]);

        if(!$validation)
        {
            echo "Something went wrong. Please try again";
        }
        else
        {
            $status = 1;
            $date = date('Y-m-d');
            $data = ['clusterName'=>$this->request->getPost('cluster_name'),'Status'=>$status,'DateCreated'=>$date];
            $clusterModel->save($data);
            echo "success";
        }
    }

    //subject

    public function fetchSubject()
    {
        $subjectModel = new \App\Models\subjectModel();
        $subject = $subjectModel->findAll();
        foreach($subject as $row)
        {
            ?>
            <tr>
                <td>
                    <div class="d-flex px-2 py-1">
                        <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm"><?php echo $row['subjectName'] ?></h6>
                        <p class="text-xs text-secondary mb-0">Date Created : <?php echo $row['DateCreated'] ?></p>
                        </div>
                    </div>
                </td>
                <td class="align-middle">
                    <button type="button" class="btn btn-sm text-xs editSubject" value="<?php echo $row['subjectID'] ?>"><i class="fa-regular fa-pen-to-square"></i>&nbsp;Rename</button>
                </td>
            </tr>
            <?php
        }
    }

    public function saveSubject()
    {
        $subjectModel = new \App\Models\subjectModel();
        $validation = $this->validate([
            'csrf_test_name'=>'required',
            'subject_name'=>'required|is_unique[tblsubject.subjectName]',
        ]);

        if(!$validation)
        {
            echo "Something went wrong. Please try again";
        }
        else
        {
            $status = 1;
            $date = date('Y-m-d');
            $data = ['subjectName'=>$this->request->getPost('subject_name'),'Status'=>$status,'DateCreated'=>$date];
            $subjectModel->save($data);
            echo "success";
        }
    }
}