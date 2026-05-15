<?php
/**
 * inc/forms/forms-core.php
 * PNGCJE Custom Form Builder — Core Engine
 *
 * - CPT: pngcje_form
 * - Fields stored as JSON in post meta
 * - Submissions stored as CPT: pngcje_submission
 * - Shortcode: [pngcje_form id="1"]
 * - Email notifications to admin + optional confirmations
 * - Honeypot + nonce spam protection
 * - AJAX + non-JS fallback submission
 */

defined( 'ABSPATH' ) || exit;

// ============================================================
// REGISTER CPTs
// ============================================================
function pngcje_forms_register_cpts() {

    // Forms
    register_post_type( 'pngcje_form', [
        'labels' => [
            'name'               => __( 'Forms',           'pngcje' ),
            'singular_name'      => __( 'Form',            'pngcje' ),
            'add_new'            => __( 'Add New Form',    'pngcje' ),
            'add_new_item'       => __( 'Add New Form',    'pngcje' ),
            'edit_item'          => __( 'Edit Form',       'pngcje' ),
            'all_items'          => __( 'All Forms',       'pngcje' ),
            'view_item'          => __( 'View Form',       'pngcje' ),
            'search_items'       => __( 'Search Forms',    'pngcje' ),
            'not_found'          => __( 'No forms found',  'pngcje' ),
        ],
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => 'pngcje-forms',
        'show_in_rest'        => false,
        'supports'            => [ 'title' ],
        'menu_icon'           => 'dashicons-feedback',
        'capability_type'     => 'post',
        'exclude_from_search' => true,
    ] );

    // Submissions
    register_post_type( 'pngcje_submission', [
        'labels' => [
            'name'          => __( 'Form Submissions', 'pngcje' ),
            'singular_name' => __( 'Submission',       'pngcje' ),
            'all_items'     => __( 'Submissions',      'pngcje' ),
            'edit_item'     => __( 'View Submission',  'pngcje' ),
            'not_found'     => __( 'No submissions',   'pngcje' ),
        ],
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => 'pngcje-forms',
        'show_in_rest'        => false,
        'supports'            => [ 'title' ],
        'capability_type'     => 'post',
        'exclude_from_search' => true,
    ] );
}
add_action( 'init', 'pngcje_forms_register_cpts' );

// ============================================================
// ADMIN MENU PAGE
// ============================================================
function pngcje_forms_admin_menu() {
    add_menu_page(
        __( 'PNGCJE Forms', 'pngcje' ),
        __( 'Forms', 'pngcje' ),
        'manage_options',
        'pngcje-forms',
        'pngcje_forms_dashboard_page',
        'dashicons-feedback',
        25
    );
    add_submenu_page(
        'pngcje-forms',
        __( 'Forms Dashboard', 'pngcje' ),
        __( 'Dashboard',       'pngcje' ),
        'manage_options',
        'pngcje-forms',
        'pngcje_forms_dashboard_page'
    );
    add_submenu_page(
        'pngcje-forms',
        __( 'Add New Form', 'pngcje' ),
        __( 'Add New Form', 'pngcje' ),
        'manage_options',
        'post-new.php?post_type=pngcje_form'
    );
}
add_action( 'admin_menu', 'pngcje_forms_admin_menu' );

function pngcje_forms_dashboard_page() {
    global $wpdb;

    $form_posts = get_posts([
        'post_type'      => 'pngcje_form',
        'post_status'    => ['publish', 'draft', 'pending', 'private'],
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
    ]);
    $forms = count($form_posts);
    $submission_counts = wp_count_posts('pngcje_submission');
    $submissions = ( $submission_counts->publish ?? 0 ) + ( $submission_counts->private ?? 0 );
    $counts_by_form = [];
    $last_by_form = [];

    $count_rows = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT pm.meta_value AS form_id, COUNT(*) AS total
             FROM {$wpdb->postmeta} pm
             INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
             WHERE pm.meta_key = %s
             AND p.post_type = %s
             AND p.post_status IN ('publish','private')
             GROUP BY pm.meta_value",
            '_pngcje_form_id',
            'pngcje_submission'
        )
    );
    foreach ( $count_rows as $row ) {
        $counts_by_form[ (int) $row->form_id ] = (int) $row->total;
    }

    $last_rows = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT pm.meta_value AS form_id, MAX(p.post_date) AS last_date
             FROM {$wpdb->postmeta} pm
             INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
             WHERE pm.meta_key = %s
             AND p.post_type = %s
             AND p.post_status IN ('publish','private')
             GROUP BY pm.meta_value",
            '_pngcje_form_id',
            'pngcje_submission'
        )
    );
    foreach ( $last_rows as $row ) {
        $last_by_form[ (int) $row->form_id ] = $row->last_date;
    }
   ?>
    <div class="wrap">
        <h1 style="display:flex;align-items:center;gap:.5rem;">
            <span>📋</span> <?php esc_html_e('PNGCJE Forms','pngcje'); ?>
        </h1>
        <div style="display:flex;gap:1.5rem;margin-top:1.5rem;flex-wrap:wrap;">
            <div style="background:#fff;border:1px solid #ddd;border-top:4px solid #1A5C2A;border-radius:6px;padding:1.5rem 2rem;min-width:160px;text-align:center;">
                <div style="font-size:2.5rem;font-weight:900;color:#1A5C2A;"><?php echo esc_html($forms); ?></div>
                <div style="font-size:.8rem;color:#888;text-transform:uppercase;letter-spacing:.08em;margin-top:.25rem;"><?php esc_html_e('Active Forms','pngcje'); ?></div>
                <a href="<?php echo esc_url(admin_url('edit.php?post_type=pngcje_form')); ?>" style="display:block;margin-top:1rem;font-size:.8rem;color:#1A5C2A;"><?php esc_html_e('Manage','pngcje'); ?> →</a>
            </div>
            <div style="background:#fff;border:1px solid #ddd;border-top:4px solid #D4960A;border-radius:6px;padding:1.5rem 2rem;min-width:160px;text-align:center;">
                <div style="font-size:2.5rem;font-weight:900;color:#D4960A;"><?php echo esc_html($submissions); ?></div>
                <div style="font-size:.8rem;color:#888;text-transform:uppercase;letter-spacing:.08em;margin-top:.25rem;"><?php esc_html_e('Submissions','pngcje'); ?></div>
                <a href="<?php echo esc_url(admin_url('edit.php?post_type=pngcje_submission')); ?>" style="display:block;margin-top:1rem;font-size:.8rem;color:#D4960A;"><?php esc_html_e('View All','pngcje'); ?> →</a>
            </div>
        </div>
        <div style="margin-top:2rem;background:#fff;border:1px solid #ddd;border-radius:6px;padding:1.5rem;">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;margin-bottom:1rem;">
                <h2 style="margin:0;font-size:1rem;"><?php esc_html_e('Forms and Submission Counts','pngcje'); ?></h2>
                <a href="<?php echo esc_url(admin_url('post-new.php?post_type=pngcje_form')); ?>" class="button button-primary">
                    <?php esc_html_e('Add New Form','pngcje'); ?>
                </a>
            </div>
            <?php if ( $form_posts ) : ?>
                <table class="widefat striped">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Form','pngcje'); ?></th>
                            <th><?php esc_html_e('Status','pngcje'); ?></th>
                            <th><?php esc_html_e('Shortcode','pngcje'); ?></th>
                            <th style="text-align:right;"><?php esc_html_e('Submissions','pngcje'); ?></th>
                            <th><?php esc_html_e('Last Submission','pngcje'); ?></th>
                            <th><?php esc_html_e('Actions','pngcje'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $form_posts as $form_post ) : ?>
                            <?php
                            $form_count = $counts_by_form[ $form_post->ID ] ?? 0;
                            $last_date  = $last_by_form[ $form_post->ID ] ?? '';
                            $submissions_url = add_query_arg(
                                [
                                    'post_type'          => 'pngcje_submission',
                                    'pngcje_form_filter' => $form_post->ID,
                                ],
                                admin_url('edit.php')
                            );
                            ?>
                            <tr>
                                <td>
                                    <strong>
                                        <a href="<?php echo esc_url(get_edit_post_link($form_post->ID)); ?>">
                                            <?php echo esc_html(get_the_title($form_post)); ?>
                                        </a>
                                    </strong>
                                </td>
                                <td><?php echo esc_html(get_post_status_object($form_post->post_status)->label ?? ucfirst($form_post->post_status)); ?></td>
                                <td><code>[pngcje_form id="<?php echo esc_html($form_post->ID); ?>"]</code></td>
                                <td style="text-align:right;font-weight:700;"><?php echo esc_html(number_format_i18n($form_count)); ?></td>
                                <td><?php echo $last_date ? esc_html(mysql2date(get_option('date_format') . ' ' . get_option('time_format'), $last_date)) : esc_html__('No submissions yet','pngcje'); ?></td>
                                <td>
                                    <a href="<?php echo esc_url(get_edit_post_link($form_post->ID)); ?>"><?php esc_html_e('Edit','pngcje'); ?></a>
                                    |
                                    <a href="<?php echo esc_url($submissions_url); ?>"><?php esc_html_e('View Submissions','pngcje'); ?></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p style="color:#666;margin:0 0 1rem;"><?php esc_html_e('No forms have been created yet.','pngcje'); ?></p>
                <a href="<?php echo esc_url(admin_url('post-new.php?post_type=pngcje_form')); ?>" class="button button-primary">
                    <?php esc_html_e('Create First Form','pngcje'); ?>
                </a>
            <?php endif; ?>
        </div>
        <div style="margin-top:2rem;background:#fff;border:1px solid #ddd;border-radius:6px;padding:1.5rem;">
            <h2 style="margin:0 0 1rem;font-size:1rem;"><?php esc_html_e('Quick Embed','pngcje'); ?></h2>
            <p style="font-size:.875rem;color:#555;margin:0 0 .75rem;"><?php esc_html_e('Use this shortcode to embed any form in pages or posts:','pngcje'); ?></p>
            <code style="background:#f4f4f4;padding:.4rem .75rem;border-radius:4px;font-size:.875rem;">[pngcje_form id="FORM_ID"]</code>
            <p style="font-size:.8rem;color:#888;margin:.75rem 0 0;"><?php esc_html_e('Copy the exact shortcode from the table above or from the form editor.','pngcje'); ?></p>
        </div>
    </div>
    <?php
}

// ============================================================
// FIELD TYPES DEFINITION
// ============================================================
function pngcje_forms_field_types() {
    return [
        'text'     => [ 'label' => __('Text',          'pngcje'), 'icon' => '📝' ],
        'email'    => [ 'label' => __('Email',         'pngcje'), 'icon' => '✉️' ],
        'tel'      => [ 'label' => __('Phone',         'pngcje'), 'icon' => '📞' ],
        'textarea' => [ 'label' => __('Textarea',      'pngcje'), 'icon' => '📄' ],
        'select'   => [ 'label' => __('Dropdown',      'pngcje'), 'icon' => '🔽' ],
        'radio'    => [ 'label' => __('Radio Buttons', 'pngcje'), 'icon' => '🔘' ],
        'checkbox' => [ 'label' => __('Checkboxes',    'pngcje'), 'icon' => '☑️' ],
        'number'   => [ 'label' => __('Number',        'pngcje'), 'icon' => '🔢' ],
        'date'     => [ 'label' => __('Date',          'pngcje'), 'icon' => '📅' ],
        'file'     => [ 'label' => __('File Upload',   'pngcje'), 'icon' => '📎' ],
        'hidden'   => [ 'label' => __('Hidden Field',  'pngcje'), 'icon' => '👁️' ],
        'h1_heading' => [ 'label' => __('H1 Heading',     'pngcje'), 'icon' => 'H1' ],
        'heading'  => [ 'label' => __('Section Heading','pngcje'),'icon' => '🏷️' ],
        'paragraph'=> [ 'label' => __('Paragraph',     'pngcje'), 'icon' => '¶' ],
        'divider'  => [ 'label' => __('Divider',       'pngcje'), 'icon' => '➖' ],
        'linebreak'=> [ 'label' => __('Line Break',    'pngcje'), 'icon' => '↵' ],
    ];
}

