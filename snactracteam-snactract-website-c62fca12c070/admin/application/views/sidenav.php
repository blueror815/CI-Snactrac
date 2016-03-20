    <div id="dashboard" class="container-fluid">
      <div class="row">
      
        <!-- LEFT SIDE NAVIGATION MENU -->
        <div class="col-sm-3 col-md-2 sidebar">
<?php
  if($user->user_type == Membership::USER_TYPE_SUPER_ADMIN){
?> 
          <ul class="nav nav-sidebar">
<?php
          echo bootstrap_nav_item('Welcome', 'super/dashboard', $active);
          echo bootstrap_nav_item('Users', 'super/users', $active);
          echo bootstrap_nav_item('Trucks', 'super/trucks', $active);
?>
          </ul>
<?php
  } 
  elseif($user->user_type == Membership::USER_TYPE_AREA_MANGER){
?>
          <ul class="nav nav-sidebar">
<?php
          echo bootstrap_nav_item('Dashboard', 'business/dashboard', $active);
          echo bootstrap_nav_item('Business', 'business/profile', $active);
          echo bootstrap_nav_item('Payment', 'business/payment', $active);
          echo bootstrap_nav_item('Trucks', 'business/trucks', $active);
?>
          </ul>
<?php
  }
  elseif($user->user_type == Membership::USER_TYPE_REGULAR_USER){
?>
          <ul class="nav nav-sidebar">
<?php 
            echo bootstrap_nav_item('Welcome', 'user/dashboard', $active);
//             echo bootstrap_nav_item('Locations', 'user/locations', $active);
//             echo bootstrap_nav_item('Favorites', 'user/favorites', $active);
//             echo bootstrap_nav_item('Alerts', 'user/alerts', $active);
?>
          </ul>
<?php 
  }
?>
          <ul class="nav nav-sidebar">
<?php
            echo bootstrap_nav_item('Profile', 'user/profile', $active);
            echo bootstrap_nav_item('Sign out', 'user/signout', $active);
?>
          </ul>
        </div>