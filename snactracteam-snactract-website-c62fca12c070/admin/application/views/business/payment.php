<div class="content-wrapper">
  	<section class="content-header" style="text-align: center" id="business-area">
	    <h1>
	      	Payment Information
	    </h1>
    	<ol class="breadcrumb">
      		<li><a href="<?php echo base_url('business/dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      		<li class="active">Payment</li>
    	</ol>
  	</section>

  	<section class="content" id="payment-area">

  		<?php
    		  // show any error msgs or alerts from server
			if(!empty($alert)){
			  	echo bootstrap_alert($alert);
			} 
	    ?>

  	</section>

</div>