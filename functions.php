<?php
/**
 * PNGCJE Theme — functions.php
 * Core theme setup, enqueues, custom post types,
 * and compatibility for Gravity Forms, Popup Maker, The Events Calendar
 */

defined( 'ABSPATH' ) || exit;

// ============================================================
// CONSTANTS
// ============================================================
define( 'PNGCJE_VERSION', '1.0.0' );
define( 'PNGCJE_DIR',     get_template_directory() );
define( 'PNGCJE_URI',     get_template_directory_uri() );

/**
 * Return a theme asset URL only when the packaged file exists.
 */
function pngcje_theme_asset_url( $relative_path ) {
    $relative_path = ltrim( $relative_path, '/' );
    $file          = PNGCJE_DIR . '/' . $relative_path;

    if ( ! file_exists( $file ) ) {
        return '';
    }

    return PNGCJE_URI . '/' . $relative_path;
}

/**
 * Print page hero attributes, using the current page/post featured image as
 * the heading background when one is available.
 */
function pngcje_page_hero_attrs( $post_id = null, $extra_style = '' ) {
    if ( ! $post_id ) {
        if ( is_home() ) {
            $post_id = get_option( 'page_for_posts' );
        } elseif ( is_singular() ) {
            $post_id = get_queried_object_id();
        }
    }

    if ( ! $post_id && is_singular() && in_the_loop() ) {
        $post_id = get_the_ID();
    }

    $classes = [ 'page-hero' ];
    $styles  = [];

    if ( $post_id && has_post_thumbnail( $post_id ) ) {
        $image_url = get_the_post_thumbnail_url( $post_id, 'pngcje-hero' );

        if ( $image_url ) {
            $classes[] = 'page-hero--has-bg';
            $styles[]  = 'background-image:url(' . esc_url_raw( $image_url ) . ')';
        }
    }

    if ( $extra_style ) {
        $styles[] = trim( $extra_style );
    }

    echo 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';

    if ( $styles ) {
        echo ' style="' . esc_attr( implode( ';', $styles ) ) . '"';
    }
}

// ============================================================
// THEME SETUP
// ============================================================
function pngcje_setup() {
    load_theme_textdomain( 'pngcje', PNGCJE_DIR . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'html5', [
        'search-form', 'comment-form', 'comment-list',
        'gallery', 'caption', 'style', 'script',
    ] );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'align-wide' );

    // Custom logo
    add_theme_support( 'custom-logo', [
        'height'      => 120,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => [ 'site-title', 'site-description' ],
    ] );

    // Post formats
    add_theme_support( 'post-formats', [ 'aside', 'gallery', 'video', 'quote', 'link' ] );

    // Thumbnail sizes
    add_image_size( 'pngcje-hero',       1920, 960, true );
    add_image_size( 'pngcje-card',        800, 500, true );
    add_image_size( 'pngcje-card-sm',     400, 250, true );
    add_image_size( 'pngcje-square',      600, 600, true );
    add_image_size( 'pngcje-wide',       1200, 500, true );
    add_image_size( 'pngcje-staff',       400, 500, true );

    // Navigation menus
    register_nav_menus( [
        'primary'    => __( 'Primary Navigation',     'pngcje' ),
        'pacific'    => __( 'Pacific Centre Menu',    'pngcje' ),
        'footer-1'   => __( 'Footer: Quick Links',   'pngcje' ),
        'footer-2'   => __( 'Footer: Our Work',       'pngcje' ),
        'footer-3'   => __( 'Footer: Pacific',        'pngcje' ),
        'topbar'     => __( 'Top Bar Links',          'pngcje' ),
    ] );
}
add_action( 'after_setup_theme', 'pngcje_setup' );

// ============================================================
// ENQUEUE SCRIPTS & STYLES
// ============================================================
function pngcje_enqueue_assets() {
    // Google Fonts — Montserrat
    wp_enqueue_style(
        'pngcje-fonts',
        'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,900;1,400&display=swap',
        [],
        null
    );

    // Main stylesheet
    wp_enqueue_style(
        'pngcje-style',
        PNGCJE_URI . '/style.css',
        [ 'pngcje-fonts' ],
        PNGCJE_VERSION
    );

    // Main JS bundle
    wp_enqueue_script(
        'pngcje-main',
        PNGCJE_URI . '/assets/js/main.js',
        [ 'jquery' ],
        PNGCJE_VERSION,
        true
    );

    // Pass data to JS
    wp_localize_script( 'pngcje-main', 'pngcjeData', [
        'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
        'nonce'     => wp_create_nonce( 'pngcje_nonce' ),
        'homeUrl'   => home_url(),
        'themeUri'  => PNGCJE_URI,
    ] );

    // Comments
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'pngcje_enqueue_assets' );

