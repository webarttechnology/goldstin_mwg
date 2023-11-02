<?php
/**Template Name: Blog
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
			<h1 class="display-4 text-theme animated fadeInLeftBig" style="animation-delay: .8s;"><?php the_title(); ?></h1>
			<p class="animated fadeInRightBig text-center text-white d-none d-md-block" style="animation-delay: .8s;"><?php the_field('sub_title'); ?></p>
		</div>
	</div>
</div>


 <section class="inblogsec text-white">
        <div class="container">

<!--             <h1 class="text-center display-4 text-theme">Recent Trends</h1> -->

            <div class="row">
                
                <?php
					$args = array (
					'post_type'              => 'post',
					'post_status'            => 'publish',
					'order'                  => 'ASC',
					'posts_per_page'=>-1
					);
					
					$banner = new WP_Query( $args );
					if ( $banner->have_posts() ) {
					while ( $banner->have_posts() ) {
					$banner->the_post();
					$imageb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
					$cnumber= get_comments_number(get_the_ID());
				?>
				
                <div class="col-12 col-md-6 col-lg-6">
                   <div class="inblgcrd">
                        <div class="inblogimg">
							 <a href="<?php the_permalink(); ?>"><img src="<?php echo $imageb[0]; ?>" class="img-fluid"></a>
							
                        </div>
                        <div class="blogcontent">
                           <h5><a href="<?php the_permalink(); ?>" class="blgname"><?php the_title();?> </a></h5>
							<p><?php echo wp_trim_words( get_the_content(), 35, $moreLink); ?></p>
                           <div  class="d-flex justify-content-center mt-3">
                               <p><i class="fa fa-calendar mr-1"></i> <?php echo get_the_date('m S F Y'); ?></p>
                               <p><i class="fa fa-clock-o mr-1"></i> <?php echo get_the_time('h A'); ?></p>
                           </div>
                           <a href="<?php the_permalink(); ?>" class="blgrdmore">Read More</a>
                        </div>
                   </div>
                </div>

                  
                  
                 <?php } } wp_reset_postdata(); ?>

                
            </div>
        </div>
    </section>

    <?php
get_footer();