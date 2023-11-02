<?php

namespace TK\EazyCFCaptcha\Helper;


if ( ! class_exists( 'Gamajo_Template_Loader' ) ) {
  require'class-gamajo-template-loader.php';
}

class EazyCFCaptchaTemplateLoader extends \Gamajo_Template_Loader {
  /**
   * Prefix for filter names.
   *
   * @since 1.1.0
   *
   * @var string
   */
  protected $filter_prefix = 'eazy-cf-captcha';

  /**
   * Directory name where custom templates for this plugin should be found in the theme.
   *
   * @since 1.1.0
   *
   * @var string
   */
  protected $theme_template_directory = 'eazy-cf-captcha';

  /**
   * Reference to the root directory path of this plugin.
   *
   * @since 1.1.0
   *
   * @var string
   */
  protected $plugin_directory = EAZY_CF_CAPTCHA_PATH;

  /**
   * Directory name where templates are found in this plugin.
   *
   * @since 1.1.0
   *
   * @var string
   */
  protected $plugin_template_directory = 'views/public';
}