// ============================================================
// CUSTOM POST TYPES
// ============================================================
function pngcje_register_post_types() {

    // --- Resources (Bench Books, Handbooks, Case Notes, etc.) ---
    register_post_type( 'pngcje_resource', [
        'labels' => [
            'name'               => __( 'Resources',          'pngcje' ),
            'singular_name'      => __( 'Resource',           'pngcje' ),
            'add_new_item'       => __( 'Add New Resource',   'pngcje' ),
            'edit_item'          => __( 'Edit Resource',      'pngcje' ),
            'search_items'       => __( 'Search Resources',   'pngcje' ),
            'not_found'          => __( 'No resources found', 'pngcje' ),
        ],
        'public'      => true,
        'has_archive' => true,
        'menu_icon'   => 'dashicons-media-document',
        'menu_position' => 5,
        'supports'    => [ 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ],
        'rewrite'     => [ 'slug' => 'resources' ],
        'show_in_rest' => true,
    ] );

    // --- Staff ---
    register_post_type( 'member', [
        'labels' => [
            'name'          => __( 'Staff Members', 'pngcje' ),
            'singular_name' => __( 'Staff Member',  'pngcje' ),
            'add_new_item'  => __( 'Add Staff Member', 'pngcje' ),
        ],
        'public'        => true,
        'has_archive'   => false,
        'menu_icon'     => 'dashicons-businessperson',
        'menu_position' => 6,
        'supports'      => [ 'title', 'editor', 'thumbnail', 'custom-fields', 'page-attributes' ],
        'rewrite'       => [ 'slug' => 'staff' ],
        'show_in_rest'  => true,
    ] );

    // --- Board Members ---
    register_post_type( 'pngcje_board_member', [
        'labels' => [
            'name'               => __( 'Board Members', 'pngcje' ),
            'singular_name'      => __( 'Board Member', 'pngcje' ),
            'add_new_item'       => __( 'Add Board Member', 'pngcje' ),
            'edit_item'          => __( 'Edit Board Member', 'pngcje' ),
            'all_items'          => __( 'All Board Members', 'pngcje' ),
            'search_items'       => __( 'Search Board Members', 'pngcje' ),
            'not_found'          => __( 'No board members found', 'pngcje' ),
            'featured_image'     => __( 'Board Member Photo', 'pngcje' ),
            'set_featured_image' => __( 'Set board member photo', 'pngcje' ),
        ],
        'public'        => true,
        'publicly_queryable' => true,
        'has_archive'   => false,
        'menu_icon'     => 'dashicons-groups',
        'menu_position' => 7,
        'supports'      => [ 'title', 'editor', 'thumbnail', 'custom-fields', 'page-attributes' ],
        'rewrite'       => [ 'slug' => 'board-members', 'with_front' => false ],
        'show_in_rest'  => true,
    ] );

    // --- Program Officers ---
    register_post_type( 'pngcje_program_officer', [
        'labels' => [
            'name'               => __( 'Program Officers', 'pngcje' ),
            'singular_name'      => __( 'Program Officer', 'pngcje' ),
            'add_new_item'       => __( 'Add Program Officer', 'pngcje' ),
            'edit_item'          => __( 'Edit Program Officer', 'pngcje' ),
            'all_items'          => __( 'All Program Officers', 'pngcje' ),
            'search_items'       => __( 'Search Program Officers', 'pngcje' ),
            'not_found'          => __( 'No program officers found', 'pngcje' ),
            'featured_image'     => __( 'Program Officer Photo', 'pngcje' ),
            'set_featured_image' => __( 'Set program officer photo', 'pngcje' ),
        ],
        'public'        => true,
        'publicly_queryable' => true,
        'has_archive'   => false,
        'menu_icon'     => 'dashicons-welcome-learn-more',
        'menu_position' => 8,
        'supports'      => [ 'title', 'editor', 'thumbnail', 'custom-fields', 'page-attributes' ],
        'rewrite'       => [ 'slug' => 'program-officers', 'with_front' => false ],
        'show_in_rest'  => true,
    ] );

    // --- Announcements (feeds Popup Maker) ---
    register_post_type( 'pngcje_announcement', [
        'labels' => [
            'name'          => __( 'Announcements', 'pngcje' ),
            'singular_name' => __( 'Announcement',  'pngcje' ),
            'add_new_item'  => __( 'Add Announcement', 'pngcje' ),
        ],
        'public'        => true,
        'has_archive'   => false,
        'menu_icon'     => 'dashicons-megaphone',
        'menu_position' => 9,
        'supports'      => [ 'title', 'editor', 'custom-fields' ],
        'rewrite'       => [ 'slug' => 'announcements' ],
        'show_in_rest'  => true,
    ] );

    // --- Homepage Hero Slides ---
    register_post_type( 'pngcje_hero_slide', [
        'labels' => [
            'name'               => __( 'Hero Slides', 'pngcje' ),
            'singular_name'      => __( 'Hero Slide', 'pngcje' ),
            'add_new_item'       => __( 'Add New Hero Slide', 'pngcje' ),
            'edit_item'          => __( 'Edit Hero Slide', 'pngcje' ),
            'search_items'       => __( 'Search Hero Slides', 'pngcje' ),
            'not_found'          => __( 'No hero slides found', 'pngcje' ),
            'featured_image'     => __( 'Hero Banner Image', 'pngcje' ),
            'set_featured_image' => __( 'Set hero banner image', 'pngcje' ),
        ],
        'public'        => false,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'menu_icon'     => 'dashicons-images-alt2',
        'menu_position' => 4,
        'supports'      => [ 'title', 'thumbnail', 'page-attributes' ],
        'show_in_rest'  => true,
    ] );

    // --- Pacific Centre Members ---
    register_post_type( 'pngcje_pacific', [
        'labels' => [
            'name'          => __( 'Pacific Members', 'pngcje' ),
            'singular_name' => __( 'Pacific Member',  'pngcje' ),
        ],
        'public'        => true,
        'has_archive'   => false,
        'menu_icon'     => 'dashicons-admin-site-alt3',
        'menu_position' => 9,
        'supports'      => [ 'title', 'editor', 'thumbnail', 'custom-fields', 'page-attributes' ],
        'rewrite'       => [ 'slug' => 'pacific-members' ],
        'show_in_rest'  => true,
    ] );
}
add_action( 'init', 'pngcje_register_post_types' );

