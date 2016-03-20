        <!-- MAIN RIGHT SIDE AREA START -->
        <div class="col-sm-9 col-md-10 main">
          <h1 class="page-header"><?php echo $truck->name?></h1>
       		<?php
      		  // show any error msgs or alerts from server
      			if(!empty($alert)){
						  echo bootstrap_alert($alert);
						} 
  		    ?>
    
          <div class="col-sm-4 col-md-4 pull-right">
           <?php
            	echo form_open(
  							"/truck/schedule/{$truck->id}",
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
          
          <a class="btn btn-primary" href="<?php echo base_url("schedule/index/{$truck->id}"); ?>">Add Schedule</a>
                  
          <h2 class="sub-header">Truck Schedule</h2>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Day</th>
                  <th>Start Time</th>
                  <th>End Time</th>
                  <th>Location</th>
                </tr>
              </thead>
              <tbody>
              <?php 
              foreach($schedules as $schedule) {
              ?>
                <tr class="clickable-row" data-url="<?php echo base_url("/schedule/index/{$truck->id}/{$schedule->schedule_id}")?>">
                  <td><?php echo $schedule->schedule_id ?></td>
                  <td><?php echo $controller->stcalendar->getDayOfWeekTitle($schedule->day_of_week) ?></td>
                  <td><?php echo sprintf("%02d:%02d", $schedule->start_time_hour, $schedule->start_time_min) ?></td>
                  <td><?php echo sprintf("%02d:%02d", $schedule->end_time_hour, $schedule->end_time_min) ?></td>
                  <td><?php echo $controller->location->summary($schedule) ?></td>
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
    </div>