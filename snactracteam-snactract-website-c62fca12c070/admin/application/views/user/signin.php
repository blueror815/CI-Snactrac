
<section id="box-sign">
    <div class="container">
        <div class="row">
            <div class="box">
                <div class="head">
                    <h6 style="font-weight: bold">Log in to your account</h6>
                </div>
               
                <!-- 
                <div class="social">
                    <a class="face-login" href="#">
                        <i class="icon-facebook"></i>
                        <span class="text">Sign in with Facebook</span>
                    </a>
                    <div class="division">
                        <hr>
                        <span>or</span>
                        <hr>
                    </div>
                </div>
								-->
								
                <div class="form" >
             		<?php
		        		  // show any error msgs or alerts from server
		        			if(!empty($alert)){
								echo bootstrap_alert($alert);
							} 
        		    ?>
                
                    <form role="form" id="signin-form" accept-charset="utf-8" method="post" action="<?php echo base_url('/user/signin') ?>">
                        <div class="form-group clearfix ">
                            <div class="col-lg-12">
                            <!-- <label class="sr-only" for="email">Email</label> -->
                                <div class="input-group">
                                    <input id="email" class="form-control" type="email" placeholder="Email" value="" name="email">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group clearfix ">
                            <div class="col-lg-12">
                                <!-- <label class="sr-only" for="password">Password</label> -->
                                <div class="input-group">
                                    <input id="password" class="form-control" type="password" placeholder="Password" value="" name="password">
                                </div>
                            </div>
                        </div>

                        <div class="forgot">
                            <a class="forgot" href="<?php echo base_url('/user/forgot') ?>">Forgot password?</a>
                        </div>

                        <div class="form-group clearfix">
                            <div class="col-lg-12">
                                <button class="btn btn-success" type="submit">Sign In</button>
                            </div>
                        </div>

                    </form>


                </div>
                
            </div>
            <p class="already">Don't have an account?<a href="<?php echo base_url('/user/signup') ?>"><strog>Sign up</strog></a></p>
        </div>
    </div>
  </section>
  