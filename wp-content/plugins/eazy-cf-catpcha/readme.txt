=== Eazy CF Captcha ===
Contributors: playwithpixels
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=QQMET2BDE8CJC&source=url
Tags: comment form, contact form 7, elementor, captcha, cf7, antispam, comment, contact, form, spam, cf captcha, cf7 captcha, mathematic, exercise, eazy, simple, captcha, tk, easy
Stable tag: 1.2.6
Requires PHP: 7.4
Requires at least: 2.9.0
Tested up to: 6.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Eazy C(omment)F(orm) Captcha adds a mathematic exercise to the comment form, contact form 7 & elementor, preventing bots to spam your comments and forms. Now with Elementor & Contact Form 7 support!

== Description ==

NEW! With support for Elementor Form Widget.

Eazy C(omment)F(orm) Captcha adds a mathematic exercise to the comment form, contact form 7 & elementor, preventing bots to spam your comments and forms. Now with Elementor & Contact Form 7 support!

You can easily customize the label text for the captcha in the backend of Wordpress. Also you can change the HTML Markup if you like.

With support for Contact Form 7.

== Screenshots ==

1. The captcha in the comment form. Also integrating with the standard WordPress Twenty Twenty Theme
2. The admin settings page
3. Support for Comment Form 7
4. Settings in the backend of Comment Form 7
5. Settings in the Elementor editor

== Installation ==


1. Install Plugin through the WordPress Admin Interface 'Plugins > Add new'
1. Activate the plugin
1. Go to 'Settings > Eazy CF Captcha' menu and change the label text.
1. Done!

OR

1. Upload `eazy-cf-captcha` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to 'Settings > Eazy CF Captcha' menu and change the label text.
1. Done!

== Frequently Asked Questions ==

= The field does not show up in a form? =

In the current form of implementation the field is not shown for logged in users. Try to visit the as anonymous user (e.g. in another browser or log out first).

= Can I change the label text? =

Of course, just go to 'Settings > Eazy CF Captcha' menu and there you have an input field, where you can change the text to whatever you want.

= How do I customize the HTML Markup? =

1. Make a new folder called 'eazy-cf-captcha' in your current template folder
1. Create a file named 'captcha.php'
1. Copy the content from 'your-wordress-folder/wp-content/plugins/eazy-cf-captcha/views/public/captcha.php' and 'your-wordress-folder/wp-content/plugins/eazy-cf-captcha/views/public/captcha-field.php' or write your own HTML
1. Two variables are available in the template: `$options` for all options set in the backend and `$exercise`, an array filled with the variables and operators for the exercise (see 'your-wordress-folder/wp-content/plugins/eazy-cf-captcha/views/public/captcha-field.php' for usage)
1. Done!

== Changelog ==

= 1.2.5 =

* Fix error on activation as pointed out by [@peter09z ](https://wordpress.org/support/users/peter09z/) (See also: [Thread on WordPress.org Support](https://wordpress.org/support/topic/plugin-could-not-be-activated-because-it-triggered-a-fatal-error-701/))
* Tested with CF7 version 5.7.7

= 1.2.4 =

* Fix autoloader causing warning on strict open_basedir php settings as pointed out by [@taruk](https://wordpress.org/support/users/taruk/)
* Tested with CF7 version 5.7.4

= 1.2.3 =

* Fix fatal error if elementor is not installed.

= 1.2.2 =

* Get rid of critical session issues on Site Health test as pointed out by [@tjdyo](https://wordpress.org/support/users/tjdyo/) (See also: [Thread on WordPress.org Support](https://wordpress.org/support/topic/php-session-staying-active-rest-api-issues-fix/))
* Add support for newest Elementor version
* Fix not displaying error messages in newest versions of Contact Form 7.

= 1.2.1 =

Add option on elementor widget: Show field for logged in users

= 1.2.0 =

* Add **Elementor support**

= 1.1.2 =

* Add Settings and donate links

= 1.1.1 =

* Fix activation / deactivation error

= 1.1.0 =

* Add **Contact Form 7 Support**
* Add **more options**. You can now make the captcha easier and remove the honeypot field, set a custom error message or remove it from comment forms
* Move settings page to **'Settings > Eazy CF Captcha'**
* Fix compatibility with newest WordPress version

= 1.0.4 =

* Shiny new Banner and Icon
* Fixed issues with slashes added on quotes in label field

= 1.0.3 =

* Updated author info

= 1.0.2 =

* Added possibility to translate the captcha text with WPML

= 1.0.1 =

* Fixed direction for translations due to a typing error in the repository name
* Suppressed output of the label tag if label text is empty

= 1.0 =
* Initial release.

== Upgrade Notice ==

= 1.2.4 =

Fix autoloader causing warnings.

= 1.2.3 =

Fix fatal error if elementor is not installed.

= 1.2.2 =

Remove session errors in Site Health & support newest Elementor and Contact form 7 versions.

= 1.1.0 =
Compatibility with never WordPress versions
