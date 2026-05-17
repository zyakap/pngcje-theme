<?php
/**
 * inc/popups/popups-core.php
 * PNGCJE Custom Popup Builder
 *
 * - CPT: pngcje_popup
 * - Trigger types: page_load, exit_intent, scroll_depth, on_click
 * - Display conditions: all pages, front page, specific page/post, post type
 * - Frequency: once per session, once per day, every visit, once ever
 * - Fully CSS-driven, zero external dependencies
 * - Shortcode: [pngcje_popup id="1"] for click-triggered inline
 */

defined( 'ABSPATH' ) || exit;

// ============================================================
// REGISTER CPT
// ============================================================
function pngcje_popups_register_cpt() {
    register_post_type( 'pngcje_popup', [
        'labels' => [
            'name'          => __( 'Popups',        'pngcje' ),
            'singular_name' => __( 'Popup',         'pngcje' ),
            'add_new_item'  => __( 'Add New Popup', 'pngcje' ),
            'edit_item'     => __( 'Edit Popup',    'pngcje' ),
            'all_items'     => __( 'All Popups',    'pngcje' ),
            'not_found'     => __( 'No popups',     'pngcje' ),
        ],
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => 'pngcje-popups',
        'show_in_rest'        => false,
        'supports'            => [ 'title', 'editor', 'thumbnail' ],
        'menu_icon'           => 'dashicons-megaphone',
        'exclude_from_search' => true,
    ] );
}
add_action( 'init', 'pngcje_popups_register_cpt' );

// ============================================================
// ADMIN MENU
// ============================================================
function pngcje_popups_admin_menu() {
    add_menu_page(
        __( 'PNGCJE Popups', 'pngcje' ),
        __( 'Popups', 'pngcje' ),
        'manage_options',
        'pngcje-popups',
        'pngcje_popups_dashboard',
        'dashicons-megaphone',
        26
    );
    add_submenu_page( 'pngcje-popups', __('All Popups','pngcje'), __('All Popups','pngcje'), 'manage_options', 'edit.php?post_type=pngcje_popup' );
    add_submenu_page( 'pngcje-popups', __('Add New','pngcje'),    __('Add New','pngcje'),    'manage_options', 'post-new.php?post_type=pngcje_popup' );
}
add_action( 'admin_menu', 'pngcje_popups_admin_menu' );

function pngcje_popups_dashboard() {
    $count = wp_count_posts('pngcje_popup')->publish ?? 0;
   ?>
    <div class="wrap">
        <h1>🔔 <?php esc_html_e('PNGCJE Popups','pngcje'); ?></h1>
        <p style="color:#555;font-size:.9rem;"><?php esc_html_e('Create and manage announcement popups, notices and modal dialogs.','pngcje'); ?></p>
        <div style="background:#fff;border:1px solid #ddd;border-top:4px solid #1A5C2A;border-radius:6px;padding:1.5rem;margin-top:1.5rem;max-width:400px;">
            <div style="font-size:2.5rem;font-weight:900;color:#1A5C2A;"><?php echo esc_html($count); ?></div>
            <div style="font-size:.8rem;color:#888;text-transform:uppercase;letter-spacing:.08em;"><?php esc_html_e('Active Popups','pngcje'); ?></div>
            <a href="<?php echo esc_url(admin_url('edit.php?post_type=pngcje_popup')); ?>" class="button button-primary" style="margin-top:1rem;"><?php esc_html_e('Manage Popups','pngcje'); ?></a>
            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=pngcje_popup')); ?>" class="button" style="margin-top:1rem;margin-left:.5rem;"><?php esc_html_e('+ Add New','pngcje'); ?></a>
        </div>
        <div style="background:#fff8e1;border:1px solid #ffe082;border-radius:6px;padding:1rem 1.25rem;margin-top:1.5rem;max-width:600px;font-size:.85rem;color:#555;">
            <strong>💡 <?php esc_html_e('Tip:','pngcje'); ?></strong>
            <?php esc_html_e('Use the Announcement Bar in Theme Customizer for simple top-bar notices. Use Popups for modal announcements, event promotions, or position vacancy alerts.','pngcje'); ?>
        </div>
    </div>
    <?php
}

