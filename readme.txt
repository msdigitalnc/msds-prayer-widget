=== MSDS Prayer Request Widget ===
Contributors: msdigitalsolutions
Tags: prayer, form, chat widget, gravity forms, popup, divi, elementor, gutenberg
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A lightweight, Gravity Forms–powered floating prayer request widget that opens a chat-style pop-up window. Compatible with most modern WordPress themes and page builders, including Gutenberg, Divi, Elementor, and Beaver Builder.

== Description ==

The **MSDS Prayer Request Widget** adds a subtle, chat-style button to your website that allows visitors to quickly submit prayer requests using a specified Gravity Form.

This plugin is ideal for churches, ministries, and faith-based organizations who want to collect prayer requests through an interactive, elegant interface without slowing down their site.

### Key Features
- Displays a floating, round “prayer” icon in any corner of the screen.
- Opens a pop-up with a designated **Gravity Form** when clicked.
- Includes simple admin settings for:
  - Button position (bottom-right, bottom-left, top-right, top-left)
  - Icon background color and icon color
  - Font Awesome icon class (e.g., `fa-solid fa-hands-praying`)
  - Gravity Form ID to display
  - Page and post exclusions (comma-separated IDs)
- Smooth open/close animations and accessibility-friendly focus trapping.
- Automatically detects if Gravity Forms is inactive and displays a warning.
- Works seamlessly with **modern WordPress themes and page builders**, including **Gutenberg**, **Divi**, **Elementor**, **Beaver Builder**, **Bricks**, **Oxygen**, **Kadence**, and others.

### Requirements
- WordPress 6.0 or newer
- PHP 7.4 or newer
- Gravity Forms plugin (optional but recommended)

### Compatibility
- Fully compatible with Divi, Elementor, Beaver Builder, Oxygen, Bricks, Kadence, and Gutenberg block themes.
- Works with both classic and full-site editing (FSE) themes.
- Tested with PHP 7.4 through 8.3.

== Installation ==
1. Upload the plugin folder `msds-prayer-widget` to `/wp-content/plugins/` or use **Plugins → Add New → Upload Plugin**.
2. Activate the plugin.
3. Go to **Settings → Prayer Request Widget** to configure position, icon, and form.
4. Save your settings and verify the floating button appears on the front end.

== Frequently Asked Questions ==
= Do I need Gravity Forms installed? =
Yes, to collect submissions you need Gravity Forms. If it isn’t active, a friendly message is shown instead of a form.

= Can I change the icon? =
Yes—enter any **Font Awesome** class (e.g., `fa-solid fa-hands-praying`). If left blank, a default SVG icon is used.

= Can I hide it on certain pages? =
Yes—enter comma-separated page/post IDs under **Page Exclusions** (e.g., `2,15,98`).

= Does it work with Gutenberg and block themes? =
Yes—the widget renders via core WordPress hooks and works seamlessly with **block-based and full-site editing (FSE)** themes.

= Does it work with other page builders? =
Yes—compatible with **Divi**, **Elementor**, **Beaver Builder**, **Bricks**, **Oxygen**, and others.

== Screenshots ==
1. Settings panel in WordPress admin.
2. Floating prayer button (corner).
3. Pop-up Gravity Form overlay.

== Changelog ==
= 1.0.0 =
* Initial public release.
* Front-end popup with Gravity Form integration.
* Admin settings for customization and exclusions.
* Smooth transitions and accessibility enhancements.
* Uninstall cleanup for option data.

== Upgrade Notice ==
= 1.0.0 =
First release — safe for production use.

== Links ==
Homepage: https://msdigitalsolutions.com
