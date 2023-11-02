// Mobile Nav

function navopenCls() {
jQuery(".mysidenav").toggleClass("navopen");
jQuery(".mynavlayr").toggle();
};



// Team Owl Carousel
	jQuery("#arrivalcarousel").owlCarousel({
		autoplay: true,
		lazyLoad: true,
		loop: true,
		margin: 20,
		// animateOut: 'fadeOut',
		// animateIn: 'fadeIn',
		responsiveClass: true,
		autoHeight: true,
		autoplayTimeout: 7000,
		smartSpeed: 800,
		nav: true,
		responsive: {
			0: {
				items: 1
			},
			600: {
				items: 2
			},
			1024: {
				items:2
			},
			1366: {
				items: 4
			}
		}
	});