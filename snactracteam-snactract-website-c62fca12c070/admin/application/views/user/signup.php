
<section id="box-sign">
    <div class="container">
        <div class="row">
            <div class="box">
                <div class="head signin-up">
                    <h6 style="font-weight: bold">Create your account</h6>
                </div>

                <div class="form" id="signup-form">
	             		<?php
			        		  // show any error msgs or alerts from server
			        			if(!empty($alert)){
											echo bootstrap_alert($alert);
										} 
	        		    ?>
                
                    <?php
                    	echo form_open(
												'/user/signup',
												array(
													'role' => 'form'
												)
											);
                    ?>
                    
	                  <?php
	                    	echo bootstrap_text(
													array(
														'type' => 'text',
														'name' => 'name',
													  'place_holder' => 'Name'
													)
												);
	                  ?>
                    
	                  <?php
	                    	echo bootstrap_text(
													array(
														'type' => 'email',
														'name' => 'email',
													  'place_holder' => 'Email'
													)
												);
	                  ?>

	                  <?php
	                    	echo bootstrap_text(
													array(
														'type' => 'password',
														'name' => 'password',
													  'place_holder' => 'Password'
													)
												);
	                  ?>
	                  
	                  <?php
	                    	echo bootstrap_text(
													array(
														'type' => 'password',
														'name' => 'confirm_password',
													  'place_holder' => 'Confirm Password'
													)
												);
	                  ?>
	                  
	                  <?php
	                    	echo bootstrap_checkbox(
													array(
														'name' => 'truck_owner',
														'label' => 'I am a food truck owner',
                            'value' => 'business',
                            'checked' => ($business == 'business')
													)
												);
	                  ?>
	                  
                    <div class="form-group clearfix">
                        <div class="col-lg-12">
                          <button type="submit" class="btn btn-success">Sign Up</button>
                        </div>
                    </div>

                    <?php form_close(); ?>
                    
                </div>
            </div>
            <p class="already">Already have an account?<a href="<?php echo base_url('/user/signin') ?>">Sign in</a></p>
        </div>
    </div>
  </section>
  
