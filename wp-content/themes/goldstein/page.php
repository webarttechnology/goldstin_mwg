<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();
?>


<div class="bodyContent">
	<div class="container">
		<div class="row">
			<div class="col-md-9">
			<?php
		/* Start the Loop */
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/content/content-page' );

			// If comments are open or there is at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		endwhile; // End of the loop.
		?>
			</div>
			<div class="col-md-3"><?php get_sidebar(); ?></div>
		</div>
	</div>
</div>





<?php
get_footer();
