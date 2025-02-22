<?php

namespace App\Controllers;
use App\Libraries\Hash;

class ActionController extends BaseController
{
    private $db;
    public function __construct()
    {
        helper(['url','form','text']);
        $this->db = db_connect();
    }

    //cluster
    public function fetchCluster()
    {
        $sql = "Select a.*,b.Fullname,b.Position from tblcluster a LEFT JOIN
        (Select clusterID,Fullname,Position,userType from tblaccount WHERE userType='PSDS') b ON b.clusterID=a.clusterID";
        $query = $this->db->query($sql);
        $cluster = $query->getResult();
        foreach($cluster as $row)
        {
            ?>
<tr>
    <td>
        <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
                <h6 class="mb-0 text-sm"><?php echo $row->clusterName ?></h6>
                <p class="text-xs text-secondary mb-0">Date Created :
                    <?php echo date('Y-M-d', strtotime($row->DateCreated)) ?></p>
            </div>
        </div>
    </td>
    <td>
        <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
                <h6 class="mb-0 text-sm"><?php echo $row->Fullname ?></h6>
                <p class="text-xs text-secondary mb-0"><?php echo $row->Position ?></p>
            </div>
        </div>
    </td>
    <td class="align-middle">
        <button type="button" class="btn btn-success btn-sm editCluster" value="<?php echo $row->clusterID ?>"><i
                class="fa-regular fa-pen-to-square"></i>&nbsp;Rename</button>
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
            //create log
            date_default_timezone_set('Asia/Manila');
            $logModel = new \App\Models\logModel();
            $data = ['accountID'=>session()->get('loggedUser'),'Activity'=>'Added new cluster','DateCreated'=>date('Y-m-d H:i:s a')];
            $logModel->save($data);
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
            //create log
            date_default_timezone_set('Asia/Manila');
            $logModel = new \App\Models\logModel();
            $data = ['accountID'=>session()->get('loggedUser'),'Activity'=>'Rename cluster','DateCreated'=>date('Y-m-d H:i:s a')];
            $logModel->save($data);
            return $this->response->setJSON(['success' => 'Successfully applied']);
        }
    }

    //subject

