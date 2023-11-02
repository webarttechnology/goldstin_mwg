<div class="control-box">
	<fieldset>
		<table class="form-table">
			<tbody>

				<tr>
					<th scope="row"><?php echo esc_html( __( 'Field type', 'contact-form-7' ) ); ?></th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><?php echo esc_html( __( 'Field type', 'contact-form-7' ) ); ?></legend>
							<label>
								<input type="checkbox" name="required" checked />
								<?php echo esc_html( __( 'Required field', 'contact-form-7' ) ); ?>
							</label>
						</fieldset>
					</td>
				</tr>

				<tr>
					<td colspan="2"><hr /></td>
				</tr>

				<tr>
					<th scope="row">
						<?php _e('Remove honeypot', 'eazycfc'); ?>
					</th>
					<td>
						<legend class="screen-reader-text"><?php _e( 'Remove additional honeypot', 'eazycfc' ); ?></legend>
						<label>
							<input class="option" name="no-honeypot" type="checkbox" <?php if($options->honeypot === false): ?>checked="checked"<?php endif; ?> />
							<?php _e( 'Remove additional honeypot', 'eazycfc' ); ?>
						</label>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<?php echo esc_html( __( 'Keep it easy', 'eazycfc' ) ); ?>
					</th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><?php echo esc_html( __( 'Keep it easy', 'eazycfc' ) ); ?></legend>
							<label>
								<input class="option" name="easy" type="checkbox" <?php if($options->easy): ?>checked="checked"<?php endif; ?> />
								<?php _e('Make the exercise easier (one digit numbers instead of two)', 'eazycfc'); ?>
							</label>
						</fieldset>
					</td>
				</tr>

				<tr>
					<td colspan="2"><hr /></td>
				</tr>

				<tr>
				<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-id' ); ?>"><?php echo esc_html( __( 'Id attribute', 'contact-form-7' ) ); ?></label></th>
				<td><input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-id' ); ?>" /></td>
				</tr>

				<tr>
				<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class attribute', 'contact-form-7' ) ); ?></label></th>
				<td><input type="text" name="class" class="classvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-class' ); ?>" /></td>
				</tr>

			</tbody>
		</table>

	</fieldset>
</div>

<div class="insert-box">
	<input type="text" name="eazy_cf_captcha" class="tag code" readonly="readonly" onfocus="this.select()" />

	<div class="submitbox">
		<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
	</div>
</div>