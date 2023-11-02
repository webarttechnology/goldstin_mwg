<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
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
			<p class="animated fadeInRightBig text-center text-white d-none d-md-block" style="animation-delay: .8s;">
			<?php
			printf(
				/* translators: %s: Search term. */
				esc_html__( 'Results for:  "%s"', 'twentytwentyone' ),
				'<span class="page-description search-term">' . esc_html( get_search_query() ) . '</span>'
			);
			?>
			</p>
		</div>
	</div>
</div>
<section class="bstslrsec py-5 mtressec">
			
			<div class="container">
				<div class="row">



			<?php
$s=get_search_query();
$args = array(
                's' =>$s,
				'post_type' => 'product'
            );
    // The Query
$the_query = new WP_Query( $args );
if ( $the_query->have_posts() ) {
       // _e("<h2 style='font-weight:bold;color:#000'>Search Results for: ".get_query_var('s')."</h2>");
        while ( $the_query->have_posts() ) {
           $the_query->the_post();
           $image2 = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );	
                 ?>


	<div class="col-md-3 col-6">

						<div class="productthumb" data-aos="fade-up" data-aos-duration="1000">

							<div class="image">
								<?php
						$bs = get_field('make_this_product_best-seller');
						if( $bs ): ?>
								<div class="selltag">Best Seller</div>
								<?php endif; ?>
								<a href="<?php the_permalink(); ?>"><img src="<?php echo $image2[0]; ?>"></a>

								<div class="icons text-center">
									<?php $cartUrl = '/?add-to-cart='.$post->ID;?>
									<a href="<?php the_field('add_affiliate_link');?>" target="_blank"><i class="icofont-shopping-cart"></i></a>
					                <a href="<?php the_permalink(); ?>"><i class="icofont-eye-alt"></i></a>

								</div>

							</div>

							<div class="content px-3 pb-3">

								<h4><?php the_title(); ?></h4>
								

								<div class="clearfix">

									<div class="price text-theme text-center"><?php echo $product->get_price_html();  ?></div>

								<div class="stars stars-5 mt-2 text-center"></div>

								</div>

								

							</div>

						</div>

					</div>
					
		
					
    <?php
        }
    }else{
?>
        <h2 style='font-weight:bold;color:#DA9B89'>Sorry, but nothing matched your search criteria. Please try again with some different keywords.</h2>
        
<?php } ?>

</div></div>
</section>
<?php
get_footer();
