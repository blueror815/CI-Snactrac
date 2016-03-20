
        <!-- <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">  -->
        <div class="col-sm-5 col-md-6 main">
        
          <h1 class="page-header"><?php echo (empty($menuItem)? 'New Item' : $menuItem->name) ?></h1>
          
            <div class="form">
             		<?php
		        		  // show any error msgs or alerts from server
		        			if(!empty($alert)){
  								  echo bootstrap_alert($alert);
									} 
        		    ?>
              
                  <?php
                    $menuItemId = (empty($menuItem) ? '' : "{$menuItem->id}");
                  	echo form_open_multipart(
											"/menu/index/{$truck->id}/{$menuItemId}",
											array(
												'role' => 'form'
											)
										);
                  ?>
                  
                  <?php
                      $itemName = (empty($menuItem) ? '' : $menuItem->name);
                    	echo bootstrap_text(
												array(
													'type' => 'text',
													'name' => 'name',
												  'place_holder' => 'Item Name',
                          'value' => $itemName
												)
											);
                  ?>
                  
                  <?php
                      $price = (empty($menuItem) ? '' : $menuItem->price / 100);
                    	echo bootstrap_text(
												array(
													'type' => 'text',
													'name' => 'price',
												  'place_holder' => 'Price',
                          'value' => $price
												)
											);
                  ?>
                  
                  <?php
                      $keywords = (empty($menuItem) ? '' : $menuItem->keywords);
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
                      $desc = (empty($menuItem) ? '' : $menuItem->description);
                    	echo bootstrap_textarea(
												array(
													'name' => 'desc',
												  'place_holder' => 'Description/Info',
                          'value' => $desc,
                          'rows' => 5
												)
											);
                  ?>
                  
                  <?php 
                    echo bootstrap_file(
	                     array(
                        'name' => 'pic',
                        'label' => 'Upload picture',
                        'error_info' => isset($fileError) ? $fileError : ''
                       )
                    );
                  ?>
                  
                  <div class="form-group clearfix">
                      <div class="col-lg-12">
                        <button name="save" type="submit" class="btn btn-success">Save</button>
                        <?php if(!empty($menuItem)) { ?>
                        <a href="<?php echo base_url("/menu/delete/{$truck->id}/{$menuItem->id}") ?>" class="btn btn-danger pull-right">Delete</a>
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
      