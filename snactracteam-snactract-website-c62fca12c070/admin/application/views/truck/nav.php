    <div id="dashboard" class="container-fluid">
      <div class="row">
      
        <!-- LEFT SIDE NAV -->
        <div class="col-md-3 sidebar">
        <h1>&nbsp;</h1>
        <ul class="nav nav-sidebar">
          <?php
          foreach($schedules as $schedule){
            $class = '';
            if(!empty($selected) and $selected->schedule_id == $schedule->schedule_id){
              $class = ' class="active"';
            } 
          ?>
          <li <?php echo $class?>>
            <a href="<?php echo base_url("/truck/view/{$schedule->truck_id}/{$schedule->schedule_id}")?>">
              <table>
                <tr>
                  <td class="search-result-info" width="80%">
                    <strong><?php echo schedule_to_date($schedule) ?></strong>
                  </td>
                </tr>
                <tr>
                  <td class="search-result-info" colspan="2">
                    <small><?php echo $controller->location->summary($schedule) ?></small>
                  </td>
                </tr>
                <!-- <tr>
                  <td class="search-result-info" width="20%">
                    <small>
                      <?php echo schedule_format_time($schedule->start_time_hour, $schedule->start_time_min)?> to <?php echo schedule_format_time($schedule->end_time_hour, $schedule->end_time_min) ?>
                    </small>
                  </td>
                </tr> -->
              </table>
            </a>
          </li>
          <?php
          }
          ?>
          </ul>
        </div>