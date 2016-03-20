$(function () {
    // Navbar
    $(window).scroll(function(){
        if ($(this).scrollTop() > 100) {
            $(".navbar.navbar-fixed-top").addClass("scroll");
        } else {
            $(".navbar.navbar-fixed-top").removeClass("scroll");
        }      
    });

    // scroll back to top btn
    $('.scrolltop').click(function(){
        $("html, body").animate({ scrollTop: 0 }, 700);
        return false;
    });
    
    // scroll navigation functionality
    $('.scroller').click(function(){
    	var section = $($(this).data("section"));
    	var top = section.offset().top;
        $("html, body").animate({ scrollTop: top }, 700);
        return false;
    });

    // Index project carrousel
	$('.flexslider').flexslider({
		directionNav: false,
		animation: "slide",
		controlNav: true,
		pauseOnHover: true
	});
	
	// fade out any fade-away alert msgs
	$('.fade-away').delay(5000).fadeOut(1500);
	
	// table with clickable rows handler
	$('.clickable-row').click(function(){
	  console.log($(this).data('url'));
	  window.location.href = $(this).data('url');
	});

});