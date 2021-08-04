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
  <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBquDodCMZW38EiH_CGLryyRaXvIi6tV3c"></script> -->
  <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCbF9O9Ks9_-QNWHi2SFxLqLUBOwrMyzXk"></script> -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5Knm3yStpPRpfNkJmbVKSxvexZ0kVezI"></script>
  <script>
  var base_url = "<?=base_url();?>";
  </script>
  <script src="<?=base_url();?>assets/js/app.js"></script>
  <script src="<?=base_url();?>assets/js/custom.js"></script>
  <?=put_headers();?>
  <?=put_footer();?>
  <?php
$username = $this->session->userdata('username');
$i_company = $this->session->userdata('i_company');
$user = $this->db->get_where('tbl_user', ['username' => $username, 'i_company' => $i_company, 'f_active' => 't'])->row_array();
$language = $this->session->userdata('language');

?>
</head>

<body>

  <!-- Main navbar -->
  <div class="navbar navbar-expand-md navbar-dark">
    <div class="navbar-brand">
      <a href="<?=base_url();?>" class="d-inline-block">
        <img src="<?=base_url();?>global_assets/images/logo_icon_light.png" alt="">
      </a>
    </div>

    <div class="d-md-none">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
        <i class="icon-tree5"></i>
      </button>
      <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
        <i class="icon-paragraph-justify3"></i>
      </button>
    </div>

    <div class="collapse navbar-collapse" id="navbar-mobile">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
            <i class="icon-paragraph-justify3"></i>
          </a>
        </li>

      </ul>

      <span class="badge bg-success ml-md-3 mr-md-auto">Online</span>

      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <?php if ($language == 'indonesia') {?>
          <a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
            <img src="<?=base_url();?>global_assets/images/lang/ind.gif" class="img-flag mr-2" alt="">
            Indonesia
          </a>
          <?php } else {?>
          <a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
            <img src="<?=base_url();?>global_assets/images/lang/gb.png" class="img-flag mr-2" alt="">
            English
          </a>
          <?php }?>
          <div class="dropdown-menu dropdown-menu-right">
            <?php if ($language == 'indonesia') {?>

            <a href="<?=base_url('dashboard/switch_language/english');?>" class="dropdown-item"><img
                src="<?=base_url();?>global_assets/images/lang/gb.png" class="img-flag" alt=""> English</a>
            <?php } else {?>
            <a href="<?=base_url('dashboard/switch_language/indonesia');?>" class="dropdown-item"><img
                src="<?=base_url();?>global_assets/images/lang/ind.gif" class="img-flag" alt=""> Indonesia</a>
            <?php }?>
          </div>
        </li>
        <li class="nav-item dropdown dropdown-user">
          <a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
            <img src="<?=base_url();?>global_assets/images/placeholders/placeholder.jpg" class="rounded-circle mr-2"
              height="34" alt="">
            <span><?=$user['e_name'];?></span>
          </a>

          <div class="dropdown-menu dropdown-menu-right">
            <a href="<?=base_url();?>auth/logout" class="dropdown-item"><i class="icon-switch2"></i>
              <?=$this->lang->line('logout');?></a>
          </div>
        </li>
      </ul>
    </div>
  </div>
  <!-- /main navbar -->


  <!-- Page content -->
  <div class="page-content">

    <!-- Main sidebar -->
    <div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

      <!-- Sidebar mobile toggler -->
      <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
          <i class="icon-arrow-left8"></i>
        </a>
        Navigation
        <a href="#" class="sidebar-mobile-expand">
          <i class="icon-screen-full"></i>
          <i class="icon-screen-normal"></i>
        </a>
      </div>
      <!-- /sidebar mobile toggler -->


      <!-- Sidebar content -->
      <div class="sidebar-content">
        <!-- Main navigation -->
        <div class="card card-sidebar-mobile">
          <ul class="nav nav-sidebar" data-nav-type="accordion">
            <li class="nav-item">
              <a href="<?=base_url();?>" class="nav-link">
                <i class="fas fa-home"></i>
                <span>
                  <?=$this->lang->line('dashboard');?>
                </span>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?=base_url();?>report" class="nav-link">
                <i class="far fa-chart-bar"></i>
                <span>
                  <?=$this->lang->line('report');?>
                </span>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?=base_url();?>staff" class="nav-link">
                <i class="fas fa-user-tie"></i>
                <span>
                  <?=$this->lang->line('staff');?>
                </span>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?=base_url();?>customer" class="nav-link">
                <i class="fas fa-store"></i>
                <span>
                  <?=$this->lang->line('customer');?>
                </span>
              </a>
            </li>
            <?php if ($user['i_role'] == '1') {?>
            <li class="nav-item">
              <a href="<?=base_url();?>product" class="nav-link">
                <i class="fas fa-box-open"></i>
                <span>
                  <?=$this->lang->line('product');?>
                </span>
              </a>
            </li>
            <?php }?>
            <li class="nav-item">
              <a href="<?=base_url();?>documentation" class="nav-link">
                <i class="fas fa-image"></i>
                <span>
                  <?=$this->lang->line('documentation');?>
                </span>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?=base_url();?>sales-order" class="nav-link">
                <i class="fas fa-shopping-cart"></i>
                <span>
                  <?=$this->lang->line('sales-order');?>
                </span>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?=base_url();?>user-management" class="nav-link">
                <i class="fas fa-user"></i>
                <span>
                  <?=$this->lang->line('user-management');?>
                </span>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?=base_url();?>geo-analytic" class="nav-link">
                <i class="fas fa-globe"></i>
                <span>
                  <?=$this->lang->line('geo-analytic');?>
                </span>
              </a>
            </li>
            <?php if ($user['i_role'] == '1' || $user['i_role'] == '2' || $user['i_role'] == '3') {?>

            <li class="nav-item">
              <a href="<?=base_url();?>push" class="nav-link">
                <i class="fab fa-pushed"></i>
                <span>
                  <?=$this->lang->line('push');?>
                </span>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?=base_url();?>live-tracking" class="nav-link">
                <i class="fas fa-map"></i>
                <span>
                  <?=$this->lang->line('live-tracking');?>
                </span>
              </a>
            </li>
            <?php }?>

          </ul>
        </div>
        <!-- /main navigation -->

      </div>
      <!-- /sidebar content -->

    </div>
    <!-- /main sidebar -->


    <!-- Main content -->
    <div class="content-wrapper">

      <?=$contents;?>

      <!-- Footer -->
      <div class="navbar navbar-expand-lg navbar-light">
        <div class="text-center d-lg-none w-100">
          <button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse"
            data-target="#navbar-footer">
            <i class="icon-unfold mr-2"></i>
            Footer
          </button>
        </div>

        <div class="navbar-collapse collapse" id="navbar-footer">
          <span class="navbar-text">
            &copy; 2020 - <?=date('Y');?>. <a href="<?=base_url();?>">Sales Force Automation Dialogue</a> by <a href="#"
              target="_blank" id="author">Wahyu Adam Husaeni</a>
          </span>
        </div>
      </div>
      <!-- /footer -->

    </div>
    <!-- /main content -->

  </div>
  <!-- /page content -->

</body>

</html>
