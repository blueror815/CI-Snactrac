<!DOCTYPE html>
<html lang="en">
<head>
	<title>SnacTrac - Tame Your Pangs</title>
  	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<!--link href="<?php echo assets_url('css/new_css/bootstrap.css') ?>" rel="stylesheet" media="screen"-->

    <!-- CSS for Animation -->
    <link href="<?php echo assets_url('css/new_css/animate.css') ?>" rel="stylesheet" media="screen">
  	
  	<link href="<?php echo assets_url('css/compiled/theme.css') ?>" rel="stylesheet" media="screen">
  	<link href="<?php echo assets_url('css/vendor/font-awesome.min.css') ?>" rel="stylesheet">
  	<link href="<?php echo assets_url('css/vendor/flexslider.css') ?>" rel="stylesheet" media="screen">

    <link href='http://fonts.googleapis.com/css?family=Raleway:400,300,700' rel='stylesheet' type='text/css'>
    
    <link rel="stylesheet" href="assets_url('plugins/select2/select2.min.css'); ?>">
  	<!-- Custom styles for this template -->
    <link href="<?php echo assets_url('css/app.css') ?>" rel="stylesheet" media="screen">
    <link href="<?php echo assets_url('css/new_css/custom.css') ?>" rel="stylesheet" media="screen">
    
  	<script src="<?php echo assets_url('js/jquery-1.11.1.min.js') ?>"></script>
  	<script src="<?php echo assets_url('js/jquery-ui-1.11.1/jquery-ui.min.js') ?>"></script>
  	<script src="<?php echo assets_url('js/new_js/smoothscroll.js') ?>"></script>
  	<script src="<?php echo assets_url('js/new_js/custom.js') ?>"></script>

  	<?php
	    if(!empty($scripts) and is_array($scripts)) {
	      foreach($scripts as $script){
  	?>
	    <script src="<?php echo base_url("$script") ?>"></script>
  	<?php
	      	}
	    }
  	?>
  
  	<!-- Style -->
  	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900,200italic,300italic,400italic,600italic,700italic,900italic' rel='stylesheet' type='text/css'>
  	<link href="<?php echo assets_url('js/slick/slick.css') ?>" rel="stylesheet" type="text/css"/>

  	<!--[if lt IE 9]>
      	<script src="<?php echo assets_url('js/html5shiv.js') ?>"></script>
      	<script src="<?php echo assets_url('js/respond.min.js') ?>"></script>
    <![endif]-->
    <!--[if IE 7]>
	  	<link rel="stylesheet" href="<?php echo assets_url('css/font-awesome-ie7.min.css') ?>">
	<![endif]-->
</head>
<body>
<?php if(empty($no_header)) { ?>
	<div id="navigation" class="navbar navbar-default navbar-fixed-top" role="navigation">
	  	<div id="navigation-container">
		    <div class="navbar-header">
		      	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
		      	</button>
		      	<a href="<?php echo base_url() ?>" class="navbar-brand scroller" id="nav-logo">
			      	<img class="img-responsive" style="margin-top: -20px;" src="<?php echo assets_url('images/new_img/logo.png') ?>">
		      	</a>
		    </div>

		    <div class="navbar-collapse collapse">
		      	<ul class="nav navbar-nav homepage-nav navbar-right">
	
			        <?php if(empty($business)){ 

			        	if($active == 'vender') { ?>
			        		<li class="active">
					        	<a href="<?php echo base_url('business/') ?>" class="scroller smothscroll">Venders</a>
					        </li>
			        	<?php } else { ?>
			        		<li class="">
					        	<a href="<?php echo base_url('business/') ?>" class="scroller smothscroll">Venders</a>
					        </li>
			        	<?php } ?>

			        <?php } else { 

				        if ($active == 'snacker') { ?>
				        	<li class="active">
					          	<a href="<?php echo base_url() ?>" class="scroller smothscroll">Snackers</a>
					        </li>
				        <?php } else { ?>
				        	<li>
					          	<a href="<?php echo base_url() ?>" class="scroller smothscroll">Snackers</a>
					        </li>	
				        <?php } ?>

			        <?php } ?>
			        
				        <!--li>
				          	<a href="<?php echo base_url('#') ?>" class="scroller smothscroll" data-section="#">Get the app</a>
				        </li-->
	        
		          	<?php
			          	$userId = $controller->session->userdata('user_id');
			          	$userName = $controller->session->userdata('user_name');
			          	if(empty($userId)){
	            	?>

		            	<?php if ($active == 'signin') { ?>

		            		<li id="signin-btn" class="active">
				              	<a href="<?php echo base_url('/user/signin') ?>">Log in</a>
				            </li>

		            	<?php } else { ?>

	        		 		<li id="signin-btn">
				              	<a href="<?php echo base_url('/user/signin') ?>">Log in</a>
				            </li>

	 	            	<?php } ?>
				          
				        <?php if ($active == 'signup') { ?>

		            		<li id="signup-btn" class="active">
				              	<a href="<?php echo base_url('/user/signup') ?>">Sign Up</a>
				            </li> 

		            	 <?php } else { ?> 

	            		 	<li id="signup-btn">
				              	<a href="<?php echo base_url('/user/signup') ?>">Sign Up</a>
				            </li>

		            	<?php } ?>
		          
		          	<?php } else {
		              	$dashboardUrl = '';
		              	list($ret, $userInfo) = $controller->membership->info($userId);
		              	if(!empty($ret)){
		                	if($userInfo->user_type == Membership::USER_TYPE_REGULAR_USER){
		                  		// $dashboardUrl = '/user/dashboard';
		                  		$dashboardUrl = '/user/signout';
		                	}
		                elseif ($userInfo->user_type == Membership::USER_TYPE_AREA_MANGER) {
		                  	$dashboardUrl = '/business/dashboard';
		                }
		                elseif ($userInfo->user_type == Membership::USER_TYPE_SUPER_ADMIN){
		                  	$dashboardUrl = '/super/dashboard';
		                }
		                else {
		                  	error_log("FATAL: Invalid user type: " . json_encode($userInfo));
		                  	die();
		                }
	              	}
	            ?>
	              	<li class="sign-btn">
		                <a href="<?php echo base_url($dashboardUrl) ?>"><?php echo $userName ?></a>
	              	</li>
	            <?php 
	            }
		          	?>
		      	</ul>
		    </div>
	  	</div>
	</div>
<?php } ?>