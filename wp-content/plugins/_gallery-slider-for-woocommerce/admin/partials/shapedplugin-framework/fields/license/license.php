<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: license
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'WCGS_Field_license' ) ) {
	class WCGS_Field_license extends WCGS_Fields {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {

			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			if ( ! in_array( 'woo-gallery-slider-pro/woo-gallery-slider-pro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				return;
			}
			echo $this->field_before();
			$type = ( ! empty( $this->field['attributes']['type'] ) ) ? $this->field['attributes']['type'] : 'text';

			$manage_license       = new Woo_Gallery_Slider_Pro_License( WOO_GALLERY_SLIDER_PRO_FILE, WOO_GALLERY_SLIDER_PRO_VERSION, 'ShapedPlugin', WOO_GALLERY_SLIDER_PRO_STORE_URL, WOO_GALLERY_SLIDER_PRO_ITEM_ID, WOO_GALLERY_SLIDER_PRO_ITEM_SLUG );
			$license_key          = $manage_license->get_license_key();
			$license_key_status   = $manage_license->get_license_status();
			$license_status       = ( is_object( $license_key_status ) ? $license_key_status->license : '' );
			$license_notices      = $manage_license->license_notices();
			$license_status_class = '';
			$license_active       = '';
			$license_data         = $manage_license->api_request();

			echo '<div class="woo-gallery-slider-pro-license text-center">';
			echo '<h3>' . __( 'Gallery Slider for WooCommerce - Pro License Key', 'woo-gallery-slider' ) . '</h3>';
			if ( 'valid' == $license_status ) {
				$license_status_class = 'license-key-active';
				$license_active       = '<span>' . __( 'Active', 'woo-gallery-slider' ) . '</span>';
				echo '<p>' . __( 'Your license key is active.', 'woo-gallery-slider' ) . '</p>';
			} elseif ( 'expired' == $license_status ) {
				echo '<p style="color: red;">Your license key expired on ' . date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) ) . '. <a href="' . WOO_GALLERY_SLIDER_PRO_STORE_URL . '/checkout/?edd_license_key=' . $license_key . '&download_id=' . WOO_GALLERY_SLIDER_PRO_ITEM_ID . '&utm_campaign=woo_gallery_slider&utm_source=licenses&utm_medium=expired" target="_blank">Renew license key at discount.</a></p>';
			} else {
				echo '<p>Please activate your license key to make the plugin work. <a href="https://docs.shapedplugin.com/docs/gallery-slider-for-woocommerce-pro/getting-started/activating-license-key/" target="_blank">How to activate license key?</a></p>';
			}
			echo '<div class="woo-gallery-slider-pro-license-area">';
			echo '<div class="woo-gallery-slider-pro-license-key"><input class="woo-gallery-slider-pro-license-key-input ' . $license_status_class . '" type="' . $type . '" name="' . $this->field_name() . '" value="' . $this->value . '"' . $this->field_attributes() . ' />' . $license_active . '</div>';
			wp_nonce_field( 'sp_woo_gallery_slider_pro_nonce', 'sp_woo_gallery_slider_pro_nonce' );
			if ( 'valid' == $license_status ) {
				echo '<input style="color: #dc3545; border-color: #dc3545;" type="submit" class="button-secondary btn-license-deactivate" name="sp_woo_gallery_slider_pro_license_deactivate" value="' . __( 'Deactivate', 'woo-gallery-slider' ) . '"/>';
			} else {
				echo '<input type="submit" class="button-secondary btn-license-save-activate" name="' . $this->unique . '[_nonce][save]" value="' . __( 'Activate', 'woo-gallery-slider' ) . '"/>';
				echo '<input type="hidden" class="btn-license-activate" name="sp_woo_gallery_slider_pro_license_activate" value="' . __( 'Activate', 'woo-gallery-slider' ) . '"/>';
			}
			echo '<br><div class="woo-gallery-slider-pro-license-error-notices">' . $license_notices . '</div>';
			echo '</div>';
			echo '</div>';
			echo $this->field_after();
		}

	}
}
