        <!-- MAIN RIGHT SIDE AREA START -->
        <div class="col-sm-9 col-md-10 main">
          <h1 class="page-header"><?php echo $truck->name?></h1>
       		<?php
      		  // show any error msgs or alerts from server
      			if(!empty($alert)){
						  echo bootstrap_alert($alert);
						} 
  		    ?>
    
          <div class="col-sm-4 col-md-4 pull-right">
           <?php
            	echo form_open(
  							"/truck/menu/{$truck->id}",
  							array(
  								'role' => 'search',
                  'class' => 'navbar-form navbar-left'
  							)
  						);
            ?>
            
            <?php
              echo bootstrap_navbar_input(
                array(
                  'type' => 'text',
                  'name' => 'filter',
                  'place_holder' => 'Filter',
                  'glyphicon' => 'glyphicon-search',
                  'btn' => 'btn-success'
                )	
              ); 
            ?>
        
          <?php echo form_close(); ?>
          </div>
          
          <a class="btn btn-primary" href="<?php echo base_url("menu/index/{$truck->id}"); ?>">Add Menu Item</a>
                  
          <h2 class="sub-header">Truck Menu</h2>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Price</th>
                  <th>Keywords</th>
                </tr>
              </thead>
              <tbody>
              <?php 
              foreach($menuItems as $menuItem) {
              ?>
                <tr class="clickable-row" data-url="<?php echo base_url("/menu/index/{$truck->id}/{$menuItem->id}")?>">
                  <td><?php echo $menuItem->id ?></td>
                  <td><?php echo $menuItem->name ?></td>
                  <td><?php echo format_limit_string($menuItem->description) ?></td>
                  <td><?php echo format_currency($menuItem->price) ?></td>
                  <td><?php echo format_limit_string($menuItem->keywords) ?></td>
                </tr>
                <?php 
                }
                ?>
              </tbody>
            </table>
            <h1>&nbsp;</h1>
            <h1>&nbsp;</h1>
            <h1>&nbsp;</h1>
          </div>
        </div>
      </div>
    </div>