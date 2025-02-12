<style>
.notification-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background-color: red;
    color: white;
    border-radius: 50%;
    padding: 0.2rem 0.4rem;
    font-size: 8px;
    font-weight: bold;
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
    navbar-scroll="true">
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;"><?=isset($about['systemTitle']) ? $about['systemTitle'] : "No Application Title"?></a></li>
                <li class="breadcrumb-item text-sm text-dark active" aria-current="page"><?=$title?></li>
            </ol>
            <h6 class="font-weight-bolder mb-0"><?=$title?></h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                <div class="input-group d-none">
                    <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="Type here...">
                </div>
            </div>
            <ul class="navbar-nav  justify-content-end">
                <li class="nav-item d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body font-weight-bold px-0">
                        <i class="fa fa-user me-sm-1"></i>
                        <span class="d-sm-inline d-none"><?=session()->get('fullname')?></span>
                    </a>
                </li>
                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </a>
                </li>
                <li class="nav-item px-3 d-flex align-items-center">
                    <a href="javascript:void(0);" class="nav-link text-body p-0">
                        <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
                    </a>
                </li>
                <?php 
                $user = session()->get('loggedUser');
                $reviewModel = new \App\Models\reviewModel();
                $review = $reviewModel->WHERE('accountID',$user)
                                    ->WHERE('Status',0)->countAllResults();
                ?>
                <li class="nav-item dropdown pe-2 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bell cursor-pointer"></i>
                        <span class="notification-badge"><?=$review ?></span>
                    </a>
                    <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4"
                        aria-labelledby="dropdownMenuButton">
                        <?php
                        $user = session()->get('loggedUser');
                        $db = db_connect();
                        $builder = $db->table('tblreview a');
                        $builder->select('a.DateReceived,b.Code');
                        $builder->join('tblform b','b.formID=a.formID','LEFT');
                        $builder->WHERE('a.Status',0)->WHERE('a.accountID',$user);
                        $review = $builder->get()->getResult();
                        foreach($review as $row)
                        {
                        ?>
                        <li class="mb-2">
                            <a class="dropdown-item border-radius-md" href="javascript:;">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        <img src="<?=base_url('assets/img/Logo.png')?>" class="avatar avatar-sm  me-3 ">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            <span class="font-weight-bold"><?php echo $row->Code ?></span> requesting
                                            for approval
                                        </h6>
                                        <p class="text-xs text-secondary mb-0 ">
                                            <i class="fa fa-clock me-1"></i>
                                            <?php echo date('Y-M-d',strtotime($row->DateReceived)) ?>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <?php
                        } 
                        ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>