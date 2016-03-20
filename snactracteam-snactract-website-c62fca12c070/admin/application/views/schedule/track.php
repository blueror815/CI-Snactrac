
        <!-- <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">  -->
        <div class="col-sm-5 col-md-6 main">
        
          <h1 class="page-header">
            <?php echo "{$truck->name} - {$dayOfWeek}" ?>
          </h1>
          <h4>Tell us how would you like to recieve tracking notifications?</h4> 
          <h4><small>Visit your <a href="<?php echo base_url('user/profile')?>">profile page</a> to change your phone number or email.</small></h4>
                    
          <div class="form">
             		<?php
		        		  // show any error msgs or alerts from server
		        			if(!empty($alert)){
  								  echo bootstrap_alert($alert);
									} 
        		    ?>
              
                  <?php
                  	echo form_open(
											"/schedule/track/{$truck->id}/{$schedule->schedule_id}",
											array(
												'role' => 'form'
											)
										);
                  ?>
                  
                  <?php
                      $checked = 'checked';
                      if(!empty($monitor) and $monitor->monitor_type != Monitor::MONITOR_TYPE_EMAIL){
                        $checked = 'checked';
                      }
                    	echo bootstrap_text(
												array(
													'type' => 'email',
													'name' => 'email',
												  'place_holder' => 'Email',
                          'value' => $user->email,
                          'readonly' => true,
                          'addon' => array(
                            'type' => 'radio',
                            'name' => 'method',
                            'value' => 'email',
                            'checked' => $checked
                          )
												)
											);
                  ?>
                                                      
                  <?php
                     $checked = '';
                     if(!empty($monitor) and $monitor->monitor_type == Monitor::MONITOR_TYPE_SMS){
                       $checked = 'checked';
                     }
                     echo bootstrap_text(
												array(
													'type' => 'text',
													'name' => 'phone',
												  'place_holder' => 'Phone',
                          'value' => $user->phone,
                          'readonly' => true,
                          'addon' => array(
                              'type' => 'radio',
                              'name' => 'method',
                              'value' => 'phone',
                              'checked' => $checked
                          )
												)
											);
                  ?>
                  
                  <div class="form-group clearfix">
                      <div class="col-lg-12">
                      <?php $btnText = empty($monitor) ? 'Track It' : 'Update'?>
                        <button name="save" value="1" type="submit" class="btn btn-success"><?php echo $btnText ?></button>
                      <?php if(!empty($monitor)) {?>
                        <div class="pull-right"><button name="stop" value="1" type="submit" class="btn btn-danger">Stop Tracking</button></div>
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
      