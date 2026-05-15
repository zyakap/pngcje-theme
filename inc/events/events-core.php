<?php
/**
 * inc/events/events-core.php
 * PNGCJE Custom Events & Training Calendar
 *
 * - CPT: pngcje_event
 * - Taxonomy: event_category
 * - Meta: start/end datetime, venue, address, organiser, cost, registration
 * - iCal (.ics) export — single event and full calendar
 * - Shortcode: [pngcje_events count="3" category="training"]
 * - Templates: archive-pngcje_event.php, single-pngcje_event.php
 */

defined( 'ABSPATH' ) || exit;

// ============================================================
// CPT + TAXONOMY
// ============================================================
function pngcje_events_register() {

    register_post_type( 'pngcje_event', [
        'labels' => [
            'name'               => __( 'Events',             'pngcje' ),
            'singular_name'      => __( 'Event',              'pngcje' ),
            'add_new_item'       => __( 'Add New Event',      'pngcje' ),
            'edit_item'          => __( 'Edit Event',         'pngcje' ),
            'all_items'          => __( 'All Events',         'pngcje' ),
            'view_item'          => __( 'View Event',         'pngcje' ),
            'search_items'       => __( 'Search Events',      'pngcje' ),
            'not_found'          => __( 'No events found',    'pngcje' ),
            'not_found_in_trash' => __( 'No events in trash', 'pngcje' ),
        ],
        'public'            => true,
        'has_archive'       => 'events',
        'rewrite'           => [ 'slug' => 'events', 'with_front' => false ],
        'menu_icon'         => 'dashicons-calendar-alt',
        'menu_position'     => 27,
        'show_in_rest'      => true,
        'supports'          => [ 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ],
        'capability_type'   => 'post',
    ] );

    register_taxonomy( 'event_category', 'pngcje_event', [
        'labels' => [
            'name'          => __( 'Event Categories', 'pngcje' ),
            'singular_name' => __( 'Event Category',   'pngcje' ),
            'add_new_item'  => __( 'Add Category',     'pngcje' ),
        ],
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => [ 'slug' => 'event-category' ],
        'show_in_rest' => true,
    ] );
}
add_action( 'init', 'pngcje_events_register' );

