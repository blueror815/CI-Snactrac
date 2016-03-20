<!-- MAIN RIGHT SIDE AREA START -->
<div class="content-wrapper">
  <section class="content-header" style="text-align: center" id="business-area">
    <h1>
        Manage Trucks
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('business/dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Trucks</li>
    </ol>
  </section>

  <section class="content">
    <div class="main" style="text-align: center">
      <div class="form business-form">
        <!-- <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">  -->
        <div class="col-lg-12 col-xs-12 col-sm-12 col-md-12 main">
        
          <h1 class="page-header"><?php echo (empty($truck)? 'New Truck' : $truck->name) ?></h1>
          
          <div class="form" id="new-truck-div">
           		<?php
	        		  // show any error msgs or alerts from server
	        			if(!empty($alert)){
								  echo bootstrap_alert($alert);
								} 
      		    ?>
            
                <?php
                  $truckId = (empty($truck) ? '' : "{$truck->id}");
                  echo form_open_multipart(
										'/truck/index/' . $truckId,
										array(
											'role' => 'form'
										)
									);
                ?>
                
                <?php
                    $truckName = (empty($truck) ? '' : $truck->name);
                  	echo bootstrap_text(
											array(
												'type' => 'text',
												'name' => 'name',
											  'place_holder' => 'Truck Name',
                        'value' => $truckName
											)
										);
                ?>
                
                <?php
                    $keywords = (empty($truck->keywords) ? '' : $truck->keywords);
                  	echo bootstrap_text(
											array(
												'type' => 'text',
												'name' => 'keywords',
											  'place_holder' => 'Keywords',
                        'value' => $keywords
											)
										);
                ?>
                
                <?php
                    $truckCalendar = (empty($truck) ? '' : $truck->calendar_url);
                  	echo bootstrap_text(
											array(
												'type' => 'text',
												'name' => 'calendar',
											  'place_holder' => 'Google Calendar URL',
                        'value' => $truckCalendar
											)
										);
                ?>
                
                <?php
                    $truckDesc = (empty($truck) ? '' : $truck->description);
                  	echo bootstrap_textarea(
											array(
												'name' => 'desc',
											  'place_holder' => 'Description/Info',
                        'value' => $truckDesc,
                        'rows' => 5
											)
										);
                ?>
                
                <?php 
                  echo bootstrap_file(
                     array(
                      'name' => 'pic',
                      'label' => 'Upload picture',
                      'class' => 'pull-left',
                      'error_info' => isset($fileError) ? $fileError : ''
                     )
                  );
                ?>
                
                <div class="form-group clearfix">
                    <div class="col-lg-12 " >
                      <button name="save" type="submit" class="btn btn-success pull-left" style="width: 120px; background-color: #F6800B; border-color: #F6800B">Save</button>
                      <?php
                        $disabled = '';
                        if(empty($truck)){
                          $disabled = 'disabled';
                        }
                      ?> 
                      <?php if(!empty($truck)) { ?>
                      <a href="<?php echo base_url("/truck/menu/{$truck->id}") ?>" class="btn btn-default <?php echo $disabled; ?>">Menu</a>
                      <a href="<?php echo base_url("/truck/schedule/{$truck->id}") ?>" class="btn btn-default <?php echo $disabled; ?>">Schedule</a>
                      <a href="<?php echo base_url("/truck/view/{$truck->id}") ?>" class="btn btn-default <?php echo $disabled; ?>">View</a>
                      <a href="<?php echo base_url("/truck/embed-code/{$truck->id}") ?>" class="btn btn-default <?php echo $disabled; ?>">Embed</a>
                      <a href="<?php echo base_url("/truck/delete/{$truck->id}") ?>" class="btn btn-danger pull-right <?php echo $disabled; ?>">Delete</a>
                      <?php } ?>
                    </div>
                    <h1>&nbsp;</h1>
                    <h1>&nbsp;</h1>
                    <h1>&nbsp;</h1>
                    <h1>&nbsp;</h1>
                 </div>
                
                <?php echo form_close(); ?>
                
          </div> <!-- form -->

        </div> <!-- main -->
          
      </div> <!-- row -->

    </div> <!-- container fluid -->

  </section>

</div>
      