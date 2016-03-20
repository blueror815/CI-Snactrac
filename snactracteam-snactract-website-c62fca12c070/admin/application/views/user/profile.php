        <div class="content-wrapper" style="min-height: 915px;">
            <section class="content-header" style="text-align: center" id="business-area">
              <h1 style="padding-top: 20px;">
                  User Information
              </h1>
              <ol class="breadcrumb">
                  <li><a href="<?php echo base_url('business/dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                  <li class="active">Profile</li>
              </ol>
            </section>

            <section class="content" id="business-area">

                <div class="main" style="text-align: center">
                  
                    <div class="form profile-form">

                        <?php
                          // show any error msgs or alerts from server
                          // $mode is used to figure out which form was submitted
                            if(!empty($alert)){
                                if(empty($mode) or $mode == 'basic'){
                                    echo bootstrap_alert($alert);
                                }
                            } 
                        ?>

                        <form id="profile-form" role="form" accept-charset="utf-8" method="post" action="<?php echo base_url('user/profile/basic'); ?>">
                            <div class="form-group clearfix ">
                                <div class="col-lg-12">
                                    <label class="profile-label" for="name">Name</label>
                                    <div class="input-group col-lg-12 col-md-12 col-sg-12 col-xs-12">
                                        <input id="name" class="form-control" type="text" placeholder="Name" value="<?php echo $userInfo->name;?>" name="name">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group clearfix ">
                                <div class="col-lg-12">
                                    <label class="profile-label" for="email">Email</label>
                                    <div class="input-group col-lg-12 col-md-12 col-sg-12 col-xs-12">
                                        <input id="email" class="form-control" type="email" placeholder="Email" value="<?php echo($userInfo->email); ?>" name="email">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group clearfix ">
                                <div class="col-lg-12">
                                    <label class="profile-label" for="email">Password</label>
                                    <div class="input-group col-lg-12 col-md-12 col-sg-12 col-xs-12">
                                        <input id="password" class="form-control" type="password" placeholder="Password" value="********" name="password">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group clearfix ">
                                <div class="col-lg-12">
                                    <label class="profile-label" for="confirm_password">Confirm Password</label>
                                    <div class="input-group col-lg-12  col-md-12 col-sg-12 col-xs-12">
                                        <input id="confirm_password" class="form-control" type="password" placeholder="Confirm Password" value="********" name="confirm_password">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group clearfix ">
                                <div class="col-lg-12">
                                    <label class="profile-label" for="phone">Phone number</label>
                                    <div class="input-group col-lg-12  col-md-12 col-sg-12 col-xs-12">
                                        <input id="phone" class="form-control" type="text" data-inputmask='"mask": "(999) 999-9999"' data-mask placeholder="Phone Number" value="<?php echo($userInfo->phone);?>" name="phone">
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