// ============================================================
// META BOX — FORM BUILDER UI
// ============================================================
function pngcje_forms_meta_boxes() {
    add_meta_box(
        'pngcje_form_builder',
        __( '⚙️ Form Fields Builder', 'pngcje' ),
        'pngcje_form_builder_cb',
        'pngcje_form',
        'normal',
        'high'
    );
    add_meta_box(
        'pngcje_form_settings',
        __( '📧 Form Settings', 'pngcje' ),
        'pngcje_form_settings_cb',
        'pngcje_form',
        'side',
        'high'
    );
    add_meta_box(
        'pngcje_form_shortcode',
        __( '📋 Shortcode', 'pngcje' ),
        'pngcje_form_shortcode_cb',
        'pngcje_form',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'pngcje_forms_meta_boxes' );

function pngcje_form_shortcode_cb( $post ) {
   ?>
    <p style="font-size:.8rem;color:#555;"><?php esc_html_e('Copy this shortcode to embed the form:','pngcje'); ?></p>
    <div style="display:flex;gap:.5rem;align-items:center;">
        <code id="pngcje-shortcode-val" style="flex:1;background:#f4f4f4;padding:.5rem .75rem;border-radius:4px;font-size:.9rem;display:block;">
            [pngcje_form id="<?php echo esc_html($post->ID); ?>"]
        </code>
        <button type="button" onclick="navigator.clipboard.writeText('[pngcje_form id=&quot;<?php echo esc_js($post->ID); ?>&quot;]');this.textContent='✅';" class="button" style="flex-shrink:0;">
            📋
        </button>
    </div>
    <?php
}

function pngcje_form_settings_cb( $post ) {
    wp_nonce_field( 'pngcje_form_save', 'pngcje_form_nonce' );
    $settings = get_post_meta( $post->ID, '_pngcje_form_settings', true ) ?: [];
    $defaults = [
        'notify_email'        => get_option('admin_email'),
        'notify_subject'      => __( 'New form submission: {form_title}', 'pngcje' ),
        'confirmation_type'   => 'message',
        'confirmation_msg'    => __( 'Thank you for your message. We will be in touch shortly.', 'pngcje' ),
        'confirmation_url'    => '',
        'send_confirmation'   => '0',
        'confirm_subject'     => __( 'Thank you for contacting PNGCJE', 'pngcje' ),
        'confirm_msg'         => __( 'We have received your enquiry and will respond within 2 business days.', 'pngcje' ),
        'submit_label'        => __( 'Submit', 'pngcje' ),
        'layout'              => 'stacked',
        'form_style'          => 'plain',
        'form_bg_color'       => '',
        'form_border_color'   => '',
        'form_border_width'   => '',
        'form_radius'         => '',
        'form_padding'        => '',
        'field_style'         => 'default',
        'submit_width'        => 'fit',
        'submit_align'        => 'left',
        'submit_color'        => '',
    ];
    $s = wp_parse_args( $settings, $defaults );
   ?>
    <table class="form-table" style="margin:0;">

        <tr>
            <td colspan="2"><strong><?php esc_html_e('Notifications','pngcje'); ?></strong></td>
        </tr>
        <tr>
            <td><label style="font-size:.8rem;font-weight:600;"><?php esc_html_e('Notify Email(s)','pngcje'); ?></label></td>
            <td><input type="text" name="pngcje_fs[notify_email]" value="<?php echo esc_attr($s['notify_email']); ?>" class="widefat" style="font-size:.85rem;">
            <p class="description" style="font-size:.75rem;"><?php esc_html_e('Comma-separated for multiple.','pngcje'); ?></p></td>
        </tr>
        <tr>
            <td><label style="font-size:.8rem;font-weight:600;"><?php esc_html_e('Email Subject','pngcje'); ?></label></td>
            <td><input type="text" name="pngcje_fs[notify_subject]" value="<?php echo esc_attr($s['notify_subject']); ?>" class="widefat" style="font-size:.85rem;"></td>
        </tr>

        <tr><td colspan="2" style="padding-top:1rem;"><strong><?php esc_html_e('After Submission','pngcje'); ?></strong></td></tr>
        <tr>
            <td><label style="font-size:.8rem;font-weight:600;"><?php esc_html_e('On Success','pngcje'); ?></label></td>
            <td>
                <select name="pngcje_fs[confirmation_type]" id="pfs_conf_type" style="font-size:.85rem;">
                    <option value="message" <?php selected($s['confirmation_type'],'message'); ?>><?php esc_html_e('Show Message','pngcje'); ?></option>
                    <option value="redirect" <?php selected($s['confirmation_type'],'redirect'); ?>><?php esc_html_e('Redirect to URL','pngcje'); ?></option>
                </select>
            </td>
        </tr>
        <tr class="pfs-conf-msg" <?php echo $s['confirmation_type']==='redirect'?'style="display:none;"':''; ?>>
            <td><label style="font-size:.8rem;font-weight:600;"><?php esc_html_e('Success Message','pngcje'); ?></label></td>
            <td><textarea name="pngcje_fs[confirmation_msg]" rows="3" class="widefat" style="font-size:.85rem;"><?php echo esc_textarea($s['confirmation_msg']); ?></textarea></td>
        </tr>
        <tr class="pfs-conf-url" <?php echo $s['confirmation_type']==='message'?'style="display:none;"':''; ?>>
            <td><label style="font-size:.8rem;font-weight:600;"><?php esc_html_e('Redirect URL','pngcje'); ?></label></td>
            <td><input type="url" name="pngcje_fs[confirmation_url]" value="<?php echo esc_attr($s['confirmation_url']); ?>" class="widefat" style="font-size:.85rem;"></td>
        </tr>

        <tr><td colspan="2" style="padding-top:1rem;"><strong><?php esc_html_e('Auto-reply to Submitter','pngcje'); ?></strong></td></tr>
        <tr>
            <td><label style="font-size:.8rem;font-weight:600;"><?php esc_html_e('Enable Auto-Reply','pngcje'); ?></label></td>
            <td><label><input type="checkbox" name="pngcje_fs[send_confirmation]" value="1" <?php checked($s['send_confirmation'],'1'); ?>> <?php esc_html_e('Send confirmation email to submitter','pngcje'); ?></label>
            <p class="description" style="font-size:.75rem;"><?php esc_html_e('Requires an Email field in your form.','pngcje'); ?></p></td>
        </tr>
        <tr>
            <td><label style="font-size:.8rem;font-weight:600;"><?php esc_html_e('Reply Subject','pngcje'); ?></label></td>
            <td><input type="text" name="pngcje_fs[confirm_subject]" value="<?php echo esc_attr($s['confirm_subject']); ?>" class="widefat" style="font-size:.85rem;"></td>
        </tr>
        <tr>
            <td><label style="font-size:.8rem;font-weight:600;"><?php esc_html_e('Reply Message','pngcje'); ?></label></td>
            <td><textarea name="pngcje_fs[confirm_msg]" rows="3" class="widefat" style="font-size:.85rem;"><?php echo esc_textarea($s['confirm_msg']); ?></textarea></td>
        </tr>

        <tr><td colspan="2" style="padding-top:1rem;"><strong><?php esc_html_e('Form Appearance','pngcje'); ?></strong></td></tr>
        <tr>
            <td><label style="font-size:.8rem;font-weight:600;"><?php esc_html_e('Submit Button Label','pngcje'); ?></label></td>
            <td><input type="text" name="pngcje_fs[submit_label]" value="<?php echo esc_attr($s['submit_label']); ?>" class="widefat" style="font-size:.85rem;"></td>
        </tr>
        <tr>
            <td><label style="font-size:.8rem;font-weight:600;"><?php esc_html_e('Submit Width','pngcje'); ?></label></td>
            <td>
                <select name="pngcje_fs[submit_width]" style="font-size:.85rem;width:100%;">
                    <option value="fit" <?php selected($s['submit_width'],'fit'); ?>><?php esc_html_e('Fit button text','pngcje'); ?></option>
                    <option value="full" <?php selected($s['submit_width'],'full'); ?>><?php esc_html_e('Full width','pngcje'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td><label style="font-size:.8rem;font-weight:600;"><?php esc_html_e('Submit Alignment','pngcje'); ?></label></td>
            <td>
                <select name="pngcje_fs[submit_align]" style="font-size:.85rem;width:100%;">
                    <option value="left" <?php selected($s['submit_align'],'left'); ?>><?php esc_html_e('Left','pngcje'); ?></option>
                    <option value="center" <?php selected($s['submit_align'],'center'); ?>><?php esc_html_e('Center','pngcje'); ?></option>
                    <option value="right" <?php selected($s['submit_align'],'right'); ?>><?php esc_html_e('Right','pngcje'); ?></option>
                </select>
                <p class="description" style="font-size:.75rem;"><?php esc_html_e('Alignment applies when the button is not full width.','pngcje'); ?></p>
            </td>
        </tr>
        <tr>
            <td><label style="font-size:.8rem;font-weight:600;"><?php esc_html_e('Submit Button Color','pngcje'); ?></label></td>
            <td>
                <div style="display:flex;gap:.5rem;align-items:center;">
                    <input type="text" name="pngcje_fs[submit_color]" value="<?php echo esc_attr($s['submit_color']); ?>" class="widefat pngcje-color-text" placeholder="#D4581A" style="font-size:.85rem;">
                    <input type="color" class="pngcje-color-picker" value="<?php echo esc_attr($s['submit_color'] ?: '#D4581A'); ?>" style="width:42px;height:34px;padding:0;border:1px solid #ccd0d4;border-radius:4px;">
                </div>
            </td>
        </tr>
        <tr>
            <td><label style="font-size:.8rem;font-weight:600;"><?php esc_html_e('Layout','pngcje'); ?></label></td>
            <td>
                <select name="pngcje_fs[layout]" style="font-size:.85rem;">
                    <option value="stacked" <?php selected($s['layout'],'stacked'); ?>><?php esc_html_e('Stacked (full width)','pngcje'); ?></option>
                    <option value="inline"  <?php selected($s['layout'],'inline'); ?>><?php esc_html_e('Inline (label left)','pngcje'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td><label style="font-size:.8rem;font-weight:600;"><?php esc_html_e('Form Style','pngcje'); ?></label></td>
            <td>
                <select name="pngcje_fs[form_style]" style="font-size:.85rem;width:100%;">
                    <option value="plain" <?php selected($s['form_style'],'plain'); ?>><?php esc_html_e('Plain / transparent','pngcje'); ?></option>
                    <option value="card" <?php selected($s['form_style'],'card'); ?>><?php esc_html_e('White card background','pngcje'); ?></option>
                    <option value="outline" <?php selected($s['form_style'],'outline'); ?>><?php esc_html_e('Outlined box','pngcje'); ?></option>
                    <option value="tinted" <?php selected($s['form_style'],'tinted'); ?>><?php esc_html_e('Light tinted background','pngcje'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td><label style="font-size:.8rem;font-weight:600;"><?php esc_html_e('Background Color','pngcje'); ?></label></td>
            <td>
                <div style="display:flex;gap:.5rem;align-items:center;">
                    <input type="text" name="pngcje_fs[form_bg_color]" value="<?php echo esc_attr($s['form_bg_color']); ?>" class="widefat pngcje-color-text" placeholder="#ffffff" style="font-size:.85rem;">
                    <input type="color" class="pngcje-color-picker" value="<?php echo esc_attr($s['form_bg_color'] ?: '#ffffff'); ?>" style="width:42px;height:34px;padding:0;border:1px solid #ccd0d4;border-radius:4px;">
                </div>
            </td>
        </tr>
        <tr>
            <td><label style="font-size:.8rem;font-weight:600;"><?php esc_html_e('Outline Color','pngcje'); ?></label></td>
            <td>
                <div style="display:flex;gap:.5rem;align-items:center;">
                    <input type="text" name="pngcje_fs[form_border_color]" value="<?php echo esc_attr($s['form_border_color']); ?>" class="widefat pngcje-color-text" placeholder="#D4581A" style="font-size:.85rem;">
                    <input type="color" class="pngcje-color-picker" value="<?php echo esc_attr($s['form_border_color'] ?: '#D4581A'); ?>" style="width:42px;height:34px;padding:0;border:1px solid #ccd0d4;border-radius:4px;">
                </div>
            </td>
        </tr>
        <tr>
            <td><label style="font-size:.8rem;font-weight:600;"><?php esc_html_e('Outline Width','pngcje'); ?></label></td>
            <td><input type="number" min="0" max="12" name="pngcje_fs[form_border_width]" value="<?php echo esc_attr($s['form_border_width']); ?>" class="small-text" placeholder="1" style="font-size:.85rem;"> px</td>
        </tr>
        <tr>
            <td><label style="font-size:.8rem;font-weight:600;"><?php esc_html_e('Corner Radius','pngcje'); ?></label></td>
            <td><input type="number" min="0" max="48" name="pngcje_fs[form_radius]" value="<?php echo esc_attr($s['form_radius']); ?>" class="small-text" placeholder="12" style="font-size:.85rem;"> px</td>
        </tr>
        <tr>
            <td><label style="font-size:.8rem;font-weight:600;"><?php esc_html_e('Inner Padding','pngcje'); ?></label></td>
            <td><input type="number" min="0" max="80" name="pngcje_fs[form_padding]" value="<?php echo esc_attr($s['form_padding']); ?>" class="small-text" placeholder="24" style="font-size:.85rem;"> px
            <p class="description" style="font-size:.75rem;"><?php esc_html_e('These form settings apply anywhere this form is embedded with its shortcode.','pngcje'); ?></p></td>
        </tr>
        <tr><td colspan="2" style="padding-top:1rem;"><strong><?php esc_html_e('Default Field Appearance','pngcje'); ?></strong></td></tr>
        <tr>
            <td><label style="font-size:.8rem;font-weight:600;"><?php esc_html_e('Default Field Style','pngcje'); ?></label></td>
            <td>
                <select name="pngcje_fs[field_style]" style="font-size:.85rem;width:100%;">
                    <option value="default" <?php selected($s['field_style'],'default'); ?>><?php esc_html_e('Default','pngcje'); ?></option>
                    <option value="boxed" <?php selected($s['field_style'],'boxed'); ?>><?php esc_html_e('Boxed fields','pngcje'); ?></option>
                    <option value="underline" <?php selected($s['field_style'],'underline'); ?>><?php esc_html_e('Underline inputs','pngcje'); ?></option>
                    <option value="soft" <?php selected($s['field_style'],'soft'); ?>><?php esc_html_e('Soft filled inputs','pngcje'); ?></option>
                </select>
                <p class="description" style="font-size:.75rem;"><?php esc_html_e('Individual fields can override this below.','pngcje'); ?></p>
            </td>
        </tr>

    </table>
    <script>
    jQuery(function($){
        $('#pfs_conf_type').on('change',function(){
            var v = $(this).val();
            $('.pfs-conf-msg').toggle(v==='message');
            $('.pfs-conf-url').toggle(v==='redirect');
        });
        $(document).on('input change', '.pngcje-color-picker', function(){
            $(this).siblings('.pngcje-color-text').val($(this).val());
        });
        $(document).on('input change', '.pngcje-color-text', function(){
            var value = $(this).val();
            if (/^#[0-9a-fA-F]{6}$/.test(value)) {
                $(this).siblings('.pngcje-color-picker').val(value);
            }
        });
    });
    </script>
    <?php
}

function pngcje_form_builder_cb( $post ) {
    $fields = get_post_meta( $post->ID, '_pngcje_form_fields', true ) ?: [];
    $types  = pngcje_forms_field_types();
   ?>
    <div id="pngcje-fb-wrap">

        <!-- Toolbar -->
        <div style="background:#f9f9f9;border:1px solid #ddd;border-radius:6px;padding:1rem;margin-bottom:1.5rem;">
            <p style="font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#555;margin:0 0 .75rem;">
                <?php esc_html_e('Add Field','pngcje'); ?>
            </p>
            <div style="display:flex;flex-wrap:wrap;gap:.5rem;">
                <?php foreach ( $types as $type_key => $type_data ) : ?>
                <button type="button"
                    class="button pngcje-add-field"
                    data-type="<?php echo esc_attr($type_key); ?>"
                    style="font-size:.8rem;"
                >
                    <?php echo esc_html($type_data['icon'] . ' ' . $type_data['label']); ?>
                </button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Field List -->
        <div id="pngcje-field-list" style="display:flex;flex-direction:column;gap:.75rem;min-height:60px;">
            <?php if ( empty($fields) ) : ?>
            <div id="pngcje-fb-empty" style="text-align:center;padding:2.5rem;background:#f9f9f9;border:2px dashed #ddd;border-radius:6px;color:#999;font-size:.9rem;">
                <?php esc_html_e('No fields yet — click a button above to add your first field.','pngcje'); ?>
            </div>
            <?php else : ?>
            <div id="pngcje-fb-empty" style="display:none;"></div>
            <?php foreach ( $fields as $i => $field ) : ?>
                <?php pngcje_render_field_row( $i, $field, $types ); ?>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Hidden JSON store -->
        <input type="hidden" name="_pngcje_form_fields_json" id="pngcje-fields-json"
            value="<?php echo esc_attr( wp_json_encode($fields) ); ?>">

        <p style="font-size:.75rem;color:#999;margin-top:1rem;">
            💡 <?php esc_html_e('Drag rows to reorder. Click ✕ to remove a field.','pngcje'); ?>
        </p>

    </div>

    <?php
    // Inline template for new fields (hidden, cloned by JS)
   ?>
    <script type="text/html" id="pngcje-field-tpl">
        <?php pngcje_render_field_row( '__IDX__', [
            'type'        => '__TYPE__',
            'label'       => '',
            'name'        => '',
            'placeholder' => '',
            'required'    => false,
            'options'     => '',
            'width'       => 'full',
            'default'     => '',
            'helptext'    => '',
            'new_line'    => false,
            'field_style' => '',
            'field_bg_color' => '',
            'field_border_color' => '',
            'field_label_color' => '',
            'heading_align' => 'left',
            'content'     => '',
        ], $types, true ); ?>
    </script>

    <?php pngcje_forms_builder_scripts(); ?>
    <?php
}

// Render a single field row in the builder
function pngcje_render_field_row( $idx, $field, $types, $is_template = false ) {
    $f = wp_parse_args( $field, [
        'type'        => 'text',
        'label'       => '',
        'name'        => '',
        'placeholder' => '',
        'required'    => false,
        'options'     => '',
        'width'       => 'full',
        'default'     => '',
        'helptext'    => '',
        'new_line'    => false,
        'field_style' => '',
        'field_bg_color' => '',
        'field_border_color' => '',
        'field_label_color' => '',
        'heading_align' => 'left',
        'heading_subheading' => '',
        'content'     => '',
    ] );
    $type_info = $types[ $f['type'] ] ?? [ 'label' => $f['type'], 'icon' => '📝' ];
    $has_options = in_array( $f['type'], ['select','radio','checkbox'] );
    $has_placeholder = in_array( $f['type'], ['text','email','tel','textarea','number','date'] );
    $is_display = in_array( $f['type'], ['h1_heading','heading','paragraph','divider','linebreak'], true );
    $is_heading = in_array( $f['type'], ['h1_heading','heading'], true );
    $is_paragraph = $f['type'] === 'paragraph';
    $has_default = ! $is_display;
   ?>
    <div class="pngcje-field-row" data-idx="<?php echo esc_attr($idx); ?>" style="background:#fff;border:1px solid #ddd;border-radius:6px;overflow:hidden;">

        <!-- Row Header -->
        <div class="pngcje-field-header" style="display:flex;align-items:center;justify-content:space-between;padding:.6rem 1rem;background:#f9f9f9;border-bottom:1px solid #eee;gap:.5rem;">
            <div style="display:flex;align-items:center;gap:.5rem;font-size:.85rem;font-weight:600;color:#333;">
                <span class="pngcje-field-drag-handle" draggable="true" style="cursor:grab;color:#aaa;font-size:1rem;" title="Drag to reorder">⠿</span>
                <span><?php echo esc_html($type_info['icon']); ?></span>
                <span class="pngcje-field-type-label"><?php echo esc_html($type_info['label']); ?></span>
                <span class="pngcje-field-label-preview" style="color:#888;font-weight:400;font-size:.8rem;">
                    <?php echo $f['label'] ? '— ' . esc_html($f['label']) : ''; ?>
                </span>
            </div>
            <div style="display:flex;align-items:center;gap:.5rem;">
                <button type="button" class="pngcje-toggle-field button button-small" style="font-size:.75rem;"><?php esc_html_e('▼ Edit','pngcje'); ?></button>
                <button type="button" class="pngcje-remove-field button button-small" style="font-size:.75rem;color:#c00;" title="<?php esc_attr_e('Remove field','pngcje'); ?>">✕</button>
            </div>
        </div>

        <!-- Row Body (collapsible) -->
        <div class="pngcje-field-body" style="padding:1rem;display:none;">
            <input type="hidden" class="pf-type"  name="pngcje_fields[<?php echo esc_attr($idx); ?>][type]"  value="<?php echo esc_attr($f['type']); ?>">

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:.75rem;margin-bottom:.75rem;">
                <!-- Label -->
                <div>
                    <label style="display:block;font-size:.78rem;font-weight:600;margin-bottom:.3rem;"><?php esc_html_e('Label','pngcje'); ?></label>
                    <input type="text" class="pf-label widefat" name="pngcje_fields[<?php echo esc_attr($idx); ?>][label]"
                        value="<?php echo esc_attr($f['label']); ?>"
                        placeholder="<?php esc_attr_e('Optional field label','pngcje'); ?>"
                        style="font-size:.85rem;">
                </div>
                <!-- Name / Key -->
                <div class="pngcje-field-name-control" <?php echo $is_display ? 'style="display:none;"' : ''; ?>>
                    <label style="display:block;font-size:.78rem;font-weight:600;margin-bottom:.3rem;"><?php esc_html_e('Field Name (key)','pngcje'); ?></label>
                    <input type="text" class="pf-name widefat" name="pngcje_fields[<?php echo esc_attr($idx); ?>][name]"
                        value="<?php echo esc_attr($f['name']); ?>"
                        placeholder="<?php esc_attr_e('auto-generated','pngcje'); ?>"
                        style="font-size:.85rem;">
                    <p style="font-size:.7rem;color:#888;margin:.2rem 0 0;"><?php esc_html_e('Lowercase, no spaces.','pngcje'); ?></p>
                </div>
                <!-- Width -->
                <div>
                    <label style="display:block;font-size:.78rem;font-weight:600;margin-bottom:.3rem;"><?php esc_html_e('Column Width','pngcje'); ?></label>
                    <select name="pngcje_fields[<?php echo esc_attr($idx); ?>][width]" style="font-size:.85rem;width:100%;">
                        <option value="full" <?php selected($f['width'],'full'); ?>><?php esc_html_e('Full (100%)','pngcje'); ?></option>
                        <option value="half" <?php selected($f['width'],'half'); ?>><?php esc_html_e('Half (50%)','pngcje'); ?></option>
                        <option value="third" <?php selected($f['width'],'third'); ?>><?php esc_html_e('Third (33%)','pngcje'); ?></option>
                    </select>
                </div>
            </div>

            <div class="pngcje-heading-align-control" <?php echo $is_heading ? '' : 'style="display:none;"'; ?>>
                <label style="display:block;font-size:.78rem;font-weight:600;margin-bottom:.3rem;"><?php esc_html_e('Heading Alignment','pngcje'); ?></label>
                <select name="pngcje_fields[<?php echo esc_attr($idx); ?>][heading_align]" style="font-size:.85rem;width:100%;max-width:220px;margin-bottom:.75rem;">
                    <option value="left" <?php selected($f['heading_align'],'left'); ?>><?php esc_html_e('Left','pngcje'); ?></option>
                    <option value="center" <?php selected($f['heading_align'],'center'); ?>><?php esc_html_e('Center','pngcje'); ?></option>
                    <option value="right" <?php selected($f['heading_align'],'right'); ?>><?php esc_html_e('Right','pngcje'); ?></option>
                </select>
            </div>

            <div class="pngcje-heading-subheading-control" <?php echo $is_heading ? '' : 'style="display:none;"'; ?>>
                <label style="display:block;font-size:.78rem;font-weight:600;margin-bottom:.3rem;"><?php esc_html_e('Sub-heading / Explanation','pngcje'); ?></label>
                <textarea name="pngcje_fields[<?php echo esc_attr($idx); ?>][heading_subheading]" rows="3" class="widefat" style="font-size:.85rem;margin-bottom:.75rem;"><?php echo esc_textarea($f['heading_subheading']); ?></textarea>
            </div>

            <div class="pngcje-paragraph-content-control" <?php echo $is_paragraph ? '' : 'style="display:none;"'; ?>>
                <label style="display:block;font-size:.78rem;font-weight:600;margin-bottom:.3rem;"><?php esc_html_e('Paragraph Text','pngcje'); ?></label>
                <textarea name="pngcje_fields[<?php echo esc_attr($idx); ?>][content]" rows="4" class="widefat" style="font-size:.85rem;margin-bottom:.75rem;"><?php echo esc_textarea($f['content']); ?></textarea>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-bottom:.75rem;">
                <div>
                    <label style="display:block;font-size:.78rem;font-weight:600;margin-bottom:.3rem;"><?php esc_html_e('Field Style','pngcje'); ?></label>
                    <select name="pngcje_fields[<?php echo esc_attr($idx); ?>][field_style]" style="font-size:.85rem;width:100%;">
                        <option value="" <?php selected($f['field_style'],''); ?>><?php esc_html_e('Use form default','pngcje'); ?></option>
                        <option value="default" <?php selected($f['field_style'],'default'); ?>><?php esc_html_e('Default','pngcje'); ?></option>
                        <option value="boxed" <?php selected($f['field_style'],'boxed'); ?>><?php esc_html_e('Boxed','pngcje'); ?></option>
                        <option value="underline" <?php selected($f['field_style'],'underline'); ?>><?php esc_html_e('Underline','pngcje'); ?></option>
                        <option value="soft" <?php selected($f['field_style'],'soft'); ?>><?php esc_html_e('Soft filled','pngcje'); ?></option>
                    </select>
                </div>
                <div class="pngcje-new-line-control" style="display:flex;align-items:flex-end;padding-bottom:.25rem;">
                    <label style="display:flex;align-items:center;gap:.5rem;font-size:.85rem;font-weight:600;cursor:pointer;">
                        <input type="checkbox"
                            name="pngcje_fields[<?php echo esc_attr($idx); ?>][new_line]"
                            value="1"
                            <?php checked(!empty($f['new_line'])); ?>
                            style="width:16px;height:16px;">
                        <?php esc_html_e('Start this element on a new line','pngcje'); ?>
                    </label>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:.75rem;margin-bottom:.75rem;">
                <div>
                    <label style="display:block;font-size:.78rem;font-weight:600;margin-bottom:.3rem;"><?php esc_html_e('Input Background','pngcje'); ?></label>
                    <div style="display:flex;gap:.4rem;align-items:center;">
                        <input type="text" name="pngcje_fields[<?php echo esc_attr($idx); ?>][field_bg_color]"
                            value="<?php echo esc_attr($f['field_bg_color']); ?>"
                            class="widefat pngcje-color-text" placeholder="#ffffff" style="font-size:.85rem;">
                        <input type="color" class="pngcje-color-picker" value="<?php echo esc_attr($f['field_bg_color'] ?: '#ffffff'); ?>" style="width:38px;height:32px;padding:0;border:1px solid #ccd0d4;border-radius:4px;">
                    </div>
                </div>
                <div>
                    <label style="display:block;font-size:.78rem;font-weight:600;margin-bottom:.3rem;"><?php esc_html_e('Input Border','pngcje'); ?></label>
                    <div style="display:flex;gap:.4rem;align-items:center;">
                        <input type="text" name="pngcje_fields[<?php echo esc_attr($idx); ?>][field_border_color]"
                            value="<?php echo esc_attr($f['field_border_color']); ?>"
                            class="widefat pngcje-color-text" placeholder="#D4581A" style="font-size:.85rem;">
                        <input type="color" class="pngcje-color-picker" value="<?php echo esc_attr($f['field_border_color'] ?: '#D4581A'); ?>" style="width:38px;height:32px;padding:0;border:1px solid #ccd0d4;border-radius:4px;">
                    </div>
                </div>
                <div>
                    <label style="display:block;font-size:.78rem;font-weight:600;margin-bottom:.3rem;"><?php esc_html_e('Label Color','pngcje'); ?></label>
                    <div style="display:flex;gap:.4rem;align-items:center;">
                        <input type="text" name="pngcje_fields[<?php echo esc_attr($idx); ?>][field_label_color]"
                            value="<?php echo esc_attr($f['field_label_color']); ?>"
                            class="widefat pngcje-color-text" placeholder="#111111" style="font-size:.85rem;">
                        <input type="color" class="pngcje-color-picker" value="<?php echo esc_attr($f['field_label_color'] ?: '#111111'); ?>" style="width:38px;height:32px;padding:0;border:1px solid #ccd0d4;border-radius:4px;">
                    </div>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-bottom:.75rem;">
                <?php if ($has_placeholder) : ?>
                <!-- Placeholder -->
                <div>
                    <label style="display:block;font-size:.78rem;font-weight:600;margin-bottom:.3rem;"><?php esc_html_e('Placeholder','pngcje'); ?></label>
                    <input type="text" name="pngcje_fields[<?php echo esc_attr($idx); ?>][placeholder]"
                        value="<?php echo esc_attr($f['placeholder']); ?>"
                        class="widefat" style="font-size:.85rem;">
                </div>
                <?php endif; ?>
                <!-- Default Value -->
                <div class="pngcje-default-control" <?php echo $has_default ? '' : 'style="display:none;"'; ?>>
                    <label style="display:block;font-size:.78rem;font-weight:600;margin-bottom:.3rem;"><?php esc_html_e('Default Value','pngcje'); ?></label>
                    <input type="text" name="pngcje_fields[<?php echo esc_attr($idx); ?>][default]"
                        value="<?php echo esc_attr($f['default']); ?>"
                        class="widefat" style="font-size:.85rem;">
                </div>
            </div>

            <!-- Options -->
            <div class="pngcje-options-control" style="margin-bottom:.75rem;<?php echo $has_options ? '' : 'display:none;'; ?>">
                <label style="display:block;font-size:.78rem;font-weight:600;margin-bottom:.3rem;">
                    <?php esc_html_e('Options (one per line, or value|Label)','pngcje'); ?>
                </label>
                <textarea name="pngcje_fields[<?php echo esc_attr($idx); ?>][options]"
                    rows="4" class="widefat" style="font-size:.85rem;"><?php echo esc_textarea($f['options']); ?></textarea>
                <p style="font-size:.7rem;color:#888;margin:.2rem 0 0;"><?php esc_html_e('e.g. "Port Moresby" or "pom|Port Moresby"','pngcje'); ?></p>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                <!-- Help Text -->
                <div>
                    <label style="display:block;font-size:.78rem;font-weight:600;margin-bottom:.3rem;"><?php esc_html_e('Help Text (below field)','pngcje'); ?></label>
                    <input type="text" name="pngcje_fields[<?php echo esc_attr($idx); ?>][helptext]"
                        value="<?php echo esc_attr($f['helptext']); ?>"
                        class="widefat" style="font-size:.85rem;">
                </div>
                <?php if ( ! $is_display ) : ?>
                    <!-- Required -->
                    <div class="pngcje-required-control" style="display:flex;align-items:flex-end;padding-bottom:.25rem;">
                        <label style="display:flex;align-items:center;gap:.5rem;font-size:.85rem;font-weight:600;cursor:pointer;">
                            <input type="checkbox"
                                name="pngcje_fields[<?php echo esc_attr($idx); ?>][required]"
                                value="1"
                                <?php checked(!empty($f['required'])); ?>
                                style="width:16px;height:16px;">
                            <?php esc_html_e('Required field','pngcje'); ?>
                        </label>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}

// ============================================================
// SAVE META
// ============================================================
function pngcje_forms_save_meta( $post_id ) {
    if ( ! isset($_POST['pngcje_form_nonce'])
        || ! wp_verify_nonce($_POST['pngcje_form_nonce'], 'pngcje_form_save') ) return;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can('edit_post', $post_id) ) return;

    // Save settings
    if ( isset($_POST['pngcje_fs']) ) {
        $settings = array_map('sanitize_text_field', wp_unslash($_POST['pngcje_fs']));
        // Allow longer text for confirmation messages
        if ( isset($_POST['pngcje_fs']['confirmation_msg']) ) {
            $settings['confirmation_msg'] = sanitize_textarea_field(wp_unslash($_POST['pngcje_fs']['confirmation_msg']));
        }
        if ( isset($_POST['pngcje_fs']['confirm_msg']) ) {
            $settings['confirm_msg'] = sanitize_textarea_field(wp_unslash($_POST['pngcje_fs']['confirm_msg']));
        }
        $settings['form_style'] = in_array($settings['form_style'] ?? 'plain', ['plain','card','outline','tinted'], true) ? $settings['form_style'] : 'plain';
        $settings['field_style'] = in_array($settings['field_style'] ?? 'default', ['default','boxed','underline','soft'], true) ? $settings['field_style'] : 'default';
        $settings['submit_width'] = in_array($settings['submit_width'] ?? 'fit', ['fit','full'], true) ? $settings['submit_width'] : 'fit';
        $settings['submit_align'] = in_array($settings['submit_align'] ?? 'left', ['left','center','right'], true) ? $settings['submit_align'] : 'left';
        foreach ( [ 'form_bg_color', 'form_border_color', 'submit_color' ] as $color_key ) {
            $settings[ $color_key ] = sanitize_hex_color( $settings[ $color_key ] ?? '' ) ?: '';
        }
        foreach ( [ 'form_border_width', 'form_radius', 'form_padding' ] as $number_key ) {
            $settings[ $number_key ] = isset( $settings[ $number_key ] ) && $settings[ $number_key ] !== '' ? min( 80, absint( $settings[ $number_key ] ) ) : '';
        }
        update_post_meta($post_id, '_pngcje_form_settings', $settings);
    }

    // Save fields from POST array (rebuild JSON)
    $fields = [];
    if ( isset($_POST['pngcje_fields']) && is_array($_POST['pngcje_fields']) ) {
        foreach ( $_POST['pngcje_fields'] as $idx => $field ) {
            $clean = [
                'type'        => sanitize_key($field['type'] ?? 'text'),
                'label'       => sanitize_text_field($field['label'] ?? ''),
                'name'        => sanitize_key(str_replace(' ','_', $field['name'] ?? '')),
                'placeholder' => sanitize_text_field($field['placeholder'] ?? ''),
                'required'    => ! empty($field['required']),
                'options'     => sanitize_textarea_field($field['options'] ?? ''),
                'width'       => in_array($field['width']??'full',['full','half','third']) ? $field['width'] : 'full',
                'default'     => sanitize_text_field($field['default'] ?? ''),
                'helptext'    => sanitize_text_field($field['helptext'] ?? ''),
                'new_line'    => ! empty($field['new_line']),
                'field_style' => in_array($field['field_style']??'',['','default','boxed','underline','soft'], true) ? sanitize_key($field['field_style']) : '',
                'field_bg_color' => sanitize_hex_color($field['field_bg_color'] ?? '') ?: '',
                'field_border_color' => sanitize_hex_color($field['field_border_color'] ?? '') ?: '',
                'field_label_color' => sanitize_hex_color($field['field_label_color'] ?? '') ?: '',
                'heading_align' => in_array($field['heading_align']??'left',['left','center','right'], true) ? sanitize_key($field['heading_align']) : 'left',
                'heading_subheading' => sanitize_textarea_field($field['heading_subheading'] ?? ''),
                'content'     => sanitize_textarea_field($field['content'] ?? ''),
            ];
            if ( in_array( $clean['type'], ['h1_heading','heading','paragraph','divider','linebreak'], true ) ) {
                $clean['name'] = '';
                $clean['required'] = false;
            }
            // Auto-generate name from label if empty
            if ( empty($clean['name']) && ! empty($clean['label']) && ! in_array( $clean['type'], ['h1_heading','heading','paragraph','divider','linebreak'], true ) ) {
                $clean['name'] = sanitize_key(str_replace(' ','_', strtolower($clean['label'])));
            }
            if ( empty($clean['name']) && ! in_array( $clean['type'], ['h1_heading','heading','paragraph','divider','linebreak'], true ) ) {
                $clean['name'] = 'field_' . absint($idx + 1);
            }
            $fields[] = $clean;
        }
    }
    update_post_meta($post_id, '_pngcje_form_fields', $fields);
}
add_action('save_post_pngcje_form', 'pngcje_forms_save_meta');

// ============================================================
// FRONTEND RENDERER
// ============================================================
function pngcje_render_form( $form_id, $context_data = [] ) {
    $form = get_post( (int) $form_id );
    if ( ! $form || $form->post_type !== 'pngcje_form' ) return '';

    $fields   = get_post_meta($form->ID, '_pngcje_form_fields', true) ?: [];
    $settings = get_post_meta($form->ID, '_pngcje_form_settings', true) ?: [];
    $settings = wp_parse_args($settings, [
        'submit_label'  => __('Submit','pngcje'),
        'layout'        => 'stacked',
        'form_style'    => 'plain',
        'form_bg_color' => '',
        'form_border_color' => '',
        'form_border_width' => '',
        'form_radius' => '',
        'form_padding' => '',
        'field_style'   => 'default',
        'submit_width'  => 'fit',
        'submit_align'  => 'left',
        'submit_color'  => '',
    ]);

    if ( empty($fields) ) return '<p class="pngcje-form-notice">' . esc_html__('This form has no fields configured yet.','pngcje') . '</p>';

    $uniq = 'pngcje-form-' . $form->ID . '-' . wp_rand(100,999);
    $form_styles = [];
    if ( ! empty( $settings['form_bg_color'] ) ) {
        $form_styles[] = 'background-color:' . sanitize_hex_color( $settings['form_bg_color'] );
    }
    if ( ! empty( $settings['form_border_color'] ) ) {
        $width = $settings['form_border_width'] !== '' ? absint( $settings['form_border_width'] ) : 1;
        $form_styles[] = 'border:' . $width . 'px solid ' . sanitize_hex_color( $settings['form_border_color'] );
    } elseif ( $settings['form_border_width'] !== '' ) {
        $form_styles[] = 'border-width:' . absint( $settings['form_border_width'] ) . 'px';
    }
    if ( $settings['form_radius'] !== '' ) {
        $form_styles[] = 'border-radius:' . absint( $settings['form_radius'] ) . 'px';
    }
    if ( $settings['form_padding'] !== '' ) {
        $form_styles[] = 'padding:' . absint( $settings['form_padding'] ) . 'px';
    }
    $submit_styles = [];
    if ( ! empty( $settings['submit_color'] ) ) {
        $submit_styles[] = 'background-color:' . sanitize_hex_color( $settings['submit_color'] );
        $submit_styles[] = 'border-color:' . sanitize_hex_color( $settings['submit_color'] );
    }

    ob_start();
   ?>
    <div class="pngcje-form-wrap" id="<?php echo esc_attr($uniq); ?>" data-form-id="<?php echo esc_attr($form->ID); ?>">

        <!-- Success message (hidden until submission) -->
        <div class="pngcje-form-success" style="display:none;" role="alert" aria-live="polite"></div>
        <div class="pngcje-form-error-global" style="display:none;" role="alert"></div>

        <form
            class="pngcje-form pngcje-form--<?php echo esc_attr($settings['layout']); ?> pngcje-form--style-<?php echo esc_attr($settings['form_style']); ?> pngcje-form--fields-<?php echo esc_attr($settings['field_style']); ?>"
            style="<?php echo esc_attr( implode( ';', array_filter( $form_styles ) ) ); ?>"
            method="post"
            enctype="multipart/form-data"
            novalidate
            data-form-id="<?php echo esc_attr($form->ID); ?>"
        >
            <?php wp_nonce_field('pngcje_form_submit_' . $form->ID, 'pngcje_nonce'); ?>
            <input type="hidden" name="action"   value="pngcje_form_submit">
            <input type="hidden" name="form_id"  value="<?php echo esc_attr($form->ID); ?>">
            <input type="hidden" name="page_url" value="<?php echo esc_attr(get_permalink() ?: home_url()); ?>">

            <!-- Context data (e.g. event title passed from template) -->
            <?php foreach ($context_data as $k => $v) : ?>
            <input type="hidden" name="ctx_<?php echo esc_attr(sanitize_key($k)); ?>" value="<?php echo esc_attr($v); ?>">
            <?php endforeach; ?>

            <!-- Honeypot anti-spam -->
            <div style="position:absolute;left:-9999px;visibility:hidden;height:0;overflow:hidden;" aria-hidden="true">
                <label><?php esc_html_e('Leave blank','pngcje'); ?></label>
                <input type="text" name="pngcje_honeypot" tabindex="-1" autocomplete="off">
            </div>

            <!-- Field rows -->
            <div class="pngcje-form-fields pngcje-form-fields--<?php echo esc_attr($settings['layout']); ?>">
                <?php foreach ($fields as $field) : ?>
                    <?php pngcje_render_field_frontend($field, $settings['layout'], $settings['field_style']); ?>
                <?php endforeach; ?>
            </div>

            <!-- Submit -->
            <div class="pngcje-form-submit pngcje-form-submit--<?php echo esc_attr($settings['submit_width']); ?> pngcje-form-submit--<?php echo esc_attr($settings['submit_align']); ?>">
                <button type="submit" class="btn btn-primary" style="<?php echo esc_attr( implode( ';', $submit_styles ) ); ?>">
                    <span class="pngcje-submit-label"><?php echo esc_html($settings['submit_label']); ?></span>
                    <span class="pngcje-submit-spinner" style="display:none;" aria-hidden="true">⏳</span>
                </button>
            </div>
        </form>
    </div>
    <?php
    return ob_get_clean();
}

// Render a single field on the frontend
function pngcje_render_field_frontend( $field, $layout = 'stacked', $default_field_style = 'default' ) {
    $break_class = ! empty($field['new_line']) ? ' pngcje-field--new-line' : '';

    if ( 'linebreak' === $field['type'] ) {
        echo '<div class="pngcje-field-linebreak" aria-hidden="true"></div>';
        return;
    }

    if ( ! empty($field['new_line']) ) {
        echo '<div class="pngcje-field-linebreak" aria-hidden="true"></div>';
    }

    if ( in_array($field['type'], ['h1_heading','heading','paragraph','divider']) ) {
        if ( $field['type'] === 'h1_heading' ) {
            $align = in_array($field['heading_align'] ?? 'left', ['left','center','right'], true) ? $field['heading_align'] : 'left';
            $subheading = ! empty($field['heading_subheading']) ? '<div class="pngcje-field-heading-subheading" style="font-size:1rem;color:var(--ink-mid);line-height:1.7;margin:-.35rem 0 1rem;">' . wpautop(esc_html($field['heading_subheading'])) . '</div>' : '';
            echo '<div class="pngcje-field-heading pngcje-field-heading--h1 pngcje-field-heading--' . esc_attr($align) . esc_attr($break_class) . '" style="grid-column:1/-1;text-align:' . esc_attr($align) . ';"><h1 style="font-size:2rem;font-weight:800;color:var(--green-dark);line-height:1.2;margin:1rem 0 .75rem;">' . esc_html($field['label']) . '</h1>' . $subheading . '</div>';
        } elseif ( $field['type'] === 'heading' ) {
            $align = in_array($field['heading_align'] ?? 'left', ['left','center','right'], true) ? $field['heading_align'] : 'left';
            $subheading = ! empty($field['heading_subheading']) ? '<div class="pngcje-field-heading-subheading" style="font-size:.95rem;color:var(--ink-mid);line-height:1.7;margin:-.25rem 0 .75rem;">' . wpautop(esc_html($field['heading_subheading'])) . '</div>' : '';
            echo '<div class="pngcje-field-heading pngcje-field-heading--' . esc_attr($align) . esc_attr($break_class) . '" style="grid-column:1/-1;text-align:' . esc_attr($align) . ';"><h3 style="font-size:1.1rem;font-weight:700;color:var(--green-dark);border-bottom:2px solid var(--gold-primary);padding-bottom:.5rem;margin:1rem 0 .5rem;">' . esc_html($field['label']) . '</h3>' . $subheading . '</div>';
        } elseif ( $field['type'] === 'paragraph' ) {
            $content = ! empty($field['content']) ? $field['content'] : ($field['label'] ?? '');
            echo '<div class="pngcje-field-paragraph' . esc_attr($break_class) . '" style="grid-column:1/-1;color:var(--ink-mid);line-height:1.75;margin:.35rem 0 .85rem;">' . wpautop(esc_html($content)) . '</div>';
        } else {
            echo '<div class="pngcje-field-divider' . esc_attr($break_class) . '" style="grid-column:1/-1;border:none;border-top:1px solid var(--border-light);margin:.5rem 0;"></div>';
        }
        return;
    }
    if ( $field['type'] === 'hidden' ) {
        echo '<input type="hidden" name="fields[' . esc_attr($field['name']) . ']" value="' . esc_attr($field['default']) . '">';
        return;
    }

    $id       = 'pff-' . esc_attr($field['name']) . '-' . wp_rand(10,99);
    $required = ! empty($field['required']);
    $req_attr = $required ? 'required aria-required="true"' : '';
    $width_class = 'pngcje-field-col--' . ($field['width'] ?? 'full');
    $field_style = ! empty($field['field_style']) ? $field['field_style'] : $default_field_style;
    $style_class = 'pngcje-field-style--' . sanitize_html_class($field_style ?: 'default');
    $label_style = ! empty($field['field_label_color']) ? ' style="color:' . esc_attr(sanitize_hex_color($field['field_label_color'])) . ';"' : '';
    $input_styles = [];
    if ( ! empty($field['field_bg_color']) ) {
        $input_styles[] = 'background-color:' . sanitize_hex_color($field['field_bg_color']);
    }
    if ( ! empty($field['field_border_color']) ) {
        $input_styles[] = 'border-color:' . sanitize_hex_color($field['field_border_color']);
    }
    $input_style_attr = $input_styles ? ' style="' . esc_attr(implode(';', $input_styles)) . '"' : '';

    echo '<div class="pngcje-field-wrap ' . esc_attr($width_class . $break_class . ' ' . $style_class) . '">';

    // Label
    if ( ! empty($field['label']) && ! in_array($field['type'], ['checkbox']) ) {
        echo '<label for="' . esc_attr($id) . '" class="pngcje-field-label"' . $label_style . '>';
        echo esc_html($field['label']);
        if ($required) echo ' <span class="pngcje-required" aria-hidden="true" style="color:var(--red-mid);">*</span>';
        echo '</label>';
    }

    $name  = 'fields[' . esc_attr($field['name']) . ']';
    $ph    = $field['placeholder'] ? 'placeholder="' . esc_attr($field['placeholder']) . '"' : '';
    $val   = 'value="' . esc_attr($field['default']) . '"';
    $base  = 'id="' . esc_attr($id) . '" name="' . $name . '" ' . $req_attr . ' class="pngcje-field-input"' . $input_style_attr;

    switch ($field['type']) {
        case 'textarea':
            echo '<textarea ' . $base . ' rows="5" ' . $ph . '>' . esc_textarea($field['default']) . '</textarea>';
            break;
        case 'select':
            echo '<select ' . $base . '>';
            echo '<option value="">' . esc_html($field['placeholder'] ?: __('— Select —','pngcje')) . '</option>';
            foreach (pngcje_parse_options($field['options']) as $opt_val => $opt_label) {
                echo '<option value="' . esc_attr($opt_val) . '"' . ($field['default']===$opt_val?' selected':'') . '>' . esc_html($opt_label) . '</option>';
            }
            echo '</select>';
            break;
        case 'radio':
            echo '<div class="pngcje-field-choices" role="radiogroup" aria-label="' . esc_attr($field['label'] ?: $field['name']) . '">';
            foreach (pngcje_parse_options($field['options']) as $opt_val => $opt_label) {
                $rid = $id . '-' . sanitize_key($opt_val);
                echo '<label class="pngcje-choice-label"><input type="radio" id="' . esc_attr($rid) . '" name="' . $name . '" value="' . esc_attr($opt_val) . '" ' . $req_attr . ' ' . checked($field['default'],$opt_val,false) . '> ' . esc_html($opt_label) . '</label>';
            }
            echo '</div>';
            break;
        case 'checkbox':
            echo '<div class="pngcje-field-choices" role="group" aria-label="' . esc_attr($field['label'] ?: $field['name']) . '">';
            if ( ! empty($field['label']) ) {
                echo '<label class="pngcje-field-label"' . $label_style . '>' . esc_html($field['label']);
                if ($required) echo ' <span class="pngcje-required" style="color:var(--red-mid);">*</span>';
                echo '</label>';
            }
            foreach (pngcje_parse_options($field['options']) as $opt_val => $opt_label) {
                $cid = $id . '-' . sanitize_key($opt_val);
                echo '<label class="pngcje-choice-label"><input type="checkbox" id="' . esc_attr($cid) . '" name="fields[' . esc_attr($field['name']) . '][]" value="' . esc_attr($opt_val) . '"> ' . esc_html($opt_label) . '</label>';
            }
            echo '</div>';
            break;
        case 'file':
            echo '<input type="file" ' . $base . ' accept=".pdf,.doc,.docx,.jpg,.png,.zip">';
            break;
        default:
            echo '<input type="' . esc_attr($field['type']) . '" ' . $base . ' ' . $ph . ' ' . $val . '>';
    }

    if (!empty($field['helptext'])) {
        echo '<p class="pngcje-field-help">' . esc_html($field['helptext']) . '</p>';
    }
    echo '<p class="pngcje-field-error" style="display:none;" role="alert"></p>';
    echo '</div>';
}

function pngcje_parse_options( $raw ) {
    $out = [];
    foreach (array_filter(array_map('trim', explode("\n", $raw))) as $line) {
        if ( strpos($line,'|') !== false ) {
            [ $v, $l ] = explode('|', $line, 2);
            $out[trim($v)] = trim($l);
        } else {
            $out[trim($line)] = trim($line);
        }
    }
    return $out;
}

// Shortcode
function pngcje_form_shortcode( $atts ) {
    $atts = shortcode_atts(['id'=>0,'class'=>''], $atts, 'pngcje_form');
    return pngcje_render_form((int)$atts['id']);
}
add_shortcode('pngcje_form', 'pngcje_form_shortcode');

function pngcje_forms_rate_limited( $form_id ) {
    $ip  = sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? 'unknown' );
    $key = 'pngcje_form_rate_' . md5( $form_id . '|' . $ip );
    $hits = (int) get_transient( $key );

    if ( $hits >= 5 ) {
        return true;
    }

    set_transient( $key, $hits + 1, 10 * MINUTE_IN_SECONDS );
    return false;
}

function pngcje_forms_uploaded_file( $key ) {
    if ( empty( $_FILES['fields']['name'][ $key ] ) ) {
        return null;
    }

    return [
        'name'     => $_FILES['fields']['name'][ $key ],
        'type'     => $_FILES['fields']['type'][ $key ] ?? '',
        'tmp_name' => $_FILES['fields']['tmp_name'][ $key ] ?? '',
        'error'    => $_FILES['fields']['error'][ $key ] ?? UPLOAD_ERR_NO_FILE,
        'size'     => $_FILES['fields']['size'][ $key ] ?? 0,
    ];
}

// ============================================================
// AJAX SUBMISSION HANDLER
// ============================================================
function pngcje_handle_form_submission() {
    // Both logged-in and guest
    $form_id = isset($_POST['form_id']) ? (int)$_POST['form_id'] : 0;

    if ( ! $form_id || ! wp_verify_nonce($_POST['pngcje_nonce'] ?? '', 'pngcje_form_submit_' . $form_id) ) {
        wp_send_json_error(['message' => __('Security check failed. Please refresh the page.','pngcje')]);
    }

    // Honeypot
    if ( ! empty($_POST['pngcje_honeypot']) ) {
        wp_send_json_success(['message' => __('Thank you for your message.','pngcje')]); // Silent drop
    }

    $form     = get_post($form_id);
    if ( ! $form || $form->post_type !== 'pngcje_form' ) {
        wp_send_json_error(['message' => __('Form not found.','pngcje')]);
    }

    if ( pngcje_forms_rate_limited( $form_id ) ) {
        wp_send_json_error(['message' => __('Too many submissions. Please wait a few minutes and try again.','pngcje')]);
    }

    $fields   = get_post_meta($form_id, '_pngcje_form_fields', true) ?: [];
    $settings = get_post_meta($form_id, '_pngcje_form_settings', true) ?: [];
    $settings = wp_parse_args($settings, [
        'notify_email'      => get_option('admin_email'),
        'notify_subject'    => 'New submission: ' . get_the_title($form_id),
        'confirmation_type' => 'message',
        'confirmation_msg'  => __('Thank you for your message. We will be in touch shortly.','pngcje'),
        'confirmation_url'  => '',
        'send_confirmation' => '0',
        'confirm_subject'   => __('Thank you for contacting PNGCJE','pngcje'),
        'confirm_msg'       => __('We have received your enquiry and will respond within 2 business days.','pngcje'),
    ]);

    // Validate
    $submitted = $_POST['fields'] ?? [];
    $errors    = [];
    $data      = [];
    $submitter_email = '';

    foreach ($fields as $field) {
        if (in_array($field['type'], ['h1_heading','heading','paragraph','divider','linebreak','hidden'])) continue;
        $key   = $field['name'];
        $field_label = ! empty($field['label']) ? $field['label'] : $key;
        $value = isset($submitted[$key]) ? $submitted[$key] : '';

        if ( $field['type'] === 'file' ) {
            $upload = pngcje_forms_uploaded_file( $key );

            if ( ! $upload || (int) $upload['error'] === UPLOAD_ERR_NO_FILE ) {
                $value = '';
            } elseif ( (int) $upload['error'] !== UPLOAD_ERR_OK ) {
                $errors[$key] = sprintf(__('%s could not be uploaded.','pngcje'), $field_label);
                $value = '';
            } else {
                require_once ABSPATH . 'wp-admin/includes/file.php';
                $allowed = [
                    'pdf'  => 'application/pdf',
                    'doc'  => 'application/msword',
                    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'jpg'  => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'png'  => 'image/png',
                    'zip'  => 'application/zip',
                ];
                $handled = wp_handle_upload( $upload, [
                    'test_form' => false,
                    'mimes'     => $allowed,
                ] );

                if ( isset( $handled['error'] ) ) {
                    $errors[$key] = $handled['error'];
                    $value = '';
                } else {
                    $value = esc_url_raw( $handled['url'] );
                }
            }
        } elseif (is_array($value)) {
            $value = implode(', ', array_map('sanitize_text_field', $value));
        } else {
            $value = $field['type'] === 'textarea'
                ? sanitize_textarea_field(wp_unslash($value))
                : sanitize_text_field(wp_unslash($value));
        }

        if ( !empty($field['required']) && empty(trim((string)$value)) ) {
            $errors[$key] = sprintf(__('%s is required.','pngcje'), $field_label);
        }
        if ( $field['type'] === 'email' && !empty($value) && !is_email($value) ) {
            $errors[$key] = __('Please enter a valid email address.','pngcje');
        }

        $data[$key] = ['label' => $field_label, 'value' => $value, 'type' => $field['type']];

        if ($field['type'] === 'email' && !empty($value) && is_email($value)) {
            $submitter_email = $value;
        } elseif ( empty($submitter_email) && !empty($value) && is_email($value) ) {
            $submitter_email = $value;
        }
    }

    if (!empty($errors)) {
        wp_send_json_error(['errors' => $errors]);
    }

    // Store submission
    $sub_title = sprintf('%s — %s', get_the_title($form_id), date('Y-m-d H:i'));
    $sub_id = wp_insert_post([
        'post_type'   => 'pngcje_submission',
        'post_title'  => $sub_title,
        'post_status' => 'private',
    ]);
    if ($sub_id) {
        $ip = sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? '');
        update_post_meta($sub_id, '_pngcje_form_id',      $form_id);
        update_post_meta($sub_id, '_pngcje_form_data',    $data);
        update_post_meta($sub_id, '_pngcje_submitted_at', current_time('mysql'));
        update_post_meta($sub_id, '_pngcje_submitter_ip', function_exists('wp_privacy_anonymize_ip') ? wp_privacy_anonymize_ip($ip) : $ip);
        update_post_meta($sub_id, '_pngcje_page_url',     esc_url_raw($_POST['page_url'] ?? ''));
    }

    // Build email body
    $email_body = "New submission from: " . get_the_title($form_id) . "\n";
    $email_body .= "Submitted: " . current_time('mysql') . "\n";
    $email_body .= "Page: " . sanitize_text_field($_POST['page_url'] ?? '') . "\n\n";
    $email_body .= str_repeat('-', 40) . "\n\n";
    foreach ($data as $row) {
        $email_body .= $row['label'] . ":\n" . $row['value'] . "\n\n";
    }
    $email_body .= str_repeat('-', 40) . "\n";
    $email_body .= "Submission ID: #" . ($sub_id ?: 'N/A') . "\n";
    $email_body .= "View in admin: " . admin_url("post.php?post={$sub_id}&action=edit") . "\n";

    $subject = str_replace('{form_title}', get_the_title($form_id), $settings['notify_subject']);

    // Notify admin
    $notify_emails = pngcje_forms_email_list( $settings['notify_email'] ?? '' );
    $mail_results = [
        'notify'     => [],
        'auto_reply' => 'not_sent',
    ];
    foreach ($notify_emails as $notify_to) {
        $mail_results['notify'][$notify_to] = wp_mail(
            $notify_to,
            $subject,
            $email_body,
            pngcje_forms_mail_headers( $submitter_email )
        ) ? 'sent' : 'failed';
    }

    // Auto-reply to submitter
    if (!empty($settings['send_confirmation']) && $settings['send_confirmation'] == '1' && $submitter_email) {
        $mail_results['auto_reply'] = wp_mail(
            $submitter_email,
            $settings['confirm_subject'],
            $settings['confirm_msg'] . "\n\n-- " . get_bloginfo('name'),
            pngcje_forms_mail_headers()
        ) ? 'sent' : 'failed';
    } elseif ( ! empty($settings['send_confirmation']) && $settings['send_confirmation'] == '1' ) {
        $mail_results['auto_reply'] = 'missing_submitter_email';
    }

    if ( $sub_id ) {
        update_post_meta($sub_id, '_pngcje_mail_results', $mail_results);
    }

    // Response
    if ($settings['confirmation_type'] === 'redirect' && !empty($settings['confirmation_url'])) {
        wp_send_json_success(['redirect' => esc_url($settings['confirmation_url'])]);
    } else {
        wp_send_json_success(['message' => wp_kses_post($settings['confirmation_msg'])]);
    }
}
add_action('wp_ajax_pngcje_form_submit',        'pngcje_handle_form_submission');
add_action('wp_ajax_nopriv_pngcje_form_submit', 'pngcje_handle_form_submission');

function pngcje_forms_email_list( $emails ) {
    $emails = preg_split( '/[\s,;]+/', (string) $emails );
    $valid  = [];

    foreach ( $emails as $email ) {
        $email = sanitize_email( trim( $email ) );
        if ( $email && is_email( $email ) && ! in_array( $email, $valid, true ) ) {
            $valid[] = $email;
        }
    }

    return $valid;
}

function pngcje_forms_from_email() {
    $host = wp_parse_url( home_url(), PHP_URL_HOST );
    $host = $host ? preg_replace( '/^www\./', '', strtolower( $host ) ) : '';
    $from = $host ? 'no-reply@' . $host : get_option( 'admin_email' );

    return is_email( $from ) ? $from : get_option( 'admin_email' );
}

function pngcje_forms_mail_headers( $reply_to = '' ) {
    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) . ' <' . pngcje_forms_from_email() . '>',
    ];

    $reply_to = sanitize_email( $reply_to );
    if ( $reply_to && is_email( $reply_to ) ) {
        $headers[] = 'Reply-To: ' . $reply_to;
    }

    return $headers;
}