// ============================================================
// META BOX — EVENT DETAILS
// ============================================================
function pngcje_events_meta_boxes() {
    add_meta_box(
        'pngcje_event_details',
        __( '📅 Event Details', 'pngcje' ),
        'pngcje_event_details_cb',
        'pngcje_event',
        'normal',
        'high'
    );
    add_meta_box(
        'pngcje_event_registration',
        __( '📝 Registration & Capacity', 'pngcje' ),
        'pngcje_event_registration_cb',
        'pngcje_event',
        'side',
        'high'
    );
    add_meta_box(
        'pngcje_event_ical',
        __( '📆 iCal Export', 'pngcje' ),
        'pngcje_event_ical_cb',
        'pngcje_event',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'pngcje_events_meta_boxes' );

function pngcje_events_admin_assets( $hook ) {
    $screen = get_current_screen();
    if ( ! $screen || 'pngcje_event' !== $screen->post_type ) {
        return;
    }

    wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'pngcje_events_admin_assets' );

function pngcje_event_details_cb( $post ) {
    wp_nonce_field( 'pngcje_event_save', 'pngcje_event_nonce' );
    $m = function($key) use ($post) { return get_post_meta($post->ID, '_pngcje_event_' . $key, true); };
   ?>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">

        <div>
            <label style="display:block;font-size:.8rem;font-weight:700;margin-bottom:.4rem;"><?php esc_html_e('Start Date','pngcje'); ?> <span style="color:red;">*</span></label>
            <input type="date" name="pngcje_ev[start_date]" value="<?php echo esc_attr($m('start_date')); ?>"
                required class="widefat" style="font-size:.9rem;padding:.4rem .5rem;">
        </div>
        <div>
            <label style="display:block;font-size:.8rem;font-weight:700;margin-bottom:.4rem;"><?php esc_html_e('Start Time','pngcje'); ?></label>
            <input type="time" name="pngcje_ev[start_time]" value="<?php echo esc_attr($m('start_time') ?: '09:00'); ?>"
                class="widefat" style="font-size:.9rem;padding:.4rem .5rem;">
        </div>
        <div>
            <label style="display:block;font-size:.8rem;font-weight:700;margin-bottom:.4rem;"><?php esc_html_e('End Date','pngcje'); ?></label>
            <input type="date" name="pngcje_ev[end_date]" value="<?php echo esc_attr($m('end_date')); ?>"
                class="widefat" style="font-size:.9rem;padding:.4rem .5rem;">
        </div>
        <div>
            <label style="display:block;font-size:.8rem;font-weight:700;margin-bottom:.4rem;"><?php esc_html_e('End Time','pngcje'); ?></label>
            <input type="time" name="pngcje_ev[end_time]" value="<?php echo esc_attr($m('end_time') ?: '17:00'); ?>"
                class="widefat" style="font-size:.9rem;padding:.4rem .5rem;">
        </div>

    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">
        <div>
            <label style="display:block;font-size:.8rem;font-weight:700;margin-bottom:.4rem;"><?php esc_html_e('Venue','pngcje'); ?></label>
            <input type="text" name="pngcje_ev[venue]" value="<?php echo esc_attr($m('venue')); ?>"
                placeholder="<?php esc_attr_e('e.g. PNGCJE Training Room','pngcje'); ?>"
                class="widefat" style="font-size:.9rem;">
        </div>
        <div>
            <label style="display:block;font-size:.8rem;font-weight:700;margin-bottom:.4rem;"><?php esc_html_e('City / Location','pngcje'); ?></label>
            <input type="text" name="pngcje_ev[city]" value="<?php echo esc_attr($m('city')); ?>"
                placeholder="Port Moresby, NCD"
                class="widefat" style="font-size:.9rem;">
        </div>
    </div>

    <div style="margin-bottom:1.25rem;">
        <label style="display:block;font-size:.8rem;font-weight:700;margin-bottom:.4rem;"><?php esc_html_e('Full Address','pngcje'); ?></label>
        <input type="text" name="pngcje_ev[address]" value="<?php echo esc_attr($m('address')); ?>"
            placeholder="<?php esc_attr_e('Street address, suburb, city','pngcje'); ?>"
            class="widefat" style="font-size:.9rem;">
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">
        <div>
            <label style="display:block;font-size:.8rem;font-weight:700;margin-bottom:.4rem;"><?php esc_html_e('Organiser','pngcje'); ?></label>
            <input type="text" name="pngcje_ev[organiser]" value="<?php echo esc_attr($m('organiser') ?: 'PNGCJE'); ?>"
                class="widefat" style="font-size:.9rem;">
        </div>
        <div>
            <label style="display:block;font-size:.8rem;font-weight:700;margin-bottom:.4rem;"><?php esc_html_e('Organiser Email','pngcje'); ?></label>
            <input type="email" name="pngcje_ev[organiser_email]" value="<?php echo esc_attr($m('organiser_email') ?: get_option('admin_email')); ?>"
                class="widefat" style="font-size:.9rem;">
        </div>
        <div>
            <label style="display:block;font-size:.8rem;font-weight:700;margin-bottom:.4rem;"><?php esc_html_e('Cost','pngcje'); ?></label>
            <input type="text" name="pngcje_ev[cost]" value="<?php echo esc_attr($m('cost')); ?>"
                placeholder="<?php esc_attr_e('Free, PGK 50, etc.','pngcje'); ?>"
                class="widefat" style="font-size:.9rem;">
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
        <div>
            <label style="display:block;font-size:.8rem;font-weight:700;margin-bottom:.4rem;"><?php esc_html_e('Event Website URL','pngcje'); ?></label>
            <input type="url" name="pngcje_ev[website]" value="<?php echo esc_attr($m('website')); ?>"
                placeholder="https://…" class="widefat" style="font-size:.9rem;">
        </div>
        <div>
            <label style="display:block;font-size:.8rem;font-weight:700;margin-bottom:.4rem;"><?php esc_html_e('Status','pngcje'); ?></label>
            <select name="pngcje_ev[status]" class="widefat" style="font-size:.9rem;">
                <option value="scheduled" <?php selected($m('status'),'scheduled'); ?>><?php esc_html_e('Scheduled','pngcje'); ?></option>
                <option value="open"      <?php selected($m('status'),'open'); ?>><?php esc_html_e('Open for Registration','pngcje'); ?></option>
                <option value="full"      <?php selected($m('status'),'full'); ?>><?php esc_html_e('Full / Waitlist','pngcje'); ?></option>
                <option value="cancelled" <?php selected($m('status'),'cancelled'); ?>><?php esc_html_e('Cancelled','pngcje'); ?></option>
                <option value="completed" <?php selected($m('status'),'completed'); ?>><?php esc_html_e('Completed','pngcje'); ?></option>
            </select>
        </div>
    </div>

    <div style="margin-top:1.5rem;padding:1rem;border:1px solid #dcdcde;border-radius:8px;background:#fff;">
        <h3 style="margin:.1rem 0 1rem;font-size:1rem;"><?php esc_html_e('Logo Carousel', 'pngcje'); ?></h3>
        <?php
        $logo_groups = [
            'organised_by' => __( 'Organised By', 'pngcje' ),
            'supported_by' => __( 'Supported By', 'pngcje' ),
            'sponsored_by' => __( 'Sponsored By', 'pngcje' ),
        ];
        foreach ( $logo_groups as $group_key => $group_label ) :
            $logo_ids = array_filter( array_map( 'absint', explode( ',', (string) $m( $group_key . '_logos' ) ) ) );
        ?>
        <div style="padding:1rem 0;border-top:1px solid #f0f0f1;">
            <label style="display:block;font-size:.8rem;font-weight:700;margin-bottom:.4rem;"><?php echo esc_html( $group_label ); ?> <?php esc_html_e( 'Logos', 'pngcje' ); ?></label>
            <input type="hidden" class="pngcje-event-logo-ids" name="pngcje_ev[<?php echo esc_attr( $group_key ); ?>_logos]" value="<?php echo esc_attr( implode( ',', $logo_ids ) ); ?>">
            <div class="pngcje-event-logo-preview" style="display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:.6rem;">
                <?php foreach ( $logo_ids as $logo_id ) : ?>
                    <?php $thumb = wp_get_attachment_image_url( $logo_id, 'thumbnail' ); ?>
                    <?php if ( $thumb ) : ?>
                    <img src="<?php echo esc_url( $thumb ); ?>" alt="" style="width:64px;height:64px;object-fit:contain;border:1px solid #dcdcde;border-radius:6px;background:#fff;">
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <button type="button" class="button pngcje-event-logo-select"><?php esc_html_e( 'Select Logos', 'pngcje' ); ?></button>
            <button type="button" class="button pngcje-event-logo-clear"><?php esc_html_e( 'Clear', 'pngcje' ); ?></button>
            <label style="display:block;font-size:.8rem;font-weight:700;margin:.9rem 0 .4rem;"><?php esc_html_e( 'Alternative names', 'pngcje' ); ?></label>
            <input type="text" name="pngcje_ev[<?php echo esc_attr( $group_key ); ?>_names]" value="<?php echo esc_attr( $m( $group_key . '_names' ) ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'Comma separated names', 'pngcje' ); ?>">
            <?php if ( 'organised_by' === $group_key ) : ?>
            <p style="font-size:.75rem;color:#646970;margin:.35rem 0 0;"><?php esc_html_e( 'If no logos or names are provided, Organised By defaults to PNGCJE.', 'pngcje' ); ?></p>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <script>
    jQuery(function($){
        $('.pngcje-event-logo-select').on('click', function(e){
            e.preventDefault();
            var $wrap = $(this).closest('div');
            var $input = $wrap.find('.pngcje-event-logo-ids');
            var $preview = $wrap.find('.pngcje-event-logo-preview');
            var frame = wp.media({
                title: '<?php echo esc_js( __( 'Select Logos', 'pngcje' ) ); ?>',
                button: { text: '<?php echo esc_js( __( 'Use selected logos', 'pngcje' ) ); ?>' },
                library: { type: 'image' },
                multiple: true
            });
            frame.on('select', function(){
                var ids = [];
                $preview.empty();
                frame.state().get('selection').each(function(attachment){
                    var item = attachment.toJSON();
                    ids.push(item.id);
                    var src = item.sizes && item.sizes.thumbnail ? item.sizes.thumbnail.url : item.url;
                    $preview.append('<img src="' + src + '" alt="" style="width:64px;height:64px;object-fit:contain;border:1px solid #dcdcde;border-radius:6px;background:#fff;">');
                });
                $input.val(ids.join(','));
            });
            frame.open();
        });
        $('.pngcje-event-logo-clear').on('click', function(e){
            e.preventDefault();
            var $wrap = $(this).closest('div');
            $wrap.find('.pngcje-event-logo-ids').val('');
            $wrap.find('.pngcje-event-logo-preview').empty();
        });
    });
    </script>
    <?php
}

function pngcje_event_registration_cb( $post ) {
    $m = function($key) use ($post) { return get_post_meta($post->ID, '_pngcje_event_' . $key, true); };
   ?>
    <table class="form-table" style="margin:0;">
        <tr>
            <td colspan="2">
                <label style="display:flex;align-items:center;gap:.5rem;font-size:.85rem;cursor:pointer;">
                    <input type="checkbox" name="pngcje_ev[registration_enabled]" value="1"
                        <?php checked($m('registration_enabled'),'1'); ?> id="pe_reg_enabled">
                    <strong><?php esc_html_e('Enable Registration Form','pngcje'); ?></strong>
                </label>
                <p style="font-size:.75rem;color:#888;margin:.25rem 0 0;"><?php esc_html_e('Displays a registration form on the event page.','pngcje'); ?></p>
            </td>
        </tr>
        <tr id="pe_reg_opts" <?php echo $m('registration_enabled')!='1'?'style="display:none;"':''; ?>>
            <td colspan="2">
                <label style="display:block;font-size:.78rem;font-weight:600;margin-bottom:.3rem;"><?php esc_html_e('Form ID (pngcje_form)','pngcje'); ?></label>
                <input type="number" name="pngcje_ev[registration_form_id]"
                    value="<?php echo esc_attr($m('registration_form_id')); ?>"
                    placeholder="<?php esc_attr_e('e.g. 3','pngcje'); ?>"
                    class="widefat" style="font-size:.85rem;">
                <p style="font-size:.72rem;color:#888;margin:.2rem 0 .75rem;"><?php esc_html_e('Leave blank to use the default Event Registration form.','pngcje'); ?></p>

                <label style="display:block;font-size:.78rem;font-weight:600;margin-bottom:.3rem;"><?php esc_html_e('Max Capacity','pngcje'); ?></label>
                <input type="number" name="pngcje_ev[capacity]"
                    value="<?php echo esc_attr($m('capacity')); ?>"
                    placeholder="0 = unlimited"
                    class="widefat" style="font-size:.85rem;">
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-top:.75rem;">
                <label style="display:block;font-size:.78rem;font-weight:600;margin-bottom:.3rem;"><?php esc_html_e('External Registration URL','pngcje'); ?></label>
                <input type="url" name="pngcje_ev[registration_url]"
                    value="<?php echo esc_attr($m('registration_url')); ?>"
                    placeholder="https://…"
                    class="widefat" style="font-size:.85rem;">
                <p style="font-size:.72rem;color:#888;margin:.2rem 0 0;"><?php esc_html_e('Overrides the built-in form if set.','pngcje'); ?></p>
            </td>
        </tr>
    </table>
    <script>jQuery('#pe_reg_enabled').on('change',function(){ jQuery('#pe_reg_opts').toggle(this.checked); });</script>
    <?php
}

function pngcje_event_ical_cb( $post ) {
    if ( ! $post->ID || $post->post_status !== 'publish' ) {
        echo '<p style="font-size:.85rem;color:#888;">' . esc_html__('Save and publish the event to generate iCal links.','pngcje') . '</p>';
        return;
    }
    $ical_url  = add_query_arg(['pngcje_ical'=>1,'event_id'=>$post->ID], home_url('/'));
    $gcal_url  = pngcje_event_gcal_url($post->ID);
   ?>
    <div style="display:flex;flex-direction:column;gap:.6rem;">
        <a href="<?php echo esc_url($ical_url); ?>" class="button" download style="text-align:center;font-size:.8rem;">
            📅 <?php esc_html_e('Download .ics','pngcje'); ?>
        </a>
        <?php if ($gcal_url) : ?>
        <a href="<?php echo esc_url($gcal_url); ?>" class="button" target="_blank" rel="noopener noreferrer" style="text-align:center;font-size:.8rem;">
            📅 <?php esc_html_e('Add to Google Calendar','pngcje'); ?>
        </a>
        <?php endif; ?>
        <div>
            <label style="font-size:.75rem;font-weight:600;display:block;margin-bottom:.3rem;"><?php esc_html_e('iCal Link:','pngcje'); ?></label>
            <input type="text" value="<?php echo esc_attr($ical_url); ?>" readonly class="widefat" style="font-size:.75rem;"
                onclick="this.select();">
        </div>
    </div>
    <?php
}

// ============================================================
// SAVE EVENT META
// ============================================================
function pngcje_events_save_meta( $post_id ) {
    if ( ! isset($_POST['pngcje_event_nonce'])
        || ! wp_verify_nonce($_POST['pngcje_event_nonce'],'pngcje_event_save') ) return;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can('edit_post',$post_id) ) return;

    if ( ! isset($_POST['pngcje_ev']) ) return;

    $fields = [
        'start_date'          => 'sanitize_text_field',
        'start_time'          => 'sanitize_text_field',
        'end_date'            => 'sanitize_text_field',
        'end_time'            => 'sanitize_text_field',
        'venue'               => 'sanitize_text_field',
        'city'                => 'sanitize_text_field',
        'address'             => 'sanitize_text_field',
        'organiser'           => 'sanitize_text_field',
        'organiser_email'     => 'sanitize_email',
        'cost'                => 'sanitize_text_field',
        'website'             => 'esc_url_raw',
        'status'              => 'sanitize_key',
        'registration_enabled'=> 'sanitize_text_field',
        'registration_form_id'=> 'absint',
        'registration_url'    => 'esc_url_raw',
        'capacity'            => 'absint',
        'organised_by_logos'  => 'pngcje_event_sanitize_id_list',
        'organised_by_names'  => 'sanitize_text_field',
        'supported_by_logos'  => 'pngcje_event_sanitize_id_list',
        'supported_by_names'  => 'sanitize_text_field',
        'sponsored_by_logos'  => 'pngcje_event_sanitize_id_list',
        'sponsored_by_names'  => 'sanitize_text_field',
    ];

    $defaults = [
        'start_time'      => '09:00',
        'end_time'        => '17:00',
        'organiser'       => 'PNGCJE',
        'organiser_email' => get_option('admin_email'),
        'status'          => 'scheduled',
    ];

    foreach ($fields as $key => $sanitizer) {
        $val = isset($_POST['pngcje_ev'][$key]) ? $_POST['pngcje_ev'][$key] : '';
        if ( '' === $val && isset($defaults[$key]) ) {
            $val = $defaults[$key];
        }
        update_post_meta($post_id, '_pngcje_event_' . $key, $sanitizer($val));
    }

    $start_date = isset($_POST['pngcje_ev']['start_date']) ? sanitize_text_field($_POST['pngcje_ev']['start_date']) : '';
    $start_time = isset($_POST['pngcje_ev']['start_time']) ? sanitize_text_field($_POST['pngcje_ev']['start_time']) : $defaults['start_time'];
    $timestamp  = $start_date ? strtotime($start_date . ' ' . $start_time) : 0;
    update_post_meta($post_id, '_pngcje_event_start_timestamp', $timestamp);
}
add_action('save_post_pngcje_event','pngcje_events_save_meta');

