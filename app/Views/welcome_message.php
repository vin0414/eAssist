<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <link rel="apple-touch-icon" sizes="76x76" href="<?=base_url('assets/img/logos')?>/<?=isset($about['systemLogo']) ? $about['systemLogo'] : "No Logo"?>">
  <link rel="icon" type="image/png" href="<?=base_url('assets/img/logos')?>/<?=isset($about['systemLogo']) ? $about['systemLogo'] : "No Logo"?>">
  <title><?=isset($about['systemTitle']) ? $about['systemTitle'] : "No Application Title"?></title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="<?=base_url('assets/css/nucleo-icons.css')?>" rel="stylesheet" />
  <link href="<?=base_url('assets/css/nucleo-svg.css')?>" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="<?=base_url('assets/css/soft-ui-dashboard.css?v=1.1.0')?>" rel="stylesheet" />
</head>

<body class="">
  <main class="main-content  mt-0">
    <section>
      <div class="page-header min-vh-75">
        <div class="container">
          <div class="row">
            <div class="col-md-4 d-flex flex-column mx-auto">
              <div class="card card-plain mt-5">
                <div class="card-header pb-0 text-center bg-transparent">
                  <img src="<?=base_url('assets/img/logos')?>/<?=isset($about['systemLogo']) ? $about['systemLogo'] : "No Logo"?>" width="100px"/>
                  <h3 class="font-weight-bolder text-info text-gradient">Welcome to <?=isset($about['systemTitle']) ? $about['systemTitle'] : "No Application Title"?></h3>
                  <p class="mb-0" style="font-size:12px;"><?=isset($about['systemDetails']) ? $about['systemDetails'] : "No Details"?></p>
                </div>
                <div class="card-body">
                  <?php if(!empty(session()->getFlashdata('fail'))) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= session()->getFlashdata('fail'); ?>
                    </div>
                  <?php endif; ?>
                  <form role="form" method="POST" action="<?=base_url('auth')?>">
                    <?= csrf_field(); ?>
                    <label>Email</label>
                    <div class="mb-3">
                      <input type="email" class="form-control" name="email" value="<?=old('email')?>" placeholder="Email" aria-label="Email" aria-describedby="email-addon" required/>
                      <div class="text-danger"><small><?=isset($validation)? display_error($validation,'email') : '' ?></small></div>
                    </div>
                    <label>Password</label>
                    <div class="mb-3">
                      <input type="password" class="form-control" name="password" placeholder="Password" aria-label="Password" aria-describedby="password-addon" required/>
                      <div class="text-danger"><small><?=isset($validation)? display_error($validation,'password') : '' ?></small></div>
                    </div>
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" id="rememberMe" checked="">
                      <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                    <div class="text-center">
                      <button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">Sign in</button>
                    </div>
                  </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <p class="mb-4 text-sm mx-auto">
                    Don't have an account?
                    <a href="<?=site_url('sign-up')?>" class="text-info text-gradient font-weight-bold">Sign up</a>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-md-8">
              <div class="oblique position-absolute top-0 h-100 d-md-block d-none">
                <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('../assets/img/curved-images/division_office.png')"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <footer class="footer py-2">
    <div class="container">
      <div class="row">
        <div class="col-8 mx-auto text-center mt-1">
          <p class="mb-0 text-secondary">
            Copyright © <script>
              document.write(new Date().getFullYear())
            </script> Soft by Creative Tim.
          </p>
        </div>
      </div>
    </div>
  </footer>
  <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <!--   Core JS Files   -->
  <script src="<?=base_url('assets/js/core/popper.min.js')?>"></script>
  <script src="<?=base_url('assets/js/core/bootstrap.min.js')?>"></script>
  <script src="<?=base_url('assets/js/plugins/perfect-scrollbar.min.js')?>"></script>
  <script src="<?=base_url('assets/js/plugins/smooth-scrollbar.min.js')?>"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="<?=base_url('assets/js/soft-ui-dashboard.min.js?v=1.1.0')?>"></script>
</body>

</html>