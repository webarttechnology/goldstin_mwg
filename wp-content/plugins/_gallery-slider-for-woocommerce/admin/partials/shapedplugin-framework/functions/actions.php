<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

/**
 *
 * Reset Ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'wcgs_reset_ajax' ) ) {
  function wcgs_reset_ajax() {

    $nonce  = ( ! empty( $_POST[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ) : '';
    $unique = ( ! empty( $_POST[ 'unique' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'unique' ] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'wcgs_backup_nonce' ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'woo-gallery-slider' ) ) );
    }

    // Success
    delete_option( $unique );

    wp_send_json_success();

  }
  add_action( 'wp_ajax_wcgs-reset', 'wcgs_reset_ajax' );
}