function pngcje_event_sanitize_id_list( $value ) {
    $ids = array_filter( array_map( 'absint', explode( ',', (string) $value ) ) );
    return implode( ',', $ids );
}

// ============================================================
// HELPER: Get event meta
// ============================================================
function pngcje_event_get( $key, $post_id = null ) {
    return get_post_meta( $post_id ?: get_the_ID(), '_pngcje_event_' . $key, true );
}

function pngcje_event_logo_groups( $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();
    $groups = [
        'organised_by' => __( 'Organised By', 'pngcje' ),
        'supported_by' => __( 'Supported By', 'pngcje' ),
        'sponsored_by' => __( 'Sponsored By', 'pngcje' ),
    ];
    $out = [];

    foreach ( $groups as $key => $label ) {
        $logo_ids = array_filter( array_map( 'absint', explode( ',', (string) pngcje_event_get( $key . '_logos', $post_id ) ) ) );
        $names = array_filter( array_map( 'trim', explode( ',', (string) pngcje_event_get( $key . '_names', $post_id ) ) ) );

        if ( 'organised_by' === $key && empty( $logo_ids ) && empty( $names ) ) {
            $names = [ 'PNGCJE' ];
        }

        if ( empty( $logo_ids ) && empty( $names ) ) {
            continue;
        }

        $items = [];
        foreach ( $logo_ids as $logo_id ) {
            $image = wp_get_attachment_image( $logo_id, 'medium', false, [
                'loading'  => 'lazy',
                'decoding' => 'async',
            ] );
            if ( $image ) {
                $items[] = [
                    'type'  => 'logo',
                    'value' => $image,
                ];
            }
        }
        foreach ( $names as $name ) {
            $items[] = [
                'type'  => 'name',
                'value' => $name,
            ];
        }

        if ( $items ) {
            $out[] = [
                'label' => $label,
                'items' => $items,
            ];
        }
    }

    return $out;
}

