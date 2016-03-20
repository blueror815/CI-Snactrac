$(function() {

	$("ul.homepage-nav > li").click(function(e) {
		$("ul.homepage-nav > li").each(function () {
			li_actived = $(this);
			$(this).removeClass("active");
		});
		li_active = $(this);
		$(this).addClass('active');
	});

})