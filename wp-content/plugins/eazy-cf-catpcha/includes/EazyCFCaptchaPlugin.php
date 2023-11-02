<?php

namespace TK\EazyCFCaptcha;

use TK\EazyCFCaptcha\BasePlugin;
use TK\EazyCFCaptcha\Admin\EazyCFCaptchaAdmin;
use TK\EazyCFCaptcha\Elementor\EazyCFElementor;
use TK\EazyCFCaptcha\PluginPublic\EazyCFCPublicCommentForm;
use TK\EazyCFCaptcha\PluginPublic\EazyCFCPublicContactForm7;
use TK\EazyCFCaptcha\Helper\EazyCFCaptchaOptions;

/**
 * Main distributor class
 */
class EazyCFCaptchaPlugin implements BasePlugin {
	/**
	 * plugin version
	 *
	 * @var String
	 */
	protected $version;

	/**
	 * Runs on plugin activation
	 *
	 * @return void
	 */
	public static function activate()
	{
		EazyCFCaptchaOptions::createOptions();
	}

	/**
	 * Runs on plugin deactivation
	 *
	 * @return void
	 */
	public static function deactivate()
	{
		EazyCFCaptchaOptions::deleteOptions();
	}

	public function __construct()
	{
		if(defined('TK_EAZY_CF_CAPTCHA_VERSION')) {
			$this->version = TK_EAZY_CF_CAPTCHA_VERSION;
		} else {
			$this->version = '1.0.0';
		}

		$this->addHooks();

		$this->setupAdmin();
		$this->setupPublic();
	}

	/**
	 * Some general hooks for both, admin and public
	 *
	 * @return void
	 */
	protected function addHooks()
	{
		add_action( 'plugins_loaded', array($this, 'loadTranslation') );
		add_filter('option_eazycfc_label_text', function($option) {
			if( is_string( $option ) ) {
				$option = stripslashes( $option ); // because WP adds slashes
			}
			return $option;
		});
		add_filter('plugin_action_links_'.EAZY_CF_CAPTCHA_PLUGIN_BASENAME, array($this, 'add_settings_link'));
	}

	public function loadTranslation()
	{
		load_plugin_textdomain( 'eazycfc', false, EAZY_CF_CAPTCHA_DIR . 'languages' );
	}

	protected function get_settings_link() {
		return sprintf(
			'<a href="%s">%s</a>',
			admin_url( 'options-general.php?page=eazycfc' ),
			__( 'Settings' )
		);
	}

	protected function get_donation_link() {
		return sprintf(
			'<a href="%s" target="_blank" class="tk__donate-link" style="color: #ffb900; font-weight: 700">%s</a>',
			'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=QQMET2BDE8CJC&source=url',
			__( 'Donate (PayPal)', 'eazycfc' )
		);
	}

	public function add_settings_link( $links ) {
		$links[] = $this->get_settings_link();
		$links[] = $this->get_donation_link();

		return $links;
	}

	/**
	 * Setup admin part
	 *
	 * @return EazyCFCaptchaAdmin
	 */
	protected function setupAdmin()
	{
		if(!is_admin()) {
			return;
		}
		$pluginAdmin = new EazyCFCaptchaAdmin();
		return $pluginAdmin;
	}

	/**
	 * Setup public part
	 *
	 * @return void
	 */
	protected function setupPublic()
	{
		// Base for WP
		$pluginPublic = new EazyCFCPublicCommentForm();
		// ContactForm 7
		$publicPublicCF7 = new EazyCFCPublicContactForm7();
		// ELementor
		$elementor = new EazyCFElementor();
		add_action('plugins_loaded', array($elementor, 'addHooks'));
	}
}