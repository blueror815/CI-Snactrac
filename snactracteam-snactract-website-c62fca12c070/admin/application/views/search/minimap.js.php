
/**
 * Loads the map and markers
 */
$(document).ready(function () {
  console.log("entering document.ready in minimap.js.php");

  // create the map object
  var map = new google.maps.Map(
    document.getElementById('mini-map'),
    {
      center: new google.maps.LatLng(<?php echo $center['latitude']?>, <?php echo $center['longitude'] ?>),
      zoom: <?php echo $zoom ?>,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
  );
  
  // add a marker on center
  /*var marker_center = new google.maps.Marker({
    position: new google.maps.LatLng(
      <?php echo $center['latitude'] ?>,
      <?php echo $center['longitude'] ?>),
    icon: "<?php echo assets_url("images/map-here.png")?>",
    map: map
  });*/
  
  // truck markers stat here
  var markerOptions;
  var infoWindowOptions;
  var location;
  var points = [];
  var point = null;

  var marker_<?php echo $truck->id ?> =
     new google.maps.Marker({
        position: new google.maps.LatLng(
          <?php echo $schedule->latitude ?>,
          <?php echo $schedule->longitude ?>
        ),
        animation: google.maps.Animation.DROP,
        map: map
        //icon: '<?php /*echo $result->image*/ ?>'
    });
      
    // setup info window
    <?php
      $title = "<div><h4>{$truck->name}</h4><h5>{$schedule->street}</h5></div>";
      $start_time = date("g:ia", mktime($schedule->start_time_hour, $schedule->start_time_min));
      $end_time = date("g:ia", mktime($schedule->end_time_hour, $schedule->end_time_min));
      $timespan = "<div>Open: {$start_time} to {$end_time}</div>";
  
      /*$menu = '';
      if(!empty($result->menu)){
        $menu = "<div><h4>Menu:</h4><ul>";
        foreach($result->menu as $menuItem){
          $menu .= "<li>{$menuItem}</li>";
        }
        $menu .= "</ul></div>";
      }*/
      
      $keywords = '';
      if(!empty($truck->keywords)){
        $keywords = "<div>{$truck->keywords}</div>";
      }
    ?>
  
    var infoWindow_<?php echo $truck->id ?> = 
      new google.maps.InfoWindow({
        content: '<?php echo "<div style=\"overflow:hidden;white-space: nowrap;\">{$title} {$keywords} {$timespan}</div>" ?>'
      });
    
    // setup info window listener
    google.maps.event.addListener(
      marker_<?php echo $truck->id ?>,
      'click',
      function(e){
        infoWindow_<?php echo $truck->id ?>.open(map, marker_<?php echo $truck->id ?>);
      }
    );
});