function pngcje_event_formatted_date( $post_id = null ) {
    $start_date = pngcje_event_get('start_date', $post_id);
    $end_date   = pngcje_event_get('end_date',   $post_id);
    $start_time = pngcje_event_get('start_time', $post_id);
    $end_time   = pngcje_event_get('end_time',   $post_id);

    if (!$start_date) return '';

    $out = date('l, j F Y', strtotime($start_date));
    if ($start_time) $out .= ' · ' . date('g:i A', strtotime($start_time));
    if ($end_time)   $out .= ' – ' . date('g:i A', strtotime($end_time));
    if ($end_date && $end_date !== $start_date) {
        $out .= ' — ' . date('l, j F Y', strtotime($end_date));
    }
    return $out;
}

function pngcje_event_status_badge( $post_id = null ) {
    $status = pngcje_event_get('status', $post_id) ?: 'scheduled';
    $map = [
        'scheduled' => ['badge--gray',  __('Scheduled','pngcje')],
        'open'      => ['badge--green', __('Open for Registration','pngcje')],
        'full'      => ['badge--gold',  __('Full','pngcje')],
        'cancelled' => ['badge--red',   __('Cancelled','pngcje')],
        'completed' => ['badge--gray',  __('Completed','pngcje')],
    ];
    [$class,$label] = $map[$status] ?? ['badge--gray','Unknown'];
    return '<span class="badge ' . esc_attr($class) . '">' . esc_html($label) . '</span>';
}