// ============================================================
// SUBMISSION VIEWER (Admin Meta Box)
// ============================================================
function pngcje_submission_viewer_metabox() {
    add_meta_box(
        'pngcje_submission_view',
        __('Submission Data','pngcje'),
        'pngcje_submission_view_cb',
        'pngcje_submission',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'pngcje_submission_viewer_metabox');

function pngcje_submission_view_cb($post) {
    $data     = get_post_meta($post->ID, '_pngcje_form_data', true) ?: [];
    $form_id  = get_post_meta($post->ID, '_pngcje_form_id',   true);
    $subtime  = get_post_meta($post->ID, '_pngcje_submitted_at', true);
    $ip       = get_post_meta($post->ID, '_pngcje_submitter_ip', true);
    $page_url = get_post_meta($post->ID, '_pngcje_page_url',  true);
    $mail_results = get_post_meta($post->ID, '_pngcje_mail_results', true);
    $download_url = wp_nonce_url(
        admin_url( 'admin-post.php?action=pngcje_export_submission&submission_id=' . absint( $post->ID ) ),
        'pngcje_export_submission_' . $post->ID
    );
    $copy_lines = [
        'Submission ID: #' . $post->ID,
        'Form: ' . ( $form_id ? get_the_title( $form_id ) : '' ),
        'Submitted: ' . $subtime,
        'Page: ' . $page_url,
        'IP: ' . $ip,
        '',
    ];
    foreach ( $data as $row ) {
        $copy_lines[] = ( $row['label'] ?? '' ) . ': ' . ( $row['value'] ?? '' );
    }
    $copy_text = implode( "\n", $copy_lines );
   ?>
    <div style="font-family:'Montserrat',sans-serif;font-size:.875rem;">
        <div style="display:flex;gap:.75rem;flex-wrap:wrap;margin-bottom:1rem;">
            <a href="<?php echo esc_url( $download_url ); ?>" class="button button-primary">
                <?php esc_html_e( 'Download CSV', 'pngcje' ); ?>
            </a>
            <button type="button" class="button" id="pngcje-copy-submission">
                <?php esc_html_e( 'Copy All', 'pngcje' ); ?>
            </button>
            <textarea id="pngcje-copy-submission-data" style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;"><?php echo esc_textarea( $copy_text ); ?></textarea>
        </div>
        <div style="display:flex;gap:2rem;flex-wrap:wrap;margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid #eee;font-size:.8rem;color:#777;">
            <?php if ($form_id) : ?>
            <span>📋 <strong><?php esc_html_e('Form:','pngcje'); ?></strong>
                <a href="<?php echo esc_url(get_edit_post_link($form_id)); ?>"><?php echo esc_html(get_the_title($form_id)); ?></a>
            </span>
            <?php endif; ?>
            <?php if ($subtime) : ?>
            <span>🕐 <strong><?php esc_html_e('Submitted:','pngcje'); ?></strong> <?php echo esc_html($subtime); ?></span>
            <?php endif; ?>
            <?php if ($ip) : ?>
            <span>🌐 <strong><?php esc_html_e('IP:','pngcje'); ?></strong> <?php echo esc_html($ip); ?></span>
            <?php endif; ?>
            <?php if ($page_url) : ?>
            <span>🔗 <strong><?php esc_html_e('Page:','pngcje'); ?></strong> <a href="<?php echo esc_url($page_url); ?>" target="_blank"><?php echo esc_html($page_url); ?></a></span>
            <?php endif; ?>
            <?php if ( is_array($mail_results) ) : ?>
            <span>✉️ <strong><?php esc_html_e('Email:','pngcje'); ?></strong>
                <?php
                $notify_status = ! empty($mail_results['notify']) && is_array($mail_results['notify'])
                    ? implode(', ', array_map(
                        static function( $email, $status ) {
                            return $email . ' (' . $status . ')';
                        },
                        array_keys($mail_results['notify']),
                        $mail_results['notify']
                    ))
                    : __('no notification recipients','pngcje');
                printf(
                    /* translators: 1: notification status, 2: auto reply status */
                    esc_html__('Notify: %1$s | Auto-reply: %2$s','pngcje'),
                    esc_html($notify_status),
                    esc_html($mail_results['auto_reply'] ?? 'not_sent')
                );
                ?>
            </span>
            <?php endif; ?>
        </div>
        <?php if (!empty($data)) : ?>
        <table style="width:100%;border-collapse:collapse;">
            <?php foreach ($data as $key => $row) : ?>
            <tr style="border-bottom:1px solid #f0f0f0;">
                <th style="text-align:left;padding:.6rem .75rem .6rem 0;font-size:.8rem;color:#555;font-weight:600;width:30%;vertical-align:top;">
                    <?php echo esc_html($row['label']); ?>
                </th>
                <td style="padding:.6rem 0;color:#111;vertical-align:top;word-break:break-word;">
                    <?php echo nl2br(esc_html($row['value'])); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php else : ?>
        <p style="color:#999;"><?php esc_html_e('No submission data found.','pngcje'); ?></p>
        <?php endif; ?>
    </div>
    <script>
    jQuery(function($){
        $('#pngcje-copy-submission').on('click', function(){
            var text = $('#pngcje-copy-submission-data').val();
            var button = this;
            function copied() {
                var oldText = button.textContent;
                button.textContent = '<?php echo esc_js( __( 'Copied', 'pngcje' ) ); ?>';
                setTimeout(function(){ button.textContent = oldText; }, 1400);
            }
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(copied);
            } else {
                $('#pngcje-copy-submission-data').trigger('select');
                document.execCommand('copy');
                copied();
            }
        });
    });
    </script>
    <?php
}

