=== Hybrid Hook ===
Contributors: greenshady
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3687060
Tags: custom, PHP, HTML
Requires at least: 3.1
Tested up to: 3.2
Stable tag: 0.3

Allows the addition of HTML, shortcodes, PHP, and/or JavaScript to the Hybrid theme's hooks from the WordPress admin.

== Description ==

*Hybrid Hook* is a plugin that allows end users the ability to tap into the <a href="http://wordpress.org/extend/themes/hybrid" title="Hybrid WordPress theme framework">Hybrid theme's</a> extensive hook selection by providing a user interface in the WordPress admin for adding custom content.

You **must** be using at least version 0.9 of the <a href="http://wordpress.org/extend/themes/hybrid" title="Hybrid theme">Hybrid theme</a> for this plugin to work.  It will also not work with any other themes.

**Features:**

* Ability to add custom HTML nearly anywhere on your site.
* Use shortcodes to quickly add content.
* Allows the input of JavaScript.
* User can select the priority the code will run.
* Can execute PHP code if the option is selected.

== Installation ==

1. Upload `hybrid-hook` to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Go to <em>Appearance > Hybrid Hook</em> to input custom content.

More detailed instructions are included in the plugin's `readme.html` file.

== Frequently Asked Questions ==

= Why was this plugin created? =

This plugin was created so that end users and non-developers could take advantage of the Hybrid theme's extensive hook system.  It allows the input of HTML, PHP, shortcodes, and JavaScript from the WordPress admin.  It's a way to work around having to learn how to use the WordPress plugin API and just add content anywhere.

= How do I set it up? =

Under *Appearance > Hybrid Hook*, you can add custom code in individual textareas.  Each textarea corresponds to one of the Hybrid theme's hooks, and each is accompanied by a priority input box and a checkbox to execute PHP.

More detailed instructions are included in the plugin's `readme.html` file.

== Changelog ==

= Version 0.3 =

* Bumped the plugin requirements up to WordPress 3.1 and Hybrid 0.9.
* Revamped the entire plugin settings page interface to be (hopefully) more user friendly.
* Removed the `hybrid_before_page_nav` setting.
* Removed the `hybrid_after_page_nav` setting.
* Removed the `hybrid_after_single` setting.
* Removed the `hybrid_after_page` setting.
* Added the `hybrid_before_primary_menu` setting.
* Added the `hybrid_after_primary_menu` setting.
* Added the `hybrid_after_singular` setting.
* Created some additional meta boxes for donations and plugin support.
* Security enhancements with saving code, making sure the `unfiltered_html` cap is checked.
* Plugin users must now have the `edit_theme_options` cap to edit the settings.  This replaced the `edit_themes` cap.

= Version 0.2 =

**Important!** Note that you'll need to resave all of your settings.  I suggest making a copy of them in a text file.

* Plugin was rewritten from the ground up to allow it to be extended for more action hooks in the future.
* Added the ability to add shortcodes to the the different hook areas.
* Each bit of custom code can now be given a priority other than the default.
* Each section can now allow/disallow PHP code to be executed.
* Added new sections for several action hooks: `hybrid_before_entry`, `hybrid_after_entry`, `hybrid_before_subsidiary`, and `hybrid_after_subsidiary`.

= Version 0.1 =

* Plugin launch.  Everything's new!