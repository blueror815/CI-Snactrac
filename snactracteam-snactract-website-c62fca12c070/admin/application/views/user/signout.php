<section id="box-sign">
    <div class="container">
        <div class="row">
            <div class="box">
                <div class="head">
                    <h6>See you soon!</h6>
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
                  <?php form_close(); ?>
                </div>
                
            </div>
            <p class="already">Signed out by mistake? <a href="<?php echo base_url('/user/signin') ?>">Sign right back in</a></p>
        </div>
    </div>
  </section>
