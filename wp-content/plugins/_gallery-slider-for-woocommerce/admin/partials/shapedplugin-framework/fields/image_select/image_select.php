<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: image_select
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'WCGS_Field_image_select' ) ) {
	class WCGS_Field_image_select extends WCGS_Fields {


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

				echo '<div class="wcgs-siblings wcgs--image-group" data-multiple="' . $args['multiple'] . '">';

				$num = 1;

				foreach ( $args['options'] as $key => $option ) {

					$type           = ( $args['multiple'] ) ? 'checkbox' : 'radio';
					$extra          = ( $args['multiple'] ) ? '[]' : '';
					$active         = ( in_array( $key, $value ) ) ? ' wcgs--active' : '';
					$checked        = ( in_array( $key, $value ) ) ? ' checked' : '';
					$pro_only_class = isset( $option['pro_only'] ) ? ' wcgs-pro-only' : '';
					echo '<div class="wcgs--sibling wcgs--image' . $active . $pro_only_class . '">';
					echo '<div class="wcgs--image-select">';
					echo '<img src="' . $option['image'] . '" alt="img-' . $num++ . '" />';
					echo '<input type="' . $type . '" name="' . $this->field_name( $extra ) . '" value="' . $key . '"' . $this->field_attributes() . $checked . '/>';
					echo '</div>';
					if ( isset( $option['pro_only'] ) && $option['pro_only'] == true ) {
						echo "<a href='#TB_inline?&width=380&height=210&inlineId=BuyProPopupContent' class='thickbox wcgs_pro_popup'></a>";
					}
					echo '<p><b>' . $option['option_name'] . '</b></p>';
					echo '</div>';

				}

				echo '</div>';

			}

			echo '<div class="clear"></div>';

			echo $this->field_after();

		}

		public function output() {

			$output    = '';
			$bg_image  = array();
			$important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
			$elements  = ( is_array( $this->field['output'] ) ) ? join( ',', $this->field['output'] ) : $this->field['output'];

			if ( ! empty( $elements ) && isset( $this->value ) && $this->value !== '' ) {
				$output = $elements . '{background-image:url(' . $this->value . ')' . $important . ';}';
			}

			$this->parent->output_css .= $output;

			return $output;

		}

	}
}
