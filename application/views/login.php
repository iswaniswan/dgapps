<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Sales Force Automation Dialogue</title>

  <!-- Global stylesheets -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
  <link href="<?=base_url();?>global_assets/css/icons/icomoon/styles.min.css" rel="stylesheet" type="text/css">
  <link href="<?=base_url();?>global_assets/css/icons/fontawesome/styles.min.css" rel="stylesheet" type="text/css">
  <link href="<?=base_url();?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="<?=base_url();?>assets/css/bootstrap_limitless.min.css" rel="stylesheet" type="text/css">
  <link href="<?=base_url();?>assets/css/layout.min.css" rel="stylesheet" type="text/css">
  <link href="<?=base_url();?>assets/css/components.min.css" rel="stylesheet" type="text/css">
  <link href="<?=base_url();?>assets/css/colors.min.css" rel="stylesheet" type="text/css">
  <!-- /global stylesheets -->

  <!-- Core JS files -->
  <script src="<?=base_url();?>global_assets/js/main/jquery.min.js"></script>
  <script src="<?=base_url();?>global_assets/js/main/bootstrap.bundle.min.js"></script>
  <script src="<?=base_url();?>global_assets/js/plugins/loaders/blockui.min.js"></script>
  <!-- /core JS files -->

  <!-- Theme JS files -->
  <script src="<?=base_url();?>assets/js/app.js"></script>

  <script src="<?=base_url();?>global_assets/js/plugins/forms/validation/validate.min.js"></script>
  <script src="<?=base_url();?>global_assets/js/plugins/forms/styling/uniform.min.js"></script>

  <script src="<?=base_url();?>assets/js/login/index.js"></script>
  <!-- /theme JS files -->

</head>

<body style="background-image: url('<?= base_url(); ?>global_assets/images/backgrounds/login-register.jpg'); height: 100%; background-position: center; background-repeat: no-repeat; background-size: cover;"
  <!-- Page content -->
  <div class="page-content">

    <!-- Main content -->
    <div class="content-wrapper">

      <!-- Content area -->
      <div class="content d-flex justify-content-center align-items-center">

        <!-- Login form -->
        <form class="login-form form-validate" action="<?=base_url('auth');?>" method="POST">
          <div class="card mb-0">
            <div class="card-body">
              <div class="text-center mb-3">
                <i
                  class="icon-reading icon-2x text-slate-300 border-slate-300 border-3 rounded-round p-3 mb-3 mt-1"></i>
                <h5 class="mb-0">Login to your account</h5>
                <span class="d-block text-muted"></span>
              </div>

              <div class="form-group form-group-feedback form-group-feedback-left">
                <input type="text" class="form-control" placeholder="Company ID" name="company" required>
                <div class="form-control-feedback">
                  <i class="fas fa-building text-muted"></i>
                </div>
              </div>

              <div class="form-group form-group-feedback form-group-feedback-left">
                <input type="text" class="form-control" autocapitalize="off" placeholder="Username" name="username" required>
                <div class="form-control-feedback">
                  <i class="icon-user text-muted"></i>
                </div>
              </div>

              <div class="form-group form-group-feedback form-group-feedback-left">
                <input type="password" class="form-control" placeholder="Password" name="password" required>
                <div class="form-control-feedback">
                  <i class="icon-lock2 text-muted"></i>
                </div>
              </div>

              <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Login <i
                    class="icon-circle-right2 ml-2"></i></button>
              </div>
              <div class="form-group text-center">
                <a href="<?=base_url('dialogue.apk');?>" class="btn btn-block btn-success">Download Apk <i
                    class="icon-download"></i></a>
              </div>
            </div>
          </div>
        </form>
        <!-- /login form -->

      </div>
      <div class="content d-flex justify-content-center align-items-center hilang">
        <span id="author" style="display: none;">Wahyu Adam Husaeni</span>
      </div>
      <!-- /content area -->

    </div>
    <!-- /main content -->

  </div>
  <!-- /page content -->

</body>

</html>