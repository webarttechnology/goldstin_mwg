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
class WCGS_Lightbox {
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
				'name'   => 'lightbox',
				'title'  => '<svg height="14px" width="14px" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path fill="#555" d="M1216 832q0-185-131.5-316.5t-316.5-131.5-316.5 131.5-131.5 316.5 131.5 316.5 316.5 131.5 316.5-131.5 131.5-316.5zm512 832q0 52-38 90t-90 38q-54 0-90-38l-343-342q-179 124-399 124-143 0-273.5-55.5t-225-150-150-225-55.5-273.5 55.5-273.5 150-225 225-150 273.5-55.5 273.5 55.5 225 150 150 225 55.5 273.5q0 220-124 399l343 343q37 37 37 90z"></path></svg>' . esc_html__( 'Lightbox', 'woo-gallery-slider' ),
				'fields' => array(
					array(
						'id'       => 'lightbox',
						'type'     => 'switcher',
						'title'    => esc_html__( 'Enable Lightbox', 'woo-gallery-slider' ),
						'subtitle' => esc_html__( 'On/Off lightbox.', 'woo-gallery-slider' ),
						'default'  => true,
					),
					array(
						'id'         => 'lightbox_icon_color_group',
						'type'       => 'color_group',
						'title'      => esc_html__( 'Lightbox Icon Color', 'woo-gallery-slider' ),
						'subtitle'   => esc_html__( 'Set lightbox icon colors.', 'woo-gallery-slider' ),
						'options'    => array(
							'color'          => esc_html__( 'Color', 'woo-gallery-slider' ),
							'hover_color'    => esc_html__( 'Hover Color', 'woo-gallery-slider' ),
							'bg_color'       => esc_html__( 'Background', 'woo-gallery-slider' ),
							'hover_bg_color' => esc_html__( 'Hover Background', 'woo-gallery-slider' ),
						),
						'default'    => array(
							'color'          => '#fff',
							'hover_color'    => '#fff',
							'bg_color'       => 'rgba(0, 0, 0, 0.5)',
							'hover_bg_color' => 'rgba(0, 0, 0, 0.8)',
						),
						'dependency' => array( 'lightbox', '==', true ),
					),
					array(
						'id'         => 'lightbox_caption',
						'type'       => 'switcher',
						'title'      => esc_html__( 'Lightbox Caption', 'woo-gallery-slider' ),
						'subtitle'   => esc_html__( 'Show caption in lightbox.', 'woo-gallery-slider' ),
						'text_on'    => esc_html__( 'Show', 'woo-gallery-slider' ),
						'text_off'   => esc_html__( 'Hide', 'woo-gallery-slider' ),
						'text_width' => 80,
						'default'    => true,
						'dependency' => array( 'lightbox', '==', true ),
					),
					array(
						'id'         => 'caption_color',
						'type'       => 'color',
						'title'      => esc_html__( 'Caption Color', 'woo-gallery-slider' ),
						'subtitle'   => esc_html__( 'Change caption color.', 'woo-gallery-slider' ),
						'default'    => '#ffffff',
						'dependency' => array( 'lightbox|lightbox_caption', '==|==', 'true|true' ),
					),
					array(
						'id'         => 'l_img_counter',
						'type'       => 'switcher',
						'title'      => esc_html__( 'Image Counter', 'woo-gallery-slider' ),
						'subtitle'   => esc_html__( 'Show lightbox image counter.', 'woo-gallery-slider' ),
						'text_on'    => esc_html__( 'Show', 'woo-gallery-slider' ),
						'text_off'   => esc_html__( 'Hide', 'woo-gallery-slider' ),
						'text_width' => 80,
						'default'    => true,
						'dependency' => array( 'lightbox', '==', true ),
					),
					array(
						'id'         => 'gallery_share',
						'type'       => 'switcher',
						'title'      => esc_html__( 'Social Share Button', 'woo-gallery-slider' ),
						'subtitle'   => esc_html__( 'Show/Hide social share button.', 'woo-gallery-slider' ),
						'text_on'    => esc_html__( 'Show', 'woo-gallery-slider' ),
						'text_off'   => esc_html__( 'Hide', 'woo-gallery-slider' ),
						'text_width' => 80,
						'default'    => false,
						'dependency' => array( 'lightbox', '==', true ),
					),
					array(
						'id'         => 'gallery_fs_btn',
						'type'       => 'switcher',
						'title'      => esc_html__( 'Full Screen Button', 'woo-gallery-slider' ),
						'subtitle'   => esc_html__( 'Show/Hide image full screen button.', 'woo-gallery-slider' ),
						'text_on'    => esc_html__( 'Show', 'woo-gallery-slider' ),
						'text_off'   => esc_html__( 'Hide', 'woo-gallery-slider' ),
						'text_width' => 80,
						'default'    => false,
						'dependency' => array( 'lightbox', '==', true ),
					),
				),
			)
		);
	}
}
