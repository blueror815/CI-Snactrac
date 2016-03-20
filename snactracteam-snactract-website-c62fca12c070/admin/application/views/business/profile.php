<div class="content-wrapper" style="min-height: 915px;">
  	<section class="content-header" style="text-align: center">
	    <h1>
	      	Business Information
	    </h1>
    	<ol class="breadcrumb">
      		<li><a href="<?php echo base_url('business/dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      		<li class="active">Business</li>
    	</ol>
  	</section>

  	<section class="content" id="business-area">
        <!-- <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">  -->
        <div class="main" style="text-align: center">
          
            <div class="form business-form">

         		<?php
	        		  // show any error msgs or alerts from server
        			if(!empty($alert)){
					  	echo bootstrap_alert($alert);
					} 
    		    ?>
          
              	<form id="profile-form" role="form" accept-charset="utf-8" method="post" action="<?php echo base_url('business/profile');?>">
					<div class="form-group clearfix ">
						<div class="col-lg-12 ">
							<label class="business-label" for="name">Business Name</label>
							<div class="input-group col-lg-12 col-md-12 col-sg-12 col-xs-12">
								<input id="name" class="form-control" type="text" placeholder="Business Name" value="<?php echo $business->name;?>" name="name">
							</div>
						</div>
					</div>

					<div class="form-group clearfix ">
						<div class="col-lg-5 col-md-5 col-sg-5 col-xs-12">
							<label class="business-label" for="phone">Phone number</label>
							<div class="input-group col-lg-12 col-md-12 col-sg-12 col-xs-12">
								<input id="phone" class="form-control" type="text" data-inputmask='"mask": "(999) 999-9999"' data-mask value="<?php echo $business->phone;?>" name="phone">
							</div>
						</div>

						<div class="col-lg-7 col-md-7 col-sg-7 col-xs-12">
							<label class="business-label" for="email">Email</label>
							<div class="input-group col-lg-12 col-md-12 col-sg-12 col-xs-12">
								<input id="email" class="form-control" type="email" placeholder="Email" value="<?php echo($business->email); ?>" name="email">
							</div>
						</div>
					</div>

					<div class="form-group clearfix ">
						<div class="col-lg-12">
							<label class="business-label" for="website">Website</label>
							<div class="input-group col-lg-12 col-md-12 col-sg-12 col-xs-12">
								<input id="website" class="form-control" type="text" placeholder="Website" value="<?php echo($business->website); ?>" name="website">
							</div>
						</div>
					</div>

					<div class="form-group clearfix ">
						<div class="col-lg-12">
							<label class="business-label">Description</label>
							<textarea class="form-control" rows="3" placeholder="Description/Info"  name="desc"><?php echo ($business->description);?></textarea>
						</div>
					</div>

					<div class="form-group clearfix ">
						<div class="col-lg-12">
							<label class="business-label" for="twitter">Link Social Media</label><br>
						</div>
						
						<div class="col-lg-6 col-md-6 col-sg-6 col-xs-12">
							<div class="input-group col-lg-12 col-md-12 col-sg-12 col-xs-12">
								<span class="social-icon facebook"><i class="fa fa-facebook"></i></span>
								<input id="facebook" class="form-control" type="text" placeholder="Add Facebook Link" value="<?php echo($business->facebook);?>" name="facebook">
							</div>
						</div>

						<div class="col-lg-6 col-md-6 col-sg-6 col-xs-12">
							<div class="input-group col-lg-12 col-md-12 col-sg-12 col-xs-12">
								<span class="social-icon twitter"><i class="fa fa-twitter"></i></span>
								<input id="twitter" class="form-control" type="text" placeholder="Add Twitter Link" value="<?php echo($business->twitter);?>" name="twitter">
							</div>
						</div>
					</div>					

					<div class="form-group clearfix">
						<div class="col-lg-12">
							<button class="btn btn-success" type="submit" style="background-color: #F6800B; border-color: #F6800B; width: 100px;">SAVE</button>
						</div>
						<h1> </h1>
					</div>
				</form>							
          	</div> <!-- form -->
        </div> <!-- main -->
    </section>
</div>