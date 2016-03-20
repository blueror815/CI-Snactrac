    <div id="dashboard" class="container-fluid">
      <div class="row">
      
        <!-- LEFT SIDDE SEARCH RESULTS NAV -->
        <div class="col-md-3 sidebar">
          <ul class="nav nav-sidebar">
          <li>
          <a href="<?php echo base_url("/welcome/index/search/{$query}") ?>">
            <div class="panel panel-default">
              <div class="panel-body">
                <i class="icon-chevron-left"></i><i class="icon-chevron-left"></i>&nbsp;BACK
              </div>
            </div>
          </a>
          </li>
          <?php
          foreach($results as $result){
            $class = '';
            if(!empty($selected) and $selected == $result->schedule_id){
              $class = ' class="active"';
            } 
          ?>
          <li <?php echo $class?>>
            <a href="<?php echo base_url("/search/details/{$result->truck_id}/{$result->schedule_id}")?>">
              <table>
                <tr>
                  <td rowspan="2"><div class="circle-icon"><img class="circle-icon shadow" src="<?php echo $result->image ?>"/></div></td>
                  <td class="search-result-info" width="80%"><?php echo $result->name ?></td>
                  <td class="search-result-info" width="20%"><?php echo $result->distance?>mi</td>
                </tr>
                <tr>
                  <td class="search-result-info" colspan="2"><?php echo $result->street ?> <?php echo $result->city ?></td>
                </tr>
              </table>
            </a>
          </li>
          <?php
          }
          ?>
          </ul>
        </div>