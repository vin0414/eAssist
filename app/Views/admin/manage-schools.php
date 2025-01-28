<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="<?=base_url('assets/img/logo.png')?>">
  <link rel="icon" type="image/png" href="<?=base_url('assets/img/logo.png')?>">
  <title>Assist</title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="<?=base_url('assets/css/nucleo-icons.css')?>" rel="stylesheet" />
  <link href="<?=base_url('assets/css/nucleo-svg.css')?>" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" />
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/fontawesome.min.css" integrity="sha512-v8QQ0YQ3H4K6Ic3PJkym91KoeNT5S3PnDKvqnwqFD1oiqIl653crGZplPdU5KKtHjO0QKcQ2aUlQZYjHczkmGw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- CSS Files -->
  <link id="pagestyle" href="<?=base_url('assets/css/soft-ui-dashboard.css?v=1.1.0')?>" rel="stylesheet" />
  <!-- Nepcha Analytics (nepcha.com) -->
  <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>

<body class="g-sidenav-show  bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="<?=site_url('/')?>" target="_blank">
        <img src="<?=base_url('assets/img/logo.png')?>" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">Assist</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main" style="height: 100%;">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="<?=site_url('/overview')?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <svg width="12px" height="12px" viewBox="0 0 45 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <title>shop </title>
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                  <g transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF" fill-rule="nonzero">
                    <g transform="translate(1716.000000, 291.000000)">
                      <g transform="translate(0.000000, 148.000000)">
                        <path class="color-background opacity-6" d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z"></path>
                        <path class="color-background" d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z"></path>
                      </g>
                    </g>
                  </g>
                </g>
              </svg>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?=site_url('/technical-assistance')?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <svg width="12px" height="12px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 12H15M12 9V15M21.0039 12C21.0039 16.9706 16.9745 21 12.0039 21C9.9675 21 3.00463 21 3.00463 21C3.00463 21 4.56382 17.2561 3.93982 16.0008C3.34076 14.7956 3.00391 13.4372 3.00391 12C3.00391 7.02944 7.03334 3 12.0039 3C16.9745 3 21.0039 7.02944 21.0039 12Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <span class="nav-link-text ms-1">Technical Assistance</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="<?=site_url('/cluster-and-schools')?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <svg width="12px" height="12px" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7.5 4.5L11.5 6.5V14.5H3.5V6.5L7.5 4.5ZM7.5 4.5V0M0 14.5H15M1.5 14.5V8.5H3.5M13.5 14.5V8.5H11.5M6.5 14.5V11.5H8.5V14.5M7.5 0.5H10.5V2.5H7.5M7.5 9.5C6.94772 9.5 6.5 9.05228 6.5 8.5C6.5 7.94772 6.94772 7.5 7.5 7.5C8.05228 7.5 8.5 7.94772 8.5 8.5C8.5 9.05228 8.05228 9.5 7.5 9.5Z" stroke="#000000"/>
                </svg>
            </div>
            <span class="nav-link-text ms-1">Cluster & Schools</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link  " href="<?=site_url('/user-accounts')?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <svg fill="#000000" width="18px" height="18px" viewBox="-6 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg">
                <title>users</title>
                <path d="M17 11.8c-0.28-1.4-1.4-2.52-2.84-2.84-0.36-1.68-1.84-2.92-3.6-2.92h-6.88c-2.040 0-3.68 1.64-3.68 3.68v6.88c0 1.76 1.24 3.24 2.92 3.6 0.28 1.4 1.4 2.52 2.84 2.84 0.36 1.68 1.84 2.92 3.6 2.92h6.88c2.040 0 3.68-1.64 3.68-3.68v-6.88c0-1.76-1.24-3.24-2.92-3.6zM18.24 15.4v6.88c0 0.080 0 0.16 0 0.24-0.48-1.12-1.32-2.080-2.4-2.72 0.24-0.48 0.36-1 0.36-1.56 0-1.88-1.52-3.4-3.4-3.4s-3.4 1.52-3.4 3.4c0 0.56 0.12 1.080 0.36 1.56-1.080 0.64-1.88 1.6-2.4 2.72 0-0.080 0-0.16 0-0.24v-6.88c0-1.12 0.88-2 2-2h6.88c1.12 0 2 0.88 2 2zM11.040 18.28c0-0.96 0.76-1.72 1.72-1.72s1.72 0.76 1.72 1.72c0 0.96-0.76 1.72-1.72 1.72-0.92 0-1.72-0.76-1.72-1.72zM1.68 16.6v-6.88c0-1.12 0.88-2 2-2h6.88c0.8 0 1.48 0.48 1.8 1.16h-5.84c-2.040 0-3.68 1.64-3.68 3.68v5.88c-0.68-0.32-1.16-1.040-1.16-1.84zM4.52 19.44v-6.88c0-1.12 0.88-2 2-2h6.88c0.8 0 1.48 0.48 1.8 1.16h-5.84c-2.040 0-3.68 1.64-3.68 3.68v5.88c-0.68-0.32-1.16-1.040-1.16-1.84zM9.36 24.28c-0.28 0-0.52-0.040-0.76-0.16 0-0.040 0.040-0.080 0.040-0.12 0.28-1.24 1.12-2.32 2.28-2.92 0.56 0.36 1.2 0.56 1.88 0.56s1.36-0.2 1.88-0.56c1.16 0.6 2 1.64 2.28 2.92 0 0.040 0.040 0.080 0.040 0.12-0.24 0.080-0.48 0.16-0.76 0.16h-6.88z"></path>
                </svg>
            </div>
            <span class="nav-link-text ms-1">User Accounts</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link  " href="<?=site_url('/reports')?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <svg width="12px" height="12px" viewBox="0 0 512 512" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <title>report-barchart</title>
                    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g id="add" fill="#000000" transform="translate(42.666667, 85.333333)">
                            <path d="M341.333333,1.42108547e-14 L426.666667,85.3333333 L426.666667,341.333333 L3.55271368e-14,341.333333 L3.55271368e-14,1.42108547e-14 L341.333333,1.42108547e-14 Z M330.666667,42.6666667 L42.6666667,42.6666667 L42.6666667,298.666667 L384,298.666667 L384,96 L330.666667,42.6666667 Z M106.666667,85.3333333 L106.666,234.666 L341.333333,234.666667 L341.333333,256 L85.3333333,256 L85.3333333,85.3333333 L106.666667,85.3333333 Z M170.666667,149.333333 L170.666667,213.333333 L128,213.333333 L128,149.333333 L170.666667,149.333333 Z M234.666667,106.666667 L234.666667,213.333333 L192,213.333333 L192,106.666667 L234.666667,106.666667 Z M298.666667,170.666667 L298.666667,213.333333 L256,213.333333 L256,170.666667 L298.666667,170.666667 Z" id="Combined-Shape">
                            </path>
                        </g>
                    </g>
                </svg>
            </div>
            <span class="nav-link-text ms-1">Reports</span>
          </a>
        </li>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account pages</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link  " href="<?=site_url('account')?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <svg width="12px" height="12px" viewBox="0 0 46 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <title>customer-support</title>
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                  <g transform="translate(-1717.000000, -291.000000)" fill="#FFFFFF" fill-rule="nonzero">
                    <g transform="translate(1716.000000, 291.000000)">
                      <g transform="translate(1.000000, 0.000000)">
                        <path class="color-background opacity-6" d="M45,0 L26,0 C25.447,0 25,0.447 25,1 L25,20 C25,20.379 25.214,20.725 25.553,20.895 C25.694,20.965 25.848,21 26,21 C26.212,21 26.424,20.933 26.6,20.8 L34.333,15 L45,15 C45.553,15 46,14.553 46,14 L46,1 C46,0.447 45.553,0 45,0 Z"></path>
                        <path class="color-background" d="M22.883,32.86 C20.761,32.012 17.324,31 13,31 C8.676,31 5.239,32.012 3.116,32.86 C1.224,33.619 0,35.438 0,37.494 L0,41 C0,41.553 0.447,42 1,42 L25,42 C25.553,42 26,41.553 26,41 L26,37.494 C26,35.438 24.776,33.619 22.883,32.86 Z"></path>
                        <path class="color-background" d="M13,28 C17.432,28 21,22.529 21,18 C21,13.589 17.411,10 13,10 C8.589,10 5,13.589 5,18 C5,22.529 8.568,28 13,28 Z"></path>
                      </g>
                    </g>
                  </g>
                </g>
              </svg>
            </div>
            <span class="nav-link-text ms-1">My Account</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link  " href="<?=site_url('logout')?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <svg width="12px" height="12px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15 12L2 12M2 12L5.5 9M2 12L5.5 15" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M9.00195 7C9.01406 4.82497 9.11051 3.64706 9.87889 2.87868C10.7576 2 12.1718 2 15.0002 2L16.0002 2C18.8286 2 20.2429 2 21.1215 2.87868C22.0002 3.75736 22.0002 5.17157 22.0002 8L22.0002 16C22.0002 18.8284 22.0002 20.2426 21.1215 21.1213C20.3531 21.8897 19.1752 21.9862 17 21.9983M9.00195 17C9.01406 19.175 9.11051 20.3529 9.87889 21.1213C10.5202 21.7626 11.4467 21.9359 13 21.9827" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </div>
            <span class="nav-link-text ms-1">Sign Out</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <?= $this->include('admin/templates/header'); ?>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <ul class="nav nav-tabs" id="myTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Cluster and Subject</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Schools</a>
        </li>
      </ul>
      <div class="tab-content" id="myTabsContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
          <br/>
          <div class="row g-2">
            <div class="col-lg-6">
              <div class="card">
                <div class="card-header p-3 pb-0">
                  <div class="d-flex align-items-center">
                    <h6 class="mb-0">
                      Cluster
                    </h6>
                    <button type="button" class="btn btn-sm btn-info text-white ms-auto mb-0">
                      <i class="fa-solid fa-plus"></i> New
                    </button>
                  </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                  <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                      <thead>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Cluster</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="card">
                <div class="card-header p-3 pb-0">
                  <div class="d-flex align-items-center">
                    <h6 class="mb-0">
                      Subjects
                    </h6>
                    <button type="button" class="btn btn-sm btn-info text-white ms-auto mb-0">
                      <i class="fa-solid fa-plus"></i> New
                    </button>
                  </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                  <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                      <thead>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Subjects</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
          <br/>
          <div class="card">
            <div class="card-header p-3 pb-0">
              <div class="d-flex align-items-center">
                <h6 class="mb-0">
                School
                </h6>
                <button type="button" class="btn btn-sm btn-info text-white ms-auto mb-0" style="margin-right:5px;">
                  <i class="fa-solid fa-plus"></i> New
                </button>
                <button type="button" class="btn btn-secondary btn-sm add mb-0"><i class="fa-solid fa-download"></i>&nbsp;Export</button>
              </div>
            </div>
            <div class="card-body">
            <div class="table-responsive">
                <table class="table table-flush" id="tblschools" style="font-size:12px;">
                  <thead class="thead-light">
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date Created</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">School</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Address</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Cluster</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                  </thead>
                  <tbody>
                  <?php foreach($school as $row): ?>
                    <tr>
                      <td><?php echo $row->DateCreated ?></td>
                      <td><?php echo $row->schoolName ?></td>
                      <td><?php echo $row->address ?></td>
                      <td><?php echo $row->clusterName ?></td>
                      <td></td>
                    </tr>
                  <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div> 
    </div>
  </main>
  <div class="fixed-plugin">
    <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
      <i class="fa fa-cog py-2"> </i>
    </a>
    <div class="card shadow-lg ">
      <div class="card-header pb-0 pt-3 ">
        <div class="float-start">
          <h5 class="mt-3 mb-0">e-Assist Configurator</h5>
          <p>See our dashboard options.</p>
        </div>
        <div class="float-end mt-4">
          <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
            <i class="fa fa-close"></i>
          </button>
        </div>
        <!-- End Toggle Button -->
      </div>
      <hr class="horizontal dark my-1">
      <div class="card-body pt-sm-3 pt-0">
        <!-- Sidebar Backgrounds -->
        <div>
          <h6 class="mb-0">Sidebar Colors</h6>
        </div>
        <a href="javascript:void(0)" class="switch-trigger background-color">
          <div class="badge-colors my-2 text-start">
            <span class="badge filter bg-primary active" data-color="primary" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-dark" data-color="dark" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-info" data-color="info" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-success" data-color="success" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-warning" data-color="warning" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-danger" data-color="danger" onclick="sidebarColor(this)"></span>
          </div>
        </a>
        <!-- Sidenav Type -->
        <div class="mt-3">
          <h6 class="mb-0">Sidenav Type</h6>
          <p class="text-sm">Choose between 2 different sidenav types.</p>
        </div>
        <div class="d-flex">
          <button class="btn btn-primary w-100 px-3 mb-2 active" data-class="bg-transparent" onclick="sidebarType(this)">Transparent</button>
          <button class="btn btn-primary w-100 px-3 mb-2 ms-2" data-class="bg-white" onclick="sidebarType(this)">White</button>
        </div>
        <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>
        <!-- Navbar Fixed -->
        <div class="mt-3">
          <h6 class="mb-0">Navbar Fixed</h6>
        </div>
        <div class="form-check form-switch ps-0">
          <input class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarFixed" onclick="navbarFixed(this)">
        </div>
      </div>
    </div>
  </div>
  <!--   Core JS Files   -->
  <script src="<?=base_url('assets/js/core/popper.min.js')?>"></script>
  <script src="<?=base_url('assets/js/core/bootstrap.min.js')?>"></script>
  <script src="<?=base_url('assets/js/plugins/perfect-scrollbar.min.js')?>"></script>
  <script src="<?=base_url('assets/js/plugins/smooth-scrollbar.min.js')?>"></script>
  <script src="<?=base_url('assets/js/plugins/chartjs.min.js')?>"></script>
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
  <script>
    $(document).ready( function () {
      $('#tblschools').DataTable();
    });
  </script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="<?=base_url('assets/js/soft-ui-dashboard.min.js?v=1.1.0')?>"></script>
</body>

</html>