	<!-- Footer -->
  <?php if(empty($no_footer)) {?>
	<section id="footer-sec" name="footerwrap"></section>
	<div id="footerwrap" >
		<div class="footer" style="text-align: center">
			<div id="footer-left" class="col-lg-4 col-md-6 col-sm-5 col-xs-12">
				<?php if(empty($buisness)) { ?>
				<div class="start-snacking">
	    			<a href="<?php echo base_url('user/signup')?>" id="snack-btn">
	    				<img src="<?php echo assets_url('images/new_img/snack_btn.png') ?>" alt="">
	    			</a>
	    		</div>
	    		<?php } else { ?> 
	    			<div class="start-snacking">
	    			<a href="<?php echo base_url('user/signup')?>" id="snack-btn">
	    				<img src="<?php echo assets_url('images/new_img/snack_btn.png') ?>" alt="">
	    			</a>
	    		</div>
	    		<?php } ?>
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
					<img id="footer-logo" src="<?php echo assets_url('images/new_img/footer_logo.png') ?>" alt="">
				</div>
			</div>
		</div>
	</div>
<?php } ?>
	
  	<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
  	<script src="<?php echo assets_url('js/new_js/bootstrap.js') ?>"></script>
    <script src="<?php echo assets_url('js/jquery.flexslider-min.js') ?>"></script>
    <script src="<?php echo assets_url('js/app.js') ?>"></script>
    <script src="<?php echo assets_url('js/holder.js') ?>"></script>
    <script src="<?php echo assets_url('js/geo.min.js') ?>"></script>
    <script src="<?php echo assets_url('js/slick/slick.min.js') ?>"></script>
  </body>
</html>