// ============================================================
// CUSTOM TAXONOMIES
// ============================================================
function pngcje_register_taxonomies() {

    // Resource Type
    register_taxonomy( 'resource_type', 'pngcje_resource', [
        'labels' => [
            'name'          => __( 'Resource Types',   'pngcje' ),
            'singular_name' => __( 'Resource Type',    'pngcje' ),
            'add_new_item'  => __( 'Add Resource Type','pngcje' ),
        ],
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => [ 'slug' => 'resource-type' ],
        'show_in_rest' => true,
    ] );

    // Staff Department
    register_taxonomy( 'department', 'member', [
        'labels' => [
            'name'          => __( 'Departments', 'pngcje' ),
            'singular_name' => __( 'Department',  'pngcje' ),
        ],
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => [ 'slug' => 'department' ],
        'show_in_rest' => true,
    ] );
}
add_action( 'init', 'pngcje_register_taxonomies' );

function pngcje_resource_type_map() {
    return [
        'case-notes'                   => [ 'label' => __( 'Case Notes', 'pngcje' ), 'aliases' => [ 'case-notes' ] ],
        'bench-books'                  => [ 'label' => __( 'Bench Books', 'pngcje' ), 'aliases' => [ 'bench-books' ] ],
        'judicial-handbook'            => [ 'label' => __( 'Judicial Handbook', 'pngcje' ), 'aliases' => [ 'judicial-handbook', 'handbook' ] ],
        'cpd-lectures'                 => [ 'label' => __( 'CPD Lectures', 'pngcje' ), 'aliases' => [ 'cpd-lectures', 'continuing-professional-development-lectures' ] ],
        'executive-director-speeches'  => [ 'label' => __( 'Executive Director Speeches', 'pngcje' ), 'aliases' => [ 'executive-director-speeches', 'ed-speeches', 'speeches' ] ],
        'annual-reports'               => [ 'label' => __( 'Annual Reports', 'pngcje' ), 'aliases' => [ 'annual-reports' ] ],
        'prospectus'                   => [ 'label' => __( 'Prospectus', 'pngcje' ), 'aliases' => [ 'prospectus' ] ],
        'lecture-series'               => [ 'label' => __( 'Lecture Series', 'pngcje' ), 'aliases' => [ 'lecture-series' ] ],
        'customer-service'             => [ 'label' => __( 'Customer Service', 'pngcje' ), 'aliases' => [ 'customer-service' ] ],
    ];
}

function pngcje_resource_type_aliases( $resource_type ) {
    $resource_type = sanitize_title( $resource_type );
    $map = pngcje_resource_type_map();

    if ( isset( $map[ $resource_type ] ) ) {
        return $map[ $resource_type ]['aliases'];
    }

    foreach ( $map as $canonical => $data ) {
        if ( in_array( $resource_type, $data['aliases'], true ) ) {
            return array_unique( array_merge( [ $canonical ], $data['aliases'] ) );
        }
    }

    return [ $resource_type ];
}

function pngcje_resource_type_query_terms( $resource_type ) {
    $aliases = pngcje_resource_type_aliases( $resource_type );
    $terms   = get_terms( [
        'taxonomy'   => 'resource_type',
        'hide_empty' => false,
    ] );

    if ( is_wp_error( $terms ) || empty( $terms ) ) {
        return $aliases;
    }

    $matches = [];
    foreach ( $terms as $term ) {
        if ( in_array( $term->slug, $aliases, true ) || in_array( sanitize_title( $term->name ), $aliases, true ) ) {
            $matches[] = $term->slug;
        }
    }

    return $matches ?: $aliases;
}

function pngcje_ensure_resource_types() {
    if ( ! taxonomy_exists( 'resource_type' ) ) {
        return;
    }

    foreach ( pngcje_resource_type_map() as $slug => $data ) {
        if ( ! term_exists( $slug, 'resource_type' ) && ! term_exists( $data['label'], 'resource_type' ) ) {
            wp_insert_term( $data['label'], 'resource_type', [ 'slug' => $slug ] );
        }
    }
}
add_action( 'init', 'pngcje_ensure_resource_types', 20 );

/**
 * Canonical map slug for resource_type identifiers (handles aliases).
 */
function pngcje_resource_map_canonical_slug( $canonical_or_alias ) {
    $slug = sanitize_title( $canonical_or_alias );
    foreach ( pngcje_resource_type_map() as $canon_slug => $data ) {
        if ( $slug === $canon_slug || in_array( $slug, $data['aliases'], true ) ) {
            return $canon_slug;
        }
    }
    return $slug;
}

/**
 * Canonical term permalink for a mapped resource_type (checks all registered aliases).
 *
 * @param string $canonical_or_alias e.g. judicial-handbook or handbook.
 * @return string|false Full URL or false if term missing.
 */
function pngcje_get_resource_type_term_permalink( $canonical_or_alias ) {
    if ( ! taxonomy_exists( 'resource_type' ) ) {
        return false;
    }
    $aliases = pngcje_resource_type_aliases( $canonical_or_alias );
    $terms   = get_terms( [
        'taxonomy'   => 'resource_type',
        'slug'       => $aliases,
        'hide_empty' => false,
        'number'     => 20,
    ] );
    if ( is_wp_error( $terms ) || empty( $terms ) ) {
        return false;
    }
    $preferred = pngcje_resource_map_canonical_slug( $canonical_or_alias );
    foreach ( $terms as $term ) {
        if ( $term->slug === $preferred ) {
            $link = get_term_link( $term );
            return is_wp_error( $link ) ? false : $link;
        }
    }
    $link = get_term_link( $terms[0] );
    return is_wp_error( $link ) ? false : $link;
}