    public function fetchSubject()
    {
        $subjectModel = new \App\Models\subjectModel();
        $searchTerm = $_GET['search']['value'] ?? '';
        
        if ($searchTerm) {
            $subjectModel->like('subjectName', $searchTerm); // Assuming you're searching on the 'subjectName' column
        }

        $subject = $subjectModel->findAll();

        $totalRecords = $subjectModel->countAllResults();

        $subjectModel->like('subjectName', $searchTerm);
        $totalFiltered = $subjectModel->countAllResults();

        $response = [
            "draw" => $_GET['draw'],
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalFiltered,
            'data' => [] 
        ];
        foreach ($subject as $row) {
            $response['data'][] = [
                'date'=>date('Y-M-d',strtotime($row['DateCreated'])),
                'area' =>$row['subjectName'],
                'action' => '<button class="btn btn-success btn-sm editSubject" value="' . htmlspecialchars($row['subjectID'], ENT_QUOTES) . '"><i class="fa-regular fa-pen-to-square"></i>&nbsp;Rename</button>'
            ];
        }
        // Return the response as JSON
        return $this->response->setJSON($response);
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
            //create log
            date_default_timezone_set('Asia/Manila');
            $logModel = new \App\Models\logModel();
            $data = ['accountID'=>session()->get('loggedUser'),'Activity'=>'Added new area of concern','DateCreated'=>date('Y-m-d H:i:s a')];
            $logModel->save($data);
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
            //create log
            date_default_timezone_set('Asia/Manila');
            $logModel = new \App\Models\logModel();
            $data = ['accountID'=>session()->get('loggedUser'),'Activity'=>'Rename selected area of concern','DateCreated'=>date('Y-m-d H:i:s a')];
            $logModel->save($data);
            return $this->response->setJSON(['success' => 'Successfully applied']);
        }
    }

    public function fetchSchoolData()
    {
        $schoolModel = new \App\Models\schoolModel();
        $searchTerm = $_GET['search']['value'] ?? ''; 
        // Initialize the query builder for the 'tblschool' table
        $builder = $this->db->table('tblschool a');
        $builder->select('a.DateCreated, a.schoolName, a.address, a.schoolID, b.clusterName');
        $builder->join('tblcluster b', 'b.clusterID = a.clusterID', 'LEFT');
        $builder->groupBy('a.schoolID');

        // Apply search filter if a search term exists
        if ($searchTerm) {
            // Add a LIKE condition to filter based on school name or address or any other column you wish to search
            $builder->groupStart()
                    ->like('a.schoolName', $searchTerm)
                    ->orLike('a.address', $searchTerm)
                    ->orLike('b.clusterName', $searchTerm)
                    ->groupEnd();
        }

        // Execute the query and fetch the results
        $school = $builder->get()->getResult();

        // Total number of records (without search filter)
        $totalRecords = $this->db->table('tblschool')->countAllResults();

        // Total number of filtered records (with search filter applied)
        $filteredRecords = count($school);

        $totalRecords = $schoolModel->getTotalRecords();

        $response = [
            "draw" => $_GET['draw'],
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $filteredRecords,
            'data' => [] 
        ];
        foreach ($school as $row) {
            $response['data'][] = [
                'DateCreated' => date('Y-M-d', strtotime($row->DateCreated)),
                'schoolName' => htmlspecialchars($row->schoolName, ENT_QUOTES),
                'address' => htmlspecialchars($row->address, ENT_QUOTES),
                'clusterName' => htmlspecialchars($row->clusterName, ENT_QUOTES),
                'actions' => '<button class="btn btn-success btn-sm view" value="' . htmlspecialchars($row->schoolID, ENT_QUOTES) . '"><i class="fa-regular fa-pen-to-square"></i>&nbsp;Edit</button>'
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
            //create log
            date_default_timezone_set('Asia/Manila');
            $logModel = new \App\Models\logModel();
            $data = ['accountID'=>session()->get('loggedUser'),'Activity'=>'Added new school','DateCreated'=>date('Y-m-d H:i:s a')];
            $logModel->save($data);
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
    <input type="hidden" name="schoolID" value="<?php echo $school['schoolID'] ?>" />
    <div class="row">
        <div class="col-12 form-group">
            <label>School Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="new_school_name" value="<?php echo $school['schoolName'] ?>"
                required />
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
                <option value="<?php echo $row['clusterID'] ?>"
                    <?php echo ($school['clusterID'] == $row['clusterID']) ? 'selected' : ''; ?>>
                    <?php echo $row['clusterName'] ?></option>
                <?php endforeach; ?>
            </select>
            <div id="new_cluster-error" class="error-message text-danger text-sm"></div>
        </div>
        <div class="col-12 form-group">
            <button type="submit" class="btn btn-primary save"><i class="fa-regular fa-floppy-disk"></i>&nbsp;Save
                Changes</button>
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
             //create log
             date_default_timezone_set('Asia/Manila');
             $logModel = new \App\Models\logModel();
             $data = ['accountID'=>session()->get('loggedUser'),'Activity'=>'Update selected school','DateCreated'=>date('Y-m-d H:i:s a')];
             $logModel->save($data);
            return $this->response->setJSON(['success' => 'Successfully applied']);
        }
    }

    public function save()
    {
        $accountModel = new \App\Models\accountModel();
        $validation = $this->validate([
            'csrf_test_name'=>'required',
            'fullname'=>'required|is_unique[tblaccount.Fullname]',
            'position'=>'required',
            'office'=>'required',
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
            $token_code = random_string('alnum',64);
            //save the data
            $data = ['Email'=>$this->request->getPost('email'), 'Password'=>$password['Password'],
                    'Fullname'=>$this->request->getPost('fullname'),
                    'Position'=>$this->request->getPost('position'),
                    'Office'=>$this->request->getPost('office'),
                    'Role'=>$this->request->getPost('role'),
                    'clusterID'=>$this->request->getPost('cluster'),
                    'schoolID'=>$this->request->getPost('school'),
                    'subjectID'=>$this->request->getPost('subject'),
                    'userType'=>$this->request->getPost('user_type'),
                    'Status'=>$status,'Token'=>$token_code,'DateCreated'=>$date];
            $accountModel->save($data);
             //create log
             date_default_timezone_set('Asia/Manila');
             $logModel = new \App\Models\logModel();
             $data = ['accountID'=>session()->get('loggedUser'),'Activity'=>'Register new account','DateCreated'=>date('Y-m-d H:i:s a')];
             $logModel->save($data);
            return $this->response->setJSON(['success' => 'Successfully registered']);
        }
    }

    public function edit()
    {
        $accountModel = new \App\Models\accountModel();
        $validation = $this->validate([
            'csrf_test_name'=>'required',
            'position'=>'required',
            'office'=>'required',
            'fullname'=>'required',
            'email'=>'required',
            'role'=>'required',
            'user_type'=>'required',
            'status'=>'required'
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
                    'Position'=>$this->request->getPost('position'),
                    'Office'=>$this->request->getPost('office'),
                    'Role'=>$this->request->getPost('role'),
                    'clusterID'=>$this->request->getPost('cluster'),
                    'schoolID'=>$this->request->getPost('school'),
                    'subjectID'=>$this->request->getPost('subject'),
                    'userType'=>$this->request->getPost('user_type'),
                    'Status'=>$this->request->getPost('status')
                ];
            $accountModel->update($this->request->getPost('accountID'),$data);
             //create log
             date_default_timezone_set('Asia/Manila');
             $logModel = new \App\Models\logModel();
             $data = ['accountID'=>session()->get('loggedUser'),'Activity'=>'Update selected account','DateCreated'=>date('Y-m-d H:i:s a')];
             $logModel->save($data);
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
             //create log
             date_default_timezone_set('Asia/Manila');
             $logModel = new \App\Models\logModel();
             $data = ['accountID'=>session()->get('loggedUser'),'Activity'=>'Added/Update system password','DateCreated'=>date('Y-m-d H:i:s a')];
             $logModel->save($data);
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
         //create log
         date_default_timezone_set('Asia/Manila');
         $logModel = new \App\Models\logModel();
         $data = ['accountID'=>session()->get('loggedUser'),'Activity'=>'Reset account password','DateCreated'=>date('Y-m-d H:i:s a')];
         $logModel->save($data);
        echo "Successfully applied changes";
    }

    public function addForm()
    {
        $formModel = new \App\Models\formModel();
        $reviewModel = new \App\Models\reviewModel();
        $accountModel = new \App\Models\accountModel();

        $validation = $this->validate([
            'csrf_test_name'=>'required',
            'agreement'=>'required',
            'area'=>'required',
            'school'=>'required',
            'details'=>'required',
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
            $status = 0;$date = date('Y-m-d');
            $dateTime = new \DateTime();
            $dateTime->modify('+7 days'); 
            $endDate = $dateTime->format('Y-m-d');
            $file = $this->request->getFile('file');
            $originalName = $file->getClientName();
            //get the cluster ID and school ID
            $schoolModel = new \App\Models\schoolModel();
            $school = $schoolModel->WHERE('schoolID',$this->request->getPost('school'))->first();
            //approver
            $account = $accountModel->WHERE('accountID',$user)->first();
            //save the form
            if(empty($originalName))
            {
                $data = ['DateCreated'=>$date,'Code'=>$code,'accountID'=>$user,
                        'clusterID'=>$school['clusterID'],'schoolID'=>$this->request->getPost('school'),
                        'Agree'=>$this->request->getPost('agreement'),'subjectID'=>$this->request->getPost('area'),
                        'Details'=>$this->request->getPost('details'),
                        'priorityLevel'=>'Low','Status'=>$status];
                $formModel->save($data);
            }
            else
            {
                $file->move('files/',$originalName);
                $data = ['DateCreated'=>$date,'Code'=>$code,'accountID'=>$user,
                        'clusterID'=>$school['clusterID'],'schoolID'=>$this->request->getPost('school'),
                        'Agree'=>$this->request->getPost('agreement'),'subjectID'=>$this->request->getPost('area'),
                        'Details'=>$this->request->getPost('details'),'File'=>$originalName,
                        'priorityLevel'=>'Low','Status'=>$status];
                $formModel->save($data);
            }
            //get the form ID
            $form = $formModel->WHERE('Code',$code)->first();
            //send to EPS/PSDS
            $data = ['DateReceived'=>$date, 
            'accountID'=>$user,
            'formID'=>$form['formID'],
            'Status'=>$status,
            'DateApproved'=>'0000-00-00'];
            $reviewModel->save($data);
        
            //send email notification
            $email = \Config\Services::email();
            $email->setTo($account['Email']);
            $email->setFrom("vinmogate@gmail.com","ASSIST");
            $imgURL = "assets/img/Logo.png";
            $email->attach($imgURL);
            $cid = $email->setAttachmentCID($imgURL);
            $template = "<center>
            <img src='cid:". $cid ."' width='100'/>
            <table style='padding:20px;background-color:#ffffff;' border='0'><tbody>
            <tr><td><center><h1>Technical Assistance</h1></center></td></tr>
            <tr><td><center>Hi, ".$account['Fullname']."</center></td></tr>
            <tr><td><p><center>".$school['schoolName']." sent you a technical assistance request for your review/approval.</center></p></td><tr>
            <tr><td><p><center>Kindly login to your account in <a href='https://assist.x10.bz'>Visit Website</a> to take the action until ".$endDate."</center></p></td></tr>
            <tr><td><center>ASSIST IT Support</center></td></tr></tbody></table></center>";
            $subject = "Technical Assistance | ASSIST";
            $email->setSubject($subject);
            $email->setMessage($template);
            $email->send();
             //create log
             date_default_timezone_set('Asia/Manila');
             $logModel = new \App\Models\logModel();
             $data = ['accountID'=>session()->get('loggedUser'),'Activity'=>'Create new Technical Assistance','DateCreated'=>date('Y-m-d H:i:s a')];
             $logModel->save($data);
            return $this->response->setJSON(['success' => 'Successfully submitted']);
        }
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
            $dateTime = new \DateTime();
            $dateTime->modify('+7 days'); 
            $endDate = $dateTime->format('Y-m-d');
            $file = $this->request->getFile('file');
            $originalName = $file->getClientName();
            //get the cluster ID and school ID
            $account = $accountModel->WHERE('accountID',$user)->first();
            //get the school name
            $schoolModel = new \App\Models\schoolModel();
            $school = $schoolModel->WHERE('schoolID',$account['schoolID'])->first();
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
            $form = $formModel->WHERE('Code',$code)->first();
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
                
                    //send email notification
                    $email = \Config\Services::email();
                    $email->setTo($row['Email']);
                    $email->setFrom("vinmogate@gmail.com","ASSIST");
                    $imgURL = "assets/img/Logo.png";
                    $email->attach($imgURL);
                    $cid = $email->setAttachmentCID($imgURL);
                    $template = "<center>
                    <img src='cid:". $cid ."' width='100'/>
                    <table style='padding:20px;background-color:#ffffff;' border='0'><tbody>
                    <tr><td><center><h1>Technical Assistance</h1></center></td></tr>
                    <tr><td><center>Hi, ".$row['Fullname']."</center></td></tr>
                    <tr><td><p><center>".$school['schoolName']." sent you a technical assistance request for your review/approval.</center></p></td><tr>
                    <tr><td><p><center>Kindly login to your account in <a href='https://assist.x10.bz'>Visit Website</a> to take the action until ".$endDate."</center></p></td></tr>
                    <tr><td><center>ASSIST IT Support</center></td></tr></tbody></table></center>";
                    $subject = "Technical Assistance | ASSIST";
                    $email->setSubject($subject);
                    $email->setMessage($template);
                    $email->send();
                }
            }
             //create log
             date_default_timezone_set('Asia/Manila');
             $logModel = new \App\Models\logModel();
             $data = ['accountID'=>session()->get('loggedUser'),'Activity'=>'Create new Technical Assistance','DateCreated'=>date('Y-m-d H:i:s a')];
             $logModel->save($data);
            return $this->response->setJSON(['success' => 'Successfully submitted']);
        }
    }

    public function userRequest()
    {
        $searchTerm = $_GET['search']['value'] ?? '';
        $formModel = new \App\Models\formModel();
        $user = session()->get('loggedUser');
        $builder = $this->db->table('tblform a');
        $builder->select('a.*,b.subjectName,IFNULL(c.Message,"-")Message,e.Fullname');
        $builder->join('tblsubject b','b.subjectID=a.subjectID','LEFT');
        $builder->join('tblcomment c', 'c.formID = a.formID', 'LEFT');
        $builder->join('tblreview d','d.formID=a.formID','LEFT');
        $builder->join('tblaccount e','e.accountID=d.accountID','LEFT');
        $builder->WHERE('a.accountID',$user);
        $builder->orderBy('c.DateCreated', 'DESC');
        $builder->groupBy('a.formID');
        if ($searchTerm) 
        {
            $builder->groupStart()
            ->like('a.DateCreated', $searchTerm)
            ->orLike('a.Code', $searchTerm)
            ->orLike('a.Details', $searchTerm)
            ->orLike('a.priorityLevel', $searchTerm)
            ->orLike('a.Status', $searchTerm)
            ->orLike('b.subjectName', $searchTerm)
            ->orLike('c.Message', $searchTerm)
            ->orLike('e.Fullname', $searchTerm)
            ->groupEnd();
        }
        $form = $builder->get()->getResult();

        $totalRecords = $formModel->WHERE('accountID',$user)->countAllResults();
        $filteredRecords = count($form);

        $response = [
            "draw" => $_GET['draw'],
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $filteredRecords,
            'data' => [] 
        ];
        foreach ($form as $row) {

            $response['data'][] = [
                'DateCreated' => date('Y-M-d', strtotime($row->DateCreated)),
                'TA' => ($row->Status==3) ? '<button type="button" class="btn btn-link btn-sm edit" style="padding:0px;" value='.$row->Code.'>'.$row->Code.'</button>' : $row->Code, 
                'subjectName' => htmlspecialchars($row->subjectName, ENT_QUOTES),
                'Details' => htmlspecialchars($row->Details, ENT_QUOTES),
                'priorityLevel' => htmlspecialchars($row->priorityLevel, ENT_QUOTES),
                'provider' => htmlspecialchars($row->Fullname, ENT_QUOTES),
                'Status'=>($row->Status == 0) ? '<span class="badge bg-warning">pending</span>' :
                (($row->Status == 2) ? '<span class="badge bg-danger">Declined</span>' :
                (($row->Status == 1) ? '<span class="badge bg-success">Completed</span>' : 
                (($row->Status == 3) ? '<span class="badge bg-info">For Revision</span>' : 
                '<span class="badge bg-info">Accepted</span>'))),
                'Comment'=>$row->Message
            ];
        }
        // Return the response as JSON
        return $this->response->setJSON($response);
    }

    public function fetchDetails()
    {
        $val = $this->request->getGet('value');
        //form
        $formModel = new \App\Models\formModel();
        $form = $formModel->WHERE('Code',$val)->first();
        //approver
        $reviewModel = new \App\Models\reviewModel();
        $review = $reviewModel->where('formID',$form['formID'])->first();
        //area of concerns
        $subjectModel = new \App\Models\subjectModel();
        $subject = $subjectModel->findAll();
        //users
        $accountModel = new \App\Models\accountModel();
        $account = $accountModel->WHERE('Role','Manager')->findAll();

        if($form):
        ?>
<form method="POST" class="row g-2" enctype="multipart/form-data" id="frmEditRequest">
    <?= csrf_field(); ?>
    <input type="hidden" name="formID" value="<?=$form['formID']?>" />
    <div class="col-12">
        <div><small>1. Please choose your area of concern</small></div>
        <select class="form-control" name="area" required>
            <option value="">Choose</option>
            <?php foreach($subject as $row): ?>
            <option value="<?php echo $row['subjectID'] ?>"
                <?php echo ($form['subjectID'] == $row['subjectID']) ? 'selected' : ''; ?>>
                <?php echo $row['subjectName'] ?>
            </option>
            <?php endforeach; ?>
        </select>
        <div id="area-error" class="error-message text-danger text-sm"></div>
    </div>
    <div class="col-12">
        <div><small>2. Based on your area of concern, from whom are you expecting the technical
                assistance to be coming?</small></div>
        <div class="row">
            <?php foreach($account as $row): ?>
            <div class="col-lg-6">
                <div class="radio-group">
                    <label>
                        <input type="checkbox" name="account[]" style="width:18px;height:18px;"
                            value="<?php echo $row['accountID'] ?>"
                            <?php echo ($review['accountID'] == $row['accountID']) ? 'checked' : ''; ?>>
                        <label class="align-middle"><?php echo $row['Fullname'] ?><br /><span
                                style="font-size:10px;"><?php echo $row['Position'] ?></span></label>
                    </label>
                </div>
            </div>
            <?php endforeach; ?>
            <div id="account-error" class="error-message text-danger text-sm"></div>
        </div>
    </div>
    <div class="col-12">
        <div><small>3. Details of Technical Assistance Needed</small></div>
        <span><small>Please provide specific details about your concerns, issues, or challenges
                based on your chosen area of concern/s. You may also provide data or any documents
                that may serve as reference for the TA providers.</small></span>
        <textarea class="form-control" name="details" required><?=$form['Details']?></textarea>
        <div id="details-error" class="error-message text-danger text-sm"></div>
    </div>
    <div class="col-12">
        <div><small>4. Supporting Documents</small></div>
        <span><small>Upload any supporting documents in PDF file format that will serve as reference
                for the TA provider in crafting his/ her technical assistance plan. Merge in one (1)
                file only</small></span>
        <input type="file" class="form-control" name="file" />
    </div>
    <div class="col-12">
        <div><small>5. Level of Priority for Technical Assistance</small></div>
        <div class="radio-group">
            <label>
                <?php if($form['priorityLevel']=="Low"){?>
                <input type="radio" name="priority" style="width:18px;height:18px;" value="Low" checked />
                <?php }else { ?>
                <input type="radio" name="priority" style="width:18px;height:18px;" value="Low" required />
                <?php } ?>
                <label class="align-middle">Low Priority</label>
            </label>
            <label>
                <?php if($form['priorityLevel']=="Medium"){?>
                <input type="radio" name="priority" style="width:18px;height:18px;" value="Medium" checked />
                <?php }else { ?>
                <input type="radio" name="priority" style="width:18px;height:18px;" value="Medium" required />
                <?php } ?>
                <label class="align-middle">Medium Priority</label>
            </label>
            <label>
                <?php if($form['priorityLevel']=="High"){?>
                <input type="radio" name="priority" style="width:18px;height:18px;" value="High" checked />
                <?php }else { ?>
                <input type="radio" name="priority" style="width:18px;height:18px;" value="High" required />
                <?php } ?>
                <label class="align-middle">High Priority</label>
            </label>
        </div>
        <div id="priority-error" class="error-message text-danger text-sm"></div>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-info save"><i class="fa-regular fa-floppy-disk"></i>&nbsp;Save Changes
        </button>
    </div>
</form>
<?php
        endif;
    }

    public function editForm()
    {
        $formModel = new \App\Models\formModel();
        $reviewModel = new \App\Models\reviewModel();
        $accountModel = new \App\Models\accountModel();
        //data
        $validation = $this->validate([
            'csrf_test_name'=>'required',
            'formID'=>'required',
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
            $file = $this->request->getFile('file');
            $originalName = $file->getClientName();
            $users = $this->request->getPost('account');
            $status = 0;
            $date = date('Y-m-d');
            $dateTime = new \DateTime();
            $dateTime->modify('+7 days'); 
            $endDate = $dateTime->format('Y-m-d');
            $val = $this->request->getPost('formID');
            //get the cluster ID and school ID
            $account = $accountModel->WHERE('accountID',session()->get('loggedUser'))->first();
            //get the school name
            $schoolModel = new \App\Models\schoolModel();
            $school = $schoolModel->WHERE('schoolID',$account['schoolID'])->first();
            //save the form
            if(empty($originalName))
            {
                $data = ['subjectID'=>$this->request->getPost('area'),
                        'Details'=>$this->request->getPost('details'),
                        'priorityLevel'=>$this->request->getPost('priority'),'Status'=>$status];
                $formModel->update($val,$data);
            }
            else
            {
                $file->move('files/',$originalName);
                $data = ['subjectID'=>$this->request->getPost('area'),
                        'Details'=>$this->request->getPost('details'),'File'=>$originalName,
                        'priorityLevel'=>$this->request->getPost('priority'),'Status'=>$status];
                $formModel->update($val,$data);
            }
            //get the form ID
            $form = $formModel->WHERE('formID',$val)->first();
            //send to EPS/PSDS
            $count = count($users);
            for($i=0;$i<$count;$i++)
            {
                //get the accountID
                $userAccount = $accountModel->WHERE('accountID',$users[$i])->findAll();
                foreach($userAccount as $row)
                {
                    //get the review ID
                    $review = $reviewModel->WHERE('formID',$form['formID'])->first();
                    //check if same user or not
                    if($review['accountID']==$row['accountID'])
                    {
                        $data = ['DateReceived'=>$date, 
                        'accountID'=>$row['accountID'],
                        'formID'=>$form['formID'],
                        'Status'=>$status,
                        'DateApproved'=>'0000-00-00'];
                        $reviewModel->update($review['reviewID'],$data);
                    }
                    else
                    {
                        $data = ['DateReceived'=>$date, 
                        'accountID'=>$row['accountID'],
                        'formID'=>$form['formID'],
                        'Status'=>$status,
                        'DateApproved'=>'0000-00-00'];
                        $reviewModel->save($data);
                    }
                
                    //send email notification
                    $email = \Config\Services::email();
                    $email->setTo($row['Email']);
                    $email->setFrom("vinmogate@gmail.com","ASSIST");
                    $imgURL = "assets/img/Logo.png";
                    $email->attach($imgURL);
                    $cid = $email->setAttachmentCID($imgURL);
                    $template = "<center>
                    <img src='cid:". $cid ."' width='100'/>
                    <table style='padding:20px;background-color:#ffffff;' border='0'><tbody>
                    <tr><td><center><h1>Technical Assistance</h1></center></td></tr>
                    <tr><td><center>Hi, ".$row['Fullname']."</center></td></tr>
                    <tr><td><p><center>".$school['schoolName']." sent you a technical assistance request for your review/approval.</center></p></td><tr>
                    <tr><td><p><center>Kindly login to your account in <a href='https://assist.x10.bz'>Visit Website</a> to take the action until ".$endDate."</center></p></td></tr>
                    <tr><td><center>ASSIST IT Support</center></td></tr></tbody></table></center>";
                    $subject = "Technical Assistance | ASSIST";
                    $email->setSubject($subject);
                    $email->setMessage($template);
                    $email->send();
                }
            }
             //create log
             date_default_timezone_set('Asia/Manila');
             $logModel = new \App\Models\logModel();
             $data = ['accountID'=>session()->get('loggedUser'),'Activity'=>'Revised the Technical Assistance','DateCreated'=>date('Y-m-d H:i:s a')];
             $logModel->save($data);
            return $this->response->setJSON(['success' => 'Successfully submitted']);
        }
    }

    public function getDetails()
    {
        $actionModel = new \App\Models\actionModel();
        $formModel = new \App\Models\formModel();
        $val = $this->request->getGet('value');
        $action = $actionModel->WHERE('actionID',$val)->first();
        $form = $formModel->WHERE('formID',$action['formID'])->first();
        echo $form['Code'];
    }

    public function reviewRequest()
    {
        $reviewModel = new \App\Models\reviewModel();
        $searchTerm = $_GET['search']['value'] ?? ''; // Get search term if exists
        $user = session()->get('loggedUser'); // Get the logged-in user

        // Count total records for pagination
        $totalRecords = $reviewModel->where('accountID', $user)->WHERE('Status',0)->countAllResults(); 

        // Initialize builder with the table and the joins
        $builder = $this->db->table('tblreview a');
        $builder->select('a.DateReceived, a.Status, c.clusterName, 
                b.Code, b.formID, b.Details, b.priorityLevel, 
                d.subjectName, e.schoolName');
        $builder->join('tblform b', 'b.formID = a.formID', 'LEFT');
        $builder->join('tblcluster c', 'c.clusterID = b.clusterID', 'LEFT');
        $builder->join('tblsubject d', 'd.subjectID = b.subjectID', 'LEFT');
        $builder->join('tblschool e', 'e.schoolID = b.schoolID', 'LEFT');

        // Apply filters for logged-in user and status = 0
        $builder->where('a.accountID', $user);
        $builder->where('a.Status', 0);

        // Check if a search term exists, and apply filtering
        if (!empty($searchTerm)) {
            $builder->groupStart() // Start grouping OR conditions
                ->like('a.DateReceived', $searchTerm)
                ->orLike('b.Code', $searchTerm)
                ->orLike('b.Details', $searchTerm)
                ->orLike('b.priorityLevel', $searchTerm)
                ->orLike('d.subjectName', $searchTerm)
                ->orLike('e.schoolName', $searchTerm)
                ->orLike('c.clusterName', $searchTerm) // Fixed typo here (removed extra dot)
                ->groupEnd(); // End grouping
        }

        // Get the results of the query
        $review = $builder->get()->getResult();

        $response = [
            "draw" => $_GET['draw'],
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => count($review),
            'data' => [] 
        ];

        foreach ($review as $row) {

            $response['data'][] = [
                'DateReceived' => date('Y-M-d', strtotime($row->DateReceived)),
                'priorityLevel' => ($row->priorityLevel=="High") ? '<span class="badge bg-danger"><i class="fa-solid fa-triangle-exclamation"></i>&nbsp;'.$row->priorityLevel.'</span>' : $row->priorityLevel,
                'RefNo' => '<button type="button" class="btn btn-link btn-sm view" value="'.$row->Code.'" style="padding:0px;">'.htmlspecialchars($row->Code, ENT_QUOTES).'</button>',
                'cluster' => htmlspecialchars($row->clusterName, ENT_QUOTES),
                'school' => htmlspecialchars($row->schoolName, ENT_QUOTES),
                'subjectName' => htmlspecialchars($row->subjectName, ENT_QUOTES),
                'Details' => htmlspecialchars($row->Details, ENT_QUOTES),
            ];
        }

        return $this->response->setJSON($response);
    }

    public function totalReview()
    {
        $user = session()->get('loggedUser');
        $status = [0];
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
        $commentModel = new \App\Models\commentModel(); 
        $user = session()->get('loggedUser');
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
    <input type="hidden" name="formID" value="<?php echo $data->formID ?>" />
    <input type="hidden" name="requestorID" value="<?php echo $data->accountID ?>" />
    <div class="col-lg-12">
        <div class="row g-2">
            <div class="col-lg-6">
                <label>Fullname</label>
                <input type="text" class="form-control" value="<?php echo $data->Fullname ?>" />
            </div>
            <div class="col-lg-6">
                <label>Email Address</label>
                <input type="email" class="form-control" value="<?php echo $data->Email ?>" />
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="row g-2">
            <div class="col-lg-4">
                <label>Cluster</label>
                <input type="text" class="form-control" value="<?php echo $data->clusterName ?>" />
            </div>
            <div class="col-lg-8">
                <label>School</label>
                <input type="text" class="form-control" value="<?php echo $data->schoolName ?>" />
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <label>Area of Concerns</label>
        <input type="text" class="form-control" value="<?php echo $data->subjectName ?>" />
    </div>
    <div class="col-lg-12">
        <label>Details of Technical Assistance Needed</label>
        <textarea class="form-control"><?php echo $data->Details ?></textarea>
    </div>
    <?php if(!empty($data->File)){ ?>
    <div class="col-lg-12">
        <label>Attachment</label>
        <a class="form-control" href="<?=base_url('files')?>/<?php echo $data->File ?>"
            target="_BLANK"><?php echo $data->File ?></a>
    </div>
    <?php }?>
    <?php if($data->Status==0){ ?>
    <div class="col-lg-12">
        <label>Date of Implementation</label>
        <input type="date" class="form-control" name="date" required />
        <div id="date-error" class="error-message text-danger text-sm"></div>
    </div>
    <?php }else if($data->Status==2){ ?>
    <div class="col-lg-12">
        <label>Comment</label>
        <?php
                    $comment = $commentModel->WHERE('formID',$data->formID)->WHERE('accountID',$user)->first();
                    ?>
        <textarea class="form-control"><?php echo $comment['Message'] ?></textarea>
    </div>
    <?php } ?>
    <div class="col-lg-12">
        <?php if($data->Status==0){ ?>
        <button type="submit" class="btn btn-info accept"><i class="fa-solid fa-check"></i>&nbsp;Accept</button>
        <button type="button" class="btn btn-danger decline" value="<?php echo $data->formID ?>"><i
                class="fa-solid fa-xmark"></i>&nbsp;Decline
        </button>
        <button type="button" class="btn btn-warning hold" style="float:right;" value="<?php echo $data->formID ?>">
            <i class="fa-solid fa-circle-exclamation"></i>&nbsp;Hold
        </button>
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
                    'accountID'=>$user,
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
             //create log
             date_default_timezone_set('Asia/Manila');
             $logModel = new \App\Models\logModel();
             $data = ['accountID'=>session()->get('loggedUser'),'Activity'=>'Accepted the new T.A. request','DateCreated'=>date('Y-m-d H:i:s a')];
             $logModel->save($data);         
            return $this->response->setJSON(['success' => 'Successfully submitted']);
        }
    }

    public function holdForm()
    {
        $reviewModel = new \App\Models\reviewModel();
        $formModel = new \App\Models\formModel();
        $commentModel = new \App\Models\commentModel();
        //data
        $validation = $this->validate([
            'value'=>'required|numeric',
            'message'=>'required'
        ]);

        if(!$validation)
        {
            echo "Invalid Input. Please try again";
        }
        else
        {
            $user = session()->get('loggedUser');
            $val = $this->request->getPost('value');
            $msg = $this->request->getPost('message');
            $date = date('Y-m-d');
            $status = 3;
            $data = ['Status'=>$status];
            $formModel->update($val,$data);
            //get the review ID
            $review = $reviewModel->WHERE('formID',$val)->first();
            //update the review status
            $record = ['Status'=>$status];
            $reviewModel->update($review['reviewID'],$record);
            //add comment
            $newData = ['formID'=>$val,'accountID'=>$user,'Message'=>$msg,'DateCreated'=>$date];
            $commentModel->save($newData);
             //create log
             date_default_timezone_set('Asia/Manila');
             $logModel = new \App\Models\logModel();
             $data = ['accountID'=>session()->get('loggedUser'),'Activity'=>'Tag T.A. request for revision','DateCreated'=>date('Y-m-d H:i:s a')];
             $logModel->save($data);
            echo "success";
        }
    }

    public function deniedForm()
    {
        $reviewModel = new \App\Models\reviewModel();
        $formModel = new \App\Models\formModel();
        $commentModel = new \App\Models\commentModel();
        //data
        $validation = $this->validate([
            'value'=>'required|numeric',
            'message'=>'required'
        ]);

        if(!$validation)
        {
            echo "Invalid Input. Please try again";
        }
        else
        {
            $user = session()->get('loggedUser');
            $val = $this->request->getPost('value');
            $msg = $this->request->getPost('message');
            $date = date('Y-m-d');
            $status = 2;
            $data = ['Status'=>$status];
            $formModel->update($val,$data);
            //get the review ID
            $review = $reviewModel->WHERE('formID',$val)->first();
            //update the review status
            $record = ['Status'=>$status];
            $reviewModel->update($review['reviewID'],$record);
            //add comment
            $newData = ['formID'=>$val,'accountID'=>$user,'Message'=>$msg,'DateCreated'=>$date];
            $commentModel->save($newData);
             //create log
             date_default_timezone_set('Asia/Manila');
             $logModel = new \App\Models\logModel();
             $data = ['accountID'=>session()->get('loggedUser'),'Activity'=>'Rejected new T.A. request','DateCreated'=>date('Y-m-d H:i:s a')];
             $logModel->save($data);
            echo "success";
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
             //create log
             date_default_timezone_set('Asia/Manila');
             $logModel = new \App\Models\logModel();
             $data = ['accountID'=>session()->get('loggedUser'),'Activity'=>'Tagged T.A. as completed','DateCreated'=>date('Y-m-d H:i:s a')];
             $logModel->save($data);
            echo "success";
        }
    }

    public function actionPlan()
    {
        $reviewModel = new \App\Models\reviewModel();
        $user = session()->get('loggedUser');
        $searchTerm = $_GET['search']['value'] ?? '';
        $totalRecords = $reviewModel->WHERE('accountID',$user)->countAllResults();
        
        $builder = $this->db->table('tblreview a');
        $builder->select("b.Details,b.Code,b.DateCreated,c.clusterName,d.schoolName,e.subjectName,f.ImplementationDate");
        $builder->join('tblform b','b.formID=a.formID','LEFT');
        $builder->join('tblcluster c','c.clusterID=b.clusterID','LEFT');
        $builder->join('tblschool d','d.schoolID=b.schoolID','LEFT');
        $builder->join('tblsubject e','e.subjectID=b.subjectID','LEFT');
        $builder->join('tblaction f','f.formID=b.formID','LEFT');
        $builder->WHERE('a.accountID',$user);
        $builder->groupBy('b.formID');
        if (!empty($searchTerm)) {
            $builder->groupStart() // Start grouping OR conditions
                ->like('b.Details', $searchTerm)
                ->orLike('b.Code', $searchTerm)
                ->orLike('b.DateCreated', $searchTerm)
                ->orLike('c.clusterName', $searchTerm)
                ->orLike('d.schoolName', $searchTerm)
                ->orLike('e.subjectName', $searchTerm)
                ->orLike('f.ImplementationDate', $searchTerm) // Fixed typo here (removed extra dot)
                ->groupEnd(); // End grouping
        }
        $review = $builder->get()->getResult();

        $response = [
            "draw" => $_GET['draw'],
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => count($review),
            'data' => [] 
        ];

        foreach ($review as $row) {

            $response['data'][] = [
                'DateCreated' => date('Y-M-d', strtotime($row->DateCreated)),
                'RefNo' => htmlspecialchars($row->Code, ENT_QUOTES),
                'cluster' => htmlspecialchars($row->clusterName, ENT_QUOTES),
                'school' => htmlspecialchars($row->schoolName, ENT_QUOTES),
                'concern' => htmlspecialchars($row->subjectName, ENT_QUOTES),
                'Details'=>$row->Details,
                'Date' => empty($row->ImplementationDate) ? '-' : date('Y-M-d', strtotime($row->ImplementationDate)),
            ];
        }

        return $this->response->setJSON($response);
    }

    public function assistPlan()
    {
        $searchTerm = $_GET['search']['value'] ?? ''; 
        
        $builder = $this->db->table('tblform b');
        $builder->select("b.Details,b.Code,b.DateCreated,c.clusterName,d.schoolName,e.subjectName,f.actionName,f.Recommendation,f.ImplementationDate");
        $builder->join('tblcluster c','c.clusterID=b.clusterID','LEFT');
        $builder->join('tblschool d','d.schoolID=b.schoolID','LEFT');
        $builder->join('tblsubject e','e.subjectID=b.subjectID','LEFT');
        $builder->join('tblaction f','f.formID=b.formID','LEFT');
        $builder->groupBy('b.formID');
        if ($searchTerm) {
            $builder->groupStart()
                    ->like('b.Details', $searchTerm)
                    ->orLike('b.Code', $searchTerm)
                    ->orLike('c.clusterName', $searchTerm)
                    ->orLike('d.schoolName', $searchTerm)
                    ->orLike('e.subjectName', $searchTerm)
                    ->orLike('f.actionName', $searchTerm)
                    ->orLike('f.Recommendation', $searchTerm)
                    ->orLike('f.ImplementationDate', $searchTerm)
                    ->groupEnd();
        }
        $review = $builder->get()->getResult();

        // Total number of records (without search filter)
        $totalRecords = $this->db->table('tblform')->countAllResults();

        // Total number of filtered records (with search filter applied)
        $filteredRecords = count($review);

        $response = [
            "draw" => $_GET['draw'],
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $filteredRecords,
            'data' => [] 
        ];

        foreach ($review as $row) {

            $response['data'][] = [
                'DateCreated' => date('Y-M-d', strtotime($row->DateCreated)),
                'RefNo' => htmlspecialchars($row->Code, ENT_QUOTES),
                'cluster' => htmlspecialchars($row->clusterName, ENT_QUOTES),
                'school' => htmlspecialchars($row->schoolName, ENT_QUOTES),
                'concern' => htmlspecialchars($row->subjectName, ENT_QUOTES),
                'Details'=>$row->Details,
                'Date'=>date('Y-M-d', strtotime($row->ImplementationDate)),
            ];
        }

        return $this->response->setJSON($response);
    }

    public function saveFeedback()
    {
        $feedbackModel = new \App\Models\feedbackModel();
        $formModel = new \App\Models\formModel();
        //data
        $validation = $this->validate([
            'csrf_test_name'=>'required',
            'code'=>'required',
            'rating'=>'required',
            'feedback'=>'required',
        ]);

        if(!$validation)
        {
            return $this->response->SetJSON(['error' => $this->validator->getErrors()]);
        }
        else
        {
            $date = date('Y-m-d');
            $user = session()->get('loggedUser');
            $code = $this->request->getPost('code');
            $rate = $this->request->getPost('rating');
            $msg = $this->request->getPost('feedback');
            //get the school and form IDs
            $form = $formModel->WHERE('Code',$code)->first();
            
            $data = ['schoolID'=>$form['schoolID'],'accountID'=>$user,'formID'=>$form['formID'],
                    'Code'=>$code,'Rate'=>$rate,'Message'=>$msg,'DateCreated'=>$date];
            $feedbackModel->save($data);
             //create log
             date_default_timezone_set('Asia/Manila');
             $logModel = new \App\Models\logModel();
             $data = ['accountID'=>session()->get('loggedUser'),'Activity'=>'Submit a feedback','DateCreated'=>date('Y-m-d H:i:s a')];
             $logModel->save($data);
            return $this->response->setJSON(['success' => 'Successfully submitted']);
        }
    }

    public function generateReport()
    {
        $month = $this->request->getGet('month');
        $year = $this->request->getGet('year');
        //builder
        $builder = $this->db->table('tblform a');
        $builder->select('a.DateCreated,a.Code,a.Details,b.schoolName,c.clusterName,d.subjectName,
                        e.actionName,e.Recommendation,f.Rate,f.Message');
        $builder->join('tblschool b','b.schoolID=a.schoolID','LEFT');
        $builder->join('tblcluster c','c.clusterID=a.clusterID','LEFT');
        $builder->join('tblsubject d','d.subjectID=a.subjectID','LEFT');
        $builder->join('tblaction e','e.formID=a.formID','LEFT');
        $builder->join('tblfeedback f','f.formID=a.formID','LEFT');
        $builder->WHERE('DATE_FORMAT(e.ImplementationDate,"%m")',$month)
                ->WHERE('DATE_FORMAT(e.ImplementationDate,"%Y")',$year)
                ->groupBy('a.formID');
        $data = $builder->get()->getResult();
        foreach($data as $row)
        {
            ?>
<tr>
    <td><?php echo $row->DateCreated ?></td>
    <td><?php echo $row->Code ?></td>
    <td><?php echo $row->clusterName ?></td>
    <td><?php echo $row->schoolName ?></td>
    <td><?php echo $row->subjectName ?></td>
    <td><?php echo $row->Details ?></td>
    <td><?php echo $row->actionName ?></td>
    <td><?php echo $row->Recommendation ?></td>
    <td><?php echo $row->Rate ?></td>
    <td><?php echo $row->Message ?></td>
</tr>
<?php
        }
    }

    public function generateTAReport()
    {
        $month = $this->request->getGet('month');
        $year = $this->request->getGet('year');
        //builder
        $builder = $this->db->table('tblform a');
        $builder->select('a.formID,a.Code,a.Details,a.Status,b.schoolName,c.clusterName,d.subjectName,e.actionName,e.Recommendation');
        $builder->join('tblschool b','b.schoolID=a.schoolID','LEFT');
        $builder->join('tblcluster c','c.clusterID=a.clusterID','LEFT');
        $builder->join('tblsubject d','d.subjectID=a.subjectID','LEFT');
        $builder->join('tblaction e','e.formID=a.formID','LEFT');
        $builder->WHERE('DATE_FORMAT(e.ImplementationDate,"%m")',$month)
                ->WHERE('DATE_FORMAT(e.ImplementationDate,"%Y")',$year)
                ->groupBy('a.formID');
        $data = $builder->get()->getResult();
        foreach($data as $row)
        {
            ?>
<tr>
    <td><?php echo $row->Code ?></td>
    <td><?php echo $row->clusterName ?></td>
    <td><?php echo $row->schoolName ?></td>
    <td><?php echo $row->subjectName ?></td>
    <td><?php echo $row->Details ?></td>
    <td><?php echo $row->actionName ?></td>
    <td><?php echo $row->Recommendation ?></td>
    <td>
        <?php if(empty($row->actionName)){ ?>
        <button type="button" class="badge bg-info add" value="<?php echo $row->formID ?>">
            <span class="fa-solid fa-plus"></span>&nbsp;Add
        </button>
        <?php }else{ ?>
        <?php if($row->Status==1){ ?>
        <span class="badge bg-success">Completed</span>
        <?php }else if($row->Status==3){ ?>
        <button type="button" class="badge bg-success complete" value="<?php echo $row->formID ?>">
            <span class="fa-solid fa-flag"></span>&nbsp;Complete
        </button>
        <?php } ?>
        <?php } ?>
    </td>
</tr>
<?php
        }
    }

    public function saveAction()
    {
        $actionModel = new \App\Models\actionModel();  
        $validation = $this->validate([
            'csrf_test_name'=>'required',
            'actionID'=>'required',
            'action'=>'required',
            'recommendation'=>'required'
        ]);
        if(!$validation)
        {
            return $this->response->SetJSON(['error' => $this->validator->getErrors()]);
        }  
        else
        {
            //get the ID
            $action = $actionModel->WHERE('formID',$this->request->getPost('actionID'))->first();
            //update the action form
            $data = ['actionName'=>$this->request->getPost('action'),
                    'Recommendation'=>$this->request->getPost('recommendation')];
            $actionModel->update($action['actionID'],$data);
             //create log
             date_default_timezone_set('Asia/Manila');
             $logModel = new \App\Models\logModel();
             $data = ['accountID'=>session()->get('loggedUser'),'Activity'=>'Added new action plan and recommendation','DateCreated'=>date('Y-m-d H:i:s a')];
             $logModel->save($data);
            return $this->response->setJSON(['success' => 'Successfully submitted']);
        }
    }

    public function saveUser()
    {
        $userTypeModel = new \App\Models\userTypeModel();
        $validation = $this->validate([
            'csrf_test_name'=>'required',
            'user_type'=>'required|is_unique[tbluser_type.userType]'
        ]);

        if(!$validation)
        {
            return $this->response->SetJSON(['error' => $this->validator->getErrors()]);
        }
        else
        {
            $data = ['userType'=>$this->request->getPost('user_type'),'DateCreated'=>date('Y-m-d')];
            $userTypeModel->save($data);
            return $this->response->setJSON(['success' => 'Successfully submitted']);
        }
    }

    public function assign()
    {
        $assignModel = new \App\Models\assignModel();
        $assign = $assignModel->first();
        $validation = $this->validate([
            'csrf_test_name'=>'required',
            'fullname'=>'required|is_unique[tblassign.Fullname]'
        ]);

        if(!$validation)
        {
            return $this->response->SetJSON(['error' => $this->validator->getErrors()]);
        }
        else
        {
            if(empty($assign))
            {
                $data = ['Fullname'=>$this->request->getPost('fullname'),'DateCreated'=>date('Y-m-d')];
                $assignModel->save($data);
            }
            else
            {
                $data = ['Fullname'=>$this->request->getPost('fullname'),'DateCreated'=>date('Y-m-d')];
                $assignModel->update($assign['assignID'],$data);
            }
            return $this->response->setJSON(['success' => 'Successfully submitted']);
        }
    }
}