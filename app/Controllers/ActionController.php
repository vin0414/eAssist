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
                'actions' => '<button class="btn btn-sm view" value="' . htmlspecialchars($row->schoolID, ENT_QUOTES) . '"><i class="fa-regular fa-pen-to-square"></i>&nbsp;Edit</button>'
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

    public function schoolData()
    {
        $clusterModel = new \App\Models\clusterModel();
        $cluster = $clusterModel->findAll();

        $schoolModel = new \App\Models\schoolModel();
        $school = $schoolModel->WHERE('schoolID',$this->request->getGet('value'))->first();
        if($school)
        {
            ?>
            <form method="POST" id="frmEditSchool">
                <?= csrf_field(); ?>
                <input type="hidden" name="schoolID" value="<?php echo $school['schoolID'] ?>"/>
                <div class="row">
                <div class="col-12 form-group">
                    <label>School Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="new_school_name" value="<?php echo $school['schoolName'] ?>" required/>
                    <div id="new_school_name-error" class="error-message text-danger text-sm"></div>
                </div>
                <div class="col-12 form-group">
                    <label>School Address <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="new_address" required><?php echo $school['address'] ?></textarea>
                    <div id="new_address-error" class="error-message text-danger text-sm"></div>
                </div>
                <div class="col-12 form-group">
                    <label>Cluster <span class="text-danger">*</span></label>
                    <select class="form-control" name="new_cluster" required>
                    <option value="">Choose</option>
                    <?php foreach($cluster as $row): ?>
                        <option value="<?php echo $row['clusterID'] ?>" <?php echo ($school['clusterID'] == $row['clusterID']) ? 'selected' : ''; ?>><?php echo $row['clusterName'] ?></option>
                    <?php endforeach; ?>
                    </select>
                    <div id="new_cluster-error" class="error-message text-danger text-sm"></div>
                </div>
                <div class="col-12 form-group">
                    <button type="submit" class="btn btn-primary save"><i class="fa-regular fa-floppy-disk"></i>&nbsp;Save Changes</button>
                </div>
                </div>
            </form>
            <?php
        }
    }

    public function editSchool()
    {
        $schoolModel = new \App\Models\schoolModel();
        $validation = $this->validate([
            'csrf_test_name'=>'required',
            'new_school_name'=>'required',
            'new_address'=>'required',
            'new_cluster'=>'required'
        ]);

        if(!$validation)
        {
            return $this->response->SetJSON(['error' => $this->validator->getErrors()]);
        }
        else
        {
            $data = ['schoolName'=>$this->request->getPost('new_school_name'),
                    'address'=>$this->request->getPost('new_address'),
                    'clusterID'=>$this->request->getPost('new_cluster')];
            $schoolModel->update($this->request->getPost('schoolID'),$data);
            return $this->response->setJSON(['success' => 'Successfully applied']);
        }
    }

    public function save()
    {
        $accountModel = new \App\Models\accountModel();
        $validation = $this->validate([
            'csrf_test_name'=>'required',
            'fullname'=>'required|is_unique[tblaccount.Fullname]',
            'email'=>'required|is_unique[tblaccount.Email]',
            'role'=>'required',
            'user_type'=>'required'
        ]);

        if(!$validation)
        {
            return $this->response->SetJSON(['error' => $this->validator->getErrors()]);
        }
        else
        {
            //get the default password
            $passwordModel = new \App\Models\passwordModel();
            $password = $passwordModel->first();
            //additional data
            $status = 1;
            $date = date('Y-m-d');
            //save the data
            $data = ['Email'=>$this->request->getPost('email'), 'Password'=>$password['Password'],
                    'Fullname'=>$this->request->getPost('fullname'),
                    'Role'=>$this->request->getPost('role'),
                    'clusterID'=>$this->request->getPost('cluster'),
                    'schoolID'=>$this->request->getPost('school'),
                    'subjectID'=>$this->request->getPost('subject'),
                    'userType'=>$this->request->getPost('user_type'),
                    'Status'=>$status,'Token'=>$this->request->getPost('csrf_test_name'),'DateCreated'=>$date];
            $accountModel->save($data);
            return $this->response->setJSON(['success' => 'Successfully registered']);
        }
    }

    public function edit()
    {
        $accountModel = new \App\Models\accountModel();
        $validation = $this->validate([
            'csrf_test_name'=>'required',
            'fullname'=>'required',
            'email'=>'required',
            'role'=>'required',
            'user_type'=>'required'
        ]);
        if(!$validation)
        {
            return $this->response->SetJSON(['error' => $this->validator->getErrors()]);
        }
        else
        {
            //save the data
            $data = ['Email'=>$this->request->getPost('email'),
                    'Fullname'=>$this->request->getPost('fullname'),
                    'Role'=>$this->request->getPost('role'),
                    'clusterID'=>$this->request->getPost('cluster'),
                    'schoolID'=>$this->request->getPost('school'),
                    'subjectID'=>$this->request->getPost('subject'),
                    'userType'=>$this->request->getPost('user_type')];
            $accountModel->update($this->request->getPost('accountID'),$data);
            return $this->response->setJSON(['success' => 'Successfully registered']);
        }
    }

    public function savePassword()
    {
        $passwordModel = new \App\Models\passwordModel();
        $validation = $this->validate([
            'csrf_test_name'=>'required',
            'password'=>'required|min_length[8]|max_length[12]|regex_match[/[A-Z]/]|regex_match[/[a-z]/]|regex_match[/[0-9]/]',
        ]);
        if(!$validation)
        {
            return $this->response->SetJSON(['error' => $this->validator->getErrors()]);
        }
        else
        {
            $newPassword = Hash::make($this->request->getPost('password'));
            $date = date('Y-m-d');
            //validate if empty of not
            $passwordData = $passwordModel->first();
            if(empty($passwordData))
            {
                $data = ['Password'=>$newPassword,'DateCreated'=>$date];
                $passwordModel->save($data);
            }
            else
            {
                $data = ['Password'=>$newPassword,'DateCreated'=>$date];
                $passwordModel->update($passwordData['passwordID'],$data);
            }
            return $this->response->setJSON(['success' => 'Successfully applied']);
        }
    }

    public function resetPassword()
    {
        $passwordModel = new \App\Models\passwordModel();
        $accountModel = new \App\Models\accountModel();
        $val = $this->request->getPost('value');
        //get the default password
        $passwordData = $passwordModel->first();
        $data = ['Password'=>$passwordData['Password']];
        $accountModel->update($val,$data);
        echo "Successfully applied changes";
    }

    public function saveForm()
    {
        $formModel = new \App\Models\formModel();
        $reviewModel = new \App\Models\reviewModel();
        $accountModel = new \App\Models\accountModel();
        //data
        $validation = $this->validate([
            'csrf_test_name'=>'required',
            'agreement'=>'required',
            'area'=>'required',
            'account.*'=>'required',
            'details'=>'required',
            'priority'=>'required'
        ]);
        if(!$validation)
        {
            return $this->response->SetJSON(['error' => $this->validator->getErrors()]);
        }
        else
        {
            $user = session()->get('loggedUser');
            $users = $this->request->getPost('account');
            $status = 0;$date = date('Y-m-d');
            $file = $this->request->getFile('file');
            $originalName = $file->getClientName();
            //get the cluster ID and school ID
            $account = $accountModel->WHERE('accountID',$user)->first();
            //save the form
            if(empty($originalName))
            {
                $data = ['DateCreated'=>$date,'accountID'=>$user,
                        'clusterID'=>$account['clusterID'],'schoolID'=>$account['schoolID'],
                        'Agree'=>$this->request->getPost('agreement'),'subjectID'=>$this->request->getPost('area'),
                        'Details'=>$this->request->getPost('details'),
                        'priorityLevel'=>$this->request->getPost('priority'),'Status'=>$status];
                $formModel->save($data);
            }
            else
            {
                $file->move('files/',$originalName);
                $data = ['DateCreated'=>$date,'accountID'=>$user,
                        'clusterID'=>$account['clusterID'],'schoolID'=>$account['schoolID'],
                        'Agree'=>$this->request->getPost('agreement'),'subjectID'=>$this->request->getPost('area'),
                        'Details'=>$this->request->getPost('details'),'File'=>$originalName,
                        'priorityLevel'=>$this->request->getPost('priority'),'Status'=>$status];
                $formModel->save($data);
            }
            //get the form ID
            $form = $formModel->WHERE('Details',$this->request->getPost('details'))
                              ->WHERE('priorityLevel',$this->request->getPost('priority'))
                              ->first();
            //get the PSDS per cluster
            $headUser = $accountModel->WHERE('clusterID',$account['clusterID'])
                                     ->WHERE('userType','PSDS')->first();
            //send to PSDS
            $data = ['DateReceived'=>$date, 
                    'accountID'=>$headUser['accountID'],
                    'formID'=>$form['formID'],
                    'Status'=>$status,
                    'DateApproved'=>'0000-00-00'];
            $reviewModel->save($data);
            //send to EPS
            $count = count($users);
            for($i=0;$i<$count;$i++)
            {
                //get the accountID
                $userAccount = $accountModel->WHERE('accountID',$users[$i])->findAll();
                foreach($userAccount as $row)
                {
                    $data = ['DateReceived'=>$date, 
                    'accountID'=>$row['accountID'],
                    'formID'=>$form['formID'],
                    'Status'=>$status,
                    'DateApproved'=>'0000-00-00'];
                    $reviewModel->save($data);
                }
            }
            return $this->response->setJSON(['success' => 'Successfully submitted']);
        }
    }
}