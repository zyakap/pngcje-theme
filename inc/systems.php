<?php
/**
 * inc/systems.php
 * Master loader for all three PNGCJE custom systems:
 *   1. Form Builder
 *   2. Popup Builder
 *   3. Events & Training Calendar
 *
 * Loaded from functions.php via require_once
 */

defined( 'ABSPATH' ) || exit;

// ============================================================
// LOAD MODULES
// ============================================================
require_once PNGCJE_DIR . '/inc/forms/forms-core.php';
require_once PNGCJE_DIR . '/inc/popups/popups-core.php';
require_once PNGCJE_DIR . '/inc/events/events-core.php';

// ============================================================
// ENQUEUE SYSTEMS CSS (frontend)
// ============================================================
function pngcje_systems_enqueue_styles() {
    if ( is_admin() ) return;
    wp_enqueue_style(
        'pngcje-systems',
        PNGCJE_URI . '/assets/css/systems.css',
        [ 'pngcje-style' ],
        PNGCJE_VERSION
    );
}
add_action( 'wp_enqueue_scripts', 'pngcje_systems_enqueue_styles' );

// ============================================================
// REMOVE OLD PLUGIN COMPATIBILITY HOOKS
// Now that we have our own systems, dequeue plugin assets
// ============================================================
function pngcje_remove_plugin_conflicts() {

    // If Gravity Forms still active — keep it but our CSS takes priority
    // If The Events Calendar still active — gracefully disable our templates
    if ( function_exists( 'tribe_get_events' ) ) {
        // TEC is active: use TEC for events, not our custom CPT
        // Unregister our pngcje_event CPT archive to avoid conflicts
        add_filter( 'pngcje_use_custom_events', '__return_false' );
    } else {
        add_filter( 'pngcje_use_custom_events', '__return_true' );
    }

    // If Popup Maker is active — disable our popup output to avoid duplication
    if ( class_exists( 'Popup_Maker' ) ) {
        remove_action( 'wp_footer', 'pngcje_popups_output' );
    }

    // Gravity Forms can run alongside the theme shortcode because the shortcode
    // name is unique and templates may still use [pngcje_form] as a fallback.
}
add_action( 'init', 'pngcje_remove_plugin_conflicts', 20 );

// ============================================================
// HELPER: Is our custom events system active?
// ============================================================
function pngcje_events_active() {
    return (bool) apply_filters( 'pngcje_use_custom_events', true );
}

// ============================================================
// FRONT-PAGE: Replace TEC events with our own if active
// ============================================================
// The pngcje_get_upcoming_events() in functions.php already
// checks for tribe_get_events. Override with our own version:
function pngcje_get_upcoming_events_override( $count = 3 ) {
    if ( ! pngcje_events_active() ) {
        // Fall back to TEC if installed
        if ( function_exists( 'tribe_get_events' ) ) {
            return tribe_get_events( [
                'posts_per_page' => $count,
                'start_date'     => date( 'Y-m-d' ),
            ] );
        }
        return [];
    }
    return pngcje_get_events( [ 'count' => $count ] );
}

// ============================================================
// HOMEPAGE EVENTS STRIP DATA
// Override the front-page.php template loop
// ============================================================
function pngcje_homepage_events( $count = 3 ) {
    $events = pngcje_get_upcoming_events_override( $count );
    if ( empty( $events ) ) return '';

    ob_start();
    foreach ( $events as $event ) {
        $post_type = get_post_type( $event->ID ?? $event );
        $id        = is_object( $event ) ? ( $event->ID ?? $event->id ) : (int) $event;

        if ( $post_type === 'pngcje_event' ) {
            // Our custom event
            $start   = pngcje_event_get( 'start_date', $id );
            $day     = $start ? date( 'j',   strtotime( $start ) ) : '--';
            $month   = $start ? date( 'M',   strtotime( $start ) ) : '---';
            $venue   = pngcje_event_get( 'venue', $id );
            $link    = get_permalink( $id );
            $title   = get_the_title( $id );
        } else {
            // TEC event
            $day   = function_exists('tribe_get_start_date') ? tribe_get_start_date($id,false,'j') : '--';
            $month = function_exists('tribe_get_start_date') ? tribe_get_start_date($id,false,'M') : '---';
            $venue = function_exists('tribe_get_venue')      ? tribe_get_venue($id) : '';
            $link  = get_permalink($id);
            $title = get_the_title($id);
        }
        $image = has_post_thumbnail( $id ) ? get_the_post_thumbnail( $id, 'medium', [
            'class'   => 'event-card__image',
            'loading' => 'lazy',
        ] ) : '';
       ?>
        <a href="<?php echo esc_url($link); ?>" class="event-card<?php echo $image ? ' event-card--has-image' : ''; ?> reveal">
            <?php if ( $image ) : ?>
            <span class="event-card__media"><?php echo $image; ?></span>
            <?php endif; ?>
            <div class="event-card__date-block">
                <span class="event-card__day"><?php echo esc_html($day); ?></span>
                <span class="event-card__month"><?php echo esc_html($month); ?></span>
            </div>
            <div class="event-card__body">
                <h3 class="event-card__title"><?php echo esc_html($title); ?></h3>
                <?php if ($venue) : ?>
                <p class="event-card__location"><?php echo esc_html($venue); ?></p>
                <?php endif; ?>
            </div>
        </a>
        <?php
    }
    return ob_get_clean();
}

// ============================================================
// ADMIN: Register all systems in the dashboard widget
// ============================================================
function pngcje_systems_dashboard_widget_data() {
    $forms_count   = wp_count_posts('pngcje_form')->publish      ?? 0;
    $popups_count  = wp_count_posts('pngcje_popup')->publish     ?? 0;
    $events_count  = wp_count_posts('pngcje_event')->publish     ?? 0;
    $submission_counts = wp_count_posts('pngcje_submission');
    $subs_count = ( $submission_counts->publish ?? 0 ) + ( $submission_counts->private ?? 0 );

    return compact('forms_count','popups_count','events_count','subs_count');
}

// ============================================================
// SHORTCODE: [pngcje_calendar_link] — iCal subscribe link
// ============================================================
add_shortcode( 'pngcje_calendar_link', function( $atts ) {
    $atts = shortcode_atts( [ 'label' => __( 'Subscribe to Training Calendar', 'pngcje' ) ], $atts );
    $url  = add_query_arg( 'pngcje_ical', '1', home_url('/') );
    return '<a href="' . esc_url($url) . '" class="btn btn-outline btn-sm" download>📅 ' . esc_html($atts['label']) . '</a>';
} );

// ============================================================
// REGISTER ALL SHORTCODES SUMMARY (for admin reference)
// ============================================================
function pngcje_systems_shortcodes_list() {
    return [
        '[pngcje_form id="N"]'                            => __('Embed a form by ID','pngcje'),
        '[pngcje_popup_trigger id="N" label="Open"]'      => __('Button to open a popup','pngcje'),
        '[pngcje_events count="3"]'                       => __('Upcoming events (cards)','pngcje'),
        '[pngcje_events count="5" style="list"]'          => __('Upcoming events (list)','pngcje'),
        '[pngcje_events count="3" style="mini"]'          => __('Upcoming events (mini)','pngcje'),
        '[pngcje_events category="training"]'             => __('Events filtered by category','pngcje'),
        '[pngcje_calendar_link]'                          => __('iCal subscribe link','pngcje'),
    ];
}
