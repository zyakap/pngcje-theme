<?php
/**
 * inc/admin.php
 * Admin enhancements: branding, dashboard widget, admin styles
 */

defined( 'ABSPATH' ) || exit;

// ============================================================
// ADMIN BAR LOGO
// ============================================================
function pngcje_admin_bar_logo( $wp_admin_bar ) {
    $wp_admin_bar->add_node( [
        'id'    => 'pngcje-site',
        'title' => '<span style="font-weight:700;color:#F9B800;letter-spacing:0.04em;">PNGCJE</span>',
        'href'  => home_url('/'),
        'meta'  => [ 'title' => 'PNGCJE Website' ],
    ] );
}
add_action( 'admin_bar_menu', 'pngcje_admin_bar_logo', 11 );

// ============================================================
// ADMIN LOGIN PAGE LOGO
// ============================================================
function pngcje_login_logo() {
    $logo_url = pngcje_theme_asset_url( 'assets/img/pngcje_logo.png' );
   ?>
    <style>
        body.login { background: #1A5C2A; }
        #login h1 a {
            <?php if ( $logo_url ) : ?>
            background-image: url('<?php echo esc_url( $logo_url ); ?>');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            text-indent: -9999px;
            <?php else : ?>
            background: none;
            text-indent: 0;
            color: #fff;
            font-size: 1.5rem;
            font-weight: 800;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            <?php endif; ?>
            width: 240px;
            height: 80px;
            margin-bottom: 1rem;
        }
        .login form {
            border-radius: 8px;
            border-top: 4px solid #D4960A;
        }
        .wp-core-ui .button-primary {
            background: #1A5C2A !important;
            border-color: #1A5C2A !important;
            font-family: 'Montserrat', sans-serif !important;
            font-weight: 700 !important;
            letter-spacing: 0.04em !important;
        }
        .wp-core-ui .button-primary:hover {
            background: #2E7D45 !important;
            border-color: #2E7D45 !important;
        }
    </style>
    <?php
}
add_action( 'login_enqueue_scripts', 'pngcje_login_logo' );

function pngcje_login_logo_url() { return home_url(); }
add_filter( 'login_headerurl', 'pngcje_login_logo_url' );

function pngcje_login_logo_title() { return get_bloginfo('name'); }
add_filter( 'login_headertext', 'pngcje_login_logo_title' );

// ============================================================
// ADMIN STYLES
// ============================================================
function pngcje_admin_styles() {
   ?>
    <style>
        /* Admin sidebar accent */
        #adminmenu .wp-has-current-submenu .wp-submenu-head,
        #adminmenu li.current a.menu-top,
        #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu {
            background: #1A5C2A !important;
        }
        /* Post type icon colours */
        #adminmenu li.menu-top:hover,
        #adminmenu li.opensub > a.menu-top {
            background: #2E7D45 !important;
        }
        /* Publish button */
        #publishing-action .button-primary.button-large {
            background: #1A5C2A;
            border-color: #1A5C2A;
        }
        #publishing-action .button-primary.button-large:hover {
            background: #2E7D45;
            border-color: #2E7D45;
        }
    </style>
    <?php
}
add_action( 'admin_head', 'pngcje_admin_styles' );

// ============================================================
// DASHBOARD WIDGET
// ============================================================
function pngcje_dashboard_widget() {
    wp_add_dashboard_widget(
        'pngcje_dash_widget',
        '🏛 PNGCJE Theme — Quick Status',
        'pngcje_dashboard_widget_cb'
    );
}
add_action( 'wp_dashboard_setup', 'pngcje_dashboard_widget' );