// ============================================================
// SUBMISSION ADMIN COLUMNS
// ============================================================
function pngcje_forms_mail_status_label( $status ) {
    $labels = [
        'sent'                    => __( 'Sent', 'pngcje' ),
        'failed'                  => __( 'Failed', 'pngcje' ),
        'missing_submitter_email' => __( 'No submitter email', 'pngcje' ),
        'not_sent'                => __( 'Not sent', 'pngcje' ),
        'not_recorded'            => __( 'Not recorded', 'pngcje' ),
        'no_recipients'           => __( 'No recipients', 'pngcje' ),
    ];

    return $labels[ $status ] ?? $status;
}

add_filter('manage_pngcje_submission_posts_columns', function($cols) {
    return [
        'cb'           => $cols['cb'],
        'title'        => __('Submission','pngcje'),
        'form'         => __('Form','pngcje'),
        'notify_email' => __('Notify Email','pngcje'),
        'auto_reply'   => __('Auto Reply','pngcje'),
        'submitted'    => __('Submitted','pngcje'),
        'ip'           => __('IP Address','pngcje'),
    ];
});
add_action('manage_pngcje_submission_posts_custom_column', function($col, $post_id) {
    $mail_results = get_post_meta($post_id, '_pngcje_mail_results', true);

    switch ($col) {
        case 'form':
            $fid = get_post_meta($post_id,'_pngcje_form_id',true);
            if ($fid) echo '<a href="' . esc_url(get_edit_post_link($fid)) . '">' . esc_html(get_the_title($fid)) . '</a>';
            break;
        case 'notify_email':
            if ( ! is_array($mail_results) || ! isset($mail_results['notify']) ) {
                echo esc_html(pngcje_forms_mail_status_label('not_recorded'));
                break;
            }
            if ( empty($mail_results['notify']) || ! is_array($mail_results['notify']) ) {
                echo esc_html(pngcje_forms_mail_status_label('no_recipients'));
                break;
            }
            $notify_statuses = array_unique(array_values($mail_results['notify']));
            echo esc_html(pngcje_forms_mail_status_label(in_array('failed', $notify_statuses, true) ? 'failed' : 'sent'));
            break;
        case 'auto_reply':
            $auto_reply = is_array($mail_results) ? ( $mail_results['auto_reply'] ?? 'not_sent' ) : 'not_recorded';
            echo esc_html(pngcje_forms_mail_status_label($auto_reply));
            break;
        case 'submitted':
            echo esc_html(get_post_meta($post_id,'_pngcje_submitted_at',true));
            break;
        case 'ip':
            echo esc_html(get_post_meta($post_id,'_pngcje_submitter_ip',true));
            break;
    }
}, 10, 2);

