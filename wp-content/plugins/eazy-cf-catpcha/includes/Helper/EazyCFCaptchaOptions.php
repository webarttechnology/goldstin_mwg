<?php

namespace TK\EazyCFCaptcha\Helper;

class LabelCompatibilityShield {
	public $label;

	public function __construct($label)
	{
		$this->label = $label;
	}
	public function __toString()
	{
		return $this->label;
	}
}

class EazyCFCaptchaOptions {
	public $label_text;
	public $label_text_comp;
	public $honeypot;
	public $easy;

	public $error_message = 'Error: please solve the captcha.';
	public $disable_comment_form = false;

	public $container_class = '';
	public $field_name = 'eazycfc_captcha';
	public $honeypot_field_name = 'eazycfc_captcha_sol';
	public $id = '';
	public $classes = '';

	protected $label_option_name = 'eazycfc_label_text';
	protected $error_message_option_name = 'eazycfc_error_message';
	protected $honeypot_option_name = 'eazycfc_easy';
	protected $easy_option_name = 'eazycfc_honeypot';
	protected $disable_comment_form_option_name = 'eazycfc_disable_comment_form';

	public function __construct($atts = null)
	{
		if($atts !== null) {
			$this->setupWithAtts($atts);
		} else {
			$this->setupCommon();
		}

		$this->translate();
	}

	protected function setupWithAtts($atts)
	{
		$atts = shortcode_atts( array(
			'easy' => false,
			'no-honeypot' => false,
			'container_class' => '',
			'field_name' => 'eazycfc_captcha',
			'id' => '',
			'class' => ''
		), $atts );

		$this->label_text = false;
		$this->error_message = get_option( $this->error_message_option_name, '' );

		$this->easy = $atts['easy'];
		$this->honeypot = !$atts['no-honeypot'];
		$this->container_class = $atts['container_class'];
		$this->field_name = $atts['field_name'];
		$this->id = $atts['id'];
		$this->classes = $atts['class'];
	}

	protected function setupCommon()
	{
		$this->label_text = get_option( $this->label_option_name, '' );
		$this->label_text_comp = new LabelCompatibilityShield($this->label_text);
		$this->error_message = get_option( $this->error_message_option_name, '' );
		$this->easy = get_option( $this->easy_option_name, false ) == 1;
		$this->honeypot = get_option( $this->honeypot_option_name, true ) == 1;
		$this->disable_comment_form = get_option( $this->disable_comment_form_option_name, '' );

		// Ensure backward compatiblity
		$this->id = 'eazycfc_captcha';
	}

	public function saveCommon()
	{
		update_option($this->label_option_name, $this->label_text);
		update_option($this->error_message_option_name, $this->error_message);
		update_option($this->easy_option_name, $this->easy);
		update_option($this->honeypot_option_name, $this->honeypot);
		update_option($this->disable_comment_form_option_name, $this->disable_comment_form);
		$this->registerTranslation();
		$this->translate();
	}

	public static function createOptions()
	{
		add_option( 'eazycfc_label_text', 'Please solve the "You are not a bot" exercise:' );
		add_option( 'eazycfc_label_text', 'Error: please solve the captcha.' );
		add_option( 'eazycfc_honeypot', false );
		add_option( 'eazycfc_easy', true );
		add_option( 'eazycfc_disable_comment_form', false );
	}

	public static function deleteOptions()
	{
		delete_option( 'eazycfc_label_text' );
		delete_option( 'eazycfc_label_text' );
		delete_option( 'eazycfc_honeypot' );
		delete_option( 'eazycfc_easy' );
		delete_option( 'eazycfc_disable_comment_form' );
	}

	protected function registerTranslation() {
		if( function_exists( 'icl_register_string' ) ) {
			\icl_register_string( 'Eazy CF Captcha', 'Captcha label text', $this->label_text );
			\icl_register_string( 'Eazy CF Captcha', 'Error message', $this->error_message );
		}
	}

	protected function translate()
	{
		if( function_exists( 'icl_t' ) ) {
			if( !empty($this->label_text) ) {
				$this->label_text = \icl_t( 'Eazy CF Captcha', 'Captcha label text', $this->label_text );
			}
			if( !empty($this->error_message) ) {
				$this->error_message = \icl_t( 'Eazy CF Captcha', 'Error message', $this->error_message );
			}
		}
	}
}