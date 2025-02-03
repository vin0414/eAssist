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
            //generate the code based on year
            $year = date('Y');$code = "";
            $builder = $this->db->table('tblform');
            $builder->select('COUNT(formID)+1 as total');
            $builder->WHERE('Year(DateCreated)',$year);
            $getData = $builder->get()->getRow();
            if($getData)
            {
                $code = "TA-".$year."-".str_pad($getData->total, 4, '0', STR_PAD_LEFT);
            }


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
                $data = ['DateCreated'=>$date,'Code'=>$code,'accountID'=>$user,
                        'clusterID'=>$account['clusterID'],'schoolID'=>$account['schoolID'],
                        'Agree'=>$this->request->getPost('agreement'),'subjectID'=>$this->request->getPost('area'),
                        'Details'=>$this->request->getPost('details'),
                        'priorityLevel'=>$this->request->getPost('priority'),'Status'=>$status];
                $formModel->save($data);
            }
            else
            {
                $file->move('files/',$originalName);
                $data = ['DateCreated'=>$date,'Code'=>$code,'accountID'=>$user,
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
            //send to EPS/PSDS
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

    public function userRequest()
    {
        $formModel = new \App\Models\formModel();

        $user = session()->get('loggedUser');
        $builder = $this->db->table('tblform a');
        $builder->select('a.*,b.subjectName');
        $builder->join('tblsubject b','b.subjectID=a.subjectID','LEFT');
        $builder->WHERE('a.accountID',$user);
        $form = $builder->get()->getResult();

        $totalRecords = $formModel->WHERE('accountID',$user)->countAllResults();

        $response = [
            "draw" => $_GET['draw'],
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecords,
            'data' => [] 
        ];
        foreach ($form as $row) {

            $response['data'][] = [
                'DateCreated' => date('Y-M-d', strtotime($row->DateCreated)),
                'TA' => htmlspecialchars($row->Code, ENT_QUOTES),
                'subjectName' => htmlspecialchars($row->subjectName, ENT_QUOTES),
                'Details' => htmlspecialchars($row->Details, ENT_QUOTES),
                'priorityLevel' => htmlspecialchars($row->priorityLevel, ENT_QUOTES),
                'Status'=>($row->Status == 0) ? '<span class="badge bg-warning">pending</span>' :
                (($row->Status == 2) ? '<span class="badge bg-danger">for revision</span>' :
                (($row->Status == 1) ? '<span class="badge bg-success">Completed</span>' : 
                '<span class="badge bg-info">Accepted</span>'))
            ];
        }
        // Return the response as JSON
        return $this->response->setJSON($response);
    }

    public function action()
    {
        $actionModel = new \App\Models\actionModel();
        $user = session()->get('loggedUser');
        $totalRecords = $actionModel->WHERE('requestorID',$user)->countAllResults();

        $builder = $this->db->table('tblaction a');
        $builder->select('a.*,b.Code,b.Status,c.subjectName');
        $builder->join('tblform b','b.formID=a.formID','LEFT');
        $builder->join('tblsubject c','c.subjectID=b.subjectID','LEFT');
        $builder->WHERE('b.accountID',$user);
        $records = $builder->get()->getResult();

        $response = [
            "draw" => $_GET['draw'],
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecords,
            'data' => [] 
        ];

        foreach ($records as $row) {

            $response['data'][] = [
                'DateCreated' => date('Y-M-d', strtotime($row->DateCreated)),
                'TA' => $row->Code,
                'subjectName' => htmlspecialchars($row->subjectName, ENT_QUOTES),
                'Recommendation' => htmlspecialchars($row->Recommendation, ENT_QUOTES),
                'Date'=>date('Y-M-d', strtotime($row->ImplementationDate)),
                'Action'=>($row->Status == 0) ? '-' :
                (($row->Status == 1) ? '<button type="button" class="badge bg-success comment" value="'.$row->actionID.'">Add Comment</button>' : 
                '<span class="badge bg-info">Processing</span>')
            ];
        }

        return $this->response->setJSON($response);
    }

    public function reviewRequest()
    {
        $reviewModel = new \App\Models\reviewModel();
        $user = session()->get('loggedUser');
        $totalRecords = $reviewModel->WHERE('accountID',$user)->countAllResults();
        
        $builder = $this->db->table('tblreview a');
        $builder->select('a.*,b.Fullname,c.Code,c.formID,c.Details,c.priorityLevel,d.subjectName');
        $builder->join('tblaccount b','b.accountID=a.accountID','LEFT');
        $builder->join('tblform c','c.formID=a.formID','LEFT');
        $builder->join('tblsubject d','d.subjectID=c.subjectID','LEFT');
        $builder->WHERE('a.accountID',$user);
        $review = $builder->get()->getResult();

        $response = [
            "draw" => $_GET['draw'],
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecords,
            'data' => [] 
        ];

        foreach ($review as $row) {

            $response['data'][] = [
                'DateReceived' => date('Y-M-d', strtotime($row->DateReceived)),
                'priorityLevel' => ($row->priorityLevel=="High") ? '<span class="badge bg-danger"><i class="fa-solid fa-triangle-exclamation"></i>&nbsp;'.$row->priorityLevel.'</span>' : $row->priorityLevel,
                'RefNo' => '<button type="button" class="btn btn-link btn-sm view" value="'.$row->Code.'" style="padding:0px;">'.htmlspecialchars($row->Code, ENT_QUOTES).'</button>',
                'From' => htmlspecialchars($row->Fullname, ENT_QUOTES),
                'subjectName' => htmlspecialchars($row->subjectName, ENT_QUOTES),
                'Details' => htmlspecialchars($row->Details, ENT_QUOTES),
                'Status'=>($row->Status == 0) ? '<span class="badge bg-warning">pending</span>' :
                (($row->Status == 2) ? '<span class="badge bg-danger">revision</span>' : 
                (($row->Status == 1) ? '<span class="badge bg-success">Completed</span>' : 
                '<span class="badge bg-info">ongoing</span>')),
                'DateApproved'=>$row->DateApproved
            ];
        }

        return $this->response->setJSON($response);
    }

    public function totalReview()
    {
        $user = session()->get('loggedUser');
        $status = ['0',3];
        $builder = $this->db->table('tblreview');
        $builder->select('COUNT(*)as total');
        $builder->WHERE('accountID',$user)->WHEREIN('Status',$status);
        $total = $builder->get()->getRow();
        if($total)
        {
            echo $total->total;
        }
    }

    public function viewDetails()
    {
        $val = $this->request->getGet('value');
        $builder = $this->db->table('tblform a');
        $builder->select('a.formID,a.accountID,a.Details,b.subjectName,c.Email,c.Fullname,d.schoolName,e.clusterName,f.Status');
        $builder->join('tblsubject b','b.subjectID=a.subjectID','LEFT');
        $builder->join('tblaccount c','c.accountID=a.accountID','LEFT');
        $builder->join('tblschool d','d.schoolID=a.schoolID','LEFT');
        $builder->join('tblcluster e','e.clusterID=a.clusterID','LEFT');
        $builder->join('tblreview f','f.formID=a.formID','LEFT');
        $builder->WHERE('a.Code',$val);
        $data = $builder->get()->getRow();
        if($data)
        {
            ?>
            <form method="POST" class="row g-2" id="frmReview">
                <?= csrf_field(); ?>
                <input type="hidden" name="formID" value="<?php echo $data->formID ?>"/>
                <input type="hidden" name="requestorID" value="<?php echo $data->accountID ?>"/>
                <div class="col-lg-12">
                    <div class="row g-2">
                        <div class="col-lg-6">
                            <label>Fullname</label>
                            <input type="text" class="form-control" value="<?php echo $data->Fullname ?>"/>
                        </div>
                        <div class="col-lg-6">
                            <label>Email Address</label>
                            <input type="email" class="form-control" value="<?php echo $data->Email ?>"/>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="row g-2">
                        <div class="col-lg-4">
                            <label>Cluster</label>
                            <input type="text" class="form-control" value="<?php echo $data->clusterName ?>"/>
                        </div>
                        <div class="col-lg-8">
                            <label>School</label>
                            <input type="text" class="form-control" value="<?php echo $data->schoolName ?>"/>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <label>Area of Concerns</label>
                    <input type="text" class="form-control" value="<?php echo $data->subjectName ?>"/>
                </div>
                <div class="col-lg-12">
                    <label>Details of Technical Assistance Needed</label>
                    <textarea class="form-control"><?php echo $data->Details ?></textarea>
                </div>
                <?php if(!empty($data->File)){ ?>
                <div class="col-lg-12">
                    <label>Attachment</label>
                    <a class="form-control" href="<?=base_url('files')?>/<?php echo $data->File ?>" target="_BLANK"><?php echo $data->File ?></a>
                </div>
                <?php }?>
                <?php if($data->Status==0){ ?>
                <div class="col-lg-12">
                    <label>Technical Assistance Provided</label>
                    <textarea class="form-control" name="action_provided" required></textarea>
                    <div id="action_provided-error" class="error-message text-danger text-sm"></div>
                </div>
                <div class="col-lg-12">
                    <label>Recommendation</label>
                    <textarea class="form-control" name="recommendation" required></textarea>
                    <div id="recommendation-error" class="error-message text-danger text-sm"></div>
                </div>
                <div class="col-lg-12">
                    <label>Date of Implementation</label>
                    <input type="date" class="form-control" name="date" required/>
                    <div id="date-error" class="error-message text-danger text-sm"></div>
                </div>
                <?php } ?>
                <div class="col-lg-12">
                    <?php if($data->Status==0){ ?>
                    <button type="submit" class="btn btn-info accept"><i class="fa-solid fa-check"></i>&nbsp;Accept</button>
                    <button type="button" class="btn btn-danger reset"><i class="fa-solid fa-xmark"></i>&nbsp;Revise</button>
                    <?php }else if($data->Status==3){ ?>
                    <button type="submit" class="btn btn-info complete" value="<?php echo $data->formID ?>"><i class="fa-solid fa-flag"></i>&nbsp;Complete</button>
                    <?php } ?>
                </div>
            </form>
            <?php
        }
    }

    public function acceptForm()
    {
        $actionModel = new \App\Models\actionModel();
        $reviewModel = new \App\Models\reviewModel();
        $formModel = new \App\Models\formModel();
        //data
        $validation = $this->validate([
            'csrf_test_name'=>'required',
            'formID'=>'required',
            'requestorID'=>'required',
            'action_provided'=>'required',
            'recommendation'=>'required',
            'date'=>'required'
        ]);
        if(!$validation)
        {
            return $this->response->SetJSON(['error' => $this->validator->getErrors()]);
        }
        else
        {
            $user = session()->get('loggedUser');
            $date = date('Y-m-d');
            $data = ['DateCreated'=>$date,'formID'=>$this->request->getPost('formID'),
                    'accountID'=>$user,'actionName'=>$this->request->getPost('action_provided'),
                    'Recommendation'=>$this->request->getPost('recommendation'),
                    'implementationDate'=>$this->request->getPost('date'),
                    'requestorID'=>$this->request->getPost('requestorID')];
            $actionModel->save($data);
            //update the status
            $review = $reviewModel
                    ->WHERE('formID',$this->request->getPost('formID'))
                    ->WHERE('accountID',$user)->first();
            $records = ['Status'=>3,'DateApproved'=>$date];
            $reviewModel->update($review['reviewID'],$records); 
            //update the form
            $newData = ['Status'=>3];
            $formModel->update($this->request->getPost('formID'),$newData);           
            return $this->response->setJSON(['success' => 'Successfully submitted']);
        }
    }

    public function deniedForm()
    {
        $reviewModel = new \App\Models\reviewModel();
        $formModel = new \App\Models\formModel();
        //data
        $validation = $this->validate([
            'csrf_test_name'=>'required',
            'formID'=>'required',
            'requestorID'=>'required',
            'action_provided'=>'required',
            'recommendation'=>'required',
            'date'=>'required'
        ]);
        if(!$validation)
        {
            return $this->response->SetJSON(['error' => $this->validator->getErrors()]);
        }
        else
        {

        }
    }

    public function completeForm()
    {
        $formModel = new \App\Models\formModel();
        $reviewModel = new \App\Models\reviewModel();
        $val = $this->request->getPost('value');

        $validation = $this->validate([
            'value'=>'required|numeric',
        ]);

        if(!$validation)
        {
            echo "Invalid Input. Please try again";
        }
        else
        {
            $status = 1;
            $data = ['Status'=>$status];
            $formModel->update($val,$data);
            //get the review ID
            $review = $reviewModel->WHERE('formID',$val)->first();
            //update the review status
            $record = ['Status'=>$status];
            $reviewModel->update($review['reviewID'],$record);
            echo "success";
        }
    }
}