function pngcje_event_gcal_url( $post_id ) {
    $start = pngcje_event_get('start_date',$post_id) . 'T' . str_replace(':','',pngcje_event_get('start_time',$post_id) ?: '090000') . '00';
    $end   = pngcje_event_get('end_date',$post_id) ?: pngcje_event_get('start_date',$post_id);
    $end  .= 'T' . str_replace(':','',pngcje_event_get('end_time',$post_id) ?: '170000') . '00';
    return add_query_arg([
        'action'   => 'TEMPLATE',
        'text'     => urlencode(get_the_title($post_id)),
        'dates'    => $start . '/' . $end,
        'details'  => urlencode(wp_strip_all_tags(get_the_excerpt($post_id))),
        'location' => urlencode(pngcje_event_get('venue',$post_id) . ' ' . pngcje_event_get('address',$post_id)),
    ], 'https://calendar.google.com/calendar/render');
}

// ============================================================
// ICAL EXPORT
// ============================================================
function pngcje_ical_export() {
    if ( ! isset($_GET['pngcje_ical']) ) return;

    $event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;

    if ($event_id) {
        $event = get_post($event_id);
        if (!$event || $event->post_type !== 'pngcje_event') return;
        $events = [$event];
        $filename = sanitize_title(get_the_title($event_id)) . '.ics';
    } else {
        // Full calendar export
        $events = get_posts([
            'post_type'      => 'pngcje_event',
            'post_status'    => 'publish',
            'posts_per_page' => 100,
            'meta_key'       => '_pngcje_event_start_timestamp',
            'orderby'        => 'meta_value_num',
            'order'          => 'ASC',
        ]);
        $filename = 'pngcje-training-calendar.ics';
    }

    header('Content-Type: text/calendar; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: no-cache, must-revalidate');

    echo "BEGIN:VCALENDAR\r\n";
    echo "VERSION:2.0\r\n";
    echo "PRODID:-//PNGCJE//Training Calendar//EN\r\n";
    echo "CALSCALE:GREGORIAN\r\n";
    echo "METHOD:PUBLISH\r\n";
    echo "X-WR-CALNAME:PNGCJE Training Calendar\r\n";
    echo "X-WR-TIMEZONE:Pacific/Port_Moresby\r\n";

    foreach ($events as $event) {
        $start_date  = pngcje_event_get('start_date',$event->ID);
        $start_time  = pngcje_event_get('start_time',$event->ID) ?: '09:00';
        $end_date    = pngcje_event_get('end_date',$event->ID) ?: $start_date;
        $end_time    = pngcje_event_get('end_time',$event->ID) ?: '17:00';
        $venue       = pngcje_event_get('venue',$event->ID);
        $address     = pngcje_event_get('address',$event->ID);
        $organiser   = pngcje_event_get('organiser',$event->ID) ?: 'PNGCJE';
        $org_email   = pngcje_event_get('organiser_email',$event->ID) ?: get_option('admin_email');

        if (!$start_date) continue;

        $dtstart = date('Ymd\THis', strtotime("$start_date $start_time"));
        $dtend   = date('Ymd\THis', strtotime("$end_date $end_time"));
        $dtstamp = date('Ymd\THis\Z');
        $uid     = $event->ID . '-' . md5(get_permalink($event->ID)) . '@pngcje.gov.pg';
        $summary = pngcje_ical_escape(get_the_title($event->ID));
        $desc    = pngcje_ical_escape(wp_strip_all_tags(get_the_excerpt($event->ID) ?: get_post_field('post_content',$event->ID)));
        $loc     = pngcje_ical_escape(trim("$venue $address"));
        $url     = get_permalink($event->ID);

        echo "BEGIN:VEVENT\r\n";
        echo "UID:{$uid}\r\n";
        echo "DTSTAMP:{$dtstamp}\r\n";
        echo "DTSTART;TZID=Pacific/Port_Moresby:{$dtstart}\r\n";
        echo "DTEND;TZID=Pacific/Port_Moresby:{$dtend}\r\n";
        echo "SUMMARY:{$summary}\r\n";
        if ($desc)  echo "DESCRIPTION:{$desc}\r\n";
        if ($loc)   echo "LOCATION:{$loc}\r\n";
        echo "URL:{$url}\r\n";
        echo "ORGANIZER;CN={$organiser}:mailto:{$org_email}\r\n";
        echo "STATUS:CONFIRMED\r\n";
        echo "END:VEVENT\r\n";
    }

    echo "END:VCALENDAR\r\n";
    exit;
}
add_action('init','pngcje_ical_export');

