<?php

namespace TK\EazyCFCaptcha\PluginPublic;

use TK\EazyCFCaptcha\PluginPublic\EazyCFCPublicBase;
use TK\EazyCFCaptcha\Helper\EazyCFCaptchaOptions;

use \WPCF7_Validation;
use \WPCF7_FormTag;

/**
 * @since 1.1.0
 */
class EazyCFCPublicContactForm7 extends EazyCFCPublicBase {
	protected $shortcode = 'eazy_cf_captcha';

	protected function addHooks()
	{
		if(function_exists('wpcf7_add_form_tag')) {
			add_action('wpcf7_init', array($this, 'addCF7Support'));
			add_filter('wpcf7_validate_eazy_cf_captcha', array($this, 'checkInput'), 20, 2);
			add_filter('wpcf7_validate_eazy_cf_captcha*', array($this, 'checkInput'), 20, 2);
		}
	}

	/**
	 * Add Contact Form 7 support
	 * 
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function addCF7Support()
	{
		if(!function_exists('wpcf7_add_form_tag')) {
			return;
		}

		wpcf7_add_form_tag(
			array(
				$this->shortcode,
				$this->shortcode . '*',
			),
			array($this, 'renderCaptchaField'),
			array('nameless' => 1)
		);
	}


	/**
	 * Make an array of attributes out of a WPCF7_FormTag
	 *
	 * @param WPCF7_FormTag $tag
	 * @return void
	 */
	protected function getAttributesFromTag($tag)
	{
		return array(
			'id' => $tag->get_id_option(),
			'class' => $tag->get_class_option('wpcf7-form-control wpcf7-text'),
			'no-honeypot' => $tag->has_option('no-honeypot'),
			'easy' => $tag->has_option('easy'),
			'container_class' => 'wpcf7-form-control-wrap eazycfc_captcha'
		);
	}

	/**
	 * Render Captcha Field into form
	 *
	 * @param WPCF7_FormTag $tag
	 * 
	 * @return string
	 */
	public function renderCaptchaField($tag)
	{
		return $this->renderField('captcha-field', $this->getAttributesFromTag($tag));
	}

	protected function validHoneypot($field_name)
	{
		return !isset( $_POST[ $field_name ] ) || empty( trim( $_POST[ $field_name ] ) );
	}

	/**
	 * Compare the captcha value
	 * 
	 * @since 1.1.0
	 *
	 * @param WPCF7_Validation $result
	 * @param WPCF7_FormTag $tag
	 * 
	 * @return WPCF7_Validation
	 */
	public function checkInput($result, $tag)
	{
		$options = new EazyCFCaptchaOptions($this->getAttributesFromTag($tag));
		$name = $options->field_name;
		$tag->name = $name;
		$empty = ! isset( $_POST[$name] ) || empty( $_POST[$name] ) && '0' !== $_POST[$name];

		if ( $tag->is_required() && $empty ) {
			$result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
		} else if( !$this->compareSolution( trim( $_POST[$name] ) ) || ( $options->honeypot && !$this->validHoneypot($options->honeypot_field_name) ) ) {
			$result->invalidate( $tag, $options->error_message );
		}

		return $result;
	}
}