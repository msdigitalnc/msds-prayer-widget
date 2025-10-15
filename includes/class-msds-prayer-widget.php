<?php
namespace MSDS_PW;
if ( ! defined( 'ABSPATH' ) ) exit;

class Plugin {
    private static $instance = null;
    public static function instance() {
        if ( null === self::$instance ) self::$instance = new self();
        return self::$instance;
    }
    private function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_action( 'wp_footer', [ $this, 'render_widget' ], 100 );
        add_action( 'wp_body_open', [ $this, 'render_widget' ], 5 );
        add_action( 'et_after_main_content', [ $this, 'render_widget' ], 100 );
    }
    private function is_excluded() {
        $opts = $this->get_options();
        $ex = array_filter( array_map( 'trim', explode( ',', $opts['exclusions'] ) ) );
        $ex = array_map( 'absint', $ex );
        if ( empty( $ex ) ) return false;
        if ( is_front_page() ) {
            $front = get_option( 'page_on_front' );
            if ( $front && in_array( $front, $ex ) ) return true;
        }
        if ( is_singular() ) {
            $id = get_queried_object_id();
            if ( $id && in_array( $id, $ex ) ) return true;
        }
        return false;
    }
    private function is_gf_active() { return class_exists( 'GFAPI' ); }
    public function get_options() {
        $opt = get_option( 'msds_prayer_widget_options', [] );
        $defaults = [
            'position' => 'bottom-right',
            'icon_bg_color' => '#1f2937',
            'icon_color' => '#ffffff',
            'icon_class' => '',
            'gf_form_id' => 0,
            'exclusions' => '',
        ];
        return wp_parse_args( $opt, $defaults );
    }
    public function enqueue_assets() {
        if ( is_admin() || wp_doing_ajax() || $this->is_excluded() ) return;
        $opts = $this->get_options();
        wp_enqueue_style( 'msds-pw-style', MSDS_PW_URL . 'assets/css/widget.css', [], MSDS_PW_VERSION );
        wp_enqueue_script( 'msds-pw-script', MSDS_PW_URL . 'assets/js/widget.js', [ 'jquery' ], MSDS_PW_VERSION, true );
        wp_localize_script( 'msds-pw-script', 'MSDSPW', [
            'toggleSelector' => '#msds-pw-button',
            'popupSelector' => '#msds-pw-popup',
            'overlaySelector'=> '#msds-pw-overlay',
            'closeSelector'  => '#msds-pw-close'
        ]);
        $fa = wp_style_is( 'font-awesome', 'enqueued' ) || wp_style_is( 'fontawesome', 'enqueued' ) || wp_style_is( 'fontawesome-free', 'enqueued' );
        if ( ! $fa && ! empty( $opts['icon_class'] ) && strpos( $opts['icon_class'], 'fa-' ) !== false ) {
            wp_enqueue_style( 'msds-pw-fa', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css', [], '6.5.2' );
        }
    }
    public function render_widget() {
        if ( is_admin() || wp_doing_ajax() || $this->is_excluded() ) return;
        $o = $this->get_options();
        $pos = $this->map_pos( $o['position'] );
        $bg = esc_attr( $o['icon_bg_color'] );
        $fg = esc_attr( $o['icon_color'] );
        $ic = sanitize_text_field( $o['icon_class'] );
        $id = absint( $o['gf_form_id'] );
        $icon = ! empty( $ic )
            ? '<i class="' . esc_attr( $ic ) . '"></i>'
            : '<svg viewBox="0 0 24 24" class="msds-pw-svg"><path d="M12 3c4.97 0 9 3.357 9 7.5S16.97 18 12 18c-.863 0-1.699-.099-2.49-.286L6 21l.96-3.84C5.145 15.88 3 13.46 3 10.5 3 6.357 7.03 3 12 3z"></path><rect x="11" y="7" width="2" height="7" rx="1"></rect><rect x="8" y="10" width="8" height="2" rx="1"></rect></svg>';
        $form = '';
        if ( ! $this->is_gf_active() ) {
            $form = '<p>' . esc_html__( 'Gravity Forms is not active.', 'msds-prayer-widget' ) . '</p>';
        } elseif ( $id > 0 ) {
            $form = do_shortcode( sprintf( '[gravityform id="%d" ajax="true" title="false" description="false"]', $id ) );
        } else {
            $form = '<p>' . esc_html__( 'Please set a Gravity Form ID in Settings → Prayer Request Widget.', 'msds-prayer-widget' ) . '</p>';
        }
        ?>
        <div id="msds-pw-overlay" class="msds-pw-overlay" hidden></div>
        <button id="msds-pw-button" title="<?php echo esc_attr( $o['tooltip_text'] ?? 'Prayer Request' ); ?>" class="msds-pw-button <?php echo esc_attr( $pos ); ?>" style="--msds-pw-bg:<?php echo $bg; ?>;--msds-pw-fg:<?php echo $fg; ?>;"><?php echo $icon; ?></button>
        <div id="msds-pw-popup" class="msds-pw-popup <?php echo esc_attr( $pos ); ?>" hidden aria-hidden="true">
            <div class="msds-pw-popup__inner">
                <button id="msds-pw-close" class="msds-pw-close" aria-label="Close">×</button>
                <div class="msds-pw-popup__header"><h3><?php esc_html_e( 'Prayer Request', 'msds-prayer-widget' ); ?></h3></div>
                <div class="msds-pw-popup__body"><?php echo $form; ?></div>
            </div>
        </div>
        <?php
    }
    private function map_pos( $p ) {
        switch ( $p ) {
            case 'bottom-left': return 'pos-bl';
            case 'top-left': return 'pos-tl';
            case 'top-right': return 'pos-tr';
            default: return 'pos-br';
        }
    }
}