/** Legacy Pretty Page paths backed by pngcje_get_resource_type_url() lookups. Key = canonical taxonomy slug where applicable. */
function pngcje_resource_type_legacy_page_paths() {
    return [
        'bench-books'                 => '/bench-books/',
        'judicial-handbook'           => '/handbook/',
        'case-notes'                  => '/papua-new-guinea-supreme-court-national-court-case-notes/',
        'cpd-lectures'                => '/continuing-professional-development-lectures/',
        'executive-director-speeches' => '/executive-director-speeches/',
    ];
}

/**
 * URL for browsing a resource type: taxonomy archive when the term exists, otherwise the legacy WP Page path when known.
 *
 * @param string $canonical_or_alias e.g. judicial-handbook, handbook.
 */
function pngcje_get_resource_type_url( $canonical_or_alias ) {
    $term_link = pngcje_get_resource_type_term_permalink( $canonical_or_alias );
    if ( $term_link ) {
        return $term_link;
    }

    $canonical_or_alias = pngcje_resource_map_canonical_slug( $canonical_or_alias );

    $legacy = pngcje_resource_type_legacy_page_paths();
    if ( isset( $legacy[ $canonical_or_alias ] ) ) {
        return home_url( $legacy[ $canonical_or_alias ] );
    }

    $archive = get_post_type_archive_link( 'pngcje_resource' );
    return $archive ? $archive : home_url( '/' );
}

/**
 * Turn legacy relative paths (e.g. /handbook/) into working URLs via taxonomy-first resolution.
 *
 * @param string $maybe_relative Path starting with "/" or absolute URL.
 */
function pngcje_home_url_via_resource_quick_path( $maybe_relative ) {
    if ( ! $maybe_relative || strpos( $maybe_relative, 'http:' ) === 0 || strpos( $maybe_relative, 'https:' ) === 0 ) {
        return $maybe_relative;
    }
    $norm = strtolower( preg_replace( '#//+#', '/', '/' . trim( $maybe_relative, '/' ) . '/' ) );
    foreach ( pngcje_resource_type_legacy_page_paths() as $canonical => $suffix ) {
        if ( strtolower( $suffix ) === $norm ) {
            return pngcje_get_resource_type_url( $canonical );
        }
    }
    return home_url( $maybe_relative );
}

/**
 * Visitors hitting /handbook/ without a Page get the taxonomy archive (no Page required).
 */
function pngcje_redirect_legacy_resource_handbook_404() {
    if ( is_admin() || wp_doing_ajax() || ! is_404() ) {
        return;
    }

    $rq        = sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ?? '' ) );
    $path_only = explode( '?', $rq, 2 )[0];
    $path_only = explode( '#', $path_only, 2 )[0];
    $path_only = wp_parse_url( $path_only, PHP_URL_PATH );
    if ( ! is_string( $path_only ) || '' === $path_only ) {
        return;
    }
    $parts = strtolower( preg_replace( '#/+#', '/', '/' . trim( $path_only, '/' ) . '/' ) );

    $home_path = trim( (string) ( wp_parse_url( home_url( '/' ), PHP_URL_PATH ) ?: '' ), '/' );
    if ( $home_path !== '' ) {
        $prefix = strtolower( preg_replace( '#/+#', '/', '/' . trim( $home_path, '/' ) . '/' ) );
        if ( strpos( $parts, $prefix ) === 0 ) {
            $parts = strtolower( preg_replace( '#/+#', '/', '/' . trim( substr( $parts, strlen( $prefix ) ), '/' ) . '/' ) );
        }
    }

    if ( '/handbook/' !== $parts ) {
        return;
    }

    $dest = pngcje_get_resource_type_term_permalink( 'judicial-handbook' );
    if ( ! $dest ) {
        return;
    }
    wp_safe_redirect( $dest, 301 );
    exit;
}
add_action( 'template_redirect', 'pngcje_redirect_legacy_resource_handbook_404', 0 );

function pngcje_annual_reports_admin_menu() {
    $reports_url = 'edit.php?post_type=pngcje_resource&resource_type=annual-reports';
    $add_url     = 'post-new.php?post_type=pngcje_resource&pngcje_resource_type=annual-reports';

    add_menu_page(
        __( 'Annual Reports', 'pngcje' ),
        __( 'Annual Reports', 'pngcje' ),
        'edit_posts',
        $reports_url,
        '',
        'dashicons-chart-area',
        5
    );

    add_submenu_page(
        $reports_url,
        __( 'All Annual Reports', 'pngcje' ),
        __( 'All Annual Reports', 'pngcje' ),
        'edit_posts',
        $reports_url
    );

    add_submenu_page(
        $reports_url,
        __( 'Add New Annual Report', 'pngcje' ),
        __( 'Add New Annual Report', 'pngcje' ),
        'edit_posts',
        $add_url
    );
}
add_action( 'admin_menu', 'pngcje_annual_reports_admin_menu' );

function pngcje_default_new_annual_report_type( $post ) {
    if ( 'pngcje_resource' !== $post->post_type ) {
        return;
    }

    $resource_type = isset( $_GET['pngcje_resource_type'] ) ? sanitize_title( wp_unslash( $_GET['pngcje_resource_type'] ) ) : '';
    if ( 'annual-reports' !== $resource_type ) {
        return;
    }
    ?>
    <input type="hidden" name="pngcje_default_resource_type" value="annual-reports">
    <?php
}
add_action( 'edit_form_after_title', 'pngcje_default_new_annual_report_type' );

