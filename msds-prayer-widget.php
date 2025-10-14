<?php
/**
 * Plugin Name: MSDS Prayer Request Widget
 * Plugin URI: https://msdigitalsolutions.com
 * Description: A lightweight, Gravity Forms–powered floating prayer request widget that opens a chat-style pop-up window. Compatible with most modern WordPress themes and page builders, including Gutenberg, Divi, Elementor, and Beaver Builder.
 * Version: 1.0.0
 * Author: MS Digital Solutions, LLC
 * Author URI: https://msdigitalsolutions.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: msds-prayer-widget
 * Domain Path: /languages
 *
 * @package MSDS_Prayer_Request_Widget
 *
 * Requires at least: 6.0
 * Tested up to: 6.7
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'MSDS_PW_VERSION', '1.0.0' );
define( 'MSDS_PW_DIR', plugin_dir_path( __FILE__ ) );
define( 'MSDS_PW_URL', plugin_dir_url( __FILE__ ) );

register_activation_hook( __FILE__, function() {
    $defaults = [
        'position'       => 'bottom-right',
        'icon_bg_color'  => '#1f2937',
        'icon_color'     => '#ffffff',
        'icon_class'     => '',
        'gf_form_id'     => 0,
        'exclusions'     => '',
    ];
    $opts = get_option( 'msds_prayer_widget_options', [] );
    update_option( 'msds_prayer_widget_options', wp_parse_args( $opts, $defaults ) );
} );

add_action( 'init', function() {
    load_plugin_textdomain( 'msds-prayer-widget', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
} );

require_once MSDS_PW_DIR . 'includes/class-msds-prayer-widget.php';
require_once MSDS_PW_DIR . 'admin/class-msds-prayer-widget-admin.php';

add_action( 'plugins_loaded', function() {
    if ( class_exists( '\MSDS_PW\Plugin' ) ) {
        \MSDS_PW\Plugin::instance();
    }
    if ( is_admin() && class_exists( '\MSDS_PW\Admin' ) ) {
        \MSDS_PW\Admin::instance();
    }
} );
