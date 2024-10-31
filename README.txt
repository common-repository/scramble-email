=== Scramble Email ===
Contributors: Kuuak
Tags: email, anti-spam, shortcode
Requires at least: 4.4
Tested up to: 4.9.4
Stable tag: 1.2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Simple shortcode to scramble (hide) email addresses to email bot harvesters.

== Description ==

Protect your email addresses from being harvested by automatic bots.
Simply replace the emails in your post or page content by an unique shortcode.

== Installation ==

1. Upload `scramble-email.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use  the shortcode `[scem email="user@example.com" title="Contact me" class="optional-class" subject="Optional email subject" /]` in post content.

== Frequently Asked Questions ==

== Screenshots ==

1. Use of the shortcode in a post content
2. Email link rendered in post page

== Changelog ==

= 1.2.1 - 27/02/2018 =
* Fix: JS template string not supported by older browsers

= 1.2.0 - 26/02/2018 =
* Add: WYSIWYG content filtering. Automatically scramble the email link in the WYSIWYG editor. activable by an option in the new options page.
* Function to scramble any email link a a given string with the new `scramble_email_filter` function.
* Refactor scramble email functions to handle any HTML attribute.

= 1.1.0 - 29/01/2018 =
* Add: Possibility to use as a function in addition to a shortcode.

= 1.0.1 - 08/02/2017 =
* Fix: Undefined `currentScript` variable when document loaded via ajax

= 1.0.0 =
* Initial release
