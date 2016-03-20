<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Snactrac-Website">
    <meta name="author" content="Reis Hood - Remy Albert">
    <link rel="shortcut icon" href="assets/ico/favicon.png">

    <title>SnacTrac - Tame Your Pangs</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo assets_url('css/new_css/bootstrap.css') ?>" rel="stylesheet" media="screen">

    <!-- CSS for Animation -->
    <link href="<?php echo assets_url('css/new_css/animate.css') ?>" rel="stylesheet" media="screen">

    <!-- Custom styles for this template -->
    <link href="<?php echo assets_url('css/new_css/main.css') ?>" rel="stylesheet" media="screen">
    <link href="<?php echo assets_url('css/new_css/custom.css') ?>" rel="stylesheet" media="screen">
    
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,300,700' rel='stylesheet' type='text/css'>
    
    <script src="<?php echo assets_url('js/new_js/jquery.min.js') ?>"></script>
    <script src="<?php echo assets_url('js/new_js/smoothscroll.js') ?>"></script>
    <!-- // <script src="assets/js/wow.min.js"></script> -->
    
    <script type="text/javascript">
	    // new WOW().init();
    </script>

  </head>

  <body data-spy="scroll" data-offset="0" data-target="#navigation">

    <!-- Fixed navbar -->
    <div id="navigation" class="navbar navbar-default navbar-fixed-top">
      	<div id="navigation-container">
	        <div class="navbar-header">
	          	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
		            <span class="icon-bar"></span>
		            <span class="icon-bar"></span>
		            <span class="icon-bar"></span>
	          	</button>
	          	<a class="navbar-brand" href="#">
		          	<img class="img-responsive" src="<?php echo assets_url('images/new_img/logo.png') ?>" alt="">
	          	</a>
	        </div>
	        <div class="navbar-collapse collapse">
	          	<ul class="nav navbar-nav navbar-right">
		            <li class="active"><a href="#headerwrap" class="smothscroll">Vendors</a></li>
		            <li><a href="#disc" class="smothScroll">Get the App</a></li>
		            <li><a href="#track" class="smothscroll">Log In</a></li>
		            <li><a href="#footerwrap" class="smothScroll">Sign Up</a></li>
	          	</ul>
	        </div><!--/.nav-collapse -->
      	</div>
    </div>

	<section id="home-sec" name="headerwrap"></section>
		
	<div id="headerwrap">
	    <div class="container">
	    	<div class="row centered">
	    		<div class="col-lg-12">
					<h1>Find A Food Truck Near You</h1>
					<h3>Discover and Track your favorite food and trucks.</h3>
					<h3>Order online and enjoy delicious food!</h3>
					<br>
	    		</div>

	    		<div class="start-snacking">
	    			<a href="#" id="snack-btn">
	    				<img src="<?php echo assets_url('images/new_img/snack_btn.png') ?>" alt="">
	    			</a>
	    		</div>
	    	</div>
	    </div> <!--/ .container -->
	    
	    <div id="location-img">
	    	<img class="img-responsive" src="<?php echo assets_url('images/new_img/crop_img.png') ?>" alt="">
	    </div>
	    
	</div><!--/ #headerwrap -->

	<section id="discover-sec" name="disc"></section>
	<div id="disc">

		<div class="col-lg-2 col-md-1 col-sm-1 col-xs-2"></div>

		<div class="col-lg-3 col-md-4 col-sm-5  col-xs-7 animation-element slide-left" data-wow-delay=".5s">
			<img class="img-responsive" src="<?php echo assets_url('images/new_img/img-circle.png') ?>" alt="">
		</div>

		<div class="col-lg-1 col-md-1 col-sm-3 col-xs-3"></div>

		<div id="discover-container" class="col-lg-4 col-md-7 col-sm-6 col-xs-7 animation-element slide-right" data-wow-delay=".2s" >
			<div class="discover">
				<div id="discover-logo" class="col-lg-3 col-md-4 col-sm-3 col-xs-3">
					<img class="img-responsive" src="<?php echo assets_url('images/new_img/magetic.png') ?>" alt="">
				</div>
				<div id="discover-title" class="col-lg-8 col-md-8 col-sm-4 col-xs-4">
					<h1>Discover</h1>
				</div>
			</div>

			<div id="discover-body">
				<p>Discover new food trucks on the go, when you're craving something new.</p>
			</div>
		</div>

		<div class="col-lg-1 col-md-1 col-sm-1"></div>

	</div><!--/ #introwrap -->

	<!-- INTRO WRAP -->
	<!-- Tracks WRAP -->
	<section id="track-sec" name="track"></section>
	<div id="track">

		<div class="col-lg-2 col-md-1 col-sm-1 col-xs-2"></div>

		<div id="track-container" class="col-lg-4 col-md-5 col-sm-5 col-xs-5">
			<div class="tracks">
				<div id="track-logo" class="col-lg-3 col-md-4 col-sm-3 col-xs-3">
					<img class="img-responsive" src="<?php echo assets_url('images/new_img/track-logo.png') ?>" alt="">
				</div>
				<div id="track-title" class="col-lg-8 col-md-8 col-sm-4 col-xs-4">
					<h1>Track</h1>
				</div>
			</div>

			<div id="track-body">
				<p>Track your favorite food trucks in real time with GPS. Never miss an opportunity to have the lunch you want again.</p>
			</div>
		</div>

		<div class="col-lg-1"></div>

		<div id="track-iphone" class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
			<img class="img-responsive" src="<?php echo assets_url('images/new_img/track-iphone.png') ?>" alt="">
		</div>

		<div class="col-lg-1 col-md-1 col-sm-1"></div> 

	</div>
	
	<section id="order-sec" name="order"></section>
	<div id="order">

		<div class="col-lg-2 col-md-1 col-sm-1 col-xs-2"></div>

		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-7">
			<img class="img-responsive" src="<?php echo assets_url('images/new_img/order-note.png') ?>" alt="">
		</div>

		<div class="col-lg-1 col-md-1 col-sm-3 col-xs-3"></div>

		<div id="order-container" class="col-lg-4 col-md-7 col-sm-5 col-xs-7">
			<div class="orders">
				<div id="order-logo" class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
					<img class="img-responsive" src="<?php echo assets_url('images/new_img/order-logo.png') ?>" alt="">
				</div>
				<div class="col-lg-1 col-md-1 col-sm-1"></div>
				<div id="order-title" style="margin-left: 20px" class="col-lg-7 col-md-6 col-sm-4 col-xs-4">
					<h1>Order</h1>
				</div>
			</div>

			<div id="order-body">
				<p>Why wait in line? Order your food right from SnacTrac and your food delivered to you!</p>
			</div>
		</div>

		<div class="col-lg-1 col-md-1 col-sm-1"></div>

	</div><!--/ #introwrap -->


	<section id="footer-sec" name="footerwrap"></section>
	<div id="footerwrap" >
		<div class="footer" style="text-align: center">
			<div id="footer-left" class="col-lg-4 col-md-6 col-sm-5 col-xs-12">
				<div class="start-snacking">
	    			<a href="#" id="snack-btn">
	    				<img src="<?php echo assets_url('images/new_img/snack_btn.png') ?>" alt="">
	    			</a>
	    		</div>
			</div>

			<div class="col-lg-4 col-sm-2"></div>

			<div id="footer-right" class="col-lg-4 col-md-6 col-sm-5 col-xs-12">
				<div id="support" class="col-lg-5 col-md-4 col-sm-5 col-xs-6">
					<div class="row">
						<a href="#"> Help & Contact </a>
					</div>
					<div class="row">
						<a href="#"> FAQ </a>
					</div>
				</div>

				<div class="col-lg-7 col-md-4 col-sm-5 col-xs-6">
					<img  src="<?php echo assets_url('images/new_img/footer_logo.png') ?>" alt="">
				</div>
			</div>
		</div>
	</div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo assets_url('js/new_js/bootstrap.js') ?>"></script>
    <!-- // <script src="assets/js/custom_animate.js"></script> -->
	<script>
		// $('.carousel').carousel({
		//   interval: 3500
		// })
	</script>
  </body>
</html>
