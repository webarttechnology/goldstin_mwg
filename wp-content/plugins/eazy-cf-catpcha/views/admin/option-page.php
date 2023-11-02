<div class="wrap eazycfc-config">
	<h1>Eazy CF Captcha - <?php _e( 'Configuration', 'eazycfc' ) ?></h1>

	<?php
	switch( $errormsg ):
		case 1:
	?>
			<div id="message" class="updated"><p><?php _e( 'Saved.' ); ?></p></div>
	<?php break;
		case 2:
	?>
			<div id="message" class="error"><p><?php _e( 'Error while saving the changes.' ); ?></p></div>
	<?php break;
		default:
		break;
	endswitch;
	?>

	<style>
		@media screen and (min-width: 768px) {
			.eazycfc-section {
				display: flex;
			}
			.eazycfc-main {
				width: 75%;
			}
			.eazycfc-side {
				width: 25%;
			}
		}
		.eazycfc-side {
			padding-left: 30px;
		}
		.eazycfc-main input[type=text] {
			width: 100%;
		}
		.eazycfc-donation-form .button {
			background-color: #ffb900;
			border-color: #ffb900;
			color: #23282d;
			font-weight: 700;
		}
		.eazycfc-donation-form .button:hover,
		.eazycfc-donation-form .button:focus,
		.eazycfc-donation-form .button:active {
			background-color: #f56e28;
			border-color: #f56e28;
			color: #fff;
		}
	</style>
	<section class="eazycfc-section">
		<main class="eazycfc-main">
			<form action="<?php echo admin_url( 'options-general.php?page=eazycfc' ); ?>" method="post">
				<?php 
					wp_nonce_field( 'eazycfc-config' ); 
				?>

				<table class="form-table" role="presentation">
					<tbody>

						<tr>
							<th scope="row">
								<label for="eazycfc-config-labeltext">
									<?php _e( 'Text for the Captcha Label', 'eazycfc' ); ?>
								</label>
							</th>
							<td>
								<input size="64" type="text" name="eazycfc-config-labeltext" id="eazycfc-config-labeltext" value="<?php echo esc_attr($options->label_text); ?>" />
							</td>
						</tr>

						<tr>
							<th scope="row">
								<label for="eazycfc-config-error_message">
									<?php _e( 'Error message', 'eazycfc' ); ?>
								</label>
							</th>
							<td>
								<input size="64" type="text" name="eazycfc-config-error_message" id="eazycfc-config-error_message" value="<?php echo esc_attr($options->error_message); ?>" />
							</td>
						</tr>

						<tr>
							<td colspan="2"><hr /></td>
						</tr>

						<tr>
							<th scope="row">
								<label for="eazycfc-config-honeypot">
									<?php _e( 'Honeypot', 'eazycfc' ); ?>
								</label>
							</th>
							<td>
								<label for="eazycfc-config-honeypot">
									<input name="eazycfc-config-honeypot" type="checkbox" id="eazycfc-config-honeypot" value="1" <?php if($options->honeypot === true): ?>checked="checked"<?php endif; ?> />
									<?php _e('Add honeypot', 'eazycfc'); ?>
								</label>
								<p class="description">
									<?php _e('Add an additional hidden field for enhanced security.', 'eazycfc'); ?>
								</p>
							</td>
						</tr>
						
						<tr>
							<th scope="row">
								<label for="eazycfc-config-easy">
									<?php _e( 'Keep it easy', 'eazycfc' ); ?>
								</label>
							</th>
							<td>
								<label for="eazycfc-config-easy">
									<input name="eazycfc-config-easy" type="checkbox" id="eazycfc-config-easy" value="1" <?php if($options->easy === true): ?>checked="checked"<?php endif; ?> />
									<?php _e('Make the exercise easier', 'eazycfc'); ?>
								</label>
								<p class="description">
									<?php _e('Use only one digit number to make it easier instead of two digits.', 'eazycfc'); ?>
								</p>
							</td>
						</tr>

						<tr>
							<td colspan="2"><hr /></td>
						</tr>

						<tr>
							<th scope="row">
								<label for="eazycfc-config-disable_comment_form">
									<?php _e( 'Deactivate in comments', 'eazycfc' ); ?>
								</label>
							</th>
							<td>
								<label for="eazycfc-config-disable_comment_form">
									<input name="eazycfc-config-disable_comment_form" type="checkbox" id="eazycfc-config-disable_comment_form" value="1" <?php if($options->disable_comment_form === true): ?>checked="checked"<?php endif; ?> />
									<?php _e('Deactivate', 'eazycfc'); ?>
								</label>
								<p class="description">
									<?php _e('Will be disabled in comments, but is still usable for contact form 7.', 'eazycfc'); ?>
								</p>
							</td>
						</tr>

					</tbody>
				</table>

				<p class="submit">
					<input type="submit" name="eazycfc-config-submit" class="button-primary" value="<?php _e( 'Save Changes' ); ?>" />
				</p>
			</form>
		</main>
		<aside class="eazycfc-side">
			<h4><?php _e( 'Support the development of this plugin.', 'eazycfc' ); ?></h4>
			<form class="eazycfc-donation-form" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
				<input type="hidden" name="cmd" value="_s-xclick" />
				<input type="hidden" name="hosted_button_id" value="QQMET2BDE8CJC" />
				<p class="submit">
					<input type="submit" name="eazycfc-config-submit" class="button" value="<?php _e( 'Donate (PayPal)', 'eazycfc' ); ?>" />
				</p>
			</form>
		</aside>
	</section>
</div>