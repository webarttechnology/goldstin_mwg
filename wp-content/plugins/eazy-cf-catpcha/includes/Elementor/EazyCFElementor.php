<?php

namespace TK\EazyCFCaptcha\Elementor;

use TK\EazyCFCaptcha\Elementor\Fields\EazyCFCaptcha;

/**
 * @since 1.2.0
 */
class EazyCFElementor {
	public function addHooks()
	{
		if ( defined( 'ELEMENTOR_PRO_VERSION' ) && version_compare( ELEMENTOR_PRO_VERSION, '3.5', '<' ) ) {
			add_action( 'elementor_pro/forms/register_action', array( $this, 'register_action' ) );
		} else {
			add_action( 'elementor_pro/forms/fields/register', array( $this, 'register_field' ) );
		}
	}

	/**
	 * Old register function for Elementor Pro < 3.5
	 *
	 * @deprecated Should be deleted after Elementor Pro 4.3.0 is released
	 * @param ElementorPro\Modules\Forms\Module $elementor_form_module
	 * @return void
	 */
	public function register_action( $elementor_form_module )
	{
		$elementor_form_module->add_form_field_type( EazyCFCaptcha::get_eazycfc_name(), new EazyCFCaptcha() );
	}

	public function register_field( $form_fields_registrar )
	{
		$form_fields_registrar->register( new EazyCFCaptcha() );
	}
}
