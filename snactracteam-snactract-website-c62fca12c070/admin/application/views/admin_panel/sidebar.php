  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar" id="left-sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel" style="display: none;">
        <div class="pull-left image">
          <img src="<?php echo assets_url('images/about.png');?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo ($user->name);?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
            <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
            </button>
          </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        
        <?php if ($active == 'dashboard') {?>
        <li class="active">
        <?php } else { ?>
        <li>
        <?php } ?>
          <a href="<?php echo base_url('business/dashboard'); ?>" style="text-align: center">
            <br>
            <i class="fa fa-dashboard"></i> 
            <br>
            <span>Dashboard</span>
          </a>
        </li>

        <?php if ($active == 'business') {?>
        <li class="active">
        <?php } else { ?>
        <li>
        <?php } ?>
          <a href="<?php echo base_url('business/profile'); ?>">
            <br>
            <i class="fa fa-diamond"></i>
            <br>
            <span>Business</span>
          </a>
        </li>

        <?php if ($active == 'payment') {?>
        <li class="active">
        <?php } else { ?>
        <li>
        <?php } ?>
          <a href="<?php echo base_url('business/payment'); ?>">
            <br>
            <i class="fa fa-bank"></i> 
            <br>
            <span>Payment</span>
          </a>
        </li>

        <?php if ($active == 'truck') {?>
        <li class="active">
        <?php } else { ?>
        <li>
        <?php } ?>
          <a href="<?php echo base_url('business/trucks'); ?>">
            <br>
            <i class="fa fa-truck"></i>
            <br>
            <span>Trucks</span>
          </a>
        </li>
        <li>
          <a href="#">
            <br>
            <i class="fa fa-calendar"></i> 
            <br>
            <span>Calendar</span>
          </a>
        </li>

        <?php if ($active == 'profile') {?>
        <li class="active">
        <?php } else { ?>
        <li>
        <?php } ?>
          <a href="<?php echo base_url('user/profile/basic'); ?>">
            <br>
            <i class="fa fa-user"></i>
            <br>
            <span>Profile</span>
          </a>
        </li>

        <?php if ($active == 'settings') {?>
        <li class="active">
        <?php } else { ?>
        <li>
        <?php } ?>
          <a href="<?php echo base_url('user/profile/settings'); ?>">
            <br>
            <i class="fa fa-cog"></i> 
            <br>
            <span>Settings</span>
          </a>
        </li>
        <li class="">
          <a href="<?php echo base_url('user/signout'); ?>">
            <br>
            <i class="fa fa-share"></i> 
            <br>
            <span>Sign out</span>
          </a>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>