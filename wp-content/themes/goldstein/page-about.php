<?php
/**Template Name: About
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
			<h1 class="display-4 text-theme animated fadeInLeftBig" style="animation-delay: .8s;"><?php the_field('title_1'); ?></h1>
			<p class="animated fadeInRightBig text-center text-white d-none d-md-block" style="animation-delay: .8s;"><?php the_field('contents_1'); ?></p>
		</div>
	</div>
</div>
<section class="abtsec text-white">
	<div class="container">
		<div class="row">
			<div class="col-12 col-md-6">
				<div class="abtimg" data-aos="zoom-in" data-aos-duration="2000">
					<img src="<?php the_field('image'); ?>" class="img-fluid">
				</div>
			</div>
			<div class="col-12 col-md-6">
				<h1 class="display-5 font-weight-normal"><?php the_field('about_title_1'); ?> <span class="d-block display-3 font-weight-bold  text-theme"><?php the_field('about_title_2'); ?></span></h1>
				<p><?php the_field('about_contents'); ?></p>
			</div>
		</div>
	</div>
</section>
<section class="abutcountingsec text-white" id="counter">
	<div class="container">
		<div class="row">
			<div class="col-md-3 col-6">
				<div class="cuntmin text-center" data-aos="fade-up" data-aos-duration="2000">
					<h1 class="display-4 text-theme fw-bold  mb-0 count" data-count="260"><?php the_field('1'); ?></h1><h5 class="font-weight-normal text-white mb-0"><?php the_field('text_1'); ?></h5>
				</div>
			</div>
			<div class="col-md-3 col-6">
				<div class="cuntmin text-center" data-aos="fade-up" data-aos-duration="2000">
					<h1 class="display-4 text-theme fw-bold  mb-0 count" data-count="980"><?php the_field('2'); ?></h1><h5 class="font-weight-normal text-white mb-0"><?php the_field('text_2'); ?></h5>
				</div>
			</div>
			<div class="col-md-3 col-6">
				<div class="cuntmin text-center" data-aos="fade-up" data-aos-duration="2000">
					<h1 class="display-4 text-theme fw-bold mb-0 count" data-count="565"><?php the_field('3'); ?></h1><h5 class="font-weight-normal text-white mb-0"><?php the_field('text_3'); ?></h5>
				</div>
			</div>
			<div class="col-md-3 col-6">
				<div class="cuntmin text-center" data-aos="fade-up" data-aos-duration="2000">
					<h1 class="display-4 text-theme fw-bold mb-0 count" data-count="825"><?php the_field('4'); ?></h1><h5 class="font-weight-normal text-white mb-0"><?php the_field('text_4'); ?></h5>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="abtvisionsec text-center text-white">
	<div class="container">
		<h1 class="display-3 text-uppercase"><?php the_field('mission_title_1'); ?> <span class="text-theme"><?php the_field('mission_title_2'); ?></span> </h1>
		<p><?php the_field('mission_contents'); ?></p>
	</div>
</section>
<section class="abtexclusivesec">
	<ul>
		<?php 
						$wcatTerms = get_terms('product_cat', array('hide_empty' => 0, 'orderby' => 'ASC', 'parent' => 0, 'number' => 4, 'exclude' => 15)); 
						$key = 0;
						foreach($wcatTerms as $wcatTerm) : 
						$wthumbnail_id = get_woocommerce_term_meta( $wcatTerm->term_id, 'thumbnail_id', true );
						$wimage = wp_get_attachment_url( $wthumbnail_id );
						
						
    				?>
		
		<li>
			<a href="#">
				<div class="image"><img src="<?php echo $wimage?>" class="img-fluid">
					<div class="content">
						<p><?php echo $wcatTerm->name;?></p>
						<a href="<?php echo get_term_link( $wcatTerm->slug, $wcatTerm->taxonomy ); ?>" class="buynowbtn">Buy Now</a>
					</div>
				</div>
				
			</a>
		</li>
		<?php $key++; endforeach; wp_reset_query(); ?>
	</ul>
</section>

<?php
get_footer();