function pngcje_save_default_resource_type( $post_id ) {
    if ( ! isset( $_POST['pngcje_default_resource_type'] ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $resource_type = sanitize_title( wp_unslash( $_POST['pngcje_default_resource_type'] ) );
    if ( 'annual-reports' !== $resource_type ) {
        return;
    }

    wp_set_object_terms( $post_id, $resource_type, 'resource_type', false );
}
add_action( 'save_post_pngcje_resource', 'pngcje_save_default_resource_type' );

// ============================================================
// WIDGETS / SIDEBARS
// ============================================================
function pngcje_register_sidebars() {
    $defaults = [
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget__title">',
        'after_title'   => '</h3>',
    ];

    register_sidebar( array_merge( $defaults, [
        'name' => __( 'Blog Sidebar',    'pngcje' ),
        'id'   => 'sidebar-blog',
        'description' => __( 'Appears on blog/news pages', 'pngcje' ),
    ] ) );

    register_sidebar( array_merge( $defaults, [
        'name' => __( 'Resources Sidebar', 'pngcje' ),
        'id'   => 'sidebar-resources',
    ] ) );

    register_sidebar( array_merge( $defaults, [
        'name' => __( 'Footer Widget Area', 'pngcje' ),
        'id'   => 'footer-widgets',
    ] ) );
}
add_action( 'widgets_init', 'pngcje_register_sidebars' );

// ============================================================
// LOAD CUSTOM SYSTEMS
// Forms, Popups, Events — all built into the theme
// ============================================================
require_once PNGCJE_DIR . '/inc/systems.php';

/**
 * Compatibility shim: pngcje_get_upcoming_events()
 * Used in front-page.php — routes to our system or TEC
 */
function pngcje_get_upcoming_events( $count = 3 ) {
    return pngcje_get_upcoming_events_override( $count );
}

/**
 * Shortcode: [pngcje_upcoming_events count="3"]
 */
function pngcje_upcoming_events_shortcode( $atts ) {
    $atts   = shortcode_atts( [ 'count' => 3 ], $atts, 'pngcje_upcoming_events' );
    $events = pngcje_get_upcoming_events( intval( $atts['count'] ) );
    if ( empty( $events ) ) return '';

    ob_start();
    foreach ( $events as $event ) {
        get_template_part( 'template-parts/events/event', 'card', [ 'event' => $event ] );
    }
    return ob_get_clean();
}
add_shortcode( 'pngcje_upcoming_events', 'pngcje_upcoming_events_shortcode' );

// ============================================================
// THEME HELPERS
// ============================================================

/**
 * Return post excerpt with custom length
 */
function pngcje_excerpt( $post = null, $length = 20 ) {
    $post    = get_post( $post );
    $excerpt = $post->post_excerpt ?: wp_strip_all_tags( $post->post_content );
    return wp_trim_words( $excerpt, $length, '&hellip;' );
}

/**
 * Render a breadcrumb trail
 */
function pngcje_breadcrumbs() {
    if ( is_front_page() ) return;
    $items = [ '<a href="' . esc_url( home_url() ) . '">' . esc_html__( 'Home', 'pngcje' ) . '</a>' ];

    if ( is_category() || is_single() ) {
        $cat = get_the_category();
        if ( $cat ) $items[] = '<a href="' . esc_url( get_category_link( $cat[0]->term_id ) ) . '">' . esc_html( $cat[0]->name ) . '</a>';
        if ( is_single() ) $items[] = '<span>' . esc_html( get_the_title() ) . '</span>';
    } elseif ( is_page() ) {
        $ancestors = get_post_ancestors( get_the_ID() );
        foreach ( array_reverse( $ancestors ) as $ancestor ) {
            $items[] = '<a href="' . esc_url( get_permalink( $ancestor ) ) . '">' . esc_html( get_the_title( $ancestor ) ) . '</a>';
        }
        $items[] = '<span>' . esc_html( get_the_title() ) . '</span>';
    } elseif ( is_archive() ) {
        $items[] = '<span>' . esc_html( wp_strip_all_tags( get_the_archive_title() ) ) . '</span>';
    } elseif ( is_search() ) {
        $items[] = '<span>' . esc_html( sprintf( __( 'Search: %s', 'pngcje' ), get_search_query() ) ) . '</span>';
    }

    echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'pngcje' ) . '">';
    echo implode( ' <span class="breadcrumbs__sep">›</span> ', $items );
    echo '</nav>';
}

/**
 * SVG icon helper
 */
function pngcje_icon( $name, $class = '' ) {
    $file = PNGCJE_DIR . '/assets/images/icons/' . sanitize_file_name( $name ) . '.svg';
    if ( ! file_exists( $file ) ) return '';
    $svg = file_get_contents( $file );
    if ( $class ) $svg = str_replace( '<svg', '<svg class="' . esc_attr( $class ) . '"', $svg );
    return $svg;
}

/**
 * Resource type icon map
 */
function pngcje_resource_icon( $type_slug ) {
    $map = [
        'bench-books'    => '📖',
        'handbook'       => '📗',
        'case-notes'     => '⚖️',
        'cpd-lectures'   => '🎓',
        'speeches'       => '🎤',
        'annual-reports' => '📊',
        'newsletters'    => '📰',
        'prospectus'     => '📋',
        'lecture-series' => '🏛️',
    ];
    return $map[ $type_slug ] ?? '📄';
}

/**
 * Human-readable file size
 */
function pngcje_file_size( $bytes ) {
    if ( $bytes >= 1048576 ) return round( $bytes / 1048576, 1 ) . ' MB';
    if ( $bytes >= 1024    ) return round( $bytes / 1024,    0 ) . ' KB';
    return $bytes . ' B';
}

/**
 * Is this a Pacific section page?
 */
function pngcje_is_pacific() {
    $pacific_page = get_page_by_path( 'pacific-island-centre-for-judicial-excellence' );
    if ( ! $pacific_page ) return false;
    $current    = get_the_ID();
    $pacific_id = $pacific_page->ID;
    return $current === $pacific_id || in_array( $pacific_id, get_post_ancestors( $current ) );
}

// ============================================================
// PACIFIC SECTION BODY CLASS
// ============================================================
function pngcje_pacific_body_class( $classes ) {
    if ( pngcje_is_pacific() ) {
        $classes[] = 'is-pacific-section';
    }
    return $classes;
}
add_filter( 'body_class', 'pngcje_pacific_body_class' );

// ============================================================
// ANNOUNCEMENT BAR via Options
// ============================================================
function pngcje_announcement_bar() {
    $text    = get_theme_mod( 'pngcje_announcement_text', '' );
    $link    = get_theme_mod( 'pngcje_announcement_url',  '' );
    $active  = get_theme_mod( 'pngcje_announcement_active', false );
    if ( ! $active || empty( $text ) ) return;
  ?>
    <div class="announcement-bar" id="pngcje-announcement" role="alert">
        <?php if ( $link ) : ?>
            <a href="<?php echo esc_url( $link ); ?>">
                <?php echo esc_html( $text ); ?>
            </a>
        <?php else : ?>
            <?php echo esc_html( $text ); ?>
        <?php endif; ?>
        <button class="announcement-bar__close" aria-label="<?php esc_attr_e( 'Dismiss', 'pngcje' ); ?>" id="dismiss-announcement">&times;</button>
    </div>
    <?php
}
add_action( 'pngcje_before_header', 'pngcje_announcement_bar' );

// ============================================================
// CUSTOMIZER OPTIONS
// ============================================================
function pngcje_sanitize_non_negative_int( $value ) {
    return max( 0, absint( $value ) );
}

function pngcje_customizer( $wp_customize ) {

    // Panel: PNGCJE Settings
    $wp_customize->add_panel( 'pngcje_panel', [
        'title'    => __( 'PNGCJE Theme Settings', 'pngcje' ),
        'priority' => 30,
    ] );

    // Section: Announcement Bar
    $wp_customize->add_section( 'pngcje_announcement', [
        'title' => __( 'Announcement Bar', 'pngcje' ),
        'panel' => 'pngcje_panel',
    ] );
    $wp_customize->add_setting( 'pngcje_announcement_active', [ 'default' => false, 'sanitize_callback' => 'wp_validate_boolean' ] );
    $wp_customize->add_control( 'pngcje_announcement_active', [
        'label'   => __( 'Show Announcement Bar', 'pngcje' ),
        'section' => 'pngcje_announcement',
        'type'    => 'checkbox',
    ] );
    $wp_customize->add_setting( 'pngcje_announcement_text', [ 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'pngcje_announcement_text', [
        'label'   => __( 'Announcement Text', 'pngcje' ),
        'section' => 'pngcje_announcement',
        'type'    => 'text',
    ] );
    $wp_customize->add_setting( 'pngcje_announcement_url', [ 'default' => '', 'sanitize_callback' => 'esc_url_raw' ] );
    $wp_customize->add_control( 'pngcje_announcement_url', [
        'label'   => __( 'Announcement Link URL', 'pngcje' ),
        'section' => 'pngcje_announcement',
        'type'    => 'url',
    ] );

    // Section: Hero
    $wp_customize->add_section( 'pngcje_hero', [
        'title' => __( 'Homepage Hero', 'pngcje' ),
        'panel' => 'pngcje_panel',
    ] );
    $wp_customize->add_setting( 'pngcje_hero_title', [ 'default' => 'The Leading Judicial Education Institution in the Pacific', 'sanitize_callback' => 'wp_kses_post' ] );
    $wp_customize->add_control( 'pngcje_hero_title', [
        'label'   => __( 'Hero Title', 'pngcje' ),
        'section' => 'pngcje_hero',
        'type'    => 'textarea',
    ] );
    $wp_customize->add_setting( 'pngcje_hero_subtitle', [ 'default' => 'Providing professional judicial education to Judges, Magistrates, Court Officers and Justice Sector practitioners across Papua New Guinea and the Pacific.', 'sanitize_callback' => 'sanitize_textarea_field' ] );
    $wp_customize->add_control( 'pngcje_hero_subtitle', [
        'label'   => __( 'Hero Subtitle', 'pngcje' ),
        'section' => 'pngcje_hero',
        'type'    => 'textarea',
    ] );

    // Section: Forms (IDs set here — templates do not embed fixed form numbers)
    $wp_customize->add_section( 'pngcje_forms', [
        'title'       => __( 'Forms', 'pngcje' ),
        'description' => __( 'Enter the numeric form ID shown in Gravity Forms (Forms → your form). Use 0 to skip Gravity Forms and use the theme form ID below instead. Theme form IDs are the Numeric ID from Forms → PNGCJE Forms (or the ID in shortcode).', 'pngcje' ),
        'panel'       => 'pngcje_panel',
    ] );

    $wp_customize->add_setting( 'pngcje_newsletter_gravity_form_id', [
        'default'           => 0,
        'sanitize_callback' => 'pngcje_sanitize_non_negative_int',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'pngcje_newsletter_gravity_form_id', [
        'label'       => __( 'Homepage newsletter — Gravity Forms ID', 'pngcje' ),
        'description' => __( '0 = do not use Gravity Forms on this block (theme form below is used instead).', 'pngcje' ),
        'section'     => 'pngcje_forms',
        'type'        => 'number',
        'input_attrs' => [ 'min' => 0, 'step' => 1 ],
    ] );
    $wp_customize->add_setting( 'pngcje_newsletter_pngcje_form_id', [
        'default'           => 65,
        'sanitize_callback' => 'pngcje_sanitize_non_negative_int',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'pngcje_newsletter_pngcje_form_id', [
        'label'       => __( 'Homepage newsletter — PNGCJE form ID', 'pngcje' ),
        'description' => __( 'Shown when Gravity Forms is inactive or Gravity Forms ID is 0.', 'pngcje' ),
        'section'     => 'pngcje_forms',
        'type'        => 'number',
        'input_attrs' => [ 'min' => 1, 'step' => 1 ],
    ] );

    $wp_customize->add_setting( 'pngcje_contact_gravity_form_id', [
        'default'           => 0,
        'sanitize_callback' => 'pngcje_sanitize_non_negative_int',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'pngcje_contact_gravity_form_id', [
        'label'       => __( 'Contact page — Gravity Forms ID', 'pngcje' ),
        'description' => __( '0 = do not use Gravity Forms on contact (theme form below is used instead).', 'pngcje' ),
        'section'     => 'pngcje_forms',
        'type'        => 'number',
        'input_attrs' => [ 'min' => 0, 'step' => 1 ],
    ] );
    $wp_customize->add_setting( 'pngcje_contact_pngcje_form_id', [
        'default'           => 124,
        'sanitize_callback' => 'pngcje_sanitize_non_negative_int',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'pngcje_contact_pngcje_form_id', [
        'label'       => __( 'Contact page — PNGCJE form ID', 'pngcje' ),
        'description' => __( 'Shown when Gravity Forms is inactive or Gravity Forms ID is 0.', 'pngcje' ),
        'section'     => 'pngcje_forms',
        'type'        => 'number',
        'input_attrs' => [ 'min' => 1, 'step' => 1 ],
    ] );

    // Section: Contact Info
    $wp_customize->add_section( 'pngcje_contact', [
        'title' => __( 'Contact Information', 'pngcje' ),
        'panel' => 'pngcje_panel',
    ] );
    foreach ( [
        'pngcje_phone'   => [ 'Phone Number',  '+675 324 5700' ],
        'pngcje_email'   => [ 'Email Address', 'info@pngcje.gov.pg' ],
        'pngcje_address' => [ 'Address',       'PO Box 7018, Boroko, NCD, Papua New Guinea' ],
    ] as $key => [ $label, $default ] ) {
        $wp_customize->add_setting( $key, [ 'default' => $default, 'sanitize_callback' => 'sanitize_text_field' ] );
        $wp_customize->add_control( $key, [
            'label'   => __( $label, 'pngcje' ),
            'section' => 'pngcje_contact',
            'type'    => 'text',
        ] );
    }

    // Section: Social Links
    $wp_customize->add_section( 'pngcje_social', [
        'title' => __( 'Social Media Links', 'pngcje' ),
        'panel' => 'pngcje_panel',
    ] );
    foreach ( [ 'facebook', 'twitter', 'linkedin', 'youtube' ] as $network ) {
        $wp_customize->add_setting( 'pngcje_social_' . $network, [ 'default' => '', 'sanitize_callback' => 'esc_url_raw' ] );
        $wp_customize->add_control( 'pngcje_social_' . $network, [
            'label'   => ucfirst( $network ) . ' URL',
            'section' => 'pngcje_social',
            'type'    => 'url',
        ] );
    }
}
add_action( 'customize_register', 'pngcje_customizer' );

// ============================================================
// ADMIN COLUMNS — Resources
// ============================================================
function pngcje_resource_columns( $columns ) {
    return array_merge( $columns, [
        'resource_type' => __( 'Type',       'pngcje' ),
        'resource_file' => __( 'File',       'pngcje' ),
        'resource_year' => __( 'Year',       'pngcje' ),
    ] );
}
add_filter( 'manage_pngcje_resource_posts_columns', 'pngcje_resource_columns' );

function pngcje_resource_column_data( $column, $post_id ) {
    switch ( $column ) {
        case 'resource_type':
            $terms = get_the_terms( $post_id, 'resource_type' );
            if ( $terms ) echo esc_html( implode( ', ', wp_list_pluck( $terms, 'name' ) ) );
            break;
        case 'resource_file':
            $file = get_post_meta( $post_id, '_pngcje_resource_file', true );
            if ( $file ) echo '<a href="' . esc_url( $file ) . '" target="_blank">View File</a>';
            break;
        case 'resource_year':
            echo esc_html( get_post_meta( $post_id, '_pngcje_resource_year', true ) );
            break;
    }
}
add_action( 'manage_pngcje_resource_posts_custom_column', 'pngcje_resource_column_data', 10, 2 );

function pngcje_youtube_embed_url( $url ) {
    $url = trim( (string) $url );
    if ( '' === $url ) {
        return '';
    }

    $parts = wp_parse_url( $url );
    if ( empty( $parts['host'] ) ) {
        return '';
    }

    $host  = strtolower( preg_replace( '/^www\./', '', $parts['host'] ) );
    $path  = isset( $parts['path'] ) ? trim( $parts['path'], '/' ) : '';
    $query = [];

    if ( ! empty( $parts['query'] ) ) {
        parse_str( $parts['query'], $query );
    }

    $video_id = '';
    if ( 'youtu.be' === $host && $path ) {
        $video_id = strtok( $path, '/' );
    } elseif ( in_array( $host, [ 'youtube.com', 'm.youtube.com', 'youtube-nocookie.com' ], true ) ) {
        if ( ! empty( $query['v'] ) ) {
            $video_id = $query['v'];
        } elseif ( preg_match( '#^(embed|live|shorts)/([^/]+)#', $path, $matches ) ) {
            $video_id = $matches[2];
        } elseif ( preg_match( '#^channel/([^/]+)/live#', $path, $matches ) ) {
            return 'https://www.youtube-nocookie.com/embed/live_stream?channel=' . rawurlencode( $matches[1] );
        }
    }

    $video_id = preg_replace( '/[^A-Za-z0-9_-]/', '', (string) $video_id );
    if ( '' === $video_id ) {
        return '';
    }

    return 'https://www.youtube-nocookie.com/embed/' . rawurlencode( $video_id );
}

// ============================================================
// INCLUDE ADMIN ENHANCEMENTS
// ============================================================
if ( is_admin() ) {
    require_once PNGCJE_DIR . '/inc/admin.php';
}

// ============================================================
// FLUSH REWRITE RULES ON ACTIVATE
// ============================================================
function pngcje_activate() {
    pngcje_register_post_types();
    pngcje_register_taxonomies();
    if ( function_exists( 'pngcje_forms_register_cpts' ) ) {
        pngcje_forms_register_cpts();
    }
    if ( function_exists( 'pngcje_popups_register_cpt' ) ) {
        pngcje_popups_register_cpt();
    }
    if ( function_exists( 'pngcje_events_register' ) ) {
        pngcje_events_register();
    }
    if ( function_exists( 'pngcje_newsletters_register' ) ) {
        pngcje_newsletters_register();
    }
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'pngcje_activate' );

function pngcje_maybe_flush_rewrite_rules() {
    $rewrite_version = '20260517_newsletters';

    if ( get_option( 'pngcje_rewrite_version' ) === $rewrite_version ) {
        return;
    }

    flush_rewrite_rules();
    update_option( 'pngcje_rewrite_version', $rewrite_version );
}
add_action( 'init', 'pngcje_maybe_flush_rewrite_rules', 99 );

// ============================================================
// SECURITY HARDENING
// ============================================================
remove_action( 'wp_head', 'wp_generator' );           // Hide WP version
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );

function pngcje_remove_rest_users( $endpoints ) {
    if ( isset( $endpoints['/wp/v2/users'] ) ) {
        unset( $endpoints['/wp/v2/users'] );
    }
    if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
        unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
    }
    return $endpoints;
}
add_filter( 'rest_endpoints', 'pngcje_remove_rest_users' );

// ============================================================
// BASIC STRUCTURED DATA
// ============================================================
function pngcje_structured_data() {
    $logo_url = pngcje_theme_asset_url( 'assets/img/pngcje_logo.png' );

    $graph = [
        [
            '@type' => 'Organization',
            '@id'   => home_url( '/#organization' ),
            'name'  => get_bloginfo( 'name' ),
            'url'   => home_url( '/' ),
        ],
        [
            '@type'     => 'WebSite',
            '@id'       => home_url( '/#website' ),
            'url'       => home_url( '/' ),
            'name'      => get_bloginfo( 'name' ),
            'publisher' => [ '@id' => home_url( '/#organization' ) ],
        ],
    ];

    if ( $logo_url ) {
        $graph[0]['logo'] = $logo_url;
    }

    if ( is_singular( 'pngcje_event' ) && function_exists( 'pngcje_event_get' ) ) {
        $start_date = pngcje_event_get( 'start_date' );
        if ( $start_date ) {
            $start_time = pngcje_event_get( 'start_time' ) ?: '09:00';
            $end_date   = pngcje_event_get( 'end_date' ) ?: $start_date;
            $end_time   = pngcje_event_get( 'end_time' ) ?: '17:00';
            $venue      = pngcje_event_get( 'venue' );
            $address    = pngcje_event_get( 'address' );
            $cost       = pngcje_event_get( 'cost' );

            $event = [
                '@type'     => 'Event',
                'name'      => get_the_title(),
                'url'       => get_permalink(),
                'startDate' => date( DATE_ATOM, strtotime( $start_date . ' ' . $start_time ) ),
                'endDate'   => date( DATE_ATOM, strtotime( $end_date . ' ' . $end_time ) ),
                'eventStatus' => 'https://schema.org/EventScheduled',
                'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
                'organizer' => [ '@id' => home_url( '/#organization' ) ],
            ];

            if ( $venue || $address ) {
                $event['location'] = [
                    '@type'   => 'Place',
                    'name'    => $venue ?: get_bloginfo( 'name' ),
                    'address' => $address,
                ];
            }

            if ( $cost ) {
                $event['offers'] = [
                    '@type' => 'Offer',
                    'price' => $cost,
                    'url'   => get_permalink(),
                ];
            }

            $graph[] = $event;
        }
    }

    echo '<script type="application/ld+json">' . wp_json_encode( [
        '@context' => 'https://schema.org',
        '@graph'   => $graph,
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'pngcje_structured_data', 20 );
