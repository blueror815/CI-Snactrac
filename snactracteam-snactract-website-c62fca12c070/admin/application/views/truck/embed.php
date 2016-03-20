
<style>
.scrollable-nav {
    max-height: 230px;
    overflow: hidden;
    overflow-y: auto;
}
</style>

<div class="col-md-9 main">
  
    <div class="row">
    
      <div class="col-md-4">
        <div id="mini-map">
        </div>
      </div>
      
      <?php if(!empty($schedules)) { ?>
      <div class="col-md-4 scrollable-nav">
        <ul class="nav nav-pills nav-stacked">
          <?php
            foreach($schedules as $schedule){
              $active = '';
              if(!empty($selected) and $selected->schedule_id == $schedule->schedule_id){
                  $active = ' class="active"';
              }
          ?>
            <li<?php echo $active ?>>
              <a href="<?php echo base_url("truck/embed/{$schedule->truck_id}/{$schedule->schedule_id}") ?>">
                <?php echo schedule_to_date($schedule) ?>
                <h5><?php echo $controller->location->summary($schedule) ?></h5>
              </a>
            </li>
          <?php } ?>
        </ul>
      </div>
      <?php } ?>
      
    </div>
    
</div>
      
    