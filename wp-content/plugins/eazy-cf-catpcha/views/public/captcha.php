<p class="comment-form-captcha">
  <?php if ( !empty( $options->label_text ) ): ?>
  <label for="eazycfc_captcha">
    <?php echo $options->label_text; ?>
  </label>
  <?php endif; ?>
  
  <?php require('captcha-field.php'); ?>
</p>