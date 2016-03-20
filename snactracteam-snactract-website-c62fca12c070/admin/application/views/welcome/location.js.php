/**
 * Loads the map and markers
 */
$(document).ready(function () {
  console.log("entering document.ready in welcome.js.php");

  if(geo_position_js.init()){
    // if we already know the location from a previous invocation then dont fetch it again.
    if(!$('#latitude').val() || !$('#longitude').val()){
      geo_position_js.getCurrentPosition(
      
        // success
        function(p){
          var latitude = p.coords.latitude.toFixed(8);
          var longitude = p.coords.longitude.toFixed(8)
          console.log('location success: lat=' + latitude + ', longitude=' + longitude);
          $('#latitude').val(latitude);
          $('#longitude').val(longitude);
          $('#location').val('Near my location');
        },
        
        //failure
        function(p){
          console.log('location error='+p.message);
        },
        
        // options
        {
          enableHighAccuracy:true
        }
      );
    }
  }
  else{
    console.log("location functionality not available");
  }
});
