<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

?>


<section class="abutcountingsec text-white" id="counter">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-12">
				<div class="cuntmin text-center" data-aos="fade-up" data-aos-duration="2000">
					<h1 class="display-4 text-theme fw-bold  mb-0"><a href="tel:12125716848">(212) 571-6848 </a>
					<h5 class="font-weight-normal text-white mb-0">Free Phone Consultation</h5>
				</div>
			</div>
		</div>
	</div>
</section>






		<section class="footer py-6">

			<div class="container">

				<div class="row">

					<div class="col-md-4" data-aos="zoom-in" data-aos-duration="1500">

						<!-- <div class="ftrlogo mb-4">
							<img src="<?php the_field('logo', 'option'); ?>" height="100" width="100"> 
						</div> -->

						<?php the_field('details', 'option'); ?>

						

					</div>

					

					<div class="col-md-5" data-aos="zoom-in" data-aos-duration="1500">

						<div class="row">

						<!-- 	<div class="col-md-6">

								<h3 class="text-white mb-3">Categories</h3>

								<?php //wp_nav_menu( array('menu' => 'Categories', 'container' => '', 'items_wrap' => '<ul class="justify-content-center">%3$s</ul>' )); ?>

							</div> -->
							
							
							<div class="col-md-6">
					    
					   <h3 class="text-white mb-3">Quick Link</h3>

								<ul>

									<?php wp_nav_menu( array('menu' => 'quick_links', 'container' => '', 'items_wrap' => '<ul class="justify-content-center">%3$s</ul>' )); ?>

								</ul>

							</div>
							
							</div>

					</div>
					
					
						<div class="col-md-3" data-aos="zoom-in" data-aos-duration="1500">

						<a href="mailto:<?php the_field('email', 'option'); ?>"><h3 class="text-white mb-3">Get In Touch</h3></a>

						<ul class="address">

							

							

<!-- 							<li><i class="fa fa-envelope"></i> <a href="mailto:<?php //the_field('email', 'option'); ?>">  <?php //the_field('email', 'option'); ?></a></li> -->

						</ul>

					<!--	<div class="socilicon text-center text-md-start mt-3">

							<a href="<?php the_field('facebook', 'option'); ?>"><i class="fa fa-facebook"></i></a>

							<a href="<?php the_field('twitter', 'option'); ?>"><i class="fa fa-twitter"></i></a>

							<a href="<?php the_field('instagram', 'option'); ?>"><i class="fa fa-instagram"></i></a>

							<a href="<?php the_field('linkedin', 'option'); ?>"><i class="fa fa-linkedin"></i></a>

						</div> -->
					
					</div>
					
                            
                           <!--
							<div class="col-md-6">

								<h3 class="text-white mb-3">Customer Care</h3>

								<ul>

									<?php wp_nav_menu( array('menu' => 'customer_care', 'container' => '', 'items_wrap' => '<ul class="justify-content-center">%3$s</ul>' )); ?>

								</ul>

							</div>-->

						</div>
					
					
					
					

					</div>
					
					
							
						

					

				

		</section>

	<!--	<section class="newltrsec text-white py-5">

			<div class="container">

				<div class="row align-items-center">

					<div class="col-md-7">

						<h3>Sign Up to Newsletter</h3>

						<p>Subscribe our newsletter gor get notification about information discount.</p>

					</div>

					<div class="col-md-5">

						<!--<form class="search" method="get" role="search">-->

						<!--	<input type="email" class="search-field form-control" placeholder="Type your email" value="" name="s" title="Search for:">-->

						<!--	<button type="submit" role="button" class="btn btn-default right">Subscribe</button>-->

						<!--</form>
						<?php echo do_shortcode('[email-subscribers-form id="1"]'); ?> --> 

					<!--</div>

				</div>

			</div>

		</section>-->

		<section class="copyright text-white bg-theme">

			<div class="container">

				<div class="row">

					<div class="col-md-6 text-center text-md-start"><p class="mb-0"><?php the_field('copyright', 'option'); ?></p></div>

				<!--	<div class="col-md-6 text-center text-md-end"><img src="<?php echo get_template_directory_uri(); ?>/images/payoption.jpg"></div> -->

				</div>

			</div>

		</section>

