<?php
namespace MSDS_PW;

if ( ! defined( 'ABSPATH' ) ) exit;

class Admin {
    private static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) self::$instance = new self();
        return self::$instance;
    }

    private function __construct() {
        add_action( 'admin_menu',  [ $this, 'add_menu' ] );
        add_action( 'admin_init',  [ $this, 'register_settings' ] );
        add_action( 'admin_notices', [ $this, 'gf_missing_notice' ] );
    }

    public function add_menu() {
        add_options_page(
            __( 'Prayer Request Widget', 'msds-prayer-widget' ),
            __( 'Prayer Request Widget', 'msds-prayer-widget' ),
            'manage_options',
            'msds-prayer-widget',
            [ $this, 'render_settings_page' ]
        );
    }

    public function register_settings() {
        register_setting( 'msds_pw_settings', 'msds_prayer_widget_options', [ $this, 'sanitize_options' ] );

        add_settings_section( 'main', __( 'Widget Settings', 'msds-prayer-widget' ), '__return_false', 'msds-prayer-widget' );

        add_settings_field( 'position', __( 'Position', 'msds-prayer-widget' ), [ $this, 'field_position' ], 'msds-prayer-widget', 'main' );
        add_settings_field( 'icon_bg_color', __( 'Icon Background Color', 'msds-prayer-widget' ), [ $this, 'field_bg' ], 'msds-prayer-widget', 'main' );
        add_settings_field( 'icon_color', __( 'Icon Color', 'msds-prayer-widget' ), [ $this, 'field_fg' ], 'msds-prayer-widget', 'main' );
        add_settings_field( 'icon_class', __( 'Icon Class (Font Awesome)', 'msds-prayer-widget' ), [ $this, 'field_class' ], 'msds-prayer-widget', 'main' );
        add_settings_field( 'gf_form_id', __( 'Gravity Form ID', 'msds-prayer-widget' ), [ $this, 'field_gf' ], 'msds-prayer-widget', 'main' );

        // ✅ New field for tooltip override
        add_settings_field(
            'tooltip_text',
            __( 'Override Tooltip Text', 'msds-prayer-widget' ),
            [ $this, 'field_tooltip' ],
            'msds-prayer-widget',
            'main'
        );

        add_settings_field( 'exclusions', __( 'Page/Post Exclusions (IDs, comma-separated)', 'msds-prayer-widget' ), [ $this, 'field_ex' ], 'msds-prayer-widget', 'main' );
    }

    public function sanitize_options( $input ) {
        $out = [];
        $positions = [ 'bottom-right', 'bottom-left', 'top-left', 'top-right' ];

        $out['position']      = in_array( $input['position'] ?? '', $positions, true ) ? $input['position'] : 'bottom-right';
        $out['icon_bg_color'] = sanitize_hex_color( $input['icon_bg_color'] ?? '#1f2937' ) ?: '#1f2937';
        $out['icon_color']    = sanitize_hex_color( $input['icon_color'] ?? '#ffffff' ) ?: '#ffffff';
        $out['icon_class']    = sanitize_text_field( $input['icon_class'] ?? '' );
        $out['gf_form_id']    = absint( $input['gf_form_id'] ?? 0 );
        $out['tooltip_text']  = sanitize_text_field( $input['tooltip_text'] ?? 'Prayer Request' );

        $ex = array_filter( array_map( 'trim', explode( ',', $input['exclusions'] ?? '' ) ) );
        $out['exclusions'] = implode( ',', array_map( 'absint', $ex ) );

        return $out;
    }

    public function render_settings_page() {
        if ( ! current_user_can( 'manage_options' ) ) return;

        $screen = function_exists('get_current_screen') ? get_current_screen() : null;
        if ( $screen ) {
            $screen->add_help_tab( [
                'id'      => 'msds_pw_help',
                'title'   => __( 'Styling Reference', 'msds-prayer-widget' ),
                'content' => $this->help_tab_content(),
            ] );
        }

        echo '<div class="wrap">';
        echo '<h1>' . esc_html__( 'Prayer Request Widget', 'msds-prayer-widget' ) . '</h1>';
        echo '<form method="post" action="options.php">';
        settings_fields( 'msds_pw_settings' );
        do_settings_sections( 'msds-prayer-widget' );
        submit_button();
        echo '</form>';
        echo '</div>';
    }

    private function help_tab_content() {
        ob_start(); ?>
        <ul>
            <li><code>.msds-pw-button</code> — Floating trigger button</li>
            <li><code>.msds-pw-popup</code> — Popup container</li>
            <li><code>.msds-pw-popup__inner</code> — Inner content wrapper</li>
            <li><code>.msds-pw-overlay</code> — Screen overlay</li>
            <li><code>.msds-pw-close</code> — Close button</li>
        </ul>
        <?php
        return ob_get_clean();
    }

    public function field_position() {
        $o = get_option( 'msds_prayer_widget_options', [] );
        $v = $o['position'] ?? 'bottom-right';
        $positions = [
            'bottom-right' => __( 'Bottom Right', 'msds-prayer-widget' ),
            'bottom-left'  => __( 'Bottom Left',  'msds-prayer-widget' ),
            'top-left'     => __( 'Top Left',     'msds-prayer-widget' ),
            'top-right'    => __( 'Top Right',    'msds-prayer-widget' ),
        ];
        echo '<select name="msds_prayer_widget_options[position]">';
        foreach ( $positions as $key => $label ) {
            printf( '<option value="%s" %s>%s</option>', esc_attr( $key ), selected( $v, $key, false ), esc_html( $label ) );
        }
        echo '</select>';
    }

    public function field_bg() {
        $o = get_option( 'msds_prayer_widget_options', [] );
        $v = esc_attr( $o['icon_bg_color'] ?? '#1f2937' );
        echo '<input type="text" name="msds_prayer_widget_options[icon_bg_color]" value="' . $v . '" class="regular-text" />';
    }

    public function field_fg() {
        $o = get_option( 'msds_prayer_widget_options', [] );
        $v = esc_attr( $o['icon_color'] ?? '#ffffff' );
        echo '<input type="text" name="msds_prayer_widget_options[icon_color]" value="' . $v . '" class="regular-text" />';
    }

    public function field_class() {
        $o = get_option( 'msds_prayer_widget_options', [] );
        $v = esc_attr( $o['icon_class'] ?? '' );
        echo '<input type="text" name="msds_prayer_widget_options[icon_class]" value="' . $v . '" class="regular-text" placeholder="e.g. fa-solid fa-hands-praying" />';
    }

    public function field_gf() {
        $o = get_option( 'msds_prayer_widget_options', [] );
        $v = absint( $o['gf_form_id'] ?? 0 );
        echo '<input type="number" name="msds_prayer_widget_options[gf_form_id]" value="' . $v . '" class="small-text" min="0" />';
    }

    // ✅ Tooltip override input field
    public function field_tooltip() {
        $o = get_option( 'msds_prayer_widget_options', [] );
        $v = esc_attr( $o['tooltip_text'] ?? 'Prayer Request' );
        echo '<input type="text" name="msds_prayer_widget_options[tooltip_text]" value="' . $v . '" class="regular-text" placeholder="e.g. Prayer Request" />';
    }

    public function field_ex() {
        $o = get_option( 'msds_prayer_widget_options', [] );
        $v = esc_attr( $o['exclusions'] ?? '' );
        echo '<input type="text" name="msds_prayer_widget_options[exclusions]" value="' . $v . '" class="regular-text" placeholder="e.g. 2,15,98" />';
    }

    public function gf_missing_notice() {
        if ( ! class_exists( 'GFAPI' ) ) {
            echo '<div class="notice notice-warning"><p><strong>'
                 . esc_html__( 'MSDS Prayer Request Widget:', 'msds-prayer-widget' )
                 . '</strong> '
                 . esc_html__( 'Gravity Forms is not active.', 'msds-prayer-widget' )
                 . '</p></div>';
        }
    }
}
