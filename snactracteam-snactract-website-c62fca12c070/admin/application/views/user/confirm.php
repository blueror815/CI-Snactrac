<section id="box-sign">
    <div class="container">
        <div class="row">
            <div class="box">
                <div class="head">
                    <h6>Verify Email</h6>
                </div>
               
                <div class="form">
                
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
                  
                  <div>
                    If you are having issues or need assistance, please contact
                    <a href="mailto:support@snactrac.com">support@snactrac.com</a>.<br/>
                  </div>
                  
                    <?php form_close(); ?>
                </div>
                
            </div>
            <p class="already">Don't have an account?<a href="<?php echo base_url('/user/signup') ?>">Sign up</a></p>
        </div>
    </div>
  </section>
