<?php
/**
 * Plugin Name: Eazy CF Captcha
 * Plugin URI: http://wordpress.org/plugins/eazy-cf-catpcha/
 * Description: Eazy C(omment)F(orm) Captcha adds a mathematic exercise to the comment form, contact form 7 & elementor, preventing bots to spam your comments and forms.
 * Version: 1.2.6
 * Requires at least: 2.9.0
 * Tested up to: 6.3
 * Requires PHP: 7.4
 * Author: Tamás Kiss
 * Author URI: http://tamas-kiss.com
 * Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=QQMET2BDE8CJC&source=url
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: eazycfc
 * Domain Path: /languages
 */

if (!defined('WPINC')) {
  die;
}

define('TK_EAZY_CF_CAPTCHA_VERSION', '1.0.0');
define('EAZY_CF_CAPTCHA_PLUGIN_BASENAME', plugin_basename(__FILE__));
define( 'EAZY_CF_CAPTCHA_URL', plugin_dir_url( __FILE__ ) );
define('EAZY_CF_CAPTCHA_PATH', plugin_dir_path( __FILE__ ));
define('EAZY_CF_CAPTCHA_DIR', str_replace(trailingslashit(WP_PLUGIN_DIR), '', EAZY_CF_CAPTCHA_PATH));

require plugin_dir_path(__FILE__) . 'includes/Autoloader.php';

$eazyCFCaptchaPlugin = new TK\EazyCFCaptcha\EazyCFCaptchaPlugin();
register_activation_hook(__FILE__, array($eazyCFCaptchaPlugin, 'activate'));
register_deactivation_hook(__FILE__, array($eazyCFCaptchaPlugin, 'deactivate'));