<a id="back-to-top" href="#" class="btn btn-light btn-lg back-to-top" role="button"><i class="bi bi-arrow-up-circle-fill" style="color: #d9765a;"></i></a>

<div class="mysidenav d-none">
	<div class="sitename">
		<div class="sidbarlogo"><a href="<?php echo get_site_url(); ?>"><img src="<?php the_field('logo', 'option'); ?>"></a></div>
	</div>
	
	<div class="clearfix"></div>

		   

           
	

<!-- 	<div class="menusociallink">
		<ul>
			<li><a href="#" target="_blank"><i class="fa fa-facebook"></i></a></li>
			<li><a href="#" target="_blank"><i class="fa fa-twitter"></i></a></li>
			<li><a href="#" target="_blank"><i class="fa fa-linkedin"></i></a></li>
			<li><a href="#" target="_blank"><i class="fa fa-instagram"></i></a></li>
		</ul>
	</div> -->
</div>
	
<nav id="menu">
		<?php wp_nav_menu( array('menu' => 'top_menu', 'container' => '', 'items_wrap' => '<ul>%3$s</ul>' )); ?>
		</nav> 


 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
<script src='<?php echo get_template_directory_uri(); ?>/assets/mmenu/mmenu-light.js' id='mmenu-main-js'></script>
<script src='<?php echo get_template_directory_uri(); ?>/assets/mmenu/mmenu-light.polyfills.js' id='mmenu-polyfill-js'></script>
<!-- 		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->

		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" type="text/javascript"></script>
	

		<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script>AOS.init();</script>

<!-- <script type="text/javascript">
function navopenCls() {
	jQuery(".mysidenav").toggleClass("navopen");
	jQuery(".mynavlayr").toggle();
	};
</script> -->
	
	<script>
		jQuery('document').ready(function(){
            jQuery('.tooltipparent').find('a').addClass('link toolTip top')
		
          });
	</script>	

<!-- 		<script type="text/javascript">

			// When the user scrolls the page, execute myFunction

		window.onscroll = function() {myFunction()};

		// Get the header

		var header = document.getElementById("myHeader");

		// Get the offset position of the navbar

		var sticky = header.offsetTop;

		// Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position

		function myFunction() {

		if (window.pageYOffset > sticky) {

		header.classList.add("sticky");

		} else {

		header.classList.remove("sticky");

		}

		}

		</script> -->





		<script type="text/javascript">

			jQuery("#brand-carousel").owlCarousel({

			autoplay: true,

			lazyLoad: true,

			loop: true,

			margin: 20 ,

			// animateOut: 'fadeOut',

			// animateIn: 'fadeIn',

			responsiveClass: true,

			autoHeight: true,

			autoplayTimeout: 7000,

			smartSpeed: 800,

			nav: false,

			responsive: {

			0: {

			items: 2

			},

			600: {

			items: 2

			},

			1024: {

			items: 5

			},

			1366: {

			items: 5

			}

			}

			});

		</script>

<script type="text/javascript">
//<![CDATA
jQuery(document).ready(function($) {
$('a').each(function() {
var a = new RegExp('/' + window.location.host + '/');
if (!a.test(this.href)) {
$(this).click(function(event) {
event.preventDefault();
event.stopPropagation();
window.open(this.href, '_blank');
});
}
});
});
//]]>
</script>
	
	
<!---------- mobile menu script ---------->
	
	
	<script>
            document.addEventListener(
                "DOMContentLoaded", () => {
                    const menu = new MmenuLight(
                        document.querySelector( "#menu" ),
                        "(max-width: 768px)"
                    );

                    const navigator = menu.navigation();
                    const drawer = menu.offcanvas();

                    document.querySelector( "a[href='#menu']" )
                        .addEventListener( "click", ( evnt ) => {
                            evnt.preventDefault();
                            drawer.open();
                        });
                }
            );
        </script>

	<script>
		$(document).ready(function(){
	$(window).scroll(function () {
			if ($(this).scrollTop() > 50) {
				$('#back-to-top').fadeIn();
			} else {
				$('#back-to-top').fadeOut();
			}
		});
		// scroll body to 0px on click
		$('#back-to-top').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 400);
			return false;
		});
});
	</script>
	

	
	
