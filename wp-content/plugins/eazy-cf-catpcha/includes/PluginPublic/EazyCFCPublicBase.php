<?php

namespace TK\EazyCFCaptcha\PluginPublic;

use Exception;
use TK\EazyCFCaptcha\Helper\EazyCFCaptchaTemplateLoader;
use TK\EazyCFCaptcha\Helper\EazyCFCaptchaExercise;
use TK\EazyCFCaptcha\Helper\EazyCFCaptchaOptions;
use TK\EazyCFCaptcha\Helper\SessionHandler;

class EazyCFCPublicBase {
	/**
	 * Session variable
	 *
	 * @var string
	 */
	protected $session_var = 'eazycfc_captcha_result';

	/**
	 * Template loader instance
	 *
	 * @var TemplateLoader
	 */
	protected $template_loader;

	/**
	 * The session handler.
	 *
	 * @var SessionHandler
	 */
	protected $session;

	public function __construct()
	{
		$this->session         = new SessionHandler();
		$this->template_loader = new EazyCFCaptchaTemplateLoader();
		$this->addHooks();

		add_action('wp_enqueue_scripts', array($this, 'registerStyles'));
	}

	protected function addHooks() {}

	protected function saveSolutionToSession($value)
	{
		$this->session->write($value);
	}

	protected function getSolutionFromSession()
	{
		return $this->session->read();
	}

	/**
	 * Validate given value with session var
	 *
	 * @param mixed $value
	 * @return bool
	 */
	protected function compareSolution($value)
	{
		if( !is_int($value) ) {
			try {
				$value = intval( $value );
			} catch( Exception $e ) {
				return false;
			}
		}

		$solution = $this->getSolutionFromSession();

		return $value === $solution;
	}

	/**
	 * Render the field html with template loader and all necessary options
	 *
	 * @param string $file
	 * @param array|null $atts
	 * 
	 * @return string
	 */
	protected function renderField($file = 'captcha', $atts = null)
	{

		$options = new EazyCFCaptchaOptions($atts);
		$exercise = new EazyCFCaptchaExercise($options->easy);
		$this->saveSolutionToSession($exercise->solution);

		ob_start();

		$this->template_loader
			->set_template_data($exercise, 'exercise')
			->set_template_data($options, 'options')
			->set_template_data($options->label_text_comp, 'label_text') // backwards compatibility
			->get_template_part($file);

		$html = ob_get_contents();
		ob_end_clean();

		wp_enqueue_style( 'eazycfc-handle' );
		return $html;
	}

	protected function printStyles()
	{
		wp_enqueue_style( 'eazycfc-handle' );
	}

	public function registerStyles()
	{
		wp_register_style( 'eazycfc-handle', false );

		wp_add_inline_style( 'eazycfc-handle', '
			.eazycfc_captcha-exercise { display: flex; align-items: center; flex-wrap: nowrap; }
			.eazycfc_captcha-exercise .eazycfc-hide, .comment-form > p .eazycfc_captcha-exercise .eazycfc-hide { display: none; }
			.eazycfc_captcha-exercise label { flex-grow: 0; flex-shrink: 0; margin-right: 0.5em; margin-bottom: 0; }
			.eazycfc_captcha-exercise input,
			.elementor-field-group .eazycfc_captcha-exercise input { flex: 1; width: auto; }
			.eazycfc_captcha-exercise .wpcf7-not-valid-tip { width: 100%; flex-basis: 100%; }
			.elementor-field-label + .eazycfc_captcha-exercise { width: 100% }
			.elementor .elementor-element .eazycfc_captcha-exercise.elementor-field-group { margin-bottom: 0; padding-left: 0; padding-right: 0; }
		' );
	}

}
