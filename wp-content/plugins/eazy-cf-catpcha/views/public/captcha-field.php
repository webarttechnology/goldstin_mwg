<span class="<?php echo $options->container_class; ?> eazycfc_captcha-exercise" data-name="<?php echo $options->field_name; ?>">
	<label for="<?php echo $options->id ?? $options->field_name; ?>">
		<span><?php echo $exercise->var1; ?></span>
		<span><?php echo $exercise->operator; ?></span>
		<span class="eazycfc-hide"><?php echo $exercise->bot_var; ?></span>
		<span class="eazycfc-hide"><?php echo $exercise->bot_operator; ?></span>
		<span><?php echo $exercise->var2; ?></span> =
	</label>

	<input
		type="text"
		name="<?php echo $options->field_name; ?>"
		<?php if($options->id): ?>
		id="<?php echo $options->id; ?>"
		<?php endif; ?>
		<?php if($options->classes): ?>
		class="<?php echo $options->classes; ?>"
		<?php endif; ?>
	/>

	<?php if($options->honeypot): ?>
		<input class="eazycfc-hide" type="text" name="<?php echo $options->honeypot_field_name; ?>" value="" />
	<?php endif; ?>
</span>