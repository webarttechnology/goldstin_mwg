<?php
/**Template Name: Terms
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

<div class="innrbnr">
	<div class="container">
		<div class="image-caption">
			<h1 class="display-4 text-theme animated fadeInLeftBig" style="animation-delay: .8s;"><?php the_field('title'); ?></h1>
			<p class="animated fadeInRightBig text-center text-white d-none d-md-block" style="animation-delay: .8s;"><?php the_field('sub_title'); ?></p>
		</div>
	</div>
</div>


<section class="termsec text-white">
	<div class="container">
		<h1 class="text-center display-4 mb-5">Terms & <span class="d-block text-theme display-3">Condition</span></h1>

		<div class="policybox" data-aos="zoom-in" data-aos-duration="2000">

			<h3 class="text-theme my-3">Our Policy</h3>
			<p><?php the_content();?></p>

			<ul>
				 <?php the_field('points'); ?>
			</ul>
            
            <h3 class="text-theme my-3">Our Policy</h3>
			<p><?php the_field('policy_contents'); ?></p>
		</div>
	</div>
</section>

<?php
get_footer();