
        <!-- <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">  -->
        <div class="col-sm-5 col-md-6 main">
        
          <h1 class="page-header">
            <?php echo "{$truck->name} - "?>
            <?php echo (empty($schedule)? 'New Schedule' : ucfirst($stcalendar->getDayOfWeekTitle($schedule->day_of_week))) ?>
          </h1>
          
            <div class="form">
             		<?php
		        		  // show any error msgs or alerts from server
		        			if(!empty($alert)){
  								  echo bootstrap_alert($alert);
									} 
        		    ?>
              
                  <?php
                    $scheduleId = (empty($schedule) ? '' : "{$schedule->schedule_id}");
                  	echo form_open(
											"/schedule/index/{$truck->id}/{$scheduleId}",
											array(
												'role' => 'form'
											)
										);
                  ?>
                  
                  <?php
                      $dayOfWeek = (empty($schedule) ? '' : $schedule->day_of_week);
                    	echo bootstrap_select(
												array(
													'name' => 'day_of_week',
                          'options' => $stcalendar->getDaysOfWeek(),
                          'value' => $dayOfWeek
												)
											);
                  ?>
                  
                  <?php
                      $startTime = (empty($schedule) ? '' : sprintf("%02d:%02d", $schedule->start_time_hour, $schedule->start_time_min));
                    	echo bootstrap_text(
												array(
													'type' => 'text',
													'name' => 'start_time',
												  'place_holder' => 'Start time in 24 hour clock e.g. 10:00',
                          'value' => $startTime
												)
											);
                  ?>
                  
                  <?php
                      $endTime = (empty($schedule) ? '' : sprintf("%02d:%02d", $schedule->end_time_hour, $schedule->end_time_min));
                    	echo bootstrap_text(
												array(
													'type' => 'text',
													'name' => 'end_time',
												  'place_holder' => 'End Time in 24 hour clock e.g. 14:25',
                          'value' => $endTime
												)
											);
                  ?>
                                    
                  <?php
                      $street = empty($schedule->street) ? '' : $schedule->street;
                    	echo bootstrap_text(
												array(
													'type' => 'text',
													'name' => 'street',
												  'place_holder' => 'Street',
                          'value' => $street
												)
											);
                  ?>
                  
                  <?php
                      $city = empty($schedule->city) ? '' : $schedule->city;
                    	echo bootstrap_text(
												array(
													'type' => 'text',
													'name' => 'city',
												  'place_holder' => 'City',
                          'value' => $city
												)
											);
                  ?>
                  
                  <?php
                      $state = empty($schedule->state) ? '' : $schedule->state;
                    	echo bootstrap_text(
												array(
													'type' => 'text',
													'name' => 'state',
												  'place_holder' => 'State',
                          'value' => $state
												)
											);
                  ?>
                  
                  <?php
                      $zip = empty($schedule->zip) ? '' : $schedule->zip; 
                    	echo bootstrap_text(
												array(
													'type' => 'text',
													'name' => 'zip',
												  'place_holder' => 'zip',
                          'value' => $zip
												)
											);
                  ?>
                  
                  <?php
                      $notes = (empty($schedule) ? '' : $schedule->notes);
                    	echo bootstrap_textarea(
												array(
													'name' => 'notes',
												  'place_holder' => 'Notes/Comments',
                          'value' => $notes,
                          'rows' => 5
												)
											);
                  ?>
                  
                  <div class="form-group clearfix">
                      <div class="col-lg-12">
                        <button name="save" type="submit" class="btn btn-success">Save</button>
                        <?php if(!empty($schedule)) { ?>
                        <a href="<?php echo base_url("/schedule/delete/{$truck->id}/{$schedule->schedule_id}") ?>" class="btn btn-danger pull-right">Delete</a>
                        <?php } ?>
                      </div>
                      <h1>&nbsp;</h1>
                      <h1>&nbsp;</h1>
                      <h1>&nbsp;</h1>
                      <h1>&nbsp;</h1>
                   </div>
                  
                  <?php echo form_close(); ?>
                  
              </div> <!-- form -->

            </div> <!-- main -->
          
          </div> <!-- row -->
          
      </div> <!-- container fluid -->
      