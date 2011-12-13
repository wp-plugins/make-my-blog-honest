=== Plugin Name ===
Contributors: dan.imbrogno
Donate link: http://bloggingsquared.com
Tags: template, tutorial, plugin
Requires at least: 3.2
Tested up to: 3.3
Stable tag: 1.4

Learn how to build better WordPress plugins with this handy tutorial. You'll learn some good plugin techniques in this easy to follow tutorial.

== Description ==

Learn how to build better WordPress plugins with this handy tutorial. You'll learn how to use classes, internationalize your plugin, use ajax from the front and back end and create your own database tables

== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Open up `/wp-content/plugins/step1.php` and read through the comments
1. Use the navigation bar at the bottom of your screen to advance to Step 2
1. Open up `/wp-content/plugins/step2.php` and read through the comments
1. Repeat steps 4 and 5 until you're done!

== Frequently Asked Questions ==

== Screenshots ==

1. Create options pages
2. Internationalize your plugin
3. Use Ajax on viewer facing pages
4. Use Ajax on admin facing pages

== Changelog ==

= 1.4 =
* Changed usage of wp_print_scripts to wp_enqueue_styles to conform to new standards introduced in WordPress 3.3

= 1.3.3 =
* Improved installation functions, stopped using activation / deactivation hooks
* Fixed $wpdb->prepare placeholders for adding a deal

= 1.3.2 =
* Moved load_plugin_textdomain into init action
* Moved adding default options into an init action

= 1.3.1 =
* Made internationalization work for the tutorial guide

= 1.3 =
* Moved plugin files into root of plugin directory

= 1.2 =
* Initial release

== Upgrade Notice ==