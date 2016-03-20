
        
        <!-- MAIN RIGHT SIDE DASHBOARD AREA START -->
        <!-- <div class="col-sm-9 col-md-10main"> -->
        <div class="col-sm-9 col-md-10 main">
          <h1 class="page-header">Dashboard</h1>

          <div class="row placeholders">
            <div class="col-xs-6 col-sm-3 placeholder">
              <img data-src="holder.js/150x150/auto/sky" class="img-responsive" alt="Daily Active Users">
              <h4>Daily</h4>
              <span class="text-muted">Daily Active Users</span>
            </div>
            <div class="col-xs-6 col-sm-3 placeholder">
              <img data-src="holder.js/150x150/auto/vine" class="img-responsive" alt="Monthly Active Users">
              <h4>Monthly</h4>
              <span class="text-muted">Monthly Active Users</span>
            </div>
          </div>
          
          <h2 class="sub-header">System Status</h2>
          <div class="row">
            <div class="panel panel-success">
              <div class="panel-body">
                <img class="status-img pull-left" src="<?php echo assets_url('/images/tick.png')?>"/> 
                All systems are running as normal.
              </div>
            </div>
          </div>
          
          <h2 class="sub-header">System Alerts</h2>
          <div class="row">
            <div class="panel panel-success">
              <div class="panel-body">
                <img class="status-img pull-left" src="<?php echo assets_url('/images/alert.png')?>"/> 
                Database backup completed 5 hours and 2 minutes ago.<br/>
                55 new errors in apache error_log in the last one hour.
              </div>
            </div>
          </div>
          
          <h2 class="sub-header">Pending Updates</h2>
          <div class="row">
            <div class="panel panel-success">
              <div class="panel-body">
                <img class="status-img pull-left" src="<?php echo assets_url('/images/download.png')?>"/> 
                MySQL 5.1.2 is available.<br/>
                PHP 5.3.2 is available.
              </div>
            </div>
          </div>
           
        </div>
        
      </div>
    </div>