// ============================================================
// META BOXES
// ============================================================
function pngcje_popups_meta_boxes() {
    add_meta_box( 'pngcje_popup_settings', __('⚙️ Popup Settings','pngcje'), 'pngcje_popup_settings_cb', 'pngcje_popup', 'side', 'high' );
    add_meta_box( 'pngcje_popup_design',   __('🎨 Design & Style', 'pngcje'), 'pngcje_popup_design_cb',   'pngcje_popup', 'side', 'default' );
    add_meta_box( 'pngcje_popup_preview',  __('👁️ Preview',        'pngcje'), 'pngcje_popup_preview_cb',  'pngcje_popup', 'normal', 'default' );
}
add_action( 'add_meta_boxes', 'pngcje_popups_meta_boxes' );

function pngcje_popup_settings_cb( $post ) {
    wp_nonce_field('pngcje_popup_save','pngcje_popup_nonce');
    $s = wp_parse_args( get_post_meta($post->ID,'_pngcje_popup_settings',true) ?: [], [
        'trigger'         => 'page_load',
        'delay'           => 2000,
        'scroll_depth'    => 50,
        'click_selector'  => '',
        'frequency'       => 'session',
        'display_on'      => 'all',
        'specific_page'   => '',
        'post_type'       => '',
        'enabled'         => '1',
        'show_close'      => '1',
        'close_on_overlay'=> '1',
        'cookie_days'     => 1,
    ]);
   ?>
    <table class="form-table" style="margin:0;">
        <tr>
            <td colspan="2"><strong style="font-size:.8rem;text-transform:uppercase;letter-spacing:.08em;color:#555;"><?php esc_html_e('Status','pngcje'); ?></strong></td>
        </tr>
        <tr>
            <td colspan="2">
                <label style="display:flex;align-items:center;gap:.5rem;font-size:.875rem;cursor:pointer;">
                    <input type="checkbox" name="pngcje_ps[enabled]" value="1" <?php checked($s['enabled'],'1'); ?> style="width:16px;height:16px;">
                    <strong><?php esc_html_e('Popup is Active','pngcje'); ?></strong>
                </label>
            </td>
        </tr>

        <tr><td colspan="2" style="padding-top:1rem;"><strong style="font-size:.8rem;text-transform:uppercase;letter-spacing:.08em;color:#555;"><?php esc_html_e('Trigger','pngcje'); ?></strong></td></tr>
        <tr>
            <td colspan="2">
                <select name="pngcje_ps[trigger]" id="pp_trigger" style="font-size:.85rem;width:100%;">
                    <option value="page_load"    <?php selected($s['trigger'],'page_load'); ?>><?php esc_html_e('Page Load (delay)','pngcje'); ?></option>
                    <option value="exit_intent"  <?php selected($s['trigger'],'exit_intent'); ?>><?php esc_html_e('Exit Intent','pngcje'); ?></option>
                    <option value="scroll_depth" <?php selected($s['trigger'],'scroll_depth'); ?>><?php esc_html_e('Scroll Depth','pngcje'); ?></option>
                    <option value="on_click"     <?php selected($s['trigger'],'on_click'); ?>><?php esc_html_e('On Click (selector)','pngcje'); ?></option>
                    <option value="manual"       <?php selected($s['trigger'],'manual'); ?>><?php esc_html_e('Manual (shortcode/button)','pngcje'); ?></option>
                </select>
            </td>
        </tr>
        <tr class="pp-opt pp-opt-delay" <?php echo !in_array($s['trigger'],['page_load',''])?'style="display:none;"':''; ?>>
            <td colspan="2">
                <label style="font-size:.78rem;font-weight:600;"><?php esc_html_e('Delay (ms)','pngcje'); ?></label>
                <input type="number" name="pngcje_ps[delay]" value="<?php echo esc_attr($s['delay']); ?>" min="0" max="30000" step="500" style="width:100%;font-size:.85rem;">
                <p style="font-size:.7rem;color:#888;margin:.2rem 0 0;"><?php esc_html_e('Milliseconds after page load. 2000 = 2 seconds.','pngcje'); ?></p>
            </td>
        </tr>
        <tr class="pp-opt pp-opt-scroll" style="display:none;">
            <td colspan="2">
                <label style="font-size:.78rem;font-weight:600;"><?php esc_html_e('Scroll Depth (%)','pngcje'); ?></label>
                <input type="number" name="pngcje_ps[scroll_depth]" value="<?php echo esc_attr($s['scroll_depth']); ?>" min="10" max="100" step="5" style="width:100%;font-size:.85rem;">
            </td>
        </tr>
        <tr class="pp-opt pp-opt-click" style="display:none;">
            <td colspan="2">
                <label style="font-size:.78rem;font-weight:600;"><?php esc_html_e('CSS Selector','pngcje'); ?></label>
                <input type="text" name="pngcje_ps[click_selector]" value="<?php echo esc_attr($s['click_selector']); ?>" placeholder=".open-popup or #my-btn" style="width:100%;font-size:.85rem;">
            </td>
        </tr>

        <tr><td colspan="2" style="padding-top:1rem;"><strong style="font-size:.8rem;text-transform:uppercase;letter-spacing:.08em;color:#555;"><?php esc_html_e('Display Conditions','pngcje'); ?></strong></td></tr>
        <tr>
            <td colspan="2">
                <select name="pngcje_ps[display_on]" id="pp_display_on" style="font-size:.85rem;width:100%;">
                    <option value="all"           <?php selected($s['display_on'],'all'); ?>><?php esc_html_e('All Pages','pngcje'); ?></option>
                    <option value="front_page"    <?php selected($s['display_on'],'front_page'); ?>><?php esc_html_e('Front Page Only','pngcje'); ?></option>
                    <option value="specific_page" <?php selected($s['display_on'],'specific_page'); ?>><?php esc_html_e('Specific Page/Post (by ID)','pngcje'); ?></option>
                    <option value="post_type"     <?php selected($s['display_on'],'post_type'); ?>><?php esc_html_e('Specific Post Type','pngcje'); ?></option>
                    <option value="events"        <?php selected($s['display_on'],'events'); ?>><?php esc_html_e('Events Pages','pngcje'); ?></option>
                </select>
            </td>
        </tr>
        <tr class="pp-opt pp-opt-specific" <?php echo $s['display_on']!=='specific_page'?'style="display:none;"':''; ?>>
            <td colspan="2">
                <label style="font-size:.78rem;font-weight:600;"><?php esc_html_e('Page/Post ID(s)','pngcje'); ?></label>
                <input type="text" name="pngcje_ps[specific_page]" value="<?php echo esc_attr($s['specific_page']); ?>" placeholder="12, 45, 78" style="width:100%;font-size:.85rem;">
                <p style="font-size:.7rem;color:#888;margin:.2rem 0 0;"><?php esc_html_e('Comma-separated post IDs.','pngcje'); ?></p>
            </td>
        </tr>
        <tr class="pp-opt pp-opt-posttype" <?php echo $s['display_on']!=='post_type'?'style="display:none;"':''; ?>>
            <td colspan="2">
                <label style="font-size:.78rem;font-weight:600;"><?php esc_html_e('Post Type Slug','pngcje'); ?></label>
                <input type="text" name="pngcje_ps[post_type]" value="<?php echo esc_attr($s['post_type']); ?>" placeholder="post, pngcje_resource" style="width:100%;font-size:.85rem;">
            </td>
        </tr>

        <tr><td colspan="2" style="padding-top:1rem;"><strong style="font-size:.8rem;text-transform:uppercase;letter-spacing:.08em;color:#555;"><?php esc_html_e('Frequency','pngcje'); ?></strong></td></tr>
        <tr>
            <td colspan="2">
                <select name="pngcje_ps[frequency]" style="font-size:.85rem;width:100%;">
                    <option value="session"  <?php selected($s['frequency'],'session'); ?>><?php esc_html_e('Once per session','pngcje'); ?></option>
                    <option value="daily"    <?php selected($s['frequency'],'daily'); ?>><?php esc_html_e('Once per day','pngcje'); ?></option>
                    <option value="weekly"   <?php selected($s['frequency'],'weekly'); ?>><?php esc_html_e('Once per week','pngcje'); ?></option>
                    <option value="always"   <?php selected($s['frequency'],'always'); ?>><?php esc_html_e('Every page load','pngcje'); ?></option>
                    <option value="once"     <?php selected($s['frequency'],'once'); ?>><?php esc_html_e('Once ever (cookie)','pngcje'); ?></option>
                </select>
            </td>
        </tr>

        <tr><td colspan="2" style="padding-top:1rem;"><strong style="font-size:.8rem;text-transform:uppercase;letter-spacing:.08em;color:#555;"><?php esc_html_e('Close Behaviour','pngcje'); ?></strong></td></tr>
        <tr>
            <td colspan="2" style="display:flex;flex-direction:column;gap:.4rem;">
                <label style="display:flex;align-items:center;gap:.5rem;font-size:.85rem;cursor:pointer;">
                    <input type="checkbox" name="pngcje_ps[show_close]" value="1" <?php checked($s['show_close'],'1'); ?>> <?php esc_html_e('Show close (×) button','pngcje'); ?>
                </label>
                <label style="display:flex;align-items:center;gap:.5rem;font-size:.85rem;cursor:pointer;">
                    <input type="checkbox" name="pngcje_ps[close_on_overlay]" value="1" <?php checked($s['close_on_overlay'],'1'); ?>> <?php esc_html_e('Close on overlay click','pngcje'); ?>
                </label>
            </td>
        </tr>
    </table>

    <script>
    jQuery(function($){
        var triggerMap = {
            'page_load':   '.pp-opt-delay',
            'exit_intent': '',
            'scroll_depth':'.pp-opt-scroll',
            'on_click':    '.pp-opt-click',
            'manual':      ''
        };
        function updateTriggerOpts(){
            $('.pp-opt-delay,.pp-opt-scroll,.pp-opt-click').hide();
            var sel = triggerMap[$('#pp_trigger').val()];
            if (sel) $(sel).show();
        }
        $('#pp_trigger').on('change', updateTriggerOpts).trigger('change');

        var displayMap = {
            'all':'','front_page':'','specific_page':'.pp-opt-specific',
            'post_type':'.pp-opt-posttype','events':''
        };
        function updateDisplayOpts(){
            $('.pp-opt-specific,.pp-opt-posttype').hide();
            var sel = displayMap[$('#pp_display_on').val()];
            if (sel) $(sel).show();
        }
        $('#pp_display_on').on('change', updateDisplayOpts).trigger('change');
    });
    </script>
    <?php
}