add_action('restrict_manage_posts', function($post_type) {
    if ( $post_type !== 'pngcje_submission' ) {
        return;
    }

    $selected = isset($_GET['pngcje_form_filter']) ? absint($_GET['pngcje_form_filter']) : 0;
    $forms = get_posts([
        'post_type'      => 'pngcje_form',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
    ]);
    ?>
    <select name="pngcje_form_filter" id="pngcje_form_filter">
        <option value="0"><?php esc_html_e('All Forms','pngcje'); ?></option>
        <?php foreach ( $forms as $form ) : ?>
            <option value="<?php echo esc_attr($form->ID); ?>" <?php selected($selected, $form->ID); ?>>
                <?php echo esc_html($form->post_title); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php
    $export_url = wp_nonce_url(
        admin_url( 'admin-post.php?action=pngcje_export_submissions' . ( $selected ? '&form_id=' . $selected : '' ) ),
        'pngcje_export_submissions'
    );
    ?>
    <a href="<?php echo esc_url($export_url); ?>" class="button" style="margin-left:.35rem;">
        <?php echo $selected ? esc_html__('Export Filtered CSV','pngcje') : esc_html__('Export All CSV','pngcje'); ?>
    </a>
    <?php
});

add_action('pre_get_posts', function($query) {
    if ( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }

    $post_type = $query->get('post_type');
    if ( $post_type !== 'pngcje_submission' ) {
        return;
    }

    $form_id = isset($_GET['pngcje_form_filter']) ? absint($_GET['pngcje_form_filter']) : 0;
    if ( $form_id ) {
        $query->set('meta_query', [
            [
                'key'   => '_pngcje_form_id',
                'value' => $form_id,
            ],
        ]);
    }
});

