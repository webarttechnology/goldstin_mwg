<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.
/**
 *
 * Field: column
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'WCGS_Field_column' ) ) {
	class WCGS_Field_column extends WCGS_Fields {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$args = wp_parse_args(
				$this->field, array(
					'number1_icon'        => '<i class="fa fa-desktop"></i>',
					'number2_icon'        => '<i class="fa fa-laptop"></i>',
					'number3_icon'        => '<i class="fa fa-tablet"></i>',
					'number4_icon'        => '<i class="fa fa-mobile"></i>',
					'number5_icon'        => '',
					'all_icon'            => '',
					'number1_placeholder' => '',
					'number2_placeholder' => '',
					'number3_placeholder' => '',
					'number4_placeholder' => '',
					'number5_placeholder' => '',
					'all_placeholder'     => '',
					'number1'             => true,
					'number2'             => true,
					'number3'             => true,
					'number4'             => true,
					'number5'             => false,
					'unit'                => false,
					'show_units'          => false,
					'all'                 => false,
					'units'               => array( 'px', '%', 'em' ),
				)
			);

			$default_values = array(
				'number1' => '',
				'number2' => '',
				'number3' => '',
				'number4' => '',
				'number5' => '',
				'all'     => '',
				'unit'    => '',
			);

			$value   = wp_parse_args( $this->value, $default_values );
			$unit    = ( count( $args['units'] ) === 1 && ! empty( $args['unit'] ) ) ? $args['units'][0] : '';
			$is_unit = ( ! empty( $unit ) ) ? ' wcgs--is-unit' : '';

			echo $this->field_before();

			echo '<div class="wcgs--inputs">';

			if ( ! empty( $args['all'] ) ) {

				$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? ' placeholder="' . esc_attr( $args['all_placeholder'] ) . '"' : '';

				echo '<div class="wcgs--input">';
				echo ( ! empty( $args['all_icon'] ) ) ? '<span class="wcgs--label wcgs--icon">' . wp_kses_post( $args['all_icon'] ) . '</span>' : '';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[all]' ) ) . '" value="' . esc_attr( $value['all'] ) . '"' . $placeholder . ' class="wcgs-input-number' . esc_attr( $is_unit ) . '" />';
				echo ( $unit ) ? '<span class="wcgs--label wcgs--unit">' . esc_attr( $args['units'][0] ) . '</span>' : '';
				echo '</div>';

			} else {

				$properties = array();

				foreach ( array( 'number1', 'number2', 'number3', 'number4', 'number5' ) as $prop ) {
					if ( ! empty( $args[ $prop ] ) ) {
						$properties[] = $prop;
					}
				}

				$properties = ( $properties === array( 'number3', 'number5' ) ) ? array_reverse( $properties ) : $properties;

				foreach ( $properties as $property ) {

					$placeholder = ( ! empty( $args[ $property . '_placeholder' ] ) ) ? ' placeholder="' . esc_attr( $args[ $property . '_placeholder' ] ) . '"' : '';

					echo '<div class="wcgs--input">';
					echo ( ! empty( $args[ $property . '_icon' ] ) ) ? '<span class="wcgs--label wcgs--icon">' . wp_kses_post( $args[ $property . '_icon' ] ) . '</span>' : '';
					echo '<input type="number" name="' . esc_attr( $this->field_name( '[' . $property . ']' ) ) . '" value="' . esc_attr( $value[ $property ] ) . '"' . $placeholder . ' class="wcgs-input-number' . esc_attr( $is_unit ) . '" />';
					echo ( $unit ) ? '<span class="wcgs--label wcgs--unit">' . esc_attr( $args['units'][0] ) . '</span>' : '';
					echo '</div>';

				}
			}

			if ( ! empty( $args['unit'] ) && ! empty( $args['show_units'] ) && count( $args['units'] ) > 1 ) {
				echo '<div class="wcgs--input">';
				echo '<select name="' . esc_attr( $this->field_name( '[unit]' ) ) . '">';
				foreach ( $args['units'] as $unit ) {
					$selected = ( $value['unit'] === $unit ) ? ' selected' : '';
					echo '<option value="' . esc_attr( $unit ) . '"' . esc_attr( $selected ) . '>' . esc_attr( $unit ) . '</option>';
				}
				echo '</select>';
				echo '</div>';
			}

			echo '</div>';

			echo $this->field_after();

		}
	}
}