function pngcje_ical_escape($str) {
    $str = str_replace(['\\',',',';',"\n"], ['\\\\','\\,','\\;','\\n'], $str);
    return wordwrap($str, 73, "\r\n ", true);
}

// ============================================================
// UPCOMING EVENTS QUERY HELPER
// ============================================================
function pngcje_get_events( $args = [] ) {
    $defaults = [
        'count'    => 5,
        'category' => '',
        'future'   => true,
        'status'   => ['publish'],
    ];
    $args = wp_parse_args($args, $defaults);

    $query_args = [
        'post_type'      => 'pngcje_event',
        'post_status'    => $args['status'],
        'posts_per_page' => $args['count'],
        'meta_key'       => '_pngcje_event_start_timestamp',
        'orderby'        => 'meta_value_num',
        'order'          => 'ASC',
    ];

    if ($args['future']) {
        $query_args['meta_query'] = [[
            'key'     => '_pngcje_event_start_timestamp',
            'value'   => time(),
            'compare' => '>=',
            'type'    => 'NUMERIC',
        ]];
    }

    if (!empty($args['category'])) {
        $query_args['tax_query'] = [[
            'taxonomy' => 'event_category',
            'field'    => 'slug',
            'terms'    => $args['category'],
        ]];
    }

    return get_posts($query_args);
}

