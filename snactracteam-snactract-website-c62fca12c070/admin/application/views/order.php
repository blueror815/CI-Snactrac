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
				<img style="width: 140%; height: auto" src="<?php echo assets_url('images/new_img/order-logo.png') ?>" alt="">
			</div>
			<div class="col-lg-1 col-md-1 col-sm-1"></div>
			<div id="order-title" style="margin-left: 20px" class="col-lg-7 col-md-6 col-sm-4 col-xs-4">
				<h1>Order</h1>
			</div>
		</div>
		<?php if (empty($business)) { ?>
			<div id="order-body">
				<p>Why wait in line? Order your food right from SnacTrac and your food delivered to you!</p>
			</div>
		<?php } else { ?>
			<div id="order-body">
				<p>Have happier customers by allowing them to pick up their orders instead of waiting in line.</p>
			</div>
		<?php } ?>
		
	</div>

	<div class="col-lg-1 col-md-1 col-sm-1"></div>

</div><!--/ #orderwrap -->