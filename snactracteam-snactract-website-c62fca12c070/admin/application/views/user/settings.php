        <div class="content-wrapper" style="min-height: 820px;">
            
            <section class="content-header" style="text-align: center" id="business-area">                    
                <h1> Settings </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url('business/dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Settings</li>
                </ol>
            </section>

            <section class="content" id="business-area">
                <div class="main" style="text-align: center">
                    <div class="form settings-form">
                   		<?php
      		        		  // show any error msgs or alerts from server
    	        			if(!empty($alert)){
                                if(empty($mode) or $mode == 'settings'){
        				            echo bootstrap_alert($alert);
    				            }
    						} 
              		    ?>

                        <form id="settings-form" role="form" accept-charset="utf-8" method="post" action="<?php echo base_url('user/profile/settings'); ?>">
                            <div class="remember">
                                <div class="checkbox col-lg-12 col-md-12 col-sg-12 col-xs-12">
                                    <label>
                                        <input type="checkbox" checked="checked" value="marketing" name="marketing_subscription">
                                        Occasionally send me emails about products and services.
                                    </label>
                                </div>
                            </div>

                            <div class="remember">
                                <div class="checkbox col-lg-12 col-md-12 col-sg-12 col-xs-12">
                                    <label>
                                        <input type="checkbox" checked="checked" value="account" name="account_notifications">
                                        Alert me about changes to my account.
                                    </label>
                                </div>
                            </div>

                            <div class="remember ">
                                <div class="checkbox col-lg-12 col-md-12 col-sg-12 col-xs-12">
                                    <label>
                                        <input type="checkbox" checked="checked" value="third_party" name="share_third_party">
                                        We may share your annonymized information with third parties.
                                    </label>
                                </div>
                            </div>

                            <div class="form-group clearfix">
                                <div class="col-lg-12">
                                    <button class="btn btn-success" type="submit" style="background-color: #F6800B; border-color: #F6800B; width: 100px;">SAVE</button>
                                </div>
                                <h1> </h1>
                            </div>
                        </form> 
                    </div>                         
                </div>
            </section>
        </div>