function pngcje_popup_design_cb( $post ) {
    $s = wp_parse_args( get_post_meta($post->ID,'_pngcje_popup_design',true) ?: [], [
        'width'        => '560px',
        'height'       => 'auto',
        'theme'        => 'default',
        'position'     => 'center',
        'overlay'      => '1',
        'overlay_blur' => '1',
        'animate'      => 'fade-scale',
        'bg_color'     => '#ffffff',
        'accent_color' => '#1A5C2A',
    ]);
   ?>
    <table class="form-table" style="margin:0;">
        <tr>
            <td><label style="font-size:.78rem;font-weight:600;"><?php esc_html_e('Width','pngcje'); ?></label></td>
            <td><input type="text" name="pngcje_pd[width]" value="<?php echo esc_attr($s['width']); ?>" style="width:100%;font-size:.85rem;" placeholder="560px or 80vw"></td>
        </tr>
        <tr>
            <td><label style="font-size:.78rem;font-weight:600;"><?php esc_html_e('Height','pngcje'); ?></label></td>
            <td><input type="text" name="pngcje_pd[height]" value="<?php echo esc_attr($s['height']); ?>" style="width:100%;font-size:.85rem;" placeholder="auto, 400px or 60vh"></td>
        </tr>
        <tr>
            <td><label style="font-size:.78rem;font-weight:600;"><?php esc_html_e('Position','pngcje'); ?></label></td>
            <td>
                <select name="pngcje_pd[position]" style="font-size:.85rem;width:100%;">
                    <option value="center" <?php selected($s['position'],'center'); ?>><?php esc_html_e('Centre Screen','pngcje'); ?></option>
                    <option value="top"    <?php selected($s['position'],'top'); ?>><?php esc_html_e('Top (banner)','pngcje'); ?></option>
                    <option value="bottom" <?php selected($s['position'],'bottom'); ?>><?php esc_html_e('Bottom (banner)','pngcje'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td><label style="font-size:.78rem;font-weight:600;"><?php esc_html_e('Theme','pngcje'); ?></label></td>
            <td>
                <select name="pngcje_pd[theme]" style="font-size:.85rem;width:100%;">
                    <option value="default"     <?php selected($s['theme'],'default'); ?>><?php esc_html_e('Default (white)','pngcje'); ?></option>
                    <option value="green"       <?php selected($s['theme'],'green'); ?>><?php esc_html_e('PNGCJE Green','pngcje'); ?></option>
                    <option value="gold"        <?php selected($s['theme'],'gold'); ?>><?php esc_html_e('Gold Accent','pngcje'); ?></option>
                    <option value="dark"        <?php selected($s['theme'],'dark'); ?>><?php esc_html_e('Dark','pngcje'); ?></option>
                    <option value="announcement"<?php selected($s['theme'],'announcement'); ?>><?php esc_html_e('Announcement (red/urgent)','pngcje'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td><label style="font-size:.78rem;font-weight:600;"><?php esc_html_e('Animation','pngcje'); ?></label></td>
            <td>
                <select name="pngcje_pd[animate]" style="font-size:.85rem;width:100%;">
                    <option value="fade-scale"    <?php selected($s['animate'],'fade-scale'); ?>><?php esc_html_e('Fade + Scale (default)','pngcje'); ?></option>
                    <option value="fade"          <?php selected($s['animate'],'fade'); ?>><?php esc_html_e('Fade','pngcje'); ?></option>
                    <option value="slide-up"      <?php selected($s['animate'],'slide-up'); ?>><?php esc_html_e('Slide Up','pngcje'); ?></option>
                    <option value="slide-down"    <?php selected($s['animate'],'slide-down'); ?>><?php esc_html_e('Slide Down','pngcje'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <label style="display:flex;align-items:center;gap:.5rem;font-size:.85rem;cursor:pointer;">
                    <input type="checkbox" name="pngcje_pd[overlay]" value="1" <?php checked($s['overlay'],'1'); ?>> <?php esc_html_e('Show overlay background','pngcje'); ?>
                </label>
                <label style="display:flex;align-items:center;gap:.5rem;font-size:.85rem;cursor:pointer;margin-top:.3rem;">
                    <input type="checkbox" name="pngcje_pd[overlay_blur]" value="1" <?php checked($s['overlay_blur'],'1'); ?>> <?php esc_html_e('Blur page behind popup','pngcje'); ?>
                </label>
            </td>
        </tr>
    </table>
    <?php
}

function pngcje_popup_preview_cb( $post ) {
   ?>
    <p style="font-size:.85rem;color:#555;margin-bottom:1rem;">
        <?php esc_html_e('The popup content is edited in the main editor above. Use the full WordPress editor to add text, images, buttons, shortcodes and embedded forms.','pngcje'); ?>
    </p>
    <div style="background:#f9f9f9;border:1px solid #ddd;border-radius:6px;padding:1.25rem;max-width:100%;overflow-x:auto;">
        <p style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#888;margin:0 0 .75rem;">
            <?php esc_html_e('Content Preview','pngcje'); ?>
        </p>
        <div id="pngcje-popup-preview" style="font-size:.875rem;color:#333;line-height:1.7;overflow-wrap:break-word;word-wrap:break-word;">
            <?php echo wp_kses_post( apply_filters('the_content', $post->post_content) ); ?>
        </div>
    </div>
    <style>
        #pngcje-popup-preview img,
        #pngcje-popup-preview iframe,
        #pngcje-popup-preview table,
        #pngcje-popup-preview video,
        #pngcje-popup-preview embed {
            max-width: 100%;
            height: auto;
        }
    </style>
    <div style="margin-top:1rem;background:#fff8e1;border:1px solid #ffe082;border-radius:6px;padding:.75rem 1rem;font-size:.8rem;color:#666;">
        💡 <?php esc_html_e('You can embed a form inside a popup using:','pngcje'); ?>
        <code style="background:#fff;padding:.2rem .4rem;border-radius:3px;">[pngcje_form id="1"]</code>
    </div>
    <?php
}

// ============================================================
// SAVE META
// ============================================================
function pngcje_popups_save_meta( $post_id ) {
    if ( ! isset($_POST['pngcje_popup_nonce'])
        || ! wp_verify_nonce($_POST['pngcje_popup_nonce'],'pngcje_popup_save') ) return;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can('edit_post',$post_id) ) return;

    if ( isset($_POST['pngcje_ps']) ) {
        $settings = array_map('sanitize_text_field', wp_unslash((array)$_POST['pngcje_ps']));
        update_post_meta($post_id,'_pngcje_popup_settings',$settings);
    }
    if ( isset($_POST['pngcje_pd']) ) {
        $design = array_map('sanitize_text_field', wp_unslash((array)$_POST['pngcje_pd']));
        update_post_meta($post_id,'_pngcje_popup_design',$design);
    }
}
add_action('save_post_pngcje_popup','pngcje_popups_save_meta');

// ============================================================
// FRONTEND: OUTPUT ACTIVE POPUPS
// ============================================================
function pngcje_popups_output() {
    $popups = get_posts([
        'post_type'      => 'pngcje_popup',
        'post_status'    => 'publish',
        'posts_per_page' => 20,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ]);
    if ( empty($popups) ) return;

    $eligible = [];

    foreach ($popups as $popup) {
        $settings = wp_parse_args( get_post_meta($popup->ID,'_pngcje_popup_settings',true) ?: [], [
            'enabled'       => '1',
            'display_on'    => 'all',
            'specific_page' => '',
            'post_type'     => '',
            'trigger'       => 'page_load',
        ]);

        if ( empty($settings['enabled']) || $settings['enabled'] != '1' ) continue;

        // Check display conditions
        $show = false;
        switch ($settings['display_on']) {
            case 'all':         $show = true; break;
            case 'front_page':  $show = is_front_page(); break;
            case 'events':      $show = ( is_singular('tribe_events') || is_post_type_archive('tribe_events') ); break;
            case 'specific_page':
                $ids = array_filter(array_map('intval', explode(',', $settings['specific_page'])));
                $show = in_array(get_the_ID(), $ids);
                break;
            case 'post_type':
                $pts = array_filter(array_map('trim', explode(',', $settings['post_type'])));
                $show = is_singular($pts) || is_post_type_archive($pts);
                break;
        }
        if (!$show) continue;

        $eligible[] = $popup;
    }

    if ( empty($eligible) ) return;

    // Enqueue JS
    wp_enqueue_script('pngcje-popups', PNGCJE_URI . '/assets/js/popups.js', ['jquery'], PNGCJE_VERSION, true);

    $popup_data = [];
    foreach ($eligible as $popup) {
        $s = wp_parse_args( get_post_meta($popup->ID,'_pngcje_popup_settings',true) ?: [], [
            'trigger'         => 'page_load',
            'delay'           => 2000,
            'scroll_depth'    => 50,
            'click_selector'  => '',
            'frequency'       => 'session',
            'show_close'      => '1',
            'close_on_overlay'=> '1',
        ]);
        $d = wp_parse_args( get_post_meta($popup->ID,'_pngcje_popup_design',true) ?: [], [
            'width'        => '560px',
            'height'       => 'auto',
            'theme'        => 'default',
            'position'     => 'center',
            'overlay'      => '1',
            'overlay_blur' => '1',
            'animate'      => 'fade-scale',
        ]);
        $popup_data[] = [
            'id'             => $popup->ID,
            'trigger'        => $s['trigger'],
            'delay'          => (int)$s['delay'],
            'scrollDepth'    => (int)$s['scroll_depth'],
            'clickSelector'  => $s['click_selector'],
            'frequency'      => $s['frequency'],
            'showClose'      => !empty($s['show_close']),
            'closeOnOverlay' => !empty($s['close_on_overlay']),
            'width'          => $d['width'],
            'height'         => $d['height'],
            'theme'          => $d['theme'],
            'position'       => $d['position'],
            'overlay'        => !empty($d['overlay']),
            'overlayBlur'    => !empty($d['overlay_blur']),
            'animate'        => $d['animate'],
        ];

        // Render HTML
        pngcje_popup_render_html($popup, $s, $d);
    }

    wp_localize_script('pngcje-popups','pngcjePopups',$popup_data);
}
add_action('wp_footer','pngcje_popups_output');

// Render popup HTML shell into footer
function pngcje_popup_render_html( $popup, $s, $d ) {
    $theme_classes = [
        'default'      => '',
        'green'        => 'pngcje-popup--green',
        'gold'         => 'pngcje-popup--gold',
        'dark'         => 'pngcje-popup--dark',
        'announcement' => 'pngcje-popup--announcement',
    ];
    $animate_class = 'pngcje-popup--anim-' . sanitize_html_class($d['animate']);
    $pos_class     = 'pngcje-popup--pos-' . sanitize_html_class($d['position']);
    $theme_class   = $theme_classes[$d['theme']] ?? '';
   ?>
    <!-- Popup: <?php echo esc_html(get_the_title($popup->ID)); ?> -->
    <?php if (!empty($d['overlay'])) : ?>
    <div class="pngcje-popup-overlay <?php echo !empty($d['overlay_blur']) ? 'pngcje-popup-overlay--blur' : ''; ?>"
        id="pngcje-overlay-<?php echo esc_attr($popup->ID); ?>"
        data-popup="<?php echo esc_attr($popup->ID); ?>"
        aria-hidden="true"
    ></div>
    <?php endif; ?>

    <div
        class="pngcje-popup <?php echo esc_attr("$theme_class $animate_class $pos_class"); ?>"
        id="pngcje-popup-<?php echo esc_attr($popup->ID); ?>"
        role="dialog"
        aria-modal="true"
        aria-label="<?php echo esc_attr(get_the_title($popup->ID)); ?>"
        aria-hidden="true"
        style="--popup-width:<?php echo esc_attr($d['width']); ?>;<?php echo (!empty($d['height']) && strtolower($d['height']) !== 'auto') ? '--popup-height:' . esc_attr($d['height']) . ';' : ''; ?>"
        data-popup-id="<?php echo esc_attr($popup->ID); ?>"
    >
        <?php if (!empty($s['show_close'])) : ?>
        <button
            class="pngcje-popup__close"
            data-popup="<?php echo esc_attr($popup->ID); ?>"
            aria-label="<?php esc_attr_e('Close','pngcje'); ?>"
        >&times;</button>
        <?php endif; ?>

        <div class="pngcje-popup__body">
            <?php if (has_post_thumbnail($popup->ID)) : ?>
            <div class="pngcje-popup__image">
                <?php echo get_the_post_thumbnail($popup->ID,'full',['alt'=>'']); ?>
            </div>
            <?php endif; ?>
            <div class="pngcje-popup__content">
                <h2 class="pngcje-popup__title"><?php echo esc_html(get_the_title($popup->ID)); ?></h2>
                <div class="pngcje-popup__text">
                    <?php echo wp_kses_post(apply_filters('the_content', get_post_field('post_content',$popup->ID))); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

// ============================================================
// SHORTCODE — [pngcje_popup_trigger id="1" label="Open"]
// Opens a specific popup on click
// ============================================================
function pngcje_popup_trigger_shortcode($atts) {
    $atts = shortcode_atts(['id'=>0,'label'=>__('Learn More','pngcje'),'class'=>'btn btn-primary'], $atts);
    if (!$atts['id']) return '';
    return '<button type="button" class="' . esc_attr($atts['class']) . '" data-open-popup="' . esc_attr($atts['id']) . '">' . esc_html($atts['label']) . '</button>';
}
add_shortcode('pngcje_popup_trigger','pngcje_popup_trigger_shortcode');

// ============================================================
// ADMIN COLUMNS
// ============================================================
add_filter('manage_pngcje_popup_posts_columns', function($cols){
    return array_merge($cols, [
        'trigger'  => __('Trigger','pngcje'),
        'display'  => __('Display On','pngcje'),
        'status'   => __('Status','pngcje'),
    ]);
});
add_action('manage_pngcje_popup_posts_custom_column', function($col,$post_id){
    $s = get_post_meta($post_id,'_pngcje_popup_settings',true) ?: [];
    switch ($col) {
        case 'trigger': echo esc_html($s['trigger'] ?? '—'); break;
        case 'display': echo esc_html($s['display_on'] ?? 'all'); break;
        case 'status':
            $enabled = ($s['enabled'] ?? '1') == '1';
            echo $enabled
                ? '<span style="color:green;font-weight:600;">● Active</span>'
                : '<span style="color:#999;">○ Inactive</span>';
            break;
    }
},10,2);
