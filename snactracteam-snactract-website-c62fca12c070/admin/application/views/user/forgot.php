<section id="box-sign">
    <div class="container">
        <div class="row">
            <div class="box">
                <div class="head">
                    <h6>Reset Password</h6>
                </div>
               
                <div class="form">
                
                  <div>
                    Enter your email and we'll send you a new password. Dont forget to check your spam folder. Contact
                    <a href="mailto:support@snactrac.com">support@snactrac.com</a> if you need help.<br/>
                  </div>
                  <div>&nbsp;</div>
	             		<?php
			        		  // show any error msgs or alerts from server
			        			if(!empty($alert)){
											echo bootstrap_alert($alert);
										} 
	        		    ?>
                
                  <?php
                    	echo form_open(
												'/user/forgot',
												array(
													'role' => 'form'
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
	                  
                    <div class="form-group clearfix">
                      <div class="col-lg-12">
                        <button type="submit" class="btn btn-success">Send</button>
                      </div>
                    </div>
                    
                    <?php echo form_close() . PHP_EOL; ?>
                </div>
                
            </div>
            <p class="already">Don't have an account?<a href="<?php echo base_url('/user/signup') ?>">Sign up</a></p>
        </div>
    </div>
  </section>
