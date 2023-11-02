<?php

//phpcs:disable WordPress.Files.FileName

namespace TK\EazyCFCaptcha\Helper;

use Exception;

defined( 'ABSPATH' ) || exit;

class SessionHandler {
	protected $current_session_id;

	public function __construct() {
		$this->gc( ini_get('session.gc_maxlifetime') ?? 1440 );
		$this->start( true );
		$this->current_session_id = session_id();
	}

	public function start( $read_and_close = false ) {
		if ( !session_id() && !headers_sent() ) {
			session_start(
				array(
					'read_and_close' => $read_and_close
				)
			);
		}
	}

	public function read() {
		if ( ! $this->current_session_id ) {
			return false;
		}

		$value = get_option( "_eazycfc_captcha_result_{$this->current_session_id}", '' );

		if( ! is_int( $value ) && '' !== $value ) {
			$value = intval( $value );
		}

		return $value;
	}

	public function write( int $value ) {
		if ( ! $this->current_session_id ) {
			return false;
		}
		$inserted = update_option( "_eazycfc_captcha_result_{$this->current_session_id}", $value );
		update_option( "_eazycfc_captcha_result_created_{$this->current_session_id}", time() );

		return $inserted;
	}

	public function destroy() {
		if ( ! $this->current_session_id ) {
			return false;
		}
		delete_option( "_eazycfc_captcha_result_{$this->current_session_id}" );
		delete_option( "_eazycfc_captcha_result_created_{$this->current_session_id}" );

		return true;
	}

	public function gc($maxlifetime) {
		global $wpdb;
		$past = time() - $maxlifetime;

		$query = $wpdb->prepare(
			"SELECT option_name FROM {$wpdb->prefix}options WHERE option_name LIKE %s AND option_value < %s",
			'_eazycfc_captcha_result_created_%',
			$past
		);
		$results = $wpdb->get_results( $query );

		foreach ( $results as $result ) {
			$id = str_replace( '_eazycfc_captcha_result_created_', '', $result->option_name );
			$this->destroy( $id );
		}

		return true;
	}
}