function pngcje_submissions_csv_rows( $submission_ids ) {
    $rows = [];
    $field_headers = [];

    foreach ( $submission_ids as $submission_id ) {
        $data = get_post_meta($submission_id, '_pngcje_form_data', true) ?: [];
        foreach ( $data as $key => $row ) {
            $label = $row['label'] ?? $key;
            if ( $label && ! in_array($label, $field_headers, true) ) {
                $field_headers[] = $label;
            }
        }
    }

    $headers = array_merge(
        ['Submission ID', 'Form', 'Submitted', 'Page URL', 'IP Address'],
        $field_headers
    );
    $rows[] = $headers;

    foreach ( $submission_ids as $submission_id ) {
        $form_id  = get_post_meta($submission_id, '_pngcje_form_id', true);
        $data     = get_post_meta($submission_id, '_pngcje_form_data', true) ?: [];
        $values   = [];

        foreach ( $data as $key => $row ) {
            $label = $row['label'] ?? $key;
            $values[$label] = $row['value'] ?? '';
        }

        $row = [
            '#' . $submission_id,
            $form_id ? get_the_title($form_id) : '',
            get_post_meta($submission_id, '_pngcje_submitted_at', true),
            get_post_meta($submission_id, '_pngcje_page_url', true),
            get_post_meta($submission_id, '_pngcje_submitter_ip', true),
        ];

        foreach ( $field_headers as $header ) {
            $row[] = $values[$header] ?? '';
        }

        $rows[] = $row;
    }

    return $rows;
}

