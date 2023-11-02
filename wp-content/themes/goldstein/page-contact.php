<?php
/**Template Name: Contact Us
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since Twenty Nineteen 1.0
 */

get_header();
?>


<section class="contctdtlssec text-center text-white d-none">
	<div class="container">
		<div class="row">
			<div class="col-12 col-md-4" data-aos="fade-up" data-aos-duration="2000">
				<h1 class="display-4 text-theme">Address</h1>
				<p><?php the_field('address', 'option'); ?></p>
			</div>
			<div class="col-12 col-md-4" data-aos="fade-up" data-aos-duration="2000">
				<h1 class="display-4 text-theme">Email</h1>
				<a href="mailto:<?php the_field('email', 'option'); ?>"><?php the_field('email', 'option'); ?></a>
			</div>
			<div class="col-12 col-md-4" data-aos="fade-up" data-aos-duration="2000">
				<h1 class="display-4 text-theme">Call us</h1>
				<a href="tel:<?php the_field('phone', 'option'); ?>"> <?php the_field('phone', 'option'); ?></a>
			</div>
		</div>
	</div>
</section>


<section class="contfrm text-white">

		<div class="container">

			<h3 class="text-center display-4 mb-3 text-theme" data-aos="zoom-in" data-aos-duration="2000">Contact Us</h3>
             <?php echo do_shortcode('[contact-form-7 id="170" title="Contact form"]'); ?>
		</div>

	</section>

	<section class="cmap d-none">

		<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3684.2048467614577!2d88.43246121491087!3d22.57144058518346!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a0275e692ae3a41%3A0x3d6fb2a77627221c!2sWebart%20Technology!5e0!3m2!1sen!2sin!4v1619431379320!5m2!1sen!2sin"  allowfullscreen="" loading="lazy"></iframe>

	</section>
	
<?php
get_footer();