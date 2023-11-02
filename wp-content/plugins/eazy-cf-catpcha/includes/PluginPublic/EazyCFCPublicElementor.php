<?php

namespace TK\EazyCFCaptcha\PluginPublic;

use Elementor\Widget_Base;
use TK\EazyCFCaptcha\PluginPublic\EazyCFCPublicBase;
use TK\EazyCFCaptcha\Helper\EazyCFCaptchaOptions;

/**
 * @since 1.2.0
 */
class EazyCFCPublicElementor extends EazyCFCPublicBase
{

	/**
	 * @param $item
	 * @param $item_index
	 * @param Widget_Base $form
	 */
	protected function getAttributesFromElementorWidget($item, $item_index, $form)
	{
		$atts = array(
			'container_class' => 'elementor-field-group'
		);

		$attributes = $form->get_render_attributes('input' . $item_index);
		if (isset($attributes['name'])) {
			$atts['field_name'] = join('', $attributes['name']);
		}
		if (isset($attributes['id'])) {
			$atts['id'] = join(' ', $attributes['id']);
		}
		if (isset($attributes['class'])) {
			$atts['class'] = join(' ', $attributes['class']);
		}

		if (!empty($item['remove_honeypot'])) {
			$atts['no-honeypot'] = $item['remove_honeypot'] === 'yes';
		}
		if (!empty($item['easy'])) {
			$atts['easy'] = $item['easy'] === 'yes';
		}

		return $atts;
	}

	/**
	 * @param $item
	 * @param $item_index
	 * @param Widget_Base $form
	 */
	public function renderCaptchaField($item, $item_index, $form)
	{
		$atts = $this->getAttributesFromElementorWidget($item, $item_index, $form);

		echo $this->renderField('captcha-field', $atts);
	}

	public function validCaptcha($value)
	{
		return $this->compareSolution($value);
	}

	public function validHoneypot($field_name)
	{
		return !isset( $_POST[ $field_name ] ) || empty( trim( $_POST[ $field_name ] ) );
	}
}
