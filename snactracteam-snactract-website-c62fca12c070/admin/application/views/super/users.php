        
        <!-- MAIN RIGHT SIDE DASHBOARD AREA START -->
        <!-- <div class="col-sm-9 col-md-10main"> -->
        <div class="col-sm-9 col-md-10 main">
          <h1 class="page-header">Users</h1>

          <div class="row placeholders">
            <div class="col-xs-6 col-sm-3 placeholder">
              <img data-src="holder.js/150x150/auto/sky" class="img-responsive" alt="Daily Active Users">
              <h4>Daily</h4>
              <span class="text-muted">Daily Active Users</span>
            </div>
            <div class="col-xs-6 col-sm-3 placeholder">
              <img data-src="holder.js/150x150/auto/vine" class="img-responsive" alt="Monthly Active Users">
              <h4>Monthly</h4>
              <span class="text-muted">Monthly Active Users</span>
            </div>
          </div>

          <div class="col-sm-4 col-md-4 pull-right">
            <?php
            	echo form_open(
  							'/super/users',
  							array(
  								'role' => 'search',
                  'class' => 'navbar-form navbar-left'
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
            
            <?php echo form_close(); ?>
          </div>
          <a class="btn btn-primary" href="<?php echo base_url('user/add'); ?>">Add User</a>
          
          <h2 class="sub-header">All Users</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Type</th>
                  <th>Metro</th>
                </tr>
              </thead>
              <tbody>
              <?php 
              foreach($users as $id=>$user) {
              ?>
                <tr>
                  <td><?php echo $id ?></td>
                  <td><?php echo $user['name'] ?></td>
                  <td><?php echo $user['email'] ?></td>
                  <td><?php echo $user['type'] ?></td>
                  <td><?php echo $user['metro'] ?></td>
                </tr>
                <?php 
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