function pngcje_output_csv_download( $filename, $rows ) {
    nocache_headers();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . sanitize_file_name($filename) . '"');

    $out = fopen('php://output', 'w');
    fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
    foreach ( $rows as $row ) {
        fputcsv($out, $row);
    }
    fclose($out);
    exit;
}

add_action('admin_post_pngcje_export_submissions', function() {
    if ( ! current_user_can('edit_posts') ) {
        wp_die( esc_html__('You do not have permission to export submissions.','pngcje') );
    }
    check_admin_referer('pngcje_export_submissions');

    $form_id = isset($_GET['form_id']) ? absint($_GET['form_id']) : 0;
    $args = [
        'post_type'      => 'pngcje_submission',
        'post_status'    => ['publish','private'],
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];
    if ( $form_id ) {
        $args['meta_query'] = [
            [
                'key'   => '_pngcje_form_id',
                'value' => $form_id,
            ],
        ];
    }

    $ids = get_posts($args);
    $suffix = $form_id ? '-' . sanitize_title(get_the_title($form_id)) : '-all';
    pngcje_output_csv_download('pngcje-submissions' . $suffix . '-' . date('Y-m-d') . '.csv', pngcje_submissions_csv_rows($ids));
});

add_action('admin_post_pngcje_export_submission', function() {
    $submission_id = isset($_GET['submission_id']) ? absint($_GET['submission_id']) : 0;
    if ( ! $submission_id || get_post_type($submission_id) !== 'pngcje_submission' ) {
        wp_die( esc_html__('Submission not found.','pngcje') );
    }
    if ( ! current_user_can('edit_post', $submission_id) ) {
        wp_die( esc_html__('You do not have permission to export this submission.','pngcje') );
    }
    check_admin_referer('pngcje_export_submission_' . $submission_id);

    pngcje_output_csv_download('pngcje-submission-' . $submission_id . '.csv', pngcje_submissions_csv_rows([$submission_id]));
});

