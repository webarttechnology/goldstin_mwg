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
<div class="innrbnr">
    <div class="container">
        <div class="image-caption d-none d-md-block">
            <h1 class="display-4 text-theme animated fadeInLeftBig" style="animation-delay: .8s;"><?php the_title(); ?></h1>
            <!--<p class="animated fadeInRightBig text-center text-white" style="animation-delay: .8s;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris placerat efficitur odio, et efficitur leo sollicitudin vel. Mauris, quis placerat ante sollicitudin eget.</p>-->
        </div>
    </div>
</div>

<section class="blogdtls text-white">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="blogdtlsimg">
					<?php if (has_post_thumbnail( $post->ID ) ): ?>
					<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
					<img src="<?php echo $image[0]; ?>" class="img-fluid">
					<?php endif; ?>
				</div>
				<div class="blogdtlscntnt">
					<h1 class="mb-4"><?php the_title(); ?></h1>
					<div class="d-flex justify-content-start">
						<p class="me-3"><i class="fa fa-calendar"></i> <?php echo get_the_date(); ?></p>
					</div>
					<?php the_content(); ?>
				</div>
			</div>
			<div class="col-md-12">
				<h2>Related Blogs</h2>
				<div class="row">
					<?php
					//$post_type = get_post_type( $post->ID );
					$category = get_category( get_query_var( 'cat' ) );
					//$cat_name = get_cat_name( $category->cat_ID );
					$related = get_posts( array( 'post_type' => get_post_type( $post->ID ), 'category__in' => wp_get_post_categories($post->ID), 'numberposts' => 4, 'post__not_in' => array($post->ID) ) );
					if( $related ) foreach( $related as $post ) {
						setup_postdata($post); 
						$image1 = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
					?>
					<div class="col-md-3">
						<div class="blgdtlsbx">
							<div class="rltdimg">
								<a href="<?php the_permalink() ?>"><img src="<?php echo $image1[0]; ?>"></a>
							</div>
							<div class="bxcntnt">
								<a href="<?php the_permalink() ?>"><?php the_title() ?>
								<p class="mt-2"><i class="fa fa-calendar"></i> <?php echo get_the_date(); ?></p></a>
							</div>
						</div>
					</div>
					<?php } wp_reset_postdata(); ?>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
