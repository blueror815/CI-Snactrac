
    <!-- MAIN RIGHT SIDE AREA -->
        <!-- <div class="col-sm-9 col-md-10 main"> -->
        <div class="col-md-9 main">
          <h2 class="page-header"><?php echo $truck->name ?></h2>
          <div class="row">
          
            <div class="col-md-4 text-center">
              <div>
                <img class="circle-icon shadow" src="<?php echo $truck->image ?>"/>
              </div>

              <h3><?php echo schedule_to_date($schedule); //date('l, j M Y') ?></h3>
              <h4><?php echo $controller->location->summary($schedule)?></h4>
              <h4><?php echo schedule_format_time($schedule->start_time_hour, $schedule->start_time_min)?> to <?php echo schedule_format_time($schedule->end_time_hour, $schedule->end_time_min)?></h4>
              <a class="btn btn-default" href="<?php echo base_url("/schedule/track/{$truck->id}/{$schedule->schedule_id}") ?>">&nbsp;Track This&nbsp;</a>
              <hr/>
              <h4><?php echo $business->phone ?></h4>
              <h4><a href="<?php echo $business->website ?>"><?php echo preg_replace("/http:\/\/w*\.*/", "", $business->website) ?></a></h4>
              <div class="socail-icons-todo">
                <?php if(!empty($business->facebook)){?>
                  <a href=""><img width="32" height="32" src="<?php echo assets_url("images/facebook.png")?>"/></a>
                <?php
                }
                if(!empty($business->twitter)){
                ?>
                  <a href="<?php echo $business->twitter ?>"><img width="32" height="32" src="<?php echo assets_url("images/twitter.png")?>"/></a>
                <?php 
                }
                if(!empty($business->gplus)){
                ?>
                  <a href=""><img width="32" height="32" src="<?php echo assets_url("images/gplus.png")?>"/></a>
                <?php 
                }
                if(!empty($truck->buzz['yelp'])) {
                ?>
                  <a href="<?php echo $truck->buzz['yelp']->url ?>"><img width="32" height="32" src="<?php echo assets_url("images/yelp.png")?>"/></a>
                <?php 
                }
                ?>
              </div>
              <hr/>
            </div>
            
            <div class="col-md-4 text-center">
              <div>
                <img src="<?php echo assets_url('/images/map-icon.png') ?>"/>
              </div>
              <br/>
              <div id="mini-map">
              </div>
            </div>
            
            <div class="col-md-4 text-center">
              <div>
                <img src="<?php echo assets_url('/images/buzz.png') ?>"/>
              </div>
              <br/>
              <?php if(!empty($truck->buzz['yelp'])) { ?>
              <div class="text-left">
                <div>
                  <a href="http://yelp.com"><img src="<?php echo assets_url('/images/yelp-review.png') ?>" /></a>
                </div>
                <div class="">
                  <a href="<?php echo $truck->buzz['yelp']->url ?>"><img src="<?php echo $truck->buzz['yelp']->rating_img_url ?>" /></a> (<?php echo $truck->buzz['yelp']->review_count ?>)
                </div>
                <div>
                 <?php echo $truck->buzz['yelp']->snippet_text?>
                </div>
                <hr/>
              </div>
              <?php } ?>
              
            </div>
            
          </div>
          
          <div id="menu-images" class="row">
          <?php
            foreach($menuItems as $menuItem){
              foreach(array('png', 'jpg') as $ext){
                if(file_exists(MENU_IMAGE_BASE_PATH . "{$menuItem->id}.{$ext}")){
                  echo "<div class=\"panel panel-default\"><img height=\"150\" src=\"" . base_url("images/menu/{$menuItem->id}.{$ext}") . "\"></div> \n";
                  break;
                }
              }
            }
          ?>
          </div>
          <h1>&nbsp;</h1>
      </div>
    </div>