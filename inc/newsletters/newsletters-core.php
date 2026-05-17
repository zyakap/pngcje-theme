<?php

defined( 'ABSPATH' ) || exit;

function pngcje_newsletters_register() {
    register_post_type( 'pngcje_newsletter', [
        'labels' => [
            'name'               => __( 'Newsletters', 'pngcje' ),
            'singular_name'      => __( 'Newsletter', 'pngcje' ),
            'add_new'            => __( 'Add New Newsletter', 'pngcje' ),
            'add_new_item'       => __( 'Add New Newsletter', 'pngcje' ),
            'edit_item'          => __( 'Edit Newsletter', 'pngcje' ),
            'all_items'          => __( 'All Newsletters', 'pngcje' ),
            'view_item'          => __( 'View Newsletter', 'pngcje' ),
            'search_items'       => __( 'Search Newsletters', 'pngcje' ),
            'not_found'          => __( 'No newsletters found', 'pngcje' ),
            'featured_image'     => __( 'Newsletter Cover', 'pngcje' ),
            'set_featured_image' => __( 'Set newsletter cover', 'pngcje' ),
        ],
        'public'        => true,
        'has_archive'   => 'newsletters',
        'show_ui'       => true,
        'show_in_menu'  => false,
        'show_in_rest'  => true,
        'supports'      => [ 'title', 'editor', 'excerpt', 'thumbnail' ],
        'rewrite'       => [ 'slug' => 'newsletters', 'with_front' => false ],
        'menu_icon'     => 'dashicons-email-alt2',
    ] );

    register_post_type( 'pngcje_subscriber', [
        'labels' => [
            'name'          => __( 'Subscribers', 'pngcje' ),
            'singular_name' => __( 'Subscriber', 'pngcje' ),
            'add_new'       => __( 'Add New Subscriber', 'pngcje' ),
            'add_new_item'  => __( 'Add New Subscriber', 'pngcje' ),
            'edit_item'     => __( 'Edit Subscriber', 'pngcje' ),
            'all_items'     => __( 'Subscribers', 'pngcje' ),
            'search_items'  => __( 'Search Subscribers', 'pngcje' ),
            'not_found'     => __( 'No subscribers found', 'pngcje' ),
        ],
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => false,
        'show_in_rest'        => false,
        'supports'            => [ 'title' ],
        'capability_type'     => 'post',
        'exclude_from_search' => true,
    ] );
}
add_action( 'init', 'pngcje_newsletters_register' );

function pngcje_newsletters_archive_order( $query ) {
    if ( is_admin() || ! $query->is_main_query() || ! $query->is_post_type_archive( 'pngcje_newsletter' ) ) {
        return;
    }

    $query->set( 'orderby', 'date' );
    $query->set( 'order', 'DESC' );
}
add_action( 'pre_get_posts', 'pngcje_newsletters_archive_order' );

function pngcje_newsletters_admin_menu() {
    add_menu_page(
        __( 'Newsletter', 'pngcje' ),
        __( 'Newsletter', 'pngcje' ),
        'manage_options',
        'pngcje-newsletters',
        'pngcje_newsletters_dashboard_page',
        'dashicons-email-alt2',
        24
    );

    add_submenu_page(
        'pngcje-newsletters',
        __( 'Newsletter Dashboard', 'pngcje' ),
        __( 'Dashboard', 'pngcje' ),
        'manage_options',
        'pngcje-newsletters',
        'pngcje_newsletters_dashboard_page'
    );

    add_submenu_page(
        'pngcje-newsletters',
        __( 'All Newsletters', 'pngcje' ),
        __( 'All Newsletters', 'pngcje' ),
        'edit_posts',
        'edit.php?post_type=pngcje_newsletter'
    );

    add_submenu_page(
        'pngcje-newsletters',
        __( 'Add New Newsletter', 'pngcje' ),
        __( 'Add New', 'pngcje' ),
        'edit_posts',
        'post-new.php?post_type=pngcje_newsletter'
    );

    add_submenu_page(
        'pngcje-newsletters',
        __( 'Subscribers', 'pngcje' ),
        __( 'Subscribers', 'pngcje' ),
        'manage_options',
        'edit.php?post_type=pngcje_subscriber'
    );

    add_submenu_page(
        'pngcje-newsletters',
        __( 'Add New Subscriber', 'pngcje' ),
        __( 'Add Subscriber', 'pngcje' ),
        'manage_options',
        'post-new.php?post_type=pngcje_subscriber'
    );
}
add_action( 'admin_menu', 'pngcje_newsletters_admin_menu' );

function pngcje_newsletters_dashboard_page() {
    $newsletter_counts = wp_count_posts( 'pngcje_newsletter' );
    $subscriber_counts = wp_count_posts( 'pngcje_subscriber' );
    $newsletters       = (int) ( $newsletter_counts->publish ?? 0 );
    $subscribers       = (int) ( $subscriber_counts->publish ?? 0 );
    $active            = count( pngcje_newsletter_get_active_subscribers() );
    ?>
    <div class="wrap">
        <h1 style="display:flex;align-items:center;gap:.5rem;"><span>✉️</span> <?php esc_html_e( 'Newsletter', 'pngcje' ); ?></h1>
        <div style="display:flex;gap:1.5rem;margin-top:1.5rem;flex-wrap:wrap;">
            <div style="background:#fff;border:1px solid #ddd;border-top:4px solid #1A5C2A;border-radius:6px;padding:1.5rem 2rem;min-width:170px;text-align:center;">
                <div style="font-size:2.5rem;font-weight:900;color:#1A5C2A;"><?php echo esc_html( number_format_i18n( $newsletters ) ); ?></div>
                <div style="font-size:.8rem;color:#888;text-transform:uppercase;letter-spacing:.08em;margin-top:.25rem;"><?php esc_html_e( 'Published Newsletters', 'pngcje' ); ?></div>
                <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=pngcje_newsletter' ) ); ?>" style="display:block;margin-top:1rem;font-size:.8rem;color:#1A5C2A;"><?php esc_html_e( 'Manage', 'pngcje' ); ?> →</a>
            </div>
            <div style="background:#fff;border:1px solid #ddd;border-top:4px solid #D4960A;border-radius:6px;padding:1.5rem 2rem;min-width:170px;text-align:center;">
                <div style="font-size:2.5rem;font-weight:900;color:#D4960A;"><?php echo esc_html( number_format_i18n( $subscribers ) ); ?></div>
                <div style="font-size:.8rem;color:#888;text-transform:uppercase;letter-spacing:.08em;margin-top:.25rem;"><?php esc_html_e( 'Total Subscribers', 'pngcje' ); ?></div>
                <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=pngcje_subscriber' ) ); ?>" style="display:block;margin-top:1rem;font-size:.8rem;color:#D4960A;"><?php esc_html_e( 'View List', 'pngcje' ); ?> →</a>
            </div>
            <div style="background:#fff;border:1px solid #ddd;border-top:4px solid #B84A00;border-radius:6px;padding:1.5rem 2rem;min-width:170px;text-align:center;">
                <div style="font-size:2.5rem;font-weight:900;color:#B84A00;"><?php echo esc_html( number_format_i18n( $active ) ); ?></div>
                <div style="font-size:.8rem;color:#888;text-transform:uppercase;letter-spacing:.08em;margin-top:.25rem;"><?php esc_html_e( 'Active Recipients', 'pngcje' ); ?></div>
                <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=pngcje_newsletter' ) ); ?>" style="display:block;margin-top:1rem;font-size:.8rem;color:#B84A00;"><?php esc_html_e( 'Create Newsletter', 'pngcje' ); ?> →</a>
            </div>
        </div>
        <div style="margin-top:2rem;background:#fff;border:1px solid #ddd;border-radius:6px;padding:1.5rem;max-width:900px;">
            <h2 style="margin-top:0;font-size:1rem;"><?php esc_html_e( 'How it works', 'pngcje' ); ?></h2>
            <ol style="margin-left:1.25rem;color:#555;line-height:1.7;">
                <li><?php esc_html_e( 'Create a newsletter and set its featured image as the cover.', 'pngcje' ); ?></li>
                <li><?php esc_html_e( 'Add download links in the Newsletter Downloads box if PDF or file versions are available.', 'pngcje' ); ?></li>
                <li><?php esc_html_e( 'When the newsletter is published for the first time, it is emailed to active subscribers.', 'pngcje' ); ?></li>
                <li><?php esc_html_e( 'Newsletter subscription form submissions are added to the Subscribers list automatically.', 'pngcje' ); ?></li>
            </ol>
        </div>
    </div>
    <?php
}

function pngcje_newsletters_add_meta_boxes() {
    add_meta_box( 'pngcje_newsletter_details', __( 'Newsletter Details', 'pngcje' ), 'pngcje_newsletter_details_meta_box', 'pngcje_newsletter', 'side', 'high' );
    add_meta_box( 'pngcje_newsletter_downloads', __( 'Newsletter Downloads', 'pngcje' ), 'pngcje_newsletter_downloads_meta_box', 'pngcje_newsletter', 'normal', 'default' );
    add_meta_box( 'pngcje_subscriber_details', __( 'Subscriber Details', 'pngcje' ), 'pngcje_subscriber_details_meta_box', 'pngcje_subscriber', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'pngcje_newsletters_add_meta_boxes' );

function pngcje_newsletter_details_meta_box( $post ) {
    wp_nonce_field( 'pngcje_newsletter_meta', 'pngcje_newsletter_meta_nonce' );
    $year   = get_post_meta( $post->ID, '_pngcje_newsletter_year', true );
    $issue  = get_post_meta( $post->ID, '_pngcje_newsletter_issue', true );
    $volume = get_post_meta( $post->ID, '_pngcje_newsletter_volume', true );
    ?>
    <p>
        <label for="pngcje_newsletter_year" style="display:block;font-weight:600;margin-bottom:4px;"><?php esc_html_e( 'Year', 'pngcje' ); ?></label>
        <input type="number" id="pngcje_newsletter_year" name="pngcje_newsletter_year" value="<?php echo esc_attr( $year ); ?>" class="small-text" min="1900" max="2099" step="1">
    </p>
    <p>
        <label for="pngcje_newsletter_issue" style="display:block;font-weight:600;margin-bottom:4px;"><?php esc_html_e( 'Issue Number', 'pngcje' ); ?></label>
        <input type="number" id="pngcje_newsletter_issue" name="pngcje_newsletter_issue" value="<?php echo esc_attr( $issue ); ?>" class="small-text" min="1" step="1">
    </p>
    <p>
        <label for="pngcje_newsletter_volume" style="display:block;font-weight:600;margin-bottom:4px;"><?php esc_html_e( 'Volume Number', 'pngcje' ); ?></label>
        <input type="number" id="pngcje_newsletter_volume" name="pngcje_newsletter_volume" value="<?php echo esc_attr( $volume ); ?>" class="small-text" min="1" step="1">
    </p>
    <?php
}

function pngcje_newsletter_downloads_meta_box( $post ) {
    $downloads = pngcje_newsletter_get_downloads( $post->ID );
    $lines     = [];

    foreach ( $downloads as $download ) {
        $lines[] = trim( $download['label'] . ' | ' . $download['url'] );
    }
    ?>
    <p style="margin-top:0;color:#555;"><?php esc_html_e( 'Enter one download per line using: Label | URL', 'pngcje' ); ?></p>
    <textarea name="pngcje_newsletter_downloads_raw" rows="6" style="width:100%;font-family:monospace;"><?php echo esc_textarea( implode( "\n", $lines ) ); ?></textarea>
    <?php
}

function pngcje_subscriber_details_meta_box( $post ) {
    wp_nonce_field( 'pngcje_subscriber_meta', 'pngcje_subscriber_meta_nonce' );
    $email      = get_post_meta( $post->ID, '_pngcje_subscriber_email', true );
    $first_name = get_post_meta( $post->ID, '_pngcje_subscriber_first_name', true );
    $last_name  = get_post_meta( $post->ID, '_pngcje_subscriber_last_name', true );
    $status     = get_post_meta( $post->ID, '_pngcje_subscriber_status', true ) ?: 'active';
    $source     = get_post_meta( $post->ID, '_pngcje_subscriber_source', true );
    ?>
    <table class="form-table" role="presentation">
        <tr><th><label for="pngcje_subscriber_email"><?php esc_html_e( 'Email', 'pngcje' ); ?></label></th><td><input type="email" id="pngcje_subscriber_email" name="pngcje_subscriber_email" value="<?php echo esc_attr( $email ); ?>" class="regular-text" required></td></tr>
        <tr><th><label for="pngcje_subscriber_first_name"><?php esc_html_e( 'First Name', 'pngcje' ); ?></label></th><td><input type="text" id="pngcje_subscriber_first_name" name="pngcje_subscriber_first_name" value="<?php echo esc_attr( $first_name ); ?>" class="regular-text"></td></tr>
        <tr><th><label for="pngcje_subscriber_last_name"><?php esc_html_e( 'Last Name', 'pngcje' ); ?></label></th><td><input type="text" id="pngcje_subscriber_last_name" name="pngcje_subscriber_last_name" value="<?php echo esc_attr( $last_name ); ?>" class="regular-text"></td></tr>
        <tr><th><label for="pngcje_subscriber_status"><?php esc_html_e( 'Status', 'pngcje' ); ?></label></th><td><select id="pngcje_subscriber_status" name="pngcje_subscriber_status"><option value="active" <?php selected( $status, 'active' ); ?>><?php esc_html_e( 'Active', 'pngcje' ); ?></option><option value="unsubscribed" <?php selected( $status, 'unsubscribed' ); ?>><?php esc_html_e( 'Unsubscribed', 'pngcje' ); ?></option></select></td></tr>
        <tr><th><label for="pngcje_subscriber_source"><?php esc_html_e( 'Source', 'pngcje' ); ?></label></th><td><input type="text" id="pngcje_subscriber_source" name="pngcje_subscriber_source" value="<?php echo esc_attr( $source ); ?>" class="regular-text"></td></tr>
    </table>
    <?php
}

function pngcje_newsletters_save_meta( $post_id, $post ) {
    if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
        return;
    }

    if ( $post->post_type === 'pngcje_newsletter' ) {
        if ( ! isset( $_POST['pngcje_newsletter_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['pngcje_newsletter_meta_nonce'] ) ), 'pngcje_newsletter_meta' ) ) {
            return;
        }
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        $year   = isset( $_POST['pngcje_newsletter_year'] ) ? (int) sanitize_text_field( wp_unslash( $_POST['pngcje_newsletter_year'] ) ) : 0;
        $issue  = isset( $_POST['pngcje_newsletter_issue'] ) ? (int) sanitize_text_field( wp_unslash( $_POST['pngcje_newsletter_issue'] ) ) : 0;
        $volume = isset( $_POST['pngcje_newsletter_volume'] ) ? (int) sanitize_text_field( wp_unslash( $_POST['pngcje_newsletter_volume'] ) ) : 0;

        if ( $year > 0 ) {
            update_post_meta( $post_id, '_pngcje_newsletter_year', $year );
        } else {
            delete_post_meta( $post_id, '_pngcje_newsletter_year' );
        }
        if ( $issue > 0 ) {
            update_post_meta( $post_id, '_pngcje_newsletter_issue', $issue );
        } else {
            delete_post_meta( $post_id, '_pngcje_newsletter_issue' );
        }
        if ( $volume > 0 ) {
            update_post_meta( $post_id, '_pngcje_newsletter_volume', $volume );
        } else {
            delete_post_meta( $post_id, '_pngcje_newsletter_volume' );
        }

        $raw       = sanitize_textarea_field( wp_unslash( $_POST['pngcje_newsletter_downloads_raw'] ?? '' ) );
        $downloads = pngcje_newsletter_parse_downloads( $raw );
        update_post_meta( $post_id, '_pngcje_newsletter_downloads', $downloads );
    }

    if ( $post->post_type === 'pngcje_subscriber' ) {
        pngcje_newsletter_save_subscriber_meta( $post_id );
    }
}
add_action( 'save_post', 'pngcje_newsletters_save_meta', 10, 2 );

function pngcje_newsletter_save_subscriber_meta( $post_id ) {
    static $updating = false;

    if ( $updating ) {
        return;
    }
    if ( ! isset( $_POST['pngcje_subscriber_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['pngcje_subscriber_meta_nonce'] ) ), 'pngcje_subscriber_meta' ) ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $email      = sanitize_email( wp_unslash( $_POST['pngcje_subscriber_email'] ?? '' ) );
    $first_name = sanitize_text_field( wp_unslash( $_POST['pngcje_subscriber_first_name'] ?? '' ) );
    $last_name  = sanitize_text_field( wp_unslash( $_POST['pngcje_subscriber_last_name'] ?? '' ) );
    $status     = sanitize_key( wp_unslash( $_POST['pngcje_subscriber_status'] ?? 'active' ) );
    $source     = sanitize_text_field( wp_unslash( $_POST['pngcje_subscriber_source'] ?? '' ) );

    if ( ! in_array( $status, [ 'active', 'unsubscribed' ], true ) ) {
        $status = 'active';
    }

    update_post_meta( $post_id, '_pngcje_subscriber_email', strtolower( $email ) );
    update_post_meta( $post_id, '_pngcje_subscriber_first_name', $first_name );
    update_post_meta( $post_id, '_pngcje_subscriber_last_name', $last_name );
    update_post_meta( $post_id, '_pngcje_subscriber_status', $status );
    update_post_meta( $post_id, '_pngcje_subscriber_source', $source );

    if ( $email ) {
        $title    = trim( $first_name . ' ' . $last_name );
        $title    = $title ? $title . ' <' . $email . '>' : $email;
        $updating = true;
        wp_update_post( [ 'ID' => $post_id, 'post_title' => $title ] );
        $updating = false;
    }
}

function pngcje_newsletter_parse_downloads( $raw ) {
    $downloads = [];
    $lines     = preg_split( '/\r\n|\r|\n/', (string) $raw );

    foreach ( $lines as $line ) {
        $line = trim( $line );
        if ( $line === '' ) {
            continue;
        }

        $parts = array_map( 'trim', explode( '|', $line, 2 ) );
        $label = $parts[0] ?? '';
        $url   = $parts[1] ?? $parts[0];
        $url   = esc_url_raw( $url );

        if ( ! $url ) {
            continue;
        }

        $downloads[] = [
            'label' => $label ? sanitize_text_field( $label ) : __( 'Download', 'pngcje' ),
            'url'   => $url,
        ];
    }

    return $downloads;
}

function pngcje_newsletter_get_downloads( $post_id = null ) {
    $post_id   = $post_id ?: get_the_ID();
    $downloads = get_post_meta( $post_id, '_pngcje_newsletter_downloads', true );

    return is_array( $downloads ) ? $downloads : [];
}

function pngcje_newsletter_upsert_subscriber( $email, $args = [] ) {
    $email = sanitize_email( $email );
    if ( ! $email || ! is_email( $email ) ) {
        return 0;
    }

    $email = strtolower( $email );
    $args  = wp_parse_args( $args, [
        'first_name' => '',
        'last_name'  => '',
        'source'     => __( 'Newsletter form', 'pngcje' ),
        'status'     => 'active',
    ] );

    $existing = get_posts( [
        'post_type'      => 'pngcje_subscriber',
        'post_status'    => [ 'publish', 'private', 'draft' ],
        'posts_per_page' => 1,
        'fields'         => 'ids',
        'meta_key'       => '_pngcje_subscriber_email',
        'meta_value'     => $email,
    ] );

    $first_name = sanitize_text_field( $args['first_name'] );
    $last_name  = sanitize_text_field( $args['last_name'] );
    $source     = sanitize_text_field( $args['source'] );
    $status     = in_array( $args['status'], [ 'active', 'unsubscribed' ], true ) ? $args['status'] : 'active';
    $title_name = trim( $first_name . ' ' . $last_name );
    $title      = $title_name ? $title_name . ' <' . $email . '>' : $email;

    if ( $existing ) {
        $subscriber_id = (int) $existing[0];
        wp_update_post( [ 'ID' => $subscriber_id, 'post_title' => $title, 'post_status' => 'publish' ] );
    } else {
        $subscriber_id = wp_insert_post( [
            'post_type'   => 'pngcje_subscriber',
            'post_title'  => $title,
            'post_status' => 'publish',
        ] );
    }

    if ( $subscriber_id && ! is_wp_error( $subscriber_id ) ) {
        update_post_meta( $subscriber_id, '_pngcje_subscriber_email', $email );
        update_post_meta( $subscriber_id, '_pngcje_subscriber_first_name', $first_name );
        update_post_meta( $subscriber_id, '_pngcje_subscriber_last_name', $last_name );
        update_post_meta( $subscriber_id, '_pngcje_subscriber_status', $status );
        update_post_meta( $subscriber_id, '_pngcje_subscriber_source', $source );
        update_post_meta( $subscriber_id, '_pngcje_subscriber_updated_at', current_time( 'mysql' ) );
    }

    return (int) $subscriber_id;
}

function pngcje_newsletter_capture_submission( $submission_id, $form_id, $data, $raw_post = [] ) {
    if ( ! pngcje_newsletter_is_subscription_form( $form_id, $data, $raw_post ) ) {
        return;
    }

    $subscriber = pngcje_newsletter_extract_subscriber_from_data( $data );
    if ( empty( $subscriber['email'] ) ) {
        return;
    }

    pngcje_newsletter_upsert_subscriber( $subscriber['email'], [
        'first_name' => $subscriber['first_name'],
        'last_name'  => $subscriber['last_name'],
        'source'     => sprintf( __( 'Form submission #%d', 'pngcje' ), (int) $submission_id ),
        'status'     => 'active',
    ] );
}
add_action( 'pngcje_form_submission_stored', 'pngcje_newsletter_capture_submission', 10, 4 );

function pngcje_newsletter_is_subscription_form( $form_id, $data = [], $raw_post = [] ) {
    $native_id = (int) get_theme_mod( 'pngcje_newsletter_pngcje_form_id', 65 );
    if ( $native_id > 0 && (int) $form_id === $native_id ) {
        return true;
    }

    $title = strtolower( (string) get_the_title( $form_id ) );
    if ( strpos( $title, 'newsletter' ) !== false || strpos( $title, 'subscribe' ) !== false || strpos( $title, 'subscription' ) !== false ) {
        return true;
    }

    $page_url = strtolower( (string) ( $raw_post['page_url'] ?? '' ) );
    if ( strpos( $page_url, 'newsletter' ) !== false ) {
        return true;
    }

    foreach ( $data as $key => $row ) {
        $label = strtolower( (string) ( $row['label'] ?? $key ) );
        if ( strpos( $label, 'newsletter' ) !== false || strpos( $label, 'subscribe' ) !== false ) {
            return true;
        }
    }

    return false;
}

function pngcje_newsletter_extract_subscriber_from_data( $data ) {
    $subscriber = [ 'email' => '', 'first_name' => '', 'last_name' => '' ];

    foreach ( $data as $key => $row ) {
        $label = strtolower( (string) ( $row['label'] ?? $key ) );
        $value = trim( (string) ( $row['value'] ?? '' ) );

        if ( ! $subscriber['email'] && is_email( $value ) ) {
            $subscriber['email'] = $value;
            continue;
        }

        if ( $value === '' ) {
            continue;
        }

        if ( ! $subscriber['first_name'] && ( strpos( $label, 'first' ) !== false || $label === 'name' || strpos( $label, 'full name' ) !== false ) ) {
            $subscriber['first_name'] = $value;
        } elseif ( ! $subscriber['last_name'] && strpos( $label, 'last' ) !== false ) {
            $subscriber['last_name'] = $value;
        }
    }

    return $subscriber;
}

function pngcje_newsletter_capture_gravity_submission( $entry, $form ) {
    $gf_id = (int) get_theme_mod( 'pngcje_newsletter_gravity_form_id', 0 );
    $form_id = isset( $form['id'] ) ? (int) $form['id'] : 0;
    $title = strtolower( (string) ( $form['title'] ?? '' ) );

    if ( ! ( $gf_id > 0 && $form_id === $gf_id ) && strpos( $title, 'newsletter' ) === false && strpos( $title, 'subscribe' ) === false ) {
        return;
    }

    $email = '';
    $first_name = '';
    $last_name = '';

    foreach ( $form['fields'] ?? [] as $field ) {
        $field_id = (string) $field->id;
        $label = strtolower( (string) $field->label );
        $value = trim( (string) ( $entry[ $field_id ] ?? '' ) );

        if ( ! $email && $value && is_email( $value ) ) {
            $email = $value;
        } elseif ( ! $first_name && $value && ( strpos( $label, 'first' ) !== false || strpos( $label, 'name' ) !== false ) ) {
            $first_name = $value;
        } elseif ( ! $last_name && $value && strpos( $label, 'last' ) !== false ) {
            $last_name = $value;
        }
    }

    if ( $email ) {
        pngcje_newsletter_upsert_subscriber( $email, [
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'source'     => sprintf( __( 'Gravity Forms submission #%s', 'pngcje' ), $entry['id'] ?? '' ),
            'status'     => 'active',
        ] );
    }
}
add_action( 'gform_after_submission', 'pngcje_newsletter_capture_gravity_submission', 10, 2 );

function pngcje_newsletter_get_active_subscribers() {
    $ids = get_posts( [
        'post_type'      => 'pngcje_subscriber',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_query'     => [
            [
                'key'     => '_pngcje_subscriber_status',
                'value'   => 'active',
                'compare' => '=',
            ],
        ],
    ] );

    $subscribers = [];
    foreach ( $ids as $id ) {
        $email = get_post_meta( $id, '_pngcje_subscriber_email', true );
        if ( $email && is_email( $email ) ) {
            $subscribers[] = [
                'id'         => $id,
                'email'      => $email,
                'first_name' => get_post_meta( $id, '_pngcje_subscriber_first_name', true ),
                'last_name'  => get_post_meta( $id, '_pngcje_subscriber_last_name', true ),
            ];
        }
    }

    return $subscribers;
}

function pngcje_newsletter_send_on_publish( $new_status, $old_status, $post ) {
    if ( $post->post_type !== 'pngcje_newsletter' || $new_status !== 'publish' || $old_status === 'publish' ) {
        return;
    }

    if ( get_post_meta( $post->ID, '_pngcje_newsletter_sent_at', true ) ) {
        return;
    }

    $result = pngcje_newsletter_send_to_subscribers( $post->ID );
    update_post_meta( $post->ID, '_pngcje_newsletter_sent_at', current_time( 'mysql' ) );
    update_post_meta( $post->ID, '_pngcje_newsletter_sent_count', (int) $result['sent'] );
    update_post_meta( $post->ID, '_pngcje_newsletter_failed_count', (int) $result['failed'] );
}
add_action( 'transition_post_status', 'pngcje_newsletter_send_on_publish', 10, 3 );

function pngcje_newsletter_send_to_subscribers( $post_id ) {
    $subscribers = pngcje_newsletter_get_active_subscribers();
    $subject     = html_entity_decode( get_the_title( $post_id ), ENT_QUOTES, get_bloginfo( 'charset' ) );
    $permalink   = get_permalink( $post_id );
    $content     = apply_filters( 'the_content', get_post_field( 'post_content', $post_id ) );
    $excerpt     = get_the_excerpt( $post_id );
    $downloads   = pngcje_newsletter_get_downloads( $post_id );
    $headers     = [ 'Content-Type: text/html; charset=UTF-8' ];
    $sent        = 0;
    $failed      = 0;

    $download_html = '';
    if ( $downloads ) {
        $download_html .= '<h3>Downloads</h3><ul>';
        foreach ( $downloads as $download ) {
            $download_html .= '<li><a href="' . esc_url( $download['url'] ) . '">' . esc_html( $download['label'] ) . '</a></li>';
        }
        $download_html .= '</ul>';
    }

    foreach ( $subscribers as $subscriber ) {
        $greeting = $subscriber['first_name'] ? 'Dear ' . esc_html( $subscriber['first_name'] ) . ',' : 'Hello,';
        $body  = '<div style="font-family:Arial,sans-serif;line-height:1.7;color:#222;max-width:680px;margin:0 auto;">';
        $body .= '<p>' . $greeting . '</p>';
        $body .= '<h1 style="color:#1A5C2A;">' . esc_html( get_the_title( $post_id ) ) . '</h1>';
        if ( $excerpt ) {
            $body .= '<p style="font-size:16px;color:#555;">' . esc_html( $excerpt ) . '</p>';
        }
        $body .= wp_kses_post( $content );
        $body .= $download_html;
        $body .= '<p><a href="' . esc_url( $permalink ) . '" style="display:inline-block;background:#B84A00;color:#fff;text-decoration:none;padding:12px 18px;border-radius:4px;font-weight:bold;">View newsletter online</a></p>';
        $body .= '<p style="font-size:12px;color:#777;">' . esc_html( get_bloginfo( 'name' ) ) . '</p>';
        $body .= '</div>';

        if ( wp_mail( $subscriber['email'], $subject, $body, $headers ) ) {
            $sent++;
        } else {
            $failed++;
        }
    }

    return [ 'sent' => $sent, 'failed' => $failed ];
}

function pngcje_newsletter_columns( $columns ) {
    $columns['pngcje_newsletter_sent'] = __( 'Email Sent', 'pngcje' );
    return $columns;
}
add_filter( 'manage_pngcje_newsletter_posts_columns', 'pngcje_newsletter_columns' );

function pngcje_newsletter_column_content( $column, $post_id ) {
    if ( $column === 'pngcje_newsletter_sent' ) {
        $sent_at = get_post_meta( $post_id, '_pngcje_newsletter_sent_at', true );
        $sent    = get_post_meta( $post_id, '_pngcje_newsletter_sent_count', true );
        echo $sent_at ? esc_html( sprintf( __( '%s recipients on %s', 'pngcje' ), number_format_i18n( (int) $sent ), mysql2date( get_option( 'date_format' ), $sent_at ) ) ) : esc_html__( 'Not sent yet', 'pngcje' );
    }
}
add_action( 'manage_pngcje_newsletter_posts_custom_column', 'pngcje_newsletter_column_content', 10, 2 );

function pngcje_subscriber_columns( $columns ) {
    return [
        'cb'     => $columns['cb'],
        'title'  => __( 'Subscriber', 'pngcje' ),
        'email'  => __( 'Email', 'pngcje' ),
        'status' => __( 'Status', 'pngcje' ),
        'source' => __( 'Source', 'pngcje' ),
        'date'   => $columns['date'],
    ];
}
add_filter( 'manage_pngcje_subscriber_posts_columns', 'pngcje_subscriber_columns' );

function pngcje_subscriber_column_content( $column, $post_id ) {
    if ( $column === 'email' ) {
        echo esc_html( get_post_meta( $post_id, '_pngcje_subscriber_email', true ) );
    } elseif ( $column === 'status' ) {
        echo esc_html( ucfirst( get_post_meta( $post_id, '_pngcje_subscriber_status', true ) ?: 'active' ) );
    } elseif ( $column === 'source' ) {
        echo esc_html( get_post_meta( $post_id, '_pngcje_subscriber_source', true ) );
    }
}
add_action( 'manage_pngcje_subscriber_posts_custom_column', 'pngcje_subscriber_column_content', 10, 2 );
