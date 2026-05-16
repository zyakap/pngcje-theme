<?php
/**
 * inc/meta-boxes.php
 * Custom meta boxes for theme-managed content CPTs
 * No ACF dependency — native WP meta boxes
 */

defined( 'ABSPATH' ) || exit;

// ============================================================
// RESOURCE META BOX
// ============================================================
function pngcje_resource_meta_box() {
    add_meta_box(
        'pngcje_resource_details',
        __( 'Resource Details', 'pngcje' ),
        'pngcje_resource_meta_box_cb',
        'pngcje_resource',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'pngcje_resource_meta_box' );

function pngcje_resource_admin_assets( $hook ) {
    $screen = get_current_screen();
    if ( ! $screen || ! in_array( $screen->post_type, [ 'pngcje_resource', 'pngcje_hero_slide' ], true ) ) {
        return;
    }

    wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'pngcje_resource_admin_assets' );

// ============================================================
// HOMEPAGE HERO SLIDE META BOX
// ============================================================
function pngcje_hero_slide_meta_box() {
    add_meta_box(
        'pngcje_hero_slide_details',
        __( 'Hero Slide Content', 'pngcje' ),
        'pngcje_hero_slide_meta_box_cb',
        'pngcje_hero_slide',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'pngcje_hero_slide_meta_box' );

function pngcje_hero_slide_meta_box_cb( $post ) {
    wp_nonce_field( 'pngcje_hero_slide_meta', 'pngcje_hero_slide_nonce' );

    $subheading = get_post_meta( $post->ID, '_pngcje_hero_subheading', true );
    $heading    = get_post_meta( $post->ID, '_pngcje_hero_heading', true );
    $intro      = get_post_meta( $post->ID, '_pngcje_hero_intro', true );
    $button     = get_post_meta( $post->ID, '_pngcje_hero_button_text', true );
    $url        = get_post_meta( $post->ID, '_pngcje_hero_button_url', true );
   ?>
    <table class="form-table">
        <tr>
            <th scope="row"><label for="hero_subheading"><?php esc_html_e( 'Subheading', 'pngcje' ); ?></label></th>
            <td>
                <input type="text" id="hero_subheading" name="_pngcje_hero_subheading" value="<?php echo esc_attr( $subheading ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'Supreme and National Courts of Papua New Guinea', 'pngcje' ); ?>">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="hero_heading"><?php esc_html_e( 'Heading', 'pngcje' ); ?></label></th>
            <td>
                <input type="text" id="hero_heading" name="_pngcje_hero_heading" value="<?php echo esc_attr( $heading ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'The Leading Judicial Education Institution in the Pacific', 'pngcje' ); ?>">
                <p class="description"><?php esc_html_e( 'If left empty, the slide title is used.', 'pngcje' ); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="hero_intro"><?php esc_html_e( 'Intro Text', 'pngcje' ); ?></label></th>
            <td>
                <textarea id="hero_intro" name="_pngcje_hero_intro" class="widefat" rows="4" placeholder="<?php esc_attr_e( 'Short introduction for the hero banner.', 'pngcje' ); ?>"><?php echo esc_textarea( $intro ); ?></textarea>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="hero_button_text"><?php esc_html_e( 'Button Text', 'pngcje' ); ?></label></th>
            <td>
                <input type="text" id="hero_button_text" name="_pngcje_hero_button_text" value="<?php echo esc_attr( $button ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'Learn More', 'pngcje' ); ?>">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="hero_button_url"><?php esc_html_e( 'Button URL', 'pngcje' ); ?></label></th>
            <td>
                <input type="url" id="hero_button_url" name="_pngcje_hero_button_url" value="<?php echo esc_attr( $url ); ?>" class="widefat" placeholder="<?php echo esc_url( home_url( '/about/' ) ); ?>">
            </td>
        </tr>
    </table>
    <p class="description">
        <?php esc_html_e( 'Set the banner image using the Featured Image panel. The homepage rotates the latest five published hero slides.', 'pngcje' ); ?>
    </p>
    <?php
}

function pngcje_save_hero_slide_meta( $post_id ) {
    if ( ! isset( $_POST['pngcje_hero_slide_nonce'] )
        || ! wp_verify_nonce( $_POST['pngcje_hero_slide_nonce'], 'pngcje_hero_slide_meta' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $fields = [
        '_pngcje_hero_subheading'  => 'sanitize_text_field',
        '_pngcje_hero_heading'     => 'sanitize_text_field',
        '_pngcje_hero_intro'       => 'sanitize_textarea_field',
        '_pngcje_hero_button_text' => 'sanitize_text_field',
        '_pngcje_hero_button_url'  => 'esc_url_raw',
    ];

    foreach ( $fields as $key => $sanitizer ) {
        if ( isset( $_POST[ $key ] ) ) {
            update_post_meta( $post_id, $key, $sanitizer( $_POST[ $key ] ) );
        }
    }
}
add_action( 'save_post_pngcje_hero_slide', 'pngcje_save_hero_slide_meta' );

function pngcje_resource_meta_box_cb( $post ) {
    wp_nonce_field( 'pngcje_resource_meta', 'pngcje_resource_nonce' );

    $file     = get_post_meta( $post->ID, '_pngcje_resource_file',     true );
    $year     = get_post_meta( $post->ID, '_pngcje_resource_year',     true );
    $filetype = get_post_meta( $post->ID, '_pngcje_resource_filetype', true );
    $filesize = get_post_meta( $post->ID, '_pngcje_resource_filesize', true );
   ?>
    <table class="form-table" style="margin:0;">
        <tr>
            <td colspan="2">
                <label for="rm_file" style="font-weight:600;display:block;margin-bottom:4px;">
                    <?php esc_html_e( 'File URL or Attachment URL', 'pngcje' ); ?>
                </label>
                <div style="display:flex;gap:6px;">
                    <input
                        type="url"
                        id="rm_file"
                        name="_pngcje_resource_file"
                        value="<?php echo esc_attr( $file ); ?>"
                        placeholder="https://…"
                        style="flex:1;"
                        class="widefat"
                    >
                    <button type="button" class="button pngcje-media-upload" data-target="rm_file">
                        <?php esc_html_e( 'Upload', 'pngcje' ); ?>
                    </button>
                </div>
                <p class="description"><?php esc_html_e( 'Direct URL to PDF or file. Use Upload to select from Media Library.', 'pngcje' ); ?></p>
            </td>
        </tr>
        <tr>
            <td>
                <label for="rm_year" style="font-weight:600;display:block;margin-bottom:4px;">
                    <?php esc_html_e( 'Publication Year', 'pngcje' ); ?>
                </label>
                <input
                    type="number"
                    id="rm_year"
                    name="_pngcje_resource_year"
                    value="<?php echo esc_attr( $year ?: date('Y') ); ?>"
                    min="2000"
                    max="<?php echo date('Y') + 2; ?>"
                    style="width:100px;"
                >
            </td>
            <td>
                <label for="rm_filetype" style="font-weight:600;display:block;margin-bottom:4px;">
                    <?php esc_html_e( 'File Type', 'pngcje' ); ?>
                </label>
                <select id="rm_filetype" name="_pngcje_resource_filetype">
                    <?php
                    $types = [ 'PDF', 'DOCX', 'XLSX', 'MP4', 'ZIP', 'External Link' ];
                    foreach ( $types as $t ) {
                        printf(
                            '<option value="%s"%s>%s</option>',
                            esc_attr( $t ),
                            selected( $filetype, $t, false ),
                            esc_html( $t )
                        );
                    }
                   ?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <label for="rm_filesize" style="font-weight:600;display:block;margin-bottom:4px;">
                    <?php esc_html_e( 'File Size (bytes)', 'pngcje' ); ?>
                </label>
                <input
                    type="number"
                    id="rm_filesize"
                    name="_pngcje_resource_filesize"
                    value="<?php echo esc_attr( $filesize ); ?>"
                    placeholder="e.g. 2048000"
                    class="widefat"
                >
                <p class="description"><?php esc_html_e( 'File size in bytes (optional — displayed as KB/MB on front end).', 'pngcje' ); ?></p>
            </td>
        </tr>
    </table>

    <script>
    jQuery(function($){
        $('.pngcje-media-upload').on('click', function(e){
            e.preventDefault();
            var target = $(this).data('target');
            var frame = wp.media({
                title: 'Select File',
                button: { text: 'Use this file' },
                multiple: false
            });
            frame.on('select', function(){
                var attachment = frame.state().get('selection').first().toJSON();
                $('#' + target).val(attachment.url);
            });
            frame.open();
        });
    });
    </script>
    <?php
}

// Save resource meta
function pngcje_save_resource_meta( $post_id ) {
    if ( ! isset( $_POST['pngcje_resource_nonce'] )
        || ! wp_verify_nonce( $_POST['pngcje_resource_nonce'], 'pngcje_resource_meta' ) ) return;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $fields = [
        '_pngcje_resource_file'     => 'esc_url_raw',
        '_pngcje_resource_year'     => 'absint',
        '_pngcje_resource_filetype' => 'sanitize_text_field',
        '_pngcje_resource_filesize' => 'absint',
    ];

    foreach ( $fields as $key => $sanitizer ) {
        if ( isset( $_POST[ $key ] ) ) {
            update_post_meta( $post_id, $key, $sanitizer( $_POST[ $key ] ) );
        }
    }
}
add_action( 'save_post_pngcje_resource', 'pngcje_save_resource_meta' );

// ============================================================
// STAFF META BOX
// ============================================================
function pngcje_staff_meta_box() {
    add_meta_box(
        'pngcje_staff_details',
        __( 'Staff Member Details', 'pngcje' ),
        'pngcje_staff_meta_box_cb',
        'member',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'pngcje_staff_meta_box' );

function pngcje_staff_meta_box_cb( $post ) {
    wp_nonce_field( 'pngcje_staff_meta', 'pngcje_staff_nonce' );

    $role  = get_post_meta( $post->ID, '_pngcje_staff_role',  true );
    $email = get_post_meta( $post->ID, '_pngcje_staff_email', true );
    $phone = get_post_meta( $post->ID, '_pngcje_staff_phone', true );
    $bio   = get_post_meta( $post->ID, '_pngcje_staff_bio',   true );
   ?>
    <table class="form-table" style="margin:0;">
        <tr>
            <td colspan="2">
                <label for="sm_role" style="font-weight:600;display:block;margin-bottom:4px;">
                    <?php esc_html_e( 'Job Title / Role', 'pngcje' ); ?>
                </label>
                <input
                    type="text"
                    id="sm_role"
                    name="_pngcje_staff_role"
                    value="<?php echo esc_attr( $role ); ?>"
                    placeholder="<?php esc_attr_e( 'e.g. Director of Programs', 'pngcje' ); ?>"
                    class="widefat"
                >
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <label for="sm_email" style="font-weight:600;display:block;margin-bottom:4px;">
                    <?php esc_html_e( 'Email Address', 'pngcje' ); ?>
                </label>
                <input
                    type="email"
                    id="sm_email"
                    name="_pngcje_staff_email"
                    value="<?php echo esc_attr( $email ); ?>"
                    placeholder="name@pngcje.gov.pg"
                    class="widefat"
                >
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <label for="sm_phone" style="font-weight:600;display:block;margin-bottom:4px;">
                    <?php esc_html_e( 'Phone Number', 'pngcje' ); ?>
                </label>
                <input
                    type="text"
                    id="sm_phone"
                    name="_pngcje_staff_phone"
                    value="<?php echo esc_attr( $phone ); ?>"
                    placeholder="+675 324 5700"
                    class="widefat"
                >
            </td>
        </tr>
    </table>
    <?php
}

function pngcje_save_staff_meta( $post_id ) {
    if ( ! isset( $_POST['pngcje_staff_nonce'] )
        || ! wp_verify_nonce( $_POST['pngcje_staff_nonce'], 'pngcje_staff_meta' ) ) return;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $fields = [
        '_pngcje_staff_role'  => 'sanitize_text_field',
        '_pngcje_staff_email' => 'sanitize_email',
        '_pngcje_staff_phone' => 'sanitize_text_field',
    ];
    foreach ( $fields as $key => $sanitizer ) {
        if ( isset( $_POST[ $key ] ) ) {
            update_post_meta( $post_id, $key, $sanitizer( $_POST[ $key ] ) );
        }
    }
}
add_action( 'save_post_member', 'pngcje_save_staff_meta' );

// ============================================================
// BOARD MEMBER META BOX
// ============================================================
function pngcje_board_member_meta_box() {
    add_meta_box(
        'pngcje_board_member_details',
        __( 'Board Member Details', 'pngcje' ),
        'pngcje_board_member_meta_box_cb',
        'pngcje_board_member',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'pngcje_board_member_meta_box' );

function pngcje_board_member_meta_box_cb( $post ) {
    wp_nonce_field( 'pngcje_board_member_meta', 'pngcje_board_member_nonce' );

    $role         = get_post_meta( $post->ID, '_pngcje_board_role', true );
    $organisation = get_post_meta( $post->ID, '_pngcje_board_organisation', true );
    $email        = get_post_meta( $post->ID, '_pngcje_board_email', true );
    $phone        = get_post_meta( $post->ID, '_pngcje_board_phone', true );
    ?>
    <table class="form-table" style="margin:0;">
        <tr>
            <td colspan="2">
                <label for="bm_role" style="font-weight:600;display:block;margin-bottom:4px;">
                    <?php esc_html_e( 'Board Role / Position', 'pngcje' ); ?>
                </label>
                <input
                    type="text"
                    id="bm_role"
                    name="_pngcje_board_role"
                    value="<?php echo esc_attr( $role ); ?>"
                    placeholder="<?php esc_attr_e( 'e.g. Board Chair', 'pngcje' ); ?>"
                    class="widefat"
                >
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <label for="bm_organisation" style="font-weight:600;display:block;margin-bottom:4px;">
                    <?php esc_html_e( 'Organisation / Representation', 'pngcje' ); ?>
                </label>
                <input
                    type="text"
                    id="bm_organisation"
                    name="_pngcje_board_organisation"
                    value="<?php echo esc_attr( $organisation ); ?>"
                    placeholder="<?php esc_attr_e( 'e.g. Supreme and National Courts', 'pngcje' ); ?>"
                    class="widefat"
                >
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <label for="bm_email" style="font-weight:600;display:block;margin-bottom:4px;">
                    <?php esc_html_e( 'Email Address', 'pngcje' ); ?>
                </label>
                <input
                    type="email"
                    id="bm_email"
                    name="_pngcje_board_email"
                    value="<?php echo esc_attr( $email ); ?>"
                    placeholder="name@pngcje.gov.pg"
                    class="widefat"
                >
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <label for="bm_phone" style="font-weight:600;display:block;margin-bottom:4px;">
                    <?php esc_html_e( 'Phone Number', 'pngcje' ); ?>
                </label>
                <input
                    type="text"
                    id="bm_phone"
                    name="_pngcje_board_phone"
                    value="<?php echo esc_attr( $phone ); ?>"
                    placeholder="+675 324 5700"
                    class="widefat"
                >
            </td>
        </tr>
    </table>
    <p class="description"><?php esc_html_e( 'Use the editor for biography/notes and Featured Image for the board member photo.', 'pngcje' ); ?></p>
    <?php
}

function pngcje_save_board_member_meta( $post_id ) {
    if ( ! isset( $_POST['pngcje_board_member_nonce'] )
        || ! wp_verify_nonce( $_POST['pngcje_board_member_nonce'], 'pngcje_board_member_meta' ) ) return;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $fields = [
        '_pngcje_board_role'         => 'sanitize_text_field',
        '_pngcje_board_organisation' => 'sanitize_text_field',
        '_pngcje_board_email'        => 'sanitize_email',
        '_pngcje_board_phone'        => 'sanitize_text_field',
    ];
    foreach ( $fields as $key => $sanitizer ) {
        if ( isset( $_POST[ $key ] ) ) {
            update_post_meta( $post_id, $key, $sanitizer( $_POST[ $key ] ) );
        }
    }
}
add_action( 'save_post_pngcje_board_member', 'pngcje_save_board_member_meta' );

// ============================================================
// PACIFIC MEMBER META BOX
// ============================================================
function pngcje_pacific_meta_box() {
    add_meta_box(
        'pngcje_pacific_details',
        __( 'Pacific Member Details', 'pngcje' ),
        'pngcje_pacific_meta_box_cb',
        'pngcje_pacific',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'pngcje_pacific_meta_box' );

function pngcje_pacific_meta_box_cb( $post ) {
    wp_nonce_field( 'pngcje_pacific_meta', 'pngcje_pacific_nonce' );
    $flag    = get_post_meta( $post->ID, '_pngcje_flag_emoji',    true );
    $country = get_post_meta( $post->ID, '_pngcje_country_name', true );
    $url     = get_post_meta( $post->ID, '_pngcje_country_url',  true );
   ?>
    <table class="form-table" style="margin:0;">
        <tr>
            <td>
                <label for="pm_flag" style="font-weight:600;display:block;margin-bottom:4px;"><?php esc_html_e( 'Flag Emoji', 'pngcje' ); ?></label>
                <input type="text" id="pm_flag" name="_pngcje_flag_emoji" value="<?php echo esc_attr( $flag ); ?>" placeholder="🇵🇬" style="width:80px;font-size:1.5rem;">
            </td>
            <td>
                <label for="pm_country" style="font-weight:600;display:block;margin-bottom:4px;"><?php esc_html_e( 'Country Name', 'pngcje' ); ?></label>
                <input type="text" id="pm_country" name="_pngcje_country_name" value="<?php echo esc_attr( $country ); ?>" class="widefat">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <label for="pm_url" style="font-weight:600;display:block;margin-bottom:4px;"><?php esc_html_e( 'Link URL (optional)', 'pngcje' ); ?></label>
                <input type="url" id="pm_url" name="_pngcje_country_url" value="<?php echo esc_attr( $url ); ?>" placeholder="https://…" class="widefat">
            </td>
        </tr>
    </table>
    <?php
}

function pngcje_save_pacific_meta( $post_id ) {
    if ( ! isset( $_POST['pngcje_pacific_nonce'] )
        || ! wp_verify_nonce( $_POST['pngcje_pacific_nonce'], 'pngcje_pacific_meta' ) ) return;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;
    $fields = [
        '_pngcje_flag_emoji'   => 'sanitize_text_field',
        '_pngcje_country_name' => 'sanitize_text_field',
        '_pngcje_country_url'  => 'esc_url_raw',
    ];
    foreach ( $fields as $key => $sanitizer ) {
        if ( isset( $_POST[ $key ] ) ) {
            update_post_meta( $post_id, $key, $sanitizer( $_POST[ $key ] ) );
        }
    }
}
add_action( 'save_post_pngcje_pacific', 'pngcje_save_pacific_meta' );
