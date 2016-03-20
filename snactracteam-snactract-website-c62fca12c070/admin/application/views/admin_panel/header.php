<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SnacTrac - Admin Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="<?php echo assets_url('css/bootstrap/bootstrap.css');?>">

  <!-- custom css for admin panel -->
  <link rel="stylesheet" type="text/css" href="<?php echo assets_url('css/admin/admin_custom.css');?>">
  
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo assets_url('css/admin/admin_lte.css');?>">
  
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo assets_url('css/admin/skin.css');?>">
  
  <link rel="stylesheet" href="<?php echo assets_url('css/admin/main_theme.css');?>">
  
  <?php if (isset($styles)) {
    foreach ($styles as $style) { ?>
      <link rel="stylesheet" type="text/css" href="<?php echo assets_url($style); ?>">    
    <?php } ?>
  <?php }?>

  

  <script src="<?php echo assets_url('plugins/jQuery/jQuery-2.1.4.min.js')?>"></script>

  <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>

  <script src="<?php echo assets_url('plugins/daterangepicker/daterangepicker.js'); ?>"></script>

  <script src="<?php echo assets_url('plugins/datepicker/bootstrap-datepicker.js'); ?>"></script>

  <script src="<?php echo assets_url('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'); ?>"></script>

  <script src="<?php echo assets_url('plugins/slimScroll/jquery.slimscroll.min.js'); ?>"></script>

  <script src="<?php echo assets_url('plugins/fastclick/fastclick.js'); ?>"></script>

  <script src="<?php echo assets_url('js/bootstrap.js');?>"></script>

  <script src="<?php echo assets_url('js/admin/app.js'); ?>"></script>
  
  <script src="<?php echo assets_url('js/admin/demo.js'); ?>"></script>

  <script>
    $.widget.bridge('uibutton', $.ui.button);
  </script>

  <?php if(isset($scripts)) {
    foreach ($scripts as $script) { ?>
      <script src="<?php echo assets_url($script); ?>"></script>
    <?php } ?>
  <?php } ?>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

</head>
<body class=" skin-blue wysihtml5-supported">
  <div class="wrapper">
    <header class="main-header">
      <!-- Logo -->
      <a href="#" class="logo" id="admin_logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>Snac</b></span>
        <img src="<?php echo assets_url('images/new_img/logo.png');?>">
      </a>
      <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle admin-toggler" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <li class="dropdown notifications-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-bell-o"></i>
                <span class="label label-warning">10</span>
              </a>
              <ul class="dropdown-menu">
                <li class="header">You have 10 notifications</li>
                <li>
                  <!-- inner menu: contains the actual data -->
                  <ul class="menu">
                    <li>
                      <a href="#">
                          <i class="fa fa-users text-aqua"></i> 5 new members joined today
                      </a>
                    </li>
                    <li>
                      <a href="#">
                        <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the
                        page and may cause design problems
                      </a>
                    </li>
                    <li>
                      <a href="#">
                        <i class="fa fa-users text-red"></i> 5 new members joined
                      </a>
                    </li>
                    <li>
                      <a href="#">
                        <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                      </a>
                    </li>
                    <li>
                      <a href="#">
                        <i class="fa fa-user text-red"></i> You changed your username
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="footer"><a href="#">View all</a></li>
              </ul>
            </li>

            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="<?php echo assets_url('images/about.png');?>" class="user-image" alt="User Image">
                <span class="hidden-xs"><?php echo ($user->name);?></span>
              </a>
              <ul class="dropdown-menu">
                <!-- User image -->
                <li class="user-header">
                  <img src="<?php echo assets_url('images/about.png');?>" class="img-circle" alt="User Image">
                  <p>
                    <?php echo ($user->name);?>
                    <?php $member_since = date('d/m/Y', $user->created_at); ?>
                    <small>Member since <?php echo $member_since;?></small>
                  </p>
                </li>
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="<?php echo base_url('user/profile/basic')?>" class="btn btn-default btn-flat">Profile</a>
                  </div>
                  <div class="pull-right">
                    <a href="<?php echo base_url('user/signout')?>" class="btn btn-default btn-flat">Sign out</a>
                  </div>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>
    </header>