<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Field: code_editor
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'WCGS_Field_code_editor' ) ) {
	class WCGS_Field_code_editor extends WCGS_Fields {


		public $version = '5.41.0';
		public $cdn_url = 'https://cdn.jsdelivr.net/npm/codemirror@';

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {

			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$default_settings = array(
				'tabSize'     => 2,
				'lineNumbers' => true,
				'theme'       => 'default',
				'mode'        => 'htmlmixed',
				'cdnURL'      => $this->cdn_url . $this->version,
			);

			$settings = ( ! empty( $this->field['settings'] ) ) ? $this->field['settings'] : array();
			$settings = wp_parse_args( $settings, $default_settings );
			$encoded  = htmlspecialchars( json_encode( $settings ) );

			echo $this->field_before();
			echo '<textarea name="' . $this->field_name() . '"' . $this->field_attributes() . ' data-editor="' . $encoded . '">' . $this->value . '</textarea>';
			echo $this->field_after();

		}

		public function enqueue() {

			$page = ( ! empty( $_GET[ 'page' ] ) ) ? sanitize_text_field( wp_unslash( $_GET[ 'page' ] ) ) : '';
	  
			// Do not loads CodeMirror in revslider page.
			if ( in_array( $page, array( 'revslider' ) ) ) { return; }
	  
			if ( ! wp_script_is( 'wcgs-codemirror' ) ) {
			  wp_enqueue_script( 'wcgs-codemirror', esc_url( $this->cdn_url . $this->version .'/lib/codemirror.min.js' ), array( 'wcgs' ), $this->version, true );
			  wp_enqueue_script( 'wcgs-codemirror-loadmode', esc_url( $this->cdn_url . $this->version .'/addon/mode/loadmode.min.js' ), array( 'wcgs-codemirror' ), $this->version, true );
			}
	  
			if ( ! wp_style_is( 'wcgs-codemirror' ) ) {
			  wp_enqueue_style( 'wcgs-codemirror', esc_url( $this->cdn_url . $this->version .'/lib/codemirror.min.css' ), array(), $this->version );
			}
	  
		  }

	}
}
