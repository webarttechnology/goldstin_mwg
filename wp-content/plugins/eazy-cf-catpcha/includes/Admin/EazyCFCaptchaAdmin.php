<?php

namespace TK\EazyCFCaptcha\Admin;

use TK\EazyCFCaptcha\Helper\EazyCFCaptchaOptions;

class EazyCFCaptchaAdmin
{

	public function __construct()
	{
		$this->addHooks();
	}

	protected function addHooks()
	{
		add_action('admin_menu', array($this, 'optionsPage'));

		if(function_exists('wpcf7_add_tag_generator')) {
			add_action('wpcf7_admin_init', array($this, 'addCF7GeneratorTag'), 60, 0);
		}
	}

	public function optionsPage()
	{
		add_options_page(
			__('Eazy CF Captcha', 'eazycfc'),
			__('Eazy CF Captcha', 'eazycfc'),
			'manage_options',
			'eazycfc',
			array($this, 'renderOptionsPage')
		);
	}

	public function renderOptionsPage()
	{
		$errormsg = 0;
		$options = new EazyCFCaptchaOptions();

		if( isset( $_POST['eazycfc-config-submit'] ) ) {
			check_admin_referer( 'eazycfc-config' );

			$options->label_text = stripslashes($_POST['eazycfc-config-labeltext']);
			$options->error_message = stripslashes($_POST['eazycfc-config-error_message']);
			$options->easy = isset($_POST['eazycfc-config-easy']) && $_POST['eazycfc-config-easy'] === '1';
			$options->honeypot = isset($_POST['eazycfc-config-honeypot']) && $_POST['eazycfc-config-honeypot'] === '1';
			$options->disable_comment_form = isset($_POST['eazycfc-config-disable_comment_form']) && $_POST['eazycfc-config-disable_comment_form'] === '1';

			$options->saveCommon();
			$errormsg = 1;
		}

		require_once EAZY_CF_CAPTCHA_PATH . 'views/admin/option-page.php';
	}

	/**
	 * Add generator tag to Contact Form 7 admin area
	 *
	 * @since 1.1.0
	 * 
	 * @return void
	 */
	public function addCF7GeneratorTag()
	{
		wpcf7_add_tag_generator(
			'eazy_cf_captcha',
			__('Eazy CF Captcha', 'eazycfc'),
			'eazy_cf_captcha',
			array($this, 'renderCF7TagPane'),
			array('nameless' => 1)
		);
	}

	/**
	 * Render the options pane in Contact Form 7 admin area
	 *
	 * @since 1.1.0
	 * 
	 * @return void
	 */
	public function renderCF7TagPane($contact_form, $args = '')
	{
		$args = wp_parse_args( $args, array() );
		$options = new EazyCFCaptchaOptions();

		require_once EAZY_CF_CAPTCHA_PATH . 'views/admin/cf7-tag-pane.php';
	}
}