// ============================================================
// ENQUEUE FORM ASSETS (frontend)
// ============================================================
function pngcje_forms_enqueue() {
    if ( is_admin() ) return;
    wp_enqueue_script('pngcje-forms', PNGCJE_URI . '/assets/js/forms.js', ['jquery'], PNGCJE_VERSION, true);
    wp_localize_script('pngcje-forms', 'pngcjeFormsData', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('pngcje_forms_nonce'),
        'strings' => [
            'submitting'  => __('Submitting…','pngcje'),
            'error'       => __('An error occurred. Please try again.','pngcje'),
        ],
    ]);
}
add_action('wp_enqueue_scripts', 'pngcje_forms_enqueue');

// ============================================================
// BUILDER SCRIPTS (admin)
// ============================================================
function pngcje_forms_builder_scripts() {
   ?>
    <script>
    jQuery(function($){
        var idx = <?php echo (int)( count( get_post_meta(get_the_ID(),'_pngcje_form_fields',true) ?: []) ); ?>;
        var types = <?php echo wp_json_encode(pngcje_forms_field_types()); ?>;

        // Add field
        $(document).on('click','.pngcje-add-field',function(){
            var type = $(this).data('type');
            var tpl  = $('#pngcje-field-tpl').html();
            tpl = tpl.replace(/__IDX__/g, idx).replace(/__TYPE__/g, type);
            var $tpl = $(tpl);
            // Update type label
            var info = types[type] || {label:type, icon:'📝'};
            $tpl.find('.pngcje-field-type-label').text(info.label);
            $tpl.find('.pf-type').val(type);
            // Show options row only for relevant types
            var hasOpts = ['select','radio','checkbox'].indexOf(type) >= 0;
            if (!hasOpts) $tpl.find('textarea[name*="[options]"]').closest('div').hide();
            var hasPlaceholder = ['text','email','tel','textarea','number','date'].indexOf(type) >= 0;
            if (!hasPlaceholder) $tpl.find('input[name*="[placeholder]"]').closest('div').hide();
            var isDisplay = ['h1_heading','heading','paragraph','divider','linebreak'].indexOf(type) >= 0;
            var isHeading = ['h1_heading','heading'].indexOf(type) >= 0;
            var isParagraph = type === 'paragraph';
            $tpl.find('.pngcje-field-name-control, .pngcje-required-control, .pngcje-default-control').toggle(!isDisplay);
            $tpl.find('.pngcje-heading-align-control').toggle(isHeading);
            $tpl.find('.pngcje-heading-subheading-control').toggle(isHeading);
            $tpl.find('.pngcje-paragraph-content-control').toggle(isParagraph);
            // Show body immediately for new fields
            $tpl.find('.pngcje-field-body').show();
            $tpl.find('.pngcje-toggle-field').text('▲ Collapse');
            $('#pngcje-fb-empty').hide();
            $('#pngcje-field-list').append($tpl);
            idx++;
            pngcje_reindex();
        });

        // Toggle field body
        $(document).on('click','.pngcje-toggle-field',function(){
            var $body = $(this).closest('.pngcje-field-row').find('.pngcje-field-body');
            var open  = $body.is(':visible');
            $body.slideToggle(150);
            $(this).text(open ? '▼ Edit' : '▲ Collapse');
        });

        // Remove field
        $(document).on('click','.pngcje-remove-field',function(){
            if (!confirm('<?php esc_js(esc_html__('Remove this field?','pngcje')); ?>')) return;
            $(this).closest('.pngcje-field-row').remove();
            pngcje_reindex();
            if ($('.pngcje-field-row').length === 0) $('#pngcje-fb-empty').show();
        });

        // Update label preview
        $(document).on('input','.pf-label',function(){
            var lbl = $(this).val();
            $(this).closest('.pngcje-field-row').find('.pngcje-field-label-preview').text(lbl ? '— '+lbl : '');
            // Auto-populate name if empty
            var $name = $(this).closest('.pngcje-field-body').find('.pf-name');
            if (!$name.val()) {
                $name.val(lbl.toLowerCase().replace(/[^a-z0-9]+/g,'_').replace(/^_|_$/g,''));
            }
        });

        $(document).on('input change', '.pngcje-color-picker', function(){
            $(this).siblings('.pngcje-color-text').val($(this).val());
        });
        $(document).on('input change', '.pngcje-color-text', function(){
            var value = $(this).val();
            if (/^#[0-9a-fA-F]{6}$/.test(value)) {
                $(this).siblings('.pngcje-color-picker').val(value);
            }
        });

        // Reindex fields
        function pngcje_reindex() {
            $('.pngcje-field-row').each(function(i){
                $(this).attr('data-idx', i);
                $(this).find('[name]').each(function(){
                    $(this).attr('name', $(this).attr('name').replace(/\[\d+\]/,'['+i+']'));
                });
            });
        }

        // Drag-to-reorder (native HTML5 draggable)
        var dragSrc = null;
        $(document).on('dragstart','.pngcje-field-drag-handle',function(e){
            dragSrc = $(this).closest('.pngcje-field-row')[0];
            e.originalEvent.dataTransfer.effectAllowed = 'move';
            e.originalEvent.dataTransfer.setData('text/plain', '');
        });
        $(document).on('dragover','.pngcje-field-row',function(e){
            e.preventDefault();
            e.originalEvent.dataTransfer.dropEffect = 'move';
            $(this).css('border-color','#D4960A');
        });
        $(document).on('dragleave','.pngcje-field-row',function(){
            $(this).css('border-color','#ddd');
        });
        $(document).on('drop','.pngcje-field-row',function(e){
            e.preventDefault();
            $(this).css('border-color','#ddd');
            if (dragSrc !== this) {
                var $list = $('#pngcje-field-list');
                var $src  = $(dragSrc);
                var $dest = $(this);
                if ($src.index() < $dest.index()) {
                    $dest.after($src);
                } else {
                    $dest.before($src);
                }
                pngcje_reindex();
            }
        });
    });
    </script>
    <?php
}
