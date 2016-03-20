
        <!-- <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">  -->
        <div class="col-sm-5 col-md-6 main">
        
          <h1 class="page-header"><?php echo (empty($truck)? 'New Truck' : $truck->name) ?></h1>
          
            <div class="form">
             		<?php
		        		  // show any error msgs or alerts from server
		        			if(!empty($alert)){
  								  echo bootstrap_alert($alert);
									} 
        		    ?>
                <div>
                <pre><a id="copy-embed-code-to-clipboard" class="btn btn-default pull-right"><i class="icon-copy"></i></a>
<?php echo htmlspecialchars($code) ?>
                </pre>
                </div>
                                                    
                  <div class="form-group clearfix">
                      <div class="col-lg-12">
                        <a href="<?php echo base_url("/truck/index/{$truck->id}") ?>" class="btn btn-success">Done</a>
                      </div>
                      <h1>&nbsp;</h1>
                      <h1>&nbsp;</h1>
                      <h1>&nbsp;</h1>
                      <h1>&nbsp;</h1>
                   </div>
                  
              </div> <!-- form -->

            </div> <!-- main -->
          
          </div> <!-- row -->
          
      </div> <!-- container fluid -->
      