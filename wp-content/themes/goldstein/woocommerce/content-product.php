<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );?>

          <div class="col-md-3 col-6">
            <div class="productthumb">

              <div class="image">
				  <?php
				  $bs = get_field('make_this_product_best-seller');
				  if( $bs ): ?>
				  <div class="selltag">Best Seller</div>
				  <?php endif; ?>
				  
                <a href="<?php the_permalink(); ?>"><img src="<?php echo $thumb['0']; ?>"></a>
                <div class="icons text-center">
                  <?php $cartUrl = '/?add-to-cart='.$post->ID;?>
                 <a href="<?php the_field('add_affiliate_link');?>" target="_blank"><i class="icofont-shopping-cart"></i></a>
                  <a href="<?php the_permalink(); ?>"><i class="icofont-eye-alt"></i></a>
                </div>
              </div>

              <div class="content px-3 pb-3">
                <h4><?php the_title(); ?></h4>
                <div class="clearfix">
                  
                <div class="stars stars-5 mt-2 text-center"></div>
                </div>
              </div>

            </div>
          </div>