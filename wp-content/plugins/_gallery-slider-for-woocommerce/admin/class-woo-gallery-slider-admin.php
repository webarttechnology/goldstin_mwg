<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Gallery_Slider
 * @subpackage Woo_Gallery_Slider/admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */
class Woo_Gallery_Slider_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		// Autoloading system.
		spl_autoload_register( array( $this, 'autoload' ) );
		if ( woo_gallery_slider_pro_is_activate() ) {
			WCGSP_Settings::options( 'wcgs_settings' );
		} else {
			WCGS_Settings::options( 'wcgs_settings' );
		}
	}

	/**
	 * Autoload class files on demand
	 *
	 * @since 1.0.0
	 * @access private
	 * @param string $class requested class name.
	 */
	private function autoload( $class ) {
		$name = explode( '_', $class );
		if ( isset( $name[1] ) ) {
			$class_name        = strtolower( $name[1] );
			$spto_config_paths = array( 'partials', 'partials/sections' );

			if ( woo_gallery_slider_pro_is_activate() ) {
				$wcgs_plugin_path = wp_normalize_path( WP_PLUGIN_DIR ) . '/woo-gallery-slider-pro/admin';
			} else {
				$wcgs_plugin_path = plugin_dir_path( __FILE__ );
			}
			foreach ( $spto_config_paths as $sptp_path ) {
				$filename = $wcgs_plugin_path . '/' . $sptp_path . '/class-wcgs-' . $class_name . '.php';
				if ( file_exists( $filename ) ) {
					require_once $filename;
				}
			}
		}
	}

	/**
	 * Implementaion of yield for better performance.
	 * https://medium.com/tech-tajawal/use-memory-gently-with-yield-in-php-7e62e2480b8d
	 * wcgs_reduce_processor_use
	 *
	 * $param $array array
	 */
	public function wcgs_reduce_processor_use( $array ) {
		$array_length = count( $array );
		for ( $i = 0; $i < $array_length; $i++ ) {
			yield $array[ $i ];
		}
	}

	/**
	 * Add WooCommerce Product Variation Gallery field from WCGS plugin.
	 *
	 * @since    1.0.0
	 * @access public
	 */
	public function woocommerce_add_gallery_product_variation( $loop, $variation_data, $variation ) {
		?>
		<div class="wcgs-variation-gallery form-row form-row-full">
		<h4><?php esc_html_e( 'Variation Image Gallery', 'woo-gallery-slider' ); ?><h4>
		<div class="wcgs-gallery-items" id="<?php echo esc_attr( $variation->ID ); ?>">
			<?php

			$variation_gallery     = get_post_meta( $variation->ID, 'woo_gallery_slider', true );
			$variation_gallery_arr = substr( $variation_gallery, 1, -1 );
			if ( ! empty( $variation_gallery_arr ) ) {
				$image_ids = explode( ',', $variation_gallery_arr );

				$yield_image_ids = $this->wcgs_reduce_processor_use( $image_ids );
				$count           = 1;
				foreach ( $yield_image_ids as $image_id ) {
					$image_attachment = wp_get_attachment_image_src( $image_id )[0];
					if ( 2 >= $count || woo_gallery_slider_pro_is_activate() ) {
						?>
						<div class="wcgs-image" data-attachmentid="<?php echo esc_attr( $image_id ); ?>">
							<img src="<?php echo esc_attr( $image_attachment ); ?>" style="max-width:100%;display:inline-block;" />
							<div class="wcgs-image-remover"><span class="dashicons dashicons-no"></span></div>
						</div>
						<?php
					}

					$count++;
				}
			}
			?>
		</div>
		<p>
			<button class="wcgs-remove-all-images button
			<?php
			if ( empty( $variation_gallery_arr ) ) {
				echo 'hidden';
			}
			?>
			"><?php esc_html_e( 'Remove all', 'woo-gallery-slider' ); ?></button>
			<button class="wcgs-upload-image button
			<?php
			if ( ! empty( $variation_gallery_arr ) ) {
				echo 'hidden';
			}
			?>
			" id="<?php echo 'wcgs-upload-' . esc_attr( $variation->ID ); ?>"><?php esc_html_e( 'Add Gallery Images', 'woo-gallery-slider' ); ?></button>
			<button class="wcgs-upload-more-image button
			<?php
			if ( empty( $variation_gallery_arr ) ) {
				echo 'hidden';
			}
			?>
			"><?php esc_html_e( 'Add more', 'woo-gallery-slider' ); ?></button>
			<button class="wcgs-edit button
			<?php
			if ( empty( $variation_gallery_arr ) ) {
				echo 'hidden';
			}
			?>
			">
			<?php esc_html_e( 'Edit Gallery', 'woo-gallery-slider' ); ?>
			</button>
			<span class="wcgs-pro-notice 
			<?php
			$image_ids = explode( ',', $variation_gallery_arr );
			if ( 2 >= count( $image_ids ) || woo_gallery_slider_pro_is_activate() ) {
				echo 'hidden';
			}
			?>
			" style="color:red;">To add more images & videos, <a href="https://shapedplugin.com/plugin/woocommerce-gallery-slider-pro/?ref=143" target="_blank" style="font-style: italic;">Upgrade To Pro!</a></span>

		</p>
		<script type="text/javascript">
		jQuery(document).ready( function($) {
			$('.wcgs-gallery-items').sortable({
				placeholder: "ui-state-highlight",
				stop: function() {
					var variableID = $(this).parents('.woocommerce_variation').find('h3 strong').text();
					var newWcgsArr = [];
					var _newWcgsArrLength = $('.wcgs-gallery-items'+variableID).find('.wcgs-image').length;
					$('.wcgs-gallery-items'+variableID).find('.wcgs-image').each( function() {
						var imageID = $(this).data('attachmentid');
						newWcgsArr.push(imageID);
					});
					$('.wcgs-gallery-items'+variableID).parents('.woocommerce_variable_attributes').find('.wcgs-gallery').val(JSON.stringify(newWcgsArr)).trigger('change');
				}
			});
		});
		</script>
		<div class="hidden">
		<?php
		woocommerce_wp_text_input(
			array(
				'id'    => 'woo_gallery_slider[' . $loop . ']',
				'class' => 'wcgs-gallery',
				'label' => '',
				'value' => get_post_meta( $variation->ID, 'woo_gallery_slider', true ),
			)
		);
		?>
		</div>
		</div>
		<?php
	}

	public function woocommerce_save_gallery_product_variation( $variation_id, $i ) {
		$custom_field = sanitize_text_field( $_POST['woo_gallery_slider'][ $i ] );
		if ( isset( $custom_field ) ) {
			update_post_meta( $variation_id, 'woo_gallery_slider', $custom_field );
		}
	}

	public function woocommerce_add_gallery_product_variation_data( $variations ) {
		$variations['woo_gallery_slider'] = get_post_meta( $variations['variation_id'], 'woo_gallery_slider', true );
		return $variations;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 * @access public
	 */
	public function enqueue_styles() {

		/**
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Gallery_Slider_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Gallery_Slider_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( 'wp-jquery-ui' );
		wp_enqueue_style( 'wcgs_font-awesome', plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css', $this->version, 'all' );
		// wp_enqueue_style( 'google-font', '//fonts.googleapis.com/css?family=Roboto:400,500&display=swap', array(), $this->version, 'all' );
		if ( false === woo_gallery_slider_pro_is_activate() ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-gallery-slider-admin.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 * @access public
	 */
	public function enqueue_scripts() {

		/**
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Gallery_Slider_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Gallery_Slider_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ( ! did_action( 'wp_enqueue_media' ) ) {
			wp_enqueue_media();
		}
		add_thickbox();
		// wp_enqueue_script( 'jquery-ui-sortable' );
		if ( false === woo_gallery_slider_pro_is_activate() ) {
			wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-gallery-slider-admin.js', array( 'jquery' ), $this->version, true );

			wp_enqueue_script( $this->plugin_name );
		}

	}

}
