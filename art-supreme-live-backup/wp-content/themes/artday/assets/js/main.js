(function ($, window, document, undefined) {
    'use strict';

	jQuery(document).ready(function() {

    //Sticky Navbar
    jQuery(document).ready(function(){
      jQuery(".ws-header-fourth").sticky({topSpacing:0});
    });
	
    // Parallax Background
    jQuery('.parallax-window').parallax;

    // Owl Carousel
    jQuery("#ws-items-carousel").owlCarousel({
      items :4,
      navigation : true
    });

    // Page Loader
    jQuery("#preloader").delay(500).fadeOut(500);

    // Scroll Reveal
    window.sr = new scrollReveal();
	
	//Navbar Scroll         
	jQuery('.navbar a').bind('click', function(event) {
		var jQueryanchor = jQuery(this);
		var url=jQueryanchor.attr('href');
		var hash = url.substring(url.indexOf('#'));
		if(jQuery(hash).length)
		{
			jQuery('html, body').stop().animate({
				scrollTop: jQuery(hash).offset().top - 70
			}, 1000);
			event.preventDefault();
		}
	});

  });

})(jQuery, window, document);