function pngcje_dashboard_widget_cb() {
    if ( ! function_exists( 'is_plugin_active' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    $plugins = [
        'gravity-forms/gravityforms.php'           => [ 'Gravity Forms',      'Form Builder'  ],
        'popup-maker/popup-maker.php'              => [ 'Popup Maker',         'Popup Builder' ],
        'the-events-calendar/the-events-calendar.php' => [ 'The Events Calendar', 'Events'     ],
    ];
   ?>
    <div style="font-family:'Montserrat',sans-serif;">
        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1.25rem;padding-bottom:1rem;border-bottom:2px solid #D4960A;">
            <span style="font-size:1.5rem;">⚖️</span>
            <div>
                <div style="font-weight:700;font-size:0.95rem;color:#1A5C2A;">PNGCJE Custom Theme</div>
                <div style="font-size:0.75rem;color:#888;">Version <?php echo PNGCJE_VERSION; ?></div>
            </div>
        </div>

        <p style="font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:#888;margin-bottom:0.75rem;">Plugin Status</p>
        <ul style="display:flex;flex-direction:column;gap:0.5rem;margin-bottom:1.25rem;">
            <?php foreach ( $plugins as $plugin_file => [ $name, $type ] ) :
                $active  = is_plugin_active( $plugin_file );
                $colour  = $active ? '#1A5C2A' : '#B71C1C';
                $icon    = $active ? '✅' : '⚠️';
                $status  = $active ? 'Active' : 'Not Installed / Inactive';
           ?>
            <li style="display:flex;align-items:center;justify-content:space-between;font-size:0.85rem;padding:0.4rem 0.5rem;background:#f9f9f9;border-radius:4px;border-left:3px solid <?php echo esc_attr($colour); ?>;">
                <span><?php echo esc_html($icon . ' ' . $name); ?> <span style="color:#999;font-size:0.75rem;">(<?php echo esc_html($type); ?>)</span></span>
                <span style="font-size:0.7rem;font-weight:700;color:<?php echo esc_attr($colour); ?>;"><?php echo esc_html($status); ?></span>
            </li>
            <?php endforeach; ?>
        </ul>

        <p style="font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:#888;margin-bottom:0.75rem;">Content Status</p>
        <ul style="display:flex;flex-direction:column;gap:0.4rem;margin-bottom:1.25rem;">
            <?php
            $counts = [
                'Posts (News)'       => wp_count_posts('post')->publish,
                'Resources'          => wp_count_posts('pngcje_resource')->publish,
                'Staff Members'      => wp_count_posts('member')->publish,
                'Board Members'      => wp_count_posts('pngcje_board_member')->publish,
                'Program Officers'   => wp_count_posts('pngcje_program_officer')->publish,
                'Pacific Members'    => wp_count_posts('pngcje_pacific')->publish,
                'Events (TEC)'       => function_exists('tribe_get_events') ? wp_count_posts('tribe_events')->publish : 'Plugin inactive',
            ];
            foreach ( $counts as $label => $count ) :
           ?>
            <li style="display:flex;justify-content:space-between;font-size:0.85rem;padding:0.3rem 0;border-bottom:1px solid #eee;">
                <span><?php echo esc_html($label); ?></span>
                <strong style="color:#1A5C2A;"><?php echo esc_html($count); ?></strong>
            </li>
            <?php endforeach; ?>
        </ul>

        <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
            <a href="<?php echo esc_url( home_url('/') ); ?>" class="button button-primary" style="background:#1A5C2A;border-color:#1A5C2A;font-size:0.8rem;" target="_blank">
                🌐 View Site
            </a>
            <a href="<?php echo esc_url( admin_url('customize.php') ); ?>" class="button" style="font-size:0.8rem;">
                🎨 Customizer
            </a>
            <a href="<?php echo esc_url( admin_url('nav-menus.php') ); ?>" class="button" style="font-size:0.8rem;">
                📋 Menus
            </a>
        </div>
    </div>
    <?php
}

function pngcje_youtube_admin_menu() {
    add_menu_page(
        __( 'YouTube', 'pngcje' ),
        __( 'YouTube', 'pngcje' ),
        'manage_options',
        'pngcje-youtube',
        'pngcje_youtube_settings_page',
        'dashicons-video-alt3',
        27
    );
}
add_action( 'admin_menu', 'pngcje_youtube_admin_menu' );

function pngcje_counters_admin_menu() {
    add_menu_page(
        __( 'Counters', 'pngcje' ),
        __( 'Counters', 'pngcje' ),
        'manage_options',
        'pngcje-counters',
        'pngcje_counters_settings_page',
        'dashicons-chart-bar',
        28
    );
}
add_action( 'admin_menu', 'pngcje_counters_admin_menu' );

function pngcje_youtube_register_settings() {
    register_setting( 'pngcje_youtube_settings', 'pngcje_youtube_video_url', [
        'type'              => 'string',
        'sanitize_callback' => 'esc_url_raw',
        'default'           => '',
    ] );

    register_setting( 'pngcje_youtube_settings', 'pngcje_youtube_is_live', [
        'type'              => 'boolean',
        'sanitize_callback' => 'absint',
        'default'           => 0,
    ] );
}
add_action( 'admin_init', 'pngcje_youtube_register_settings' );

function pngcje_counters_register_settings() {
    for ( $i = 1; $i <= 4; $i++ ) {
        register_setting( 'pngcje_counters_settings', 'pngcje_counter_' . $i . '_number', [
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
        ] );

        register_setting( 'pngcje_counters_settings', 'pngcje_counter_' . $i . '_metric', [
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
        ] );
    }
}
add_action( 'admin_init', 'pngcje_counters_register_settings' );

function pngcje_youtube_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $video_url = get_option( 'pngcje_youtube_video_url', '' );
    $is_live   = (int) get_option( 'pngcje_youtube_is_live', 0 );
   ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'YouTube', 'pngcje' ); ?></h1>
        <p><?php esc_html_e( 'Enter the YouTube video or live stream URL to embed on the front page above Video Messages.', 'pngcje' ); ?></p>
        <form method="post" action="options.php">
            <?php settings_fields( 'pngcje_youtube_settings' ); ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="pngcje_youtube_video_url"><?php esc_html_e( 'YouTube URL', 'pngcje' ); ?></label></th>
                    <td>
                        <input type="url" id="pngcje_youtube_video_url" name="pngcje_youtube_video_url" value="<?php echo esc_attr( $video_url ); ?>" class="regular-text" placeholder="https://www.youtube.com/watch?v=...">
                        <p class="description"><?php esc_html_e( 'Supports normal YouTube links, youtu.be links, Shorts links, embed links and channel live links.', 'pngcje' ); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Live', 'pngcje' ); ?></th>
                    <td>
                        <label>
                            <input type="hidden" name="pngcje_youtube_is_live" value="0">
                            <input type="checkbox" name="pngcje_youtube_is_live" value="1" <?php checked( $is_live, 1 ); ?>>
                            <?php esc_html_e( 'This video is currently a live stream', 'pngcje' ); ?>
                        </label>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function pngcje_counters_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $defaults = [
        1 => [ 'number' => '2010', 'metric' => 'Established' ],
        2 => [ 'number' => '15',   'metric' => 'Pacific Jurisdictions' ],
        3 => [ 'number' => '500',  'metric' => 'Officers Trained Annually' ],
        4 => [ 'number' => '6',    'metric' => 'International Partners' ],
    ];
   ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Counters', 'pngcje' ); ?></h1>
        <p><?php esc_html_e( 'Control the four front-page counter numbers and metric labels.', 'pngcje' ); ?></p>
        <form method="post" action="options.php">
            <?php settings_fields( 'pngcje_counters_settings' ); ?>
            <table class="form-table" role="presentation">
                <?php for ( $i = 1; $i <= 4; $i++ ) :
                    $number = get_option( 'pngcje_counter_' . $i . '_number', $defaults[$i]['number'] );
                    $metric = get_option( 'pngcje_counter_' . $i . '_metric', $defaults[$i]['metric'] );
               ?>
                <tr>
                    <th scope="row"><?php echo esc_html( sprintf( __( 'Counter %d', 'pngcje' ), $i ) ); ?></th>
                    <td>
                        <label for="pngcje_counter_<?php echo esc_attr( $i ); ?>_number" style="display:block;font-weight:600;margin-bottom:.35rem;"><?php esc_html_e( 'Number', 'pngcje' ); ?></label>
                        <input type="text" id="pngcje_counter_<?php echo esc_attr( $i ); ?>_number" name="pngcje_counter_<?php echo esc_attr( $i ); ?>_number" value="<?php echo esc_attr( $number ); ?>" class="regular-text" style="margin-bottom:.75rem;">
                        <label for="pngcje_counter_<?php echo esc_attr( $i ); ?>_metric" style="display:block;font-weight:600;margin-bottom:.35rem;"><?php esc_html_e( 'Metric', 'pngcje' ); ?></label>
                        <input type="text" id="pngcje_counter_<?php echo esc_attr( $i ); ?>_metric" name="pngcje_counter_<?php echo esc_attr( $i ); ?>_metric" value="<?php echo esc_attr( $metric ); ?>" class="regular-text">
                    </td>
                </tr>
                <?php endfor; ?>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// ============================================================
// INCLUDE META BOXES
// ============================================================
require_once PNGCJE_DIR . '/inc/meta-boxes.php';