// ============================================================
// SHORTCODE: [pngcje_events count="3" category="training" style="cards"]
// ============================================================
function pngcje_events_shortcode( $atts ) {
    $atts = shortcode_atts([
        'count'    => 3,
        'category' => '',
        'style'    => 'cards', // cards | list | mini
    ], $atts, 'pngcje_events');

    $events = pngcje_get_events([
        'count'    => (int)$atts['count'],
        'category' => sanitize_key($atts['category']),
    ]);

    if (empty($events)) {
        return '<p class="pngcje-events-empty">' . esc_html__('No upcoming events at this time.','pngcje') . '</p>';
    }

    ob_start();
    if ($atts['style'] === 'list') {
        echo '<ul class="pngcje-events-list">';
        foreach ($events as $ev) {
            $date  = pngcje_event_get('start_date',$ev->ID);
            $venue = pngcje_event_get('venue',$ev->ID);
            echo '<li class="pngcje-events-list__item">';
            echo '<a href="' . esc_url(get_permalink($ev->ID)) . '" class="pngcje-events-list__link">';
            if ($date) echo '<span class="pngcje-events-list__date">' . esc_html(date('j M Y',strtotime($date))) . '</span>';
            echo '<span class="pngcje-events-list__title">' . esc_html(get_the_title($ev->ID)) . '</span>';
            if ($venue) echo '<span class="pngcje-events-list__venue">📍 ' . esc_html($venue) . '</span>';
            echo '</a></li>';
        }
        echo '</ul>';
    } elseif ($atts['style'] === 'mini') {
        echo '<div class="pngcje-events-mini">';
        foreach ($events as $ev) {
            $date = pngcje_event_get('start_date',$ev->ID);
            echo '<div class="pngcje-events-mini__item">';
            if ($date) {
                echo '<div class="pngcje-events-mini__date-block">';
                echo '<span class="pngcje-events-mini__day">' . esc_html(date('j',strtotime($date))) . '</span>';
                echo '<span class="pngcje-events-mini__month">' . esc_html(date('M',strtotime($date))) . '</span>';
                echo '</div>';
            }
            echo '<div class="pngcje-events-mini__body">';
            echo '<a href="' . esc_url(get_permalink($ev->ID)) . '" class="pngcje-events-mini__title">' . esc_html(get_the_title($ev->ID)) . '</a>';
            $venue = pngcje_event_get('venue',$ev->ID);
            if ($venue) echo '<span class="pngcje-events-mini__venue">📍 ' . esc_html($venue) . '</span>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        // Cards (default)
        echo '<div class="pngcje-events-cards">';
        foreach ($events as $ev) {
            $date  = pngcje_event_get('start_date',$ev->ID);
            $time  = pngcje_event_get('start_time',$ev->ID);
            $venue = pngcje_event_get('venue',$ev->ID);
            $cost  = pngcje_event_get('cost',$ev->ID);
            echo '<a href="' . esc_url(get_permalink($ev->ID)) . '" class="event-card">';
            if ($date) {
                echo '<div class="event-card__date-block">';
                echo '<span class="event-card__day">' . esc_html(date('j',strtotime($date))) . '</span>';
                echo '<span class="event-card__month">' . esc_html(date('M',strtotime($date))) . '</span>';
                echo '</div>';
            }
            echo '<div class="event-card__body">';
            echo '<h3 class="event-card__title">' . esc_html(get_the_title($ev->ID)) . '</h3>';
            $meta = array_filter([
                $time  ? date('g:i A',strtotime($time)) : '',
                $venue ?: '',
                $cost  ?: '',
            ]);
            if ($meta) echo '<p class="event-card__location">' . esc_html(implode(' · ',$meta)) . '</p>';
            echo '</div>';
            echo '</a>';
        }
        echo '</div>';
    }
    return ob_get_clean();
}
add_shortcode('pngcje_events','pngcje_events_shortcode');

// ============================================================
// ADMIN COLUMNS
// ============================================================
add_filter('manage_pngcje_event_posts_columns', function($cols){
    unset($cols['date']);
    return array_merge($cols,[
        'event_date'   => __('Event Date','pngcje'),
        'event_venue'  => __('Venue','pngcje'),
        'event_status' => __('Status','pngcje'),
    ]);
});
add_action('manage_pngcje_event_posts_custom_column', function($col,$post_id){
    switch ($col) {
        case 'event_date':
            $d = pngcje_event_get('start_date',$post_id);
            echo $d ? esc_html(date('j M Y',strtotime($d))) : '—';
            break;
        case 'event_venue':
            $v = pngcje_event_get('venue',$post_id);
            $c = pngcje_event_get('city',$post_id);
            echo esc_html(implode(', ',array_filter([$v,$c]))) ?: '—';
            break;
        case 'event_status':
            echo wp_kses_post(pngcje_event_status_badge($post_id));
            break;
    }
},10,2);

// Sort by event date in admin
add_filter('pre_get_posts', function($q){
    if (!is_admin() || !$q->is_main_query()) return;
    if ($q->get('post_type') === 'pngcje_event') {
        $q->set('meta_key','_pngcje_event_start_timestamp');
        $q->set('orderby','meta_value_num');
        $q->set('order','ASC');
    }
});

// ============================================================
// ADD ICAL DOWNLOAD LINK TO CALENDAR EXPORT PAGE
// ============================================================
function pngcje_calendar_ical_download_link() {
    $url = add_query_arg('pngcje_ical','1',home_url('/'));
    return '<a href="' . esc_url($url) . '" class="btn btn-outline btn-sm">📅 ' . esc_html__('Download Full Calendar (.ics)','pngcje') . '</a>';
}
