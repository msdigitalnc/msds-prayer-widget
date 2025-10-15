<?php
/**
 * Plugin Name: MSDS Prayer Request Widget
 * Description: Floating prayer-request chat-style widget that opens a Gravity Form in a pop-up. Compatible with Divi Theme Builder and standard themes.
 * Version: 1.0.1
 * Author: MS Digital Solutions, LLC
 * License: GPLv2 or later
 * Text Domain: msds-prayer-widget
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'MSDS_PW_VERSION', '1.0.1' );
define( 'MSDS_PW_DIR', plugin_dir_path( __FILE__ ) );
define( 'MSDS_PW_URL', plugin_dir_url( __FILE__ ) );

register_activation_hook( __FILE__, function() {
    $defaults = [
        'position' => 'bottom-right',
        'icon_bg_color' => '#1f2937',
        'icon_color' => '#ffffff',
        'icon_class' => '',
        'gf_form_id' => 0,
        'exclusions' => '',
    ];
    $opts = get_option( 'msds_prayer_widget_options', [] );
    update_option( 'msds_prayer_widget_options', wp_parse_args( $opts, $defaults ) );
});

add_action( 'init', function() {
    load_plugin_textdomain( 'msds-prayer-widget', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
});

require_once MSDS_PW_DIR . 'includes/class-msds-prayer-widget.php';
require_once MSDS_PW_DIR . 'admin/class-msds-prayer-widget-admin.php';

add_action( 'plugins_loaded', function() {
    \MSDS_PW\Plugin::instance();
    if ( is_admin() ) {
        \MSDS_PW\Admin::instance();
    }
});
