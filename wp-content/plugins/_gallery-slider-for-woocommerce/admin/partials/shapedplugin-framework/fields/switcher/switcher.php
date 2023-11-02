<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: switcher
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'WCGS_Field_switcher' ) ) {
	class WCGS_Field_switcher extends WCGS_Fields {


		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {

			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$active     = ( ! empty( $this->value ) ) ? ' wcgs--active' : '';
			$text_on    = ( ! empty( $this->field['text_on'] ) ) ? $this->field['text_on'] : esc_html__( 'On', 'woo-gallery-slider' );
			$text_off   = ( ! empty( $this->field['text_off'] ) ) ? $this->field['text_off'] : esc_html__( 'Off', 'woo-gallery-slider' );
			$text_width = ( ! empty( $this->field['text_width'] ) ) ? ' style="width: ' . $this->field['text_width'] . 'px;"' : '';

			echo $this->field_before();

			echo '<div class="wcgs--switcher' . $active . '"' . $text_width . '>';
			echo '<span class="wcgs--on">' . $text_on . '</span>';
			echo '<span class="wcgs--off">' . $text_off . '</span>';
			echo '<span class="wcgs--ball"></span>';
			echo '<input type="text" name="' . $this->field_name() . '" value="' . $this->value . '"' . $this->field_attributes() . ' />';
			echo '</div>';

			echo ( ! empty( $this->field['label'] ) ) ? '<span class="wcgs--label">' . $this->field['label'] . '</span>' : '';

			echo '<div class="clear"></div>';

			echo $this->field_after();

		}

	}
}
