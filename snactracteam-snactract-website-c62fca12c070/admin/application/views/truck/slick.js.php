$(document).ready(function () {
  //console.log("entering document.ready in truck/slick.js.php");
  $('#menu-images').slick({
    infinite: false,
    variableWidth: true,
    slidesToShow: 4,
    responsive: [
     {
      breakpoint: 1024,
      settings: {
        slidesToShow: 4,
      }
     },
     {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
      }
     },
     {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
     }
   ]
  });  
});