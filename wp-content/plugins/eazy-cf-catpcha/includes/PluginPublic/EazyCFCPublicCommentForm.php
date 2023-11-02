<?php
namespace TK\EazyCFCaptcha\PluginPublic;

use TK\EazyCFCaptcha\PluginPublic\EazyCFCPublicBase;
use TK\EazyCFCaptcha\Helper\EazyCFCaptchaOptions;


class EazyCFCPublicCommentForm extends EazyCFCPublicBase {
	protected $options;

	public function __construct()
	{
		$this->options= new EazyCFCaptchaOptions();
		parent::__construct();
	}

	protected function addHooks()
	{
		if(!$this->options->disable_comment_form) {
			add_filter( 'comment_form_after_fields', array($this, 'renderCaptchaField'), 999, 1 );
			add_action( 'pre_comment_on_post', array($this, 'checkInput'), 999, 1 );
		}
	}

	public function renderCaptchaField()
	{
		if ( is_user_logged_in() ) {
			return;
		}

		echo $this->renderField();
	}

	protected function validCaptcha()
	{
		$name = $this->options->field_name;
		return isset( $_POST[ $name ] ) && $this->compareSolution( trim( $_POST[ $name ] ) );
	}

	protected function validHoneypot()
	{
		$name = $this->options->honeypot_field_name;
		return !isset( $_POST[ $name ] ) || empty( trim( $_POST[ $name ] ) );
	}

	protected function throwError()
	{
		wp_die(
			'<p>' . $this->options->error_message . '</p>',
			__( 'Comment Submission Failure' ),
			array(
				'response'  => 200,
				'back_link' => true,
			)
		);
		exit;
	}

	public function checkInput($comment_post_ID)
	{
		if ( is_user_logged_in() ) {
			return $comment_post_ID;
		}

		if( !$this->validCaptcha() || !$this->validHoneypot() ) {
			return $this->throwError();
		}
		
		return $comment_post_ID;
	}

}
