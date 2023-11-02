<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: background
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! class_exists( 'WCGS_Field_background' ) ) {
  class WCGS_Field_background extends WCGS_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args                             = wp_parse_args( $this->field, array(
        'background_color'              => true,
        'background_image'              => true,
        'background_position'           => true,
        'background_repeat'             => true,
        'background_attachment'         => true,
        'background_size'               => true,
        'background_origin'             => false,
        'background_clip'               => false,
        'background_blend-mode'         => false,
        'background_gradient'           => false,
        'background_gradient_color'     => true,
        'background_gradient_direction' => true,
        'background_image_preview'      => true,
        'background_image_library'      => 'image',
        'background_image_placeholder'  => esc_html__( 'No background selected', 'woo-gallery-slider' ),
      ) );

      $default_value                    = array(
        'background-color'              => '',
        'background-image'              => '',
        'background-position'           => '',
        'background-repeat'             => '',
        'background-attachment'         => '',
        'background-size'               => '',
        'background-origin'             => '',
        'background-clip'               => '',
        'background-blend-mode'         => '',
        'background-gradient-color'     => '',
        'background-gradient-direction' => '',
      );

      $default_value = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;

      $this->value = wp_parse_args( $this->value, $default_value );

      echo $this->field_before();

      //
      // Background Color
      if( ! empty( $args['background_color'] ) ) {

        echo '<div class="wcgs--block wcgs--color">';
        echo ( ! empty( $args['background_gradient'] ) ) ? '<div class="wcgs--title">'. esc_html__( 'From', 'woo-gallery-slider' ) .'</div>' : '';

        WCGS::field( array(
          'id'      => 'background-color',
          'type'    => 'color',
          'default' => $default_value['background-color'],
        ), $this->value['background-color'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      //
      // Background Gradient Color
      if( ! empty( $args['background_gradient_color'] ) && ! empty( $args['background_gradient'] ) ) {

        echo '<div class="wcgs--block wcgs--color">';
        echo ( ! empty( $args['background_gradient'] ) ) ? '<div class="wcgs--title">'. esc_html__( 'To', 'woo-gallery-slider' ) .'</div>' : '';

        WCGS::field( array(
          'id'      => 'background-gradient-color',
          'type'    => 'color',
          'default' => $default_value['background-gradient-color'],
        ), $this->value['background-gradient-color'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      //
      // Background Gradient Direction
      if( ! empty( $args['background_gradient_direction'] ) && ! empty( $args['background_gradient'] ) ) {

        echo '<div class="wcgs--block wcgs--gradient">';
        echo ( ! empty( $args['background_gradient'] ) ) ? '<div class="wcgs--title">'. esc_html__( 'Direction', 'woo-gallery-slider' ) .'</div>' : '';

        WCGS::field( array(
          'id'          => 'background-gradient-direction',
          'type'        => 'select',
          'options'     => array(
            ''          => esc_html__( 'Gradient Direction', 'woo-gallery-slider' ),
            'to bottom' => esc_html__( '&#8659; top to bottom', 'woo-gallery-slider' ),
            'to right'  => esc_html__( '&#8658; left to right', 'woo-gallery-slider' ),
            '135deg'    => esc_html__( '&#8664; corner top to right', 'woo-gallery-slider' ),
            '-135deg'   => esc_html__( '&#8665; corner top to left', 'woo-gallery-slider' ),
          ),
        ), $this->value['background-gradient-direction'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      echo '<div class="clear"></div>';

      //
      // Background Image
      if( ! empty( $args['background_image'] ) ) {

        echo '<div class="wcgs--block wcgs--media">';

        WCGS::field( array(
          'id'          => 'background-image',
          'type'        => 'media',
          'library'     => $args['background_image_library'],
          'preview'     => $args['background_image_preview'],
          'placeholder' => $args['background_image_placeholder']
        ), $this->value['background-image'], $this->field_name(), 'field/background' );

        echo '</div>';

        echo '<div class="clear"></div>';

      }

      //
      // Background Position
      if( ! empty( $args['background_position'] ) ) {
        echo '<div class="wcgs--block wcgs--select">';

        WCGS::field( array(
          'id'              => 'background-position',
          'type'            => 'select',
          'options'         => array(
            ''              => esc_html__( 'Background Position', 'woo-gallery-slider' ),
            'left top'      => esc_html__( 'Left Top', 'woo-gallery-slider' ),
            'left center'   => esc_html__( 'Left Center', 'woo-gallery-slider' ),
            'left bottom'   => esc_html__( 'Left Bottom', 'woo-gallery-slider' ),
            'center top'    => esc_html__( 'Center Top', 'woo-gallery-slider' ),
            'center center' => esc_html__( 'Center Center', 'woo-gallery-slider' ),
            'center bottom' => esc_html__( 'Center Bottom', 'woo-gallery-slider' ),
            'right top'     => esc_html__( 'Right Top', 'woo-gallery-slider' ),
            'right center'  => esc_html__( 'Right Center', 'woo-gallery-slider' ),
            'right bottom'  => esc_html__( 'Right Bottom', 'woo-gallery-slider' ),
          ),
        ), $this->value['background-position'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      //
      // Background Repeat
      if( ! empty( $args['background_repeat'] ) ) {
        echo '<div class="wcgs--block wcgs--select">';

        WCGS::field( array(
          'id'          => 'background-repeat',
          'type'        => 'select',
          'options'     => array(
            ''          => esc_html__( 'Background Repeat', 'woo-gallery-slider' ),
            'repeat'    => esc_html__( 'Repeat', 'woo-gallery-slider' ),
            'no-repeat' => esc_html__( 'No Repeat', 'woo-gallery-slider' ),
            'repeat-x'  => esc_html__( 'Repeat Horizontally', 'woo-gallery-slider' ),
            'repeat-y'  => esc_html__( 'Repeat Vertically', 'woo-gallery-slider' ),
          ),
        ), $this->value['background-repeat'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      //
      // Background Attachment
      if( ! empty( $args['background_attachment'] ) ) {
        echo '<div class="wcgs--block wcgs--select">';

        WCGS::field( array(
          'id'       => 'background-attachment',
          'type'     => 'select',
          'options'  => array(
            ''       => esc_html__( 'Background Attachment', 'woo-gallery-slider' ),
            'scroll' => esc_html__( 'Scroll', 'woo-gallery-slider' ),
            'fixed'  => esc_html__( 'Fixed', 'woo-gallery-slider' ),
          ),
        ), $this->value['background-attachment'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      //
      // Background Size
      if( ! empty( $args['background_size'] ) ) {
        echo '<div class="wcgs--block wcgs--select">';

        WCGS::field( array(
          'id'        => 'background-size',
          'type'      => 'select',
          'options'   => array(
            ''        => esc_html__( 'Background Size', 'woo-gallery-slider' ),
            'cover'   => esc_html__( 'Cover', 'woo-gallery-slider' ),
            'contain' => esc_html__( 'Contain', 'woo-gallery-slider' ),
          ),
        ), $this->value['background-size'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      //
      // Background Origin
      if( ! empty( $args['background_origin'] ) ) {
        echo '<div class="wcgs--block wcgs--select">';

        WCGS::field( array(
          'id'            => 'background-origin',
          'type'          => 'select',
          'options'       => array(
            ''            => esc_html__( 'Background Origin', 'woo-gallery-slider' ),
            'padding-box' => esc_html__( 'Padding Box', 'woo-gallery-slider' ),
            'border-box'  => esc_html__( 'Border Box', 'woo-gallery-slider' ),
            'content-box' => esc_html__( 'Content Box', 'woo-gallery-slider' ),
          ),
        ), $this->value['background-origin'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      //
      // Background Clip
      if( ! empty( $args['background_clip'] ) ) {
        echo '<div class="wcgs--block wcgs--select">';

        WCGS::field( array(
          'id'            => 'background-clip',
          'type'          => 'select',
          'options'       => array(
            ''            => esc_html__( 'Background Clip', 'woo-gallery-slider' ),
            'border-box'  => esc_html__( 'Border Box', 'woo-gallery-slider' ),
            'padding-box' => esc_html__( 'Padding Box', 'woo-gallery-slider' ),
            'content-box' => esc_html__( 'Content Box', 'woo-gallery-slider' ),
          ),
        ), $this->value['background-clip'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      //
      // Background Blend Mode
      if( ! empty( $args['background_blend_mode'] ) ) {
        echo '<div class="wcgs--block wcgs--select">';

        WCGS::field( array(
          'id'            => 'background-blend-mode',
          'type'          => 'select',
          'options'       => array(
            ''            => esc_html__( 'Background Blend Mode', 'woo-gallery-slider' ),
            'normal'      => esc_html__( 'Normal', 'woo-gallery-slider' ),
            'multiply'    => esc_html__( 'Multiply', 'woo-gallery-slider' ),
            'screen'      => esc_html__( 'Screen', 'woo-gallery-slider' ),
            'overlay'     => esc_html__( 'Overlay', 'woo-gallery-slider' ),
            'darken'      => esc_html__( 'Darken', 'woo-gallery-slider' ),
            'lighten'     => esc_html__( 'Lighten', 'woo-gallery-slider' ),
            'color-dodge' => esc_html__( 'Color Dodge', 'woo-gallery-slider' ),
            'saturation'  => esc_html__( 'Saturation', 'woo-gallery-slider' ),
            'color'       => esc_html__( 'Color', 'woo-gallery-slider' ),
            'luminosity'  => esc_html__( 'Luminosity', 'woo-gallery-slider' ),
          ),
        ), $this->value['background-blend-mode'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      echo '<div class="clear"></div>';

      echo $this->field_after();

    }

    public function output() {

      $output    = '';
      $bg_image  = array();
      $important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
      $element   = ( is_array( $this->field['output'] ) ) ? join( ',', $this->field['output'] ) : $this->field['output'];

      // Background image and gradient
      $background_color        = ( ! empty( $this->value['background-color']              ) ) ? $this->value['background-color']              : '';
      $background_gd_color     = ( ! empty( $this->value['background-gradient-color']     ) ) ? $this->value['background-gradient-color']     : '';
      $background_gd_direction = ( ! empty( $this->value['background-gradient-direction'] ) ) ? $this->value['background-gradient-direction'] : '';
      $background_image        = ( ! empty( $this->value['background-image']['url']       ) ) ? $this->value['background-image']['url']       : '';


      if( $background_color && $background_gd_color ) {
        $gd_direction   = ( $background_gd_direction ) ? $background_gd_direction .',' : '';
        $bg_image[] = 'linear-gradient('. $gd_direction . $background_color .','. $background_gd_color .')';
      }

      if( $background_image ) {
        $bg_image[] = 'url('. $background_image .')';
      }

      if( ! empty( $bg_image ) ) {
        $output .= 'background-image:'. implode( ',', $bg_image ) . $important .';';
      }

      // Common background properties
      $properties = array( 'color', 'position', 'repeat', 'attachment', 'size', 'origin', 'clip', 'blend-mode' );

      foreach( $properties as $property ) {
        $property = 'background-'. $property;
        if( ! empty( $this->value[$property] ) ) {
          $output .= $property .':'. $this->value[$property] . $important .';';
        }
      }

      if( $output ) {
        $output = $element .'{'. $output .'}';
      }

      $this->parent->output_css .= $output;

      return $output;

    }

  }
}
