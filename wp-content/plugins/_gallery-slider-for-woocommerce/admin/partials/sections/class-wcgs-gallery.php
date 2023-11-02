<?php
/**
 * The gallery tab functionality of this plugin.
 *
 * Defines the sections of gallery tab.
 *
 * @package    Woo_Gallery_Slider
 * @subpackage Woo_Gallery_Slider/admin
 * @author     Shapedplugin <support@shapedplugin.com>
 */
class WCGS_Gallery {
	/**
	 * Specify the Gallery tab for the Woo Gallery Slider.
	 *
	 * @since    1.0.0
	 * @param string $prefix Define prefix wcgs_settings.
	 */
	public static function section( $prefix ) {
		WCGS::createSection(
			$prefix,
			array(
				'name'   => 'gallery',
				'title'  => '<svg height="17px" width="17px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve"><g><path fill="none" d="M21.033,35.681c2.066,0,3.735-1.668,3.735-3.69c0-2.031-1.669-3.702-3.735-3.702   c-2.059,0-3.729,1.67-3.729,3.702C17.303,34.013,18.974,35.681,21.033,35.681z"></path><g><path d="M12,15.667v67.556h76V81.82V15.667H12z M84.124,19.868V79.02H15.875V19.868H84.124z"></path><path d="M19.729,23.625v36.593h60.542V23.625H19.729z M24.323,55.641l-0.27,0.862v-0.862H24.323l8.379-26.582l15.135,18.298    l8.651-4.577l2.16,12.859H24.323V55.641z M69.504,41.917c-3.58,0-6.482-3.07-6.482-6.861s2.902-6.861,6.482-6.861    c3.583,0,6.487,3.071,6.487,6.861S73.087,41.917,69.504,41.917z"></path><polygon points="24.852,49.669 25.111,48.902 24.852,48.902   "></polygon><polygon points="24.852,48.902 24.852,49.669 25.111,48.902   "></polygon><rect x="19.729" y="65.536" width="15.397" height="9.853"></rect><rect x="42.483" y="65.536" width="15.4" height="9.853"></rect><rect x="64.874" y="65.536" width="15.397" height="9.853"></rect></g></g></svg>' . esc_html__( 'Gallery', 'woo-gallery-slider' ),
				'fields' => array(
					array(
						'id'         => 'infinite_loop',
						'type'       => 'checkbox',
						'title'      => esc_html__( 'Infinite Loop', 'woo-gallery-slider' ),
						'subtitle'   => esc_html__( 'Check to make the gallery thumbnail infinite loop.', 'woo-gallery-slider' ),
						'default'    => true,
						'dependency' => array( 'gallery_layout', '!=', 'hide_thumb', true ),
					),
					array(
						'id'       => 'adaptive_height',
						'type'     => 'switcher',
						'title'    => esc_html__( 'Adaptive Height', 'woo-gallery-slider' ),
						'subtitle' => esc_html__( 'On/Off adaptive height.', 'woo-gallery-slider' ),
						'default'  => true,
					),
					array(
						'id'       => 'accessibility',
						'type'     => 'switcher',
						'title'    => esc_html__( 'Accessibility', 'woo-gallery-slider' ),
						'subtitle' => esc_html__( 'On/Off accessibility.', 'woo-gallery-slider' ),
						'default'  => true,
					),
					array(
						'id'       => 'slider_dir',
						'type'     => 'switcher',
						'title'    => esc_html__( 'RTL Mode', 'woo-gallery-slider' ),
						'subtitle' => esc_html__( 'On/Off the mode for RTL languages.', 'woo-gallery-slider' ),
						'default'  => false,
					),
					array(
						'type'    => 'subheading',
						'content' => esc_html__( 'Slider Navigation & Pagination', 'woo-gallery-slider' ),
					),
					array(
						'id'         => 'navigation',
						'type'       => 'switcher',
						'title'      => esc_html__( 'Slider Navigation', 'woo-gallery-slider' ),
						'subtitle'   => esc_html__( 'Show/Hide slider navigation.', 'woo-gallery-slider' ),
						'text_on'    => esc_html__( 'Show', 'woo-gallery-slider' ),
						'text_off'   => esc_html__( 'Hide', 'woo-gallery-slider' ),
						'text_width' => 80,
						'default'    => true,
					),

					array(
						'id'         => 'navigation_icon_size',
						'type'       => 'spinner',
						'title'      => esc_html__( 'Navigation Icon Size', 'woo-gallery-slider' ),
						'subtitle'   => esc_html__( 'Set navigation icon size.', 'woo-gallery-slider' ),
						'dependency' => array( 'navigation', '==', true ),
						'default'    => 16,
					),

					array(
						'id'         => 'navigation_icon_color_group',
						'type'       => 'color_group',
						'title'      => esc_html__( 'Navigation Color', 'woo-gallery-slider' ),
						'subtitle'   => esc_html__( 'Set navigation icon and background colors.', 'woo-gallery-slider' ),
						'options'    => array(
							'color'          => esc_html__( 'Color', 'woo-gallery-slider' ),
							'hover_color'    => esc_html__( 'Hover Color', 'woo-gallery-slider' ),
							'bg_color'       => esc_html__( 'Background', 'woo-gallery-slider' ),
							'hover_bg_color' => esc_html__( 'Hover Background', 'woo-gallery-slider' ),
						),
						'dependency' => array( 'navigation', '==', true ),
						'default'    => array(
							'color'          => '#fff',
							'hover_color'    => '#fff',
							'bg_color'       => 'rgba(0, 0, 0, .5)',
							'hover_bg_color' => 'rgba(0, 0, 0, .85)',
						),
					),
					array(
						'id'         => 'pagination',
						'type'       => 'switcher',
						'title'      => esc_html__( 'Slider Pagination', 'woo-gallery-slider' ),
						'subtitle'   => esc_html__( 'Show/Hide slider pagination bullet.', 'woo-gallery-slider' ),
						'text_on'    => esc_html__( 'Show', 'woo-gallery-slider' ),
						'text_off'   => esc_html__( 'Hide', 'woo-gallery-slider' ),
						'text_width' => 80,
						'default'    => false,
					),

					array(
						'id'         => 'pagination_icon_color_group',
						'type'       => 'color_group',
						'title'      => esc_html__( 'Pagination Color', 'woo-gallery-slider' ),
						'subtitle'   => esc_html__( 'Set slider pagination bullet color.', 'woo-gallery-slider' ),
						'options'    => array(
							'color'        => esc_html__( 'Color', 'woo-gallery-slider' ),
							'active_color' => esc_html__( 'Active Color', 'woo-gallery-slider' ),
						),
						'dependency' => array( 'pagination', '==', true ),
						'default'    => array(
							'color'        => 'rgba(115, 119, 121, 0.5)',
							'active_color' => 'rgba(115, 119, 121, 0.8)',
						),
					),

					array(
						'type'    => 'subheading',
						'content' => esc_html__( 'Thumbnails Navigation', 'woo-gallery-slider' ),
					),
					array(
						'id'         => 'thumbnailnavigation',
						'type'       => 'switcher',
						'title'      => esc_html__( 'Thumbnails Navigation', 'woo-gallery-slider' ),
						'subtitle'   => esc_html__( 'Check to show gallery thumbnails navigation arrow.', 'woo-gallery-slider' ),
						'text_on'    => esc_html__( 'Show', 'woo-gallery-slider' ),
						'text_off'   => esc_html__( 'Hide', 'woo-gallery-slider' ),
						'text_width' => 80,
						'default'    => false,
					),

					array(
						'id'         => 'thumbnailnavigation_icon_size',
						'type'       => 'spinner',
						'title'      => esc_html__( 'Thumbnail Navigation Icon Size', 'woo-gallery-slider' ),
						'subtitle'   => esc_html__( 'Set thumbnail navigation icon size.', 'woo-gallery-slider' ),
						'dependency' => array( 'thumbnailnavigation|gallery_layout', '==|!=', 'true|hide_thumb', true ),
						'default'    => 12,
					),

					array(
						'id'         => 'thumbnailnavigation_icon_color_group',
						'type'       => 'color_group',
						'title'      => esc_html__( 'Thumbnail Navigation Color', 'woo-gallery-slider' ),
						'subtitle'   => esc_html__( 'Set thumbnail navigation colors.', 'woo-gallery-slider' ),
						'options'    => array(
							'color'          => esc_html__( 'Color', 'woo-gallery-slider' ),
							'hover_color'    => esc_html__( 'Hover Color', 'woo-gallery-slider' ),
							'bg_color'       => esc_html__( 'Background', 'woo-gallery-slider' ),
							'hover_bg_color' => esc_html__( 'Hover Background', 'woo-gallery-slider' ),
						),
						'dependency' => array( 'thumbnailnavigation|gallery_layout', '==|!=', 'true|hide_thumb', true ),
						'default'    => array(
							'color'          => '#fff',
							'hover_color'    => '#fff',
							'bg_color'       => 'rgba(0, 0, 0, 0.5)',
							'hover_bg_color' => 'rgba(0, 0, 0, 0.8)',
						),
					),
					array(
						'type'    => 'subheading',
						'content' => esc_html__( 'Product Image', 'woo-gallery-slider' ),
					),
					array(
						'id'       => 'zoom',
						'type'     => 'switcher',
						'title'    => esc_html__( 'Enable Image Zoom', 'woo-gallery-slider' ),
						'subtitle' => esc_html__( 'On/Off product image zoom.', 'woo-gallery-slider' ),
						'default'  => true,
					),
					array(
						'id'         => 'mobile_zoom',
						'type'       => 'switcher',
						'title'      => esc_html__( 'Enable Zoom for Mobile Devices', 'woo-gallery-slider' ),
						'subtitle'   => esc_html__( 'Enable or Disable image zoom for mobile devices.', 'woo-gallery-slider' ),
						'default'    => false,
						'dependency' => array( 'zoom', '==', true ),
					),
					array(
						'id'       => 'image_sizes',
						'type'     => 'image_sizes',
						'title'    => esc_html__( 'Image Size', 'woo-gallery-slider' ),
						'subtitle' => esc_html__( 'Select images size.', 'woo-gallery-slider' ),
						'default'  => 'full',
					),
					array(
						'id'       => 'preloader',
						'type'     => 'switcher',
						'title'    => esc_html__( 'Preloader', 'woo-gallery-slider' ),
						'subtitle' => esc_html__( 'Preloader will show when variation changes.', 'woo-gallery-slider' ),
						'default'  => true,
					),
				),
			)
		);
	}
}
