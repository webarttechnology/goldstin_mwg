<?php

namespace TK\EazyCFCaptcha\Elementor\Fields;

use Elementor\Controls_Manager;
use ElementorPro\Plugin;
use Elementor\Widget_Base;
use ElementorPro\Modules\Forms\Fields\Field_Base;
use ElementorPro\Modules\Forms\Classes\Form_Record;
use ElementorPro\Modules\Forms\Classes\Ajax_Handler;
use TK\EazyCFCaptcha\Helper\EazyCFCaptchaOptions;
use TK\EazyCFCaptcha\PluginPublic\EazyCFCPublicElementor;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

/**
 * @since 1.2.0
 */
class EazyCFCaptcha extends Field_Base
{
	/**
	 * Undocumented variable
	 *
	 * @var EazyCFCPublicElementor
	 */
	protected $frontend_handler;

	public function __construct()
	{
		$this->frontend_handler = new EazyCFCPublicElementor();
		$field_type = static::get_eazycfc_name();
		add_action("elementor_pro/forms/render_field/{$field_type}", [$this, 'field_render'], 10, 3);
		add_action("elementor_pro/forms/render/item", [$this, 'filter_field_item'], 10, 3);
		add_action("elementor_pro/forms/validation/{$field_type}", [$this, 'validation'], 10, 3);
		add_action("elementor_pro/forms/process/{$field_type}", [$this, 'process_field'], 10, 3);
		add_filter('elementor_pro/forms/field_types', [$this, 'add_field_type']);
		add_filter("elementor_pro/forms/sanitize/{$field_type}", [$this, 'sanitize_field'], 10, 2);
		add_action('elementor/preview/enqueue_scripts', [$this, 'add_preview_depends']);
		if (method_exists($this, 'update_controls')) {
			add_action('elementor/element/form/section_form_fields/before_section_end', [$this, 'update_controls']);
		}
		$this->register_scripts();
	}

	public static function get_eazycfc_name()
	{
		return 'eazycfcaptcha';
	}

	public function get_name()
	{
		return __('Eazy CF Captcha', 'eazycfc');
	}

	public function get_type()
	{
		return 'text';
	}

	public function register_scripts()
	{
		wp_register_script(
			'eazycfcaptcha-elementor',
			EAZY_CF_CAPTCHA_URL . 'assets/js/admin.elementor.js',
			['jquery'],
			'1.2.0',
			true
		);
	}

	public function add_preview_depends()
	{
		parent::add_preview_depends();
		wp_enqueue_script('eazycfcaptcha-elementor');
	}

	public function add_field_type($field_types)
	{
		if (!in_array(static::get_eazycfc_name(), $field_types)) {
			$field_types[static::get_eazycfc_name()] = $this->get_name();
		}

		return $field_types;
	}

	/**
	 * @param $item
	 * @param $item_index
	 * @param Widget_Base $form
	 */
	public function filter_field_item($item, $item_index, $form)
	{
		if (static::get_eazycfc_name() === $item['field_type']) {
			if ( 'yes' !== $item['show_logged_in'] && is_user_logged_in() ) {
				$item['field_label'] = false;
				$form->set_render_attribute( 'field-group' . $item_index, 'class', 'elementor-field-type-text' );
			}
			// $item['field_label'] .= ' PADDED';
			// $form->add_render_attribute('input' . $item_index, 'type', 'text', true);
		}

		return $item;
	}

	/**
	 * @param $item
	 * @param $item_index
	 * @param Widget_Base $form
	 */
	public function render($item, $item_index, $form)
	{
		if ( 'yes' !== $item['show_logged_in'] && is_user_logged_in() ) {
			return;
		}

		$form->add_render_attribute('input' . $item_index, 'type', 'text', true);
		$form->add_render_attribute('input' . $item_index, 'class', 'elementor-field-textual');

		$this->frontend_handler->renderCaptchaField($item, $item_index, $form);
	}

	/**
	 * @param Widget_Base $widget
	 */
	public function update_controls($widget)
	{
		$elementor = Plugin::elementor();

		$control_data = $elementor->controls_manager->get_control_from_stack($widget->get_unique_name(), 'form_fields');

		if (is_wp_error($control_data)) {
			return;
		}

		foreach ($control_data['fields'] as $index => $field) {
			if ('required' === $field['name'] || 'width' === $field['name']) {
				$control_data['fields'][$index]['conditions']['terms'][] = [
					'name' => 'field_type',
					'operator' => '!in',
					'value' => [
						static::get_eazycfc_name(),
					],
				];
			}
		}

		$field_controls = [
			'remove_honeypot' => [
				'name' => 'remove_honeypot',
				'label' => __('Remove honeypot', 'eazycfc'),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'field_type' => static::get_eazycfc_name(),
				],
				'tab' => 'content',
				'inner_tab' => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			],
			'easy' => [
				'name' => 'easy',
				'label' => __('Keep it easy', 'eazycfc'),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'field_type' => static::get_eazycfc_name(),
				],
				'tab' => 'content',
				'inner_tab' => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			],
			'show_logged_in' => [
				'name' => 'show_logged_in',
				'label' => __('Show if user is logged in', 'eazycfc'),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'field_type' => static::get_eazycfc_name(),
				],
				'tab' => 'content',
				'inner_tab' => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			],
		];

		$control_data['fields'] = $this->inject_field_controls($control_data['fields'], $field_controls);
		$widget->update_control('form_fields', $control_data);
	}

	public function validation($field, Form_Record $record, Ajax_Handler $ajax_handler)
	{
		$options = new EazyCFCaptchaOptions();

		$fields = $record->get_field([
			'type' => static::get_eazycfc_name(),
		]);

		if (empty($fields)) {
			$ajax_handler->add_error($field['id'], '');
			return;
		}

		$settings = $record->get_form_settings('form_fields');
		$key = array_search($field['id'], array_column($settings, 'custom_id'));
		// Check honeypot
		if ($key !== false) {
			$field_settings = $settings[$key];
			if ($field_settings['remove_honeypot'] !== 'yes') {
				if (!$this->frontend_handler->validHoneypot($options->honeypot_field_name)) {
					$ajax_handler->add_error($field['id'], '');
					return;
				}
			}
		}

		// Check regular field
		$field = current($fields);
		if (empty($field['raw_value']) || !$this->frontend_handler->validCaptcha($field['value'])) {
			$ajax_handler->add_error($field['id'], '');
			return;
		}

		$record->remove_field($field['id']);
	}

	public function sanitize_field($value, $field)
	{
		return intval($value);
	}
}
