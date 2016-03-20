<!-- MAIN RIGHT SIDE AREA START -->
<div class="content-wrapper">
  <section class="content-header" style="text-align: center" id="business-area">
    <h1>
        Manage Trucks
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('business/dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Trucks</li>
    </ol>
  </section>

  <section class="content">
    <div class="main" style="text-align: center">
      <div class="form business-form">
          <?php
            // show any error msgs or alerts from server
            if(!empty($alert)){
              echo bootstrap_alert($alert);
            } 
          ?>
    
          <div class="" style="float: left; width: 100%; padding-top: 50px">
              <a id="add-truck-btn" class="btn btn-primary pull-left" href="<?php echo base_url('truck/index'); ?>">Add Truck</a>    
              <?php
               /* echo form_open(
                  '/business/trucks',
                  array(
                    'role' => 'search',
                    'class' => 'navbar-form navbar-left pull-left'
                  )
                );
              ?>
              <?php
                echo bootstrap_navbar_input(
                  array(
                    'type' => 'text',
                    'name' => 'filter',
                    'place_holder' => 'Filter',
                    'glyphicon' => 'glyphicon-search',
                    'btn' => 'btn-success'
                  ) 
                ); 
              ?>
              <?php echo form_close(); */?>
              <form action="<?php echo base_url('business/trucks');?>" method="post" id="truck-search" class="sidebar-form">
                <div class="input-group">
                  <input type="text" name="filter" class="form-control" placeholder="Search...">
                  <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                    </button>
                  </span>
                </div>
              </form>          
          </div>
                            
          <h4 class="sub-header pull-left" style="padding-top: 50px">Truck List</h4>
          <div class="table-responsive">
            <table class="table table-hover" style="background-color: white;">
              <div>
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Keywords</th>
                  <th>Description</th>
                </tr>
              </thead>
              </div>
              <tbody>
              <?php 
              foreach($trucks as $truck) {
              ?>
                <tr class="clickable-row" data-url="<?php echo base_url("/truck/index/{$truck->id}");?>">
                  <td><?php echo $truck->id ?></td>
                  <td><strong style="font-size: 17px; font-weight: bold"><?php echo $truck->name ?></strong></td>
                  <td><?php echo format_limit_string($truck->keywords) ?></td>
                  <td><p><?php echo format_limit_string($truck->description) ?></p></td>
                </tr>
                <?php 
                }
                ?>
              </tbody>
            </table>
            <h1>&nbsp;</h1>
            <h1>&nbsp;</h1>
            <h1>&nbsp;</h1>
          </div>
      </div>
    </div>
  </section>    
</div>