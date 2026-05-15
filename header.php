<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php do_action( 'pngcje_before_header' ); ?>

<a class="sr-only" href="#main-content"><?php esc_html_e( 'Skip to main content', 'pngcje' ); ?></a>

<!-- ============================================================
     SITE HEADER
     ============================================================ -->
<header class="site-header" id="site-header" role="banner">

    <!-- Top Bar -->
    <div class="header-topbar">
        <div class="container">
            <div class="header-topbar__inner">
                <span class="header-topbar__gov">
                    <?php esc_html_e( 'Supreme and National Courts of Papua New Guinea', 'pngcje' ); ?>
                </span>
                <nav class="header-topbar__links" aria-label="<?php esc_attr_e( 'Quick Links', 'pngcje' ); ?>">
                    <?php
                    wp_nav_menu( [
                        'theme_location' => 'topbar',
                        'container'      => false,
                        'items_wrap'     => '%3$s',
                        'walker'         => new Walker_Nav_Menu(),
                        'fallback_cb'    => function() {
                            $links = [
                                'PNG Judiciary'  => 'https://www.pngjudiciary.gov.pg/',
                                'Magisterial Services' => 'http://www.magisterialservices.gov.pg/',
                                'PacLII'         => 'http://www.paclii.org/',
                                'Contact'        => home_url( '/contact-us/' ),
                            ];
                            foreach ( $links as $label => $url ) {
                                echo '<a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a>';
                            }
                        },
                    ] );
                  ?>
                </nav>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <div class="header-navbar">
        <div class="container">
            <div class="header-navbar__inner">

                <!-- Logo -->
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> — <?php esc_attr_e( 'Home', 'pngcje' ); ?>">
                    <?php if ( has_custom_logo() ) : ?>
                        <?php the_custom_logo(); ?>
                    <?php elseif ( pngcje_theme_asset_url( 'assets/img/pngcje_logo.png' ) ) : ?>
                        <img
                            src="<?php echo esc_url( pngcje_theme_asset_url( 'assets/img/pngcje_logo.png' ) ); ?>"
                            alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
                            width="220"
                            height="56"
                        >
                    <?php else : ?>
                        <span class="site-logo__text"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
                    <?php endif; ?>
                </a>

                <!-- Primary Navigation -->
                <nav class="primary-nav-wrap" aria-label="<?php esc_attr_e( 'Main Navigation', 'pngcje' ); ?>">
                    <?php
                    wp_nav_menu( [
                        'theme_location'  => 'primary',
                        'container'       => false,
                        'menu_class'      => 'primary-nav',
                        'items_wrap'      => '<ul class="%2$s" role="list">%3$s</ul>',
                        'walker'          => new PNGCJE_Nav_Walker(),
                        'fallback_cb'     => 'pngcje_fallback_nav',
                    ] );
                  ?>
                </nav>

                <!-- Header Actions -->
                <div class="header-actions">
                    <!-- Search -->
                    <button
                        class="header-search-toggle"
                        id="search-toggle"
                        aria-label="<?php esc_attr_e( 'Open Search', 'pngcje' ); ?>"
                        aria-expanded="false"
                        aria-controls="search-overlay"
                    >
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                    </button>

                    <!-- LMS CTA — Prominent Gold Button -->
                    <a
                        href="https://piccje.csod.com/login/render.aspx?id=defaultclp"
                        class="btn btn-lms" style="background:var(--ember-primary);border-color:var(--ember-primary);"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="<?php esc_attr_e( 'Access Learning Management System (opens in new tab)', 'pngcje' ); ?>"
                    >
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.31h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8 9a16 16 0 0 0 6 6l.85-.85a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21.5 16"/>
                        </svg>
                        <?php esc_html_e( 'Access LMS', 'pngcje' ); ?>
                    </a>

                    <!-- Mobile Toggle -->
                    <button
                        class="mobile-menu-toggle"
                        id="mobile-menu-toggle"
                        aria-label="<?php esc_attr_e( 'Open Menu', 'pngcje' ); ?>"
                        aria-expanded="false"
                        aria-controls="mobile-nav-drawer"
                    >
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>

            </div><!-- .header-navbar__inner -->
        </div>
    </div><!-- .header-navbar -->

</header><!-- .site-header -->

<!-- Header spacer to compensate for fixed position -->
<div class="site-header-spacer" aria-hidden="true"></div>

<!-- ============================================================
     SEARCH OVERLAY
     ============================================================ -->
<div class="search-overlay" id="search-overlay" role="dialog" aria-label="<?php esc_attr_e( 'Search', 'pngcje' ); ?>" aria-modal="true" hidden>
    <button class="search-overlay__close" id="search-overlay-close" aria-label="<?php esc_attr_e( 'Close Search', 'pngcje' ); ?>">
        &times;
    </button>
    <div class="search-overlay__form">
        <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
            <label for="search-input" class="sr-only"><?php esc_html_e( 'Search', 'pngcje' ); ?></label>
            <input
                type="search"
                id="search-input"
                class="search-overlay__input"
                placeholder="<?php esc_attr_e( 'Search PngCJE…', 'pngcje' ); ?>"
                name="s"
                autocomplete="off"
                aria-label="<?php esc_attr_e( 'Search query', 'pngcje' ); ?>"
            >
        </form>
        <p style="color:rgba(255,255,255,0.35);font-size:0.8rem;margin-top:1rem;">
            <?php esc_html_e( 'Press Enter to search or Esc to close', 'pngcje' ); ?>
        </p>
    </div>
</div>

<!-- ============================================================
     MOBILE NAV DRAWER
     ============================================================ -->
<div class="mobile-nav-overlay" id="mobile-nav-overlay" aria-hidden="true"></div>

<nav
    class="mobile-nav-drawer"
    id="mobile-nav-drawer"
    aria-label="<?php esc_attr_e( 'Mobile Navigation', 'pngcje' ); ?>"
    aria-hidden="true"
    role="dialog"
    aria-modal="true"
>
    <div class="mobile-nav-drawer__header">
        <?php if ( pngcje_theme_asset_url( 'assets/img/pngcje_logo.png' ) ) : ?>
            <img
                src="<?php echo esc_url( pngcje_theme_asset_url( 'assets/img/pngcje_logo.png' ) ); ?>"
                alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
                width="160"
                height="40"
            >
        <?php else : ?>
            <span class="site-logo__text site-logo__text--mobile"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
        <?php endif; ?>
        <button
            class="mobile-nav-drawer__close"
            id="mobile-nav-close"
            aria-label="<?php esc_attr_e( 'Close Menu', 'pngcje' ); ?>"
        >&times;</button>
    </div>

    <?php
    wp_nav_menu( [
        'theme_location' => 'primary',
        'container'      => false,
        'menu_class'     => 'mobile-nav-menu',
        'fallback_cb'    => 'pngcje_fallback_nav',
    ] );
  ?>

    <div class="mobile-nav-drawer__lms">
        <a
            href="https://piccje.csod.com/login/render.aspx?id=defaultclp"
            class="btn btn-gold btn-lg"
            style="width:100%;justify-content:center;"
            target="_blank"
            rel="noopener noreferrer"
        >
            <?php esc_html_e( 'Access LMS Portal', 'pngcje' ); ?>
        </a>
    </div>
</nav>

<!-- ============================================================
     CUSTOM NAV WALKER
     ============================================================ -->
<?php
class PNGCJE_Nav_Walker extends Walker_Nav_Menu {
    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $classes   = empty( $item->classes ) ? [] : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        $class_str = implode( ' ', array_filter( $classes ) );

        $has_children = in_array( 'menu-item-has-children', $classes );
        $output .= '<li class="' . esc_attr( $class_str ) . '">';

        $atts = [
            'href'   => ! empty( $item->url ) ? $item->url : '#',
            'target' => ! empty( $item->target ) ? $item->target : '',
            'rel'    => ! empty( $item->xfn ) ? $item->xfn : '',
            'title'  => ! empty( $item->attr_title ) ? $item->attr_title : '',
        ];
        if ( $item->current ) $atts['aria-current'] = 'page';

        $attr_str = '';
        foreach ( $atts as $k => $v ) {
            if ( $v ) $attr_str .= ' ' . $k . '="' . esc_attr( $v ) . '"';
        }

        $title = apply_filters( 'the_title', $item->title, $item->ID );
        $output .= '<a' . $attr_str . '>' . esc_html( $title );
        if ( $has_children && $depth === 0 ) {
            $output .= '<span class="nav-arrow" aria-hidden="true">▾</span>';
        }
        $output .= '</a>';
    }
}

function pngcje_fallback_nav() {
    $pages = [
        __( 'Home',          'pngcje' ) => home_url( '/' ),
        __( 'About',         'pngcje' ) => home_url( '/about/' ),
        __( 'Our Work',      'pngcje' ) => home_url( '/our-work/' ),
        __( 'News',          'pngcje' ) => home_url( '/news/' ),
        __( 'Pacific Centre','pngcje' ) => home_url( '/pacific-island-centre-for-judicial-excellence/' ),
        __( 'Contact Us',    'pngcje' ) => home_url( '/contact-us/' ),
    ];
    echo '<ul class="primary-nav" role="list">';
    foreach ( $pages as $label => $url ) {
        echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
    }
    echo '</ul>';
}
?>

<main id="main-content" class="site-main" role="main" tabindex="-1">