<script>
	$('#searchsubmit').replaceWith('<button class="search-submit" type="submit"><i class="fa fa-search"></i></button>')
</script>
	
	
<?php wp_footer(); ?>

	
	<script> 
	
		jQuery(document).ready(function(){
			
			 $('.dscf7_refresh_captcha').attr('href', '#').attr('target', '_self').css('display','none');
			
			$(".dscf7_refresh_captcha").next().css('display','none');
			$(".cf7as-firstAct:eq(1)").first()[0].nextSibling.textContent=" = ";
// 			$(".cf7as-firstAct:eq(1)").first()[0].nextSibling.css('font-size','20px');
			$(".dscf7captcha > input:eq(2)").first()[0].nextSibling.textContent="";
			$(".dscf7captcha > input:first").before("<div class='humans-checker'>Are You Human ?</div>");
			
			
// 			var $targetElement = $(".dscf7_refresh_captcha");
	        var $targetElement = $('.wpcf7-form .col-md-6.capfrm .mb-3 .dscf7captcha .wpcf7-form-control-wrap input');

    // Create the <div> element you want to add
    var $divElement = $("<a href='#' id='reloads'> Reload </a>");

    // Use the after() method to add the <div> element next to the target element
    $targetElement.after($divElement);
			
			$('#reloads').click(function(event){
				
				event.preventDefault();
				
				// Generate a random number between 1 and 99
var randomNumber1 = Math.floor(Math.random() * 10);
var randomNumber2 = Math.floor(Math.random() * 10);

// 				Commercial Transactions
				
				$(".cf7as-firstAct:first").text(randomNumber1);
$(".cf7as-firstAct:eq(1)").text(" "+randomNumber2);
				$(".dscf7captcha > input:first").val(randomNumber1);
				$(".dscf7captcha > input:eq(1)").val(randomNumber2);
				
				// 				For Random Signs

 var signs = ["+", "x"];
                
                // Generate a random index to select a sign from the array
                var randomIndex = Math.floor(Math.random() * signs.length);
                
                // Get the random sign
                var randomSign = signs[randomIndex];

var plusSignNode = $(".cf7as-firstAct").first()[0].nextSibling;

				console.log(plusSignNode , randomSign);
                // Replace the content of the text node with "-"
                plusSignNode.textContent = " "+randomSign;
				$(".dscf7captcha > input:eq(2)").val(randomSign);
// 				plusSignNode.html("-");
				
			});
			
			
			$(".wpcf7-form").submit(function(event){
				
				var randomNumber1 = Math.floor(Math.random() * 10);
var randomNumber2 = Math.floor(Math.random() * 10);

// 				Commercial Transactions
				
				$(".cf7as-firstAct:first").text(randomNumber1);
$(".cf7as-firstAct:eq(1)").text(" "+randomNumber2);
				$(".dscf7captcha > input:first").val(randomNumber1);
				$(".dscf7captcha > input:eq(1)").val(randomNumber2);
				
				
				// 				For Random Signs

 var signs = ["+", "x"];
                
                // Generate a random index to select a sign from the array
                var randomIndex = Math.floor(Math.random() * signs.length);
                
                // Get the random sign
                var randomSign = signs[randomIndex];

var plusSignNode = $(".cf7as-firstAct").first()[0].nextSibling;

				console.log(plusSignNode , randomSign);
                // Replace the content of the text node with "-"
                plusSignNode.textContent = " "+randomSign;
				$(".dscf7captcha > input:eq(2)").val(randomSign);
// 				plusSignNode.html("-");
			});
			
		});
		
	</script>
	
</body>
</html>
