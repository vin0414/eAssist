<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76"
        href="<?=base_url('assets/img/logos')?>/<?=isset($about['systemLogo']) ? $about['systemLogo'] : "No Logo"?>">
    <link rel="icon" type="image/png"
        href="<?=base_url('assets/img/logos')?>/<?=isset($about['systemLogo']) ? $about['systemLogo'] : "No Logo"?>">
    <title><?=isset($about['systemTitle']) ? $about['systemTitle'] : "No Application Title"?></title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="<?=base_url('assets/css/nucleo-icons.css')?>" rel="stylesheet" />
    <link href="<?=base_url('assets/css/nucleo-svg.css')?>" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/fontawesome.min.css"
        integrity="sha512-v8QQ0YQ3H4K6Ic3PJkym91KoeNT5S3PnDKvqnwqFD1oiqIl653crGZplPdU5KKtHjO0QKcQ2aUlQZYjHczkmGw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CSS Files -->
    <link id="pagestyle" href="<?=base_url('assets/css/soft-ui-dashboard.css?v=1.1.0')?>" rel="stylesheet" />
    <style>
    ::-webkit-scrollbar {
        width: 2px;
        /* Sets the width of the scrollbar */
        height: 5px;
    }

    /* Style the scrollbar track */
    ::-webkit-scrollbar-track {
        background-color: #f1f1f1;
    }

    /* Style the scrollbar thumb (the draggable part) */
    ::-webkit-scrollbar-thumb {
        background-color: #888;
        border-radius: 10px;
    }

    /* Style the thumb when hovered */
    ::-webkit-scrollbar-thumb:hover {
        background-color: #555;
    }

    .floating-modal {
        position: fixed;
        bottom: 20px;
        /* Distance from bottom */
        right: 20px;
        /* Distance from right */
        width: 300px;
        /* Width of the modal */
        background-color: rgba(255, 255, 255, 0.9);
        /* Semi-transparent white */
        border-radius: 10px;
        /* Rounded corners */
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        /* Floating shadow effect */
        padding: 20px;
        /* Padding inside the modal */
        z-index: 1000;
        /* Ensure it appears above other content */
        opacity: 0;
        /* Initially hidden */
        transform: translateY(100px);
        /* Move offscreen by default */
        transition: opacity 0.3s ease, transform 0.3s ease;
        /* Smooth transition for appearance */
    }

    .floating-modal.open {
        opacity: 1;
        transform: translateY(0);
        /* Animate to normal position */
    }


    .qr-code img {
        max-width: 100%;
        /* Ensure the QR code image is responsive */
        height: auto;
    }

    .close-btn {
        margin-top: 10px;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .close-btn:hover {
        background-color: #0056b3;
        /* Darker shade on hover */
    }
    </style>
</head>

<body class="g-sidenav-show  bg-gray-100">
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 "
        id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href="<?=site_url('/')?>" target="_blank">
                <img src="<?=base_url('assets/img/logos')?>/<?=isset($about['systemLogo']) ? $about['systemLogo'] : "No Logo"?>"
                    class="navbar-brand-img h-100" alt="main_logo">
                <span
                    class="ms-1 font-weight-bold"><?=isset($about['systemTitle']) ? $about['systemTitle'] : "No Application Title"?></span>
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main" style="height: 100%;">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?=site_url('user/overview')?>">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <svg width="12px" height="12px" viewBox="0 0 45 40" version="1.1"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>shop </title>
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF"
                                        fill-rule="nonzero">
                                        <g transform="translate(1716.000000, 291.000000)">
                                            <g transform="translate(0.000000, 148.000000)">
                                                <path class="color-background opacity-6"
                                                    d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z">
                                                </path>
                                                <path class="color-background"
                                                    d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z">
                                                </path>
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
                    <a class="nav-link active" href="<?=site_url('user/technical-assistance')?>">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <svg width="12px" height="12px" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9 12H15M12 9V15M21.0039 12C21.0039 16.9706 16.9745 21 12.0039 21C9.9675 21 3.00463 21 3.00463 21C3.00463 21 4.56382 17.2561 3.93982 16.0008C3.34076 14.7956 3.00391 13.4372 3.00391 12C3.00391 7.02944 7.03334 3 12.0039 3C16.9745 3 21.0039 7.02944 21.0039 12Z"
                                    stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1">Request</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  " href="<?=site_url('user/feedback')?>">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <svg width="12px" height="12px" viewBox="0 0 512 512" version="1.1"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>report-barchart</title>
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="add" fill="#000000" transform="translate(42.666667, 85.333333)">
                                        <path
                                            d="M341.333333,1.42108547e-14 L426.666667,85.3333333 L426.666667,341.333333 L3.55271368e-14,341.333333 L3.55271368e-14,1.42108547e-14 L341.333333,1.42108547e-14 Z M330.666667,42.6666667 L42.6666667,42.6666667 L42.6666667,298.666667 L384,298.666667 L384,96 L330.666667,42.6666667 Z M106.666667,85.3333333 L106.666,234.666 L341.333333,234.666667 L341.333333,256 L85.3333333,256 L85.3333333,85.3333333 L106.666667,85.3333333 Z M170.666667,149.333333 L170.666667,213.333333 L128,213.333333 L128,149.333333 L170.666667,149.333333 Z M234.666667,106.666667 L234.666667,213.333333 L192,213.333333 L192,106.666667 L234.666667,106.666667 Z M298.666667,170.666667 L298.666667,213.333333 L256,213.333333 L256,170.666667 L298.666667,170.666667 Z"
                                            id="Combined-Shape">
                                        </path>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1">Feedback</span>
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account pages</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link  " href="<?=site_url('user/account')?>">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <svg width="12px" height="12px" viewBox="0 0 46 42" version="1.1"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>customer-support</title>
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g transform="translate(-1717.000000, -291.000000)" fill="#FFFFFF"
                                        fill-rule="nonzero">
                                        <g transform="translate(1716.000000, 291.000000)">
                                            <g transform="translate(1.000000, 0.000000)">
                                                <path class="color-background opacity-6"
                                                    d="M45,0 L26,0 C25.447,0 25,0.447 25,1 L25,20 C25,20.379 25.214,20.725 25.553,20.895 C25.694,20.965 25.848,21 26,21 C26.212,21 26.424,20.933 26.6,20.8 L34.333,15 L45,15 C45.553,15 46,14.553 46,14 L46,1 C46,0.447 45.553,0 45,0 Z">
                                                </path>
                                                <path class="color-background"
                                                    d="M22.883,32.86 C20.761,32.012 17.324,31 13,31 C8.676,31 5.239,32.012 3.116,32.86 C1.224,33.619 0,35.438 0,37.494 L0,41 C0,41.553 0.447,42 1,42 L25,42 C25.553,42 26,41.553 26,41 L26,37.494 C26,35.438 24.776,33.619 22.883,32.86 Z">
                                                </path>
                                                <path class="color-background"
                                                    d="M13,28 C17.432,28 21,22.529 21,18 C21,13.589 17.411,10 13,10 C8.589,10 5,13.589 5,18 C5,22.529 8.568,28 13,28 Z">
                                                </path>
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
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <svg width="12px" height="12px" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M15 12L2 12M2 12L5.5 9M2 12L5.5 15" stroke="#1C274C" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M9.00195 7C9.01406 4.82497 9.11051 3.64706 9.87889 2.87868C10.7576 2 12.1718 2 15.0002 2L16.0002 2C18.8286 2 20.2429 2 21.1215 2.87868C22.0002 3.75736 22.0002 5.17157 22.0002 8L22.0002 16C22.0002 18.8284 22.0002 20.2426 21.1215 21.1213C20.3531 21.8897 19.1752 21.9862 17 21.9983M9.00195 17C9.01406 19.175 9.11051 20.3529 9.87889 21.1213C10.5202 21.7626 11.4467 21.9359 13 21.9827"
                                    stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
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
        <?= view('templates/header'); ?>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="card">
                <div class="card-header p-3 pb-0">
                    <div class="d-flex align-items-center">
                        <h6 class="mb-0">
                            <i class="fa-solid fa-clipboard-list"></i>&nbsp;Request
                        </h6>
                        <button type="button" class="btn btn-info btn-sm add ms-auto mb-0" style="margin-right:5px;"
                            data-bs-toggle="modal" data-bs-target="#addModal"><i
                                class="fa-solid fa-plus"></i>&nbsp;New</button>
                        <button type="button" class="btn btn-secondary btn-sm mb-0" id="btnExport"><i
                                class="fa-solid fa-download"></i>&nbsp;Export</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-flush" id="tblrequest" style="font-size:12px;">
                            <thead class="thead-light">
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Date Created</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    T.A. ID</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Area of Concern</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Details of Technical Assistance Needed</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Priority Level</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Technical Assistance Provider</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Status</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Comment</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div class="fixed-plugin">
        <div class="floating-modal">
            <div class="modal-content">
                <div class="qr-code">
                    <!-- QR code image or content here -->
                    <img src="<?=base_url('assets/img/qrcode.jpg')?>" alt="QR Code">
                </div>
                <small class="text-center">Click this link to participate in the client satisfaction survey <a
                        href="https://csm.depedgentri.com/csm.php" target="_blank">here</a>.</small>
                <button class="close-btn">Close</button>
            </div>
        </div>
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
                        <span class="badge filter bg-primary active" data-color="primary"
                            onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-dark" data-color="dark"
                            onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-info" data-color="info"
                            onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-success" data-color="success"
                            onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-warning" data-color="warning"
                            onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-danger" data-color="danger"
                            onclick="sidebarColor(this)"></span>
                    </div>
                </a>
                <!-- Sidenav Type -->
                <div class="mt-3">
                    <h6 class="mb-0">Sidenav Type</h6>
                    <p class="text-sm">Choose between 2 different sidenav types.</p>
                </div>
                <div class="d-flex">
                    <button class="btn btn-primary w-100 px-3 mb-2 active" data-class="bg-transparent"
                        onclick="sidebarType(this)">Transparent</button>
                    <button class="btn btn-primary w-100 px-3 mb-2 ms-2" data-class="bg-white"
                        onclick="sidebarType(this)">White</button>
                </div>
                <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>
                <!-- Navbar Fixed -->
                <div class="mt-3">
                    <h6 class="mb-0">Navbar Fixed</h6>
                </div>
                <div class="form-check form-switch ps-0">
                    <input class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarFixed"
                        onclick="navbarFixed(this)">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title text-white" id="exampleModalLabel"><img
                            src="<?=base_url('assets/img/logos')?>/<?=isset($about['systemLogo']) ? $about['systemLogo'] : "No Logo"?>"
                            width="30px" />&nbsp;New Technical Assistance
                    </h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" class="row g-2" enctype="multipart/form-data" id="frmRequest">
                        <?= csrf_field(); ?>
                        <div class="col-12">
                            <div><small>1. Do you allow DEPED - Division of General Trias City to Process all data
                                    gathered by this form?</small></div>
                            <div class="radio-group">
                                <label>
                                    <input type="radio" name="agreement" style="width:18px;height:18px;" value="Yes"
                                        required>
                                    <label class="align-middle">Yes</label>
                                </label>
                                <label>
                                    <input type="radio" name="agreement" style="width:18px;height:18px;" value="No">
                                    <label class="align-middle">No</label>
                                </label>
                            </div>
                            <div id="agreement-error" class="error-message text-danger text-sm"></div>
                        </div>
                        <div class="col-12">
                            <div><small>2. Please choose your area of concern</small></div>
                            <select class="form-control" name="area" required>
                                <option value="">Choose</option>
                                <?php foreach($subject as $row): ?>
                                <option value="<?php echo $row['subjectID'] ?>"><?php echo $row['subjectName'] ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <div id="area-error" class="error-message text-danger text-sm"></div>
                        </div>
                        <div class="col-12">
                            <div><small>3. Based on your area of concern, from whom are you expecting the technical
                                    assistance to be coming?</small></div>
                            <div class="row">
                                <?php foreach($account as $row): ?>
                                <div class="col-lg-6">
                                    <div class="radio-group">
                                        <label>
                                            <input type="checkbox" name="account[]" style="width:18px;height:18px;"
                                                value="<?php echo $row['accountID'] ?>">
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
                            <div><small>4. Details of Technical Assistance Needed</small></div>
                            <span><small>Please provide specific details about your concerns, issues, or challenges
                                    based on your chosen area of concern/s. You may also provide data or any documents
                                    that may serve as reference for the TA providers.</small></span>
                            <textarea class="form-control" name="details" required></textarea>
                            <div id="details-error" class="error-message text-danger text-sm"></div>
                        </div>
                        <div class="col-12">
                            <div><small>5. Supporting Documents</small></div>
                            <span><small>Upload any supporting documents in PDF file format that will serve as reference
                                    for the TA provider in crafting his/ her technical assistance plan. Merge in one (1)
                                    file only</small></span>
                            <input type="file" class="form-control" name="file" />
                        </div>
                        <div class="col-12">
                            <div><small>6. Level of Priority for Technical Assistance</small></div>
                            <div class="radio-group">
                                <label>
                                    <input type="radio" name="priority" style="width:18px;height:18px;" value="Low"
                                        required>
                                    <label class="align-middle">Low Priority</label>
                                </label>
                                <label>
                                    <input type="radio" name="priority" style="width:18px;height:18px;" value="Medium">
                                    <label class="align-middle">Medium Priority</label>
                                </label>
                                <label>
                                    <input type="radio" name="priority" style="width:18px;height:18px;" value="High">
                                    <label class="align-middle">High Priority</label>
                                </label>
                            </div>
                            <div id="priority-error" class="error-message text-danger text-sm"></div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-info"><i
                                    class="fa-regular fa-floppy-disk"></i>&nbsp;Submit</button>
                            <button type="reset" class="btn btn-success"><i
                                    class="fa-solid fa-arrows-rotate"></i>&nbsp;Clear Form</button>
                        </div>
                    </form>
                    <span id="loadingMessage" style="display:none;">Loading. Please wait.</span>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title text-white" id="exampleModalLabel"><img
                            src="<?=base_url('assets/img/logos')?>/<?=isset($about['systemLogo']) ? $about['systemLogo'] : "No Logo"?>"
                            width="30px" />&nbsp;Edit Technical Assistance
                    </h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div id="result"></div>
                    <span id="loadingMessages" style="display:none;">Loading. Please wait.</span>
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
    <script src="<?=base_url('assets/js/fullcalendar.min.js')?>"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    $(document).ready(function() {
        const modal = document.querySelector('.floating-modal');
        const closeButton = document.querySelector('.close-btn');

        // Open the modal
        function openModal() {
            modal.classList.add('open');
            modal.classList.remove('closed');
        }

        // Close the modal
        function closeModal() {
            modal.classList.add('closed');
            modal.classList.remove('open');
        }

        // Event listener for the close button
        closeButton.addEventListener('click', closeModal);

        // Optionally, you can open the modal using JavaScript as needed:
        openModal();
        var table = $('#tblrequest').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?=site_url('user-request')?>",
                "type": "GET",
                "dataSrc": function(json) {
                    // Handle the data if needed
                    return json.data;
                },
                "error": function(xhr, error, code) {
                    console.error("AJAX Error: " + error);
                    alert("Error occurred while loading data.");
                }
            },
            "searching": true,
            "columns": [{
                    "data": "DateCreated"
                },
                {
                    "data": "TA"
                },
                {
                    "data": "subjectName"
                },
                {
                    "data": "Details"
                },
                {
                    "data": "priorityLevel"
                },
                {
                    "data": "provider"
                },
                {
                    "data": "Status"
                },
                {
                    "data": "Comment"
                }
            ]
        });

        $('#frmRequest').on('submit', function(e) {
            e.preventDefault();
            $('.error-message').html('');
            $('#frmRequest').slideUp();
            document.getElementById('loadingMessage').style = "display:block";
            let data = $(this).serialize();
            $.ajax({
                url: "<?=site_url('save-form')?>",
                method: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    document.getElementById('loadingMessage').style = "display:none";
                    $('#frmRequest').slideDown();
                    if (response.success) {
                        $('#frmRequest')[0].reset();
                        table.ajax.reload();
                        Swal.fire({
                            title: "Great!",
                            text: "Successfully submitted",
                            icon: "success"
                        });
                    } else {
                        var errors = response.error;
                        // Iterate over each error and display it under the corresponding input field
                        for (var field in errors) {
                            $('#' + field + '-error').html('<p>' + errors[field] +
                                '</p>'); // Show the first error message
                            $('#' + field).addClass(
                                'text-danger'); // Highlight the input field with an error
                        }
                    }
                }
            });
        });

        $(document).on('click', '.save', function(e) {
            e.preventDefault();
            $('.error-message').html('');
            $('#frmEditRequest').slideUp();
            document.getElementById('loadingMessages').style = "display:block";
            $.ajax({
                url: "<?=site_url('edit-form')?>",
                method: "POST",
                data: new FormData($('#frmEditRequest')[0]),
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    document.getElementById('loadingMessages').style = "display:none";
                    $('#frmEditRequest').slideDown();
                    $('#editModal').modal('hide');
                    if (response.success) {
                        table.ajax.reload();
                        Swal.fire({
                            title: "Great!",
                            text: "Successfully submitted",
                            icon: "success"
                        });
                    } else {
                        var errors = response.error;
                        // Iterate over each error and display it under the corresponding input field
                        for (var field in errors) {
                            $('#' + field + '-error').html('<p>' + errors[field] +
                                '</p>'); // Show the first error message
                            $('#' + field).addClass(
                                'text-danger'); // Highlight the input field with an error
                        }
                    }
                }
            });
        });

        $(document).on('click', '.edit', function() {
            var val = $(this).val();
            $.ajax({
                url: "<?=site_url('fetch-details')?>",
                method: "GET",
                data: {
                    value: val
                },
                success: function(response) {
                    $('#result').html(response);
                    $('#editModal').modal('show');
                }
            });
        });
    });
    document.getElementById('btnExport').addEventListener('click', function() {
        const table = document.getElementById('tblrequest');
        let html = table.outerHTML;
        let blob = new Blob([html], {
            type: 'application/vnd.ms-excel'
        });
        let link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'request.xls';
        link.click();
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