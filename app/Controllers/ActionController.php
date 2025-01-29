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
                        <p class="text-xs text-secondary mb-0">Date Created : <?php echo date('Y-M-d', strtotime($row['DateCreated'])) ?></p>
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
            return $this->response->SetJSON(['error' => $this->validator->getErrors()]);
        }
        else
        {
            $status = 1;
            $date = date('Y-m-d');
            $data = ['clusterName'=>$this->request->getPost('cluster_name'),'Status'=>$status,'DateCreated'=>$date];
            $clusterModel->save($data);
            return $this->response->setJSON(['success' => 'Successfully applied']);
        }
    }

    public function editCluster()
    {
        $clusterModel = new \App\Models\clusterModel();

        $validation = $this->validate([
            'csrf_test_name'=>'required',
            'clusterID'=>'required',
            'new_cluster_name'=>'required|is_unique[tblcluster.clusterName]',
        ]);

        if(!$validation)
        {
            return $this->response->SetJSON(['error' => $this->validator->getErrors()]);
        }
        else
        {
            $data = ['clusterName'=>$this->request->getPost('new_cluster_name')];
            $clusterModel->update($this->request->getPost('clusterID'),$data);
            return $this->response->setJSON(['success' => 'Successfully applied']);
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
                        <p class="text-xs text-secondary mb-0">Date Created : <?php echo date('Y-M-d', strtotime($row['DateCreated'])) ?></p>
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
            return $this->response->SetJSON(['error' => $this->validator->getErrors()]);
        }
        else
        {
            $status = 1;
            $date = date('Y-m-d');
            $data = ['subjectName'=>$this->request->getPost('subject_name'),'Status'=>$status,'DateCreated'=>$date];
            $subjectModel->save($data);
            return $this->response->setJSON(['success' => 'Successfully applied']);
        }
    }

    public function editSubject()
    {
        $subjectModel = new \App\Models\subjectModel();
        $validation = $this->validate([
            'csrf_test_name'=>'required',
            'subjectID'=>'required',
            'new_subject_name'=>'required|is_unique[tblsubject.subjectName]',
        ]);

        if(!$validation)
        {
            return $this->response->SetJSON(['error' => $this->validator->getErrors()]);
        }
        else
        {
            $data = ['subjectName'=>$this->request->getPost('new_subject_name')];
            $subjectModel->update($this->request->getPost('subjectID'),$data);
            return $this->response->setJSON(['success' => 'Successfully applied']);
        }
    }

    public function fetchSchoolData()
    {
        $schoolModel = new \App\Models\schoolModel();
        //schools
        $builder = $this->db->table('tblschool a');
        $builder->select('a.DateCreated,a.schoolName,a.address,a.schoolID,b.clusterName');
        $builder->join('tblcluster b','b.clusterID=a.clusterID','LEFT');
        $builder->groupBy('a.schoolID');
        $school = $builder->get()->getResult();

        $totalRecords = $schoolModel->getTotalRecords();

        $response = [
            "draw" => $_GET['draw'],
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecords,
            'data' => [] 
        ];
        foreach ($school as $row) {
            $response['data'][] = [
                'DateCreated' => date('Y-M-d', strtotime($row->DateCreated)),
                'schoolName' => htmlspecialchars($row->schoolName, ENT_QUOTES),
                'address' => htmlspecialchars($row->address, ENT_QUOTES),
                'clusterName' => htmlspecialchars($row->clusterName, ENT_QUOTES),
                'actions' => '<button class="btn btn-sm" data-id="' . htmlspecialchars($row->schoolID, ENT_QUOTES) . '">Edit</button>'
            ];
        }
        // Return the response as JSON
        return $this->response->setJSON($response);
    }

    public function saveSchool()
    {
        $schoolModel = new \App\Models\schoolModel();
        $validation = $this->validate([
            'csrf_test_name'=>'required',
            'school_name'=>'required|is_unique[tblschool.schoolName]',
            'address'=>'required',
            'cluster'=>'required'
        ]);

        if(!$validation)
        {
            return $this->response->SetJSON(['error' => $this->validator->getErrors()]);
        }
        else
        {
            $status = 1;
            $date = date('Y-m-d');
            $data = ['schoolName'=>$this->request->getPost('school_name'),
                    'address'=>$this->request->getPost('address'),
                    'clusterID'=>$this->request->getPost('cluster'),
                    'Status'=>$status,'DateCreated'=>$date];
            $schoolModel->save($data);
            return $this->response->setJSON(['success' => 'Successfully applied']);
        }
    }
}