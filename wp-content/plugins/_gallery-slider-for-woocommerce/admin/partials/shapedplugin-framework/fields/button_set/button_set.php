<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: button_set
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'WCGS_Field_button_set' ) ) {
	class WCGS_Field_button_set extends WCGS_Fields {


		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {

			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$args = wp_parse_args(
				$this->field,
				array(
					'multiple' => false,
					'options'  => array(),
				)
			);

			$value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

			echo $this->field_before();

			if ( ! empty( $args['options'] ) ) {

				echo '<div class="wcgs-siblings wcgs--button-group" data-multiple="' . $args['multiple'] . '">';

				foreach ( $args['options'] as $key => $option ) {
					$type           = ( $args['multiple'] ) ? 'checkbox' : 'radio';
					$extra          = ( $args['multiple'] ) ? '[]' : '';
					$active         = ( in_array( $key, $value ) ) ? ' wcgs--active' : '';
					$checked        = ( in_array( $key, $value ) ) ? ' checked' : '';
					$pro_only_class = ( isset( $option['pro_only'] ) && $option['pro_only'] == true ) ? ' wcgs-pro-only' : '';
					echo '<div class="wcgs--sibling wcgs--button' . $active . $pro_only_class . '">';
					echo '<input type="' . $type . '" name="' . $this->field_name( $extra ) . '" value="' . $key . '"' . $this->field_attributes() . $checked . '/>';
					if ( ! empty( $option['option_name'] ) ) {
						echo $option['option_name'];
					} else {
						echo $option;
					}
					if ( isset( $option['pro_only'] ) && $option['pro_only'] == true ) {
						echo "<a href='#TB_inline?&width=380&height=210&inlineId=BuyProPopupContent' class='thickbox wcgs_pro_popup'></a>";
					}
					echo '</div>';
				}
				echo '</div>';
			}

			echo '<div class="clear"></div>';

			echo $this->field_after();

		}

	}
}
