</main><!-- #main-content -->

<!-- ============================================================
     SITE FOOTER
     ============================================================ -->
<footer class="site-footer" role="contentinfo" id="site-footer">

    <div class="footer-main">
        <div class="container">
            <div class="footer-grid">

                <!-- Brand Column -->
                <div class="footer-brand">
                    <?php if ( pngcje_theme_asset_url( 'assets/img/pngcje_logo.png' ) ) : ?>
                        <img
                            src="<?php echo esc_url( pngcje_theme_asset_url( 'assets/img/pngcje_logo.png' ) ); ?>"
                            alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
                            class="footer-brand__logo"
                            width="200"
                            height="50"
                            loading="lazy"
                        >
                    <?php else : ?>
                        <div class="footer-brand__logo-text"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></div>
                    <?php endif; ?>
                    <p class="footer-brand__tagline">
                        <?php esc_html_e( 'To achieve an independent, honest and competent judiciary through the delivery of effective and responsive judicial education.', 'pngcje' ); ?>
                    </p>
                    <!-- Social Links -->
                    <?php
                    $socials = [
                        'facebook' => [ get_theme_mod( 'pngcje_social_facebook', '' ), 'Facebook', '<svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>' ],
                        'twitter'  => [ get_theme_mod( 'pngcje_social_twitter',  '' ), 'X / Twitter', '<svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.748l7.73-8.835L1.254 2.25H8.08l4.259 5.631zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>' ],
                        'linkedin' => [ get_theme_mod( 'pngcje_social_linkedin', '' ), 'LinkedIn', '<svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>' ],
                        'youtube'  => [ get_theme_mod( 'pngcje_social_youtube',  '' ), 'YouTube', '<svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>' ],
                    ];
                    $has_social = array_filter( array_column( $socials, 0 ) );
                    if ( $has_social ) :
                  ?>
                    <div class="footer-social" style="display:flex;gap:0.75rem;flex-wrap:wrap;">
                        <?php foreach ( $socials as $key => [ $url, $label, $icon ] ) : ?>
                            <?php if ( $url ) : ?>
                            <a
                                href="<?php echo esc_url( $url ); ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="<?php echo esc_attr( $label ); ?>"
                                style="width:36px;height:36px;background:rgba(255,255,255,0.08);border-radius:6px;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.55);transition:all 0.25s ease;"
                                onmouseover="this.style.background='rgba(212,150,10,0.2)';this.style.color='#F9B800';"
                                onmouseout="this.style.background='rgba(255,255,255,0.08)';this.style.color='rgba(255,255,255,0.55)';"
                            >
                                <?php echo $icon; ?>
                            </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Quick Links -->
                <div class="footer-col">
                    <h3 class="footer-col__title"><?php esc_html_e( 'Quick Links', 'pngcje' ); ?></h3>
                    <?php
                    wp_nav_menu( [
                        'theme_location' => 'footer-1',
                        'container'      => false,
                        'fallback_cb'    => function() {
                            $links = [
                                __( 'Home',                     'pngcje' ) => home_url( '/' ),
                                __( 'About the PNGCJE',         'pngcje' ) => home_url( '/about/' ),
                                __( 'Our Staff',                'pngcje' ) => home_url( '/our-staff/' ),
                                __( 'Governance',               'pngcje' ) => home_url( '/about/governance/' ),
                                __( 'Contact Us',               'pngcje' ) => home_url( '/contact-us/' ),
                                __( 'Sitemap',                  'pngcje' ) => home_url( '/about/sitemap/' ),
                            ];
                            echo '<ul>';
                            foreach ( $links as $label => $url ) {
                                echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
                            }
                            echo '</ul>';
                        },
                    ] );
                  ?>
                </div>

                <!-- Our Work -->
                <div class="footer-col">
                    <h3 class="footer-col__title"><?php esc_html_e( 'Our Work', 'pngcje' ); ?></h3>
                    <?php
                    wp_nav_menu( [
                        'theme_location' => 'footer-2',
                        'container'      => false,
                        'fallback_cb'    => function() {
                            $links = [
                                __( 'Bench Books',        'pngcje' ) => pngcje_get_resource_type_url( 'bench-books' ),
                                __( 'Judicial Handbook',  'pngcje' ) => pngcje_get_resource_type_url( 'judicial-handbook' ),
                                __( 'Case Notes',         'pngcje' ) => pngcje_get_resource_type_url( 'case-notes' ),
                                __( 'CPD Lectures',       'pngcje' ) => pngcje_get_resource_type_url( 'cpd-lectures' ),
                                __( 'Training Calendar',  'pngcje' ) => home_url( '/training-calendar/' ),
                                __( 'Annual Reports',     'pngcje' ) => home_url( '/annual-reports/' ),
                                __( 'Newsletters',        'pngcje' ) => home_url( '/newsletters/' ),
                            ];
                            echo '<ul>';
                            foreach ( $links as $label => $url ) {
                                echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
                            }
                            echo '</ul>';
                        },
                    ] );
                  ?>
                </div>

                <!-- Pacific & Partners -->
                <div class="footer-col">
                    <h3 class="footer-col__title"><?php esc_html_e( 'Pacific & Partners', 'pngcje' ); ?></h3>
                    <?php
                    wp_nav_menu( [
                        'theme_location' => 'footer-3',
                        'container'      => false,
                        'fallback_cb'    => function() {
                            $links = [
                                __( 'Pacfic CJE', 'pngcje' ) => home_url( '/pacific-island-centre-for-judicial-excellence/' ),
                                __( 'PNG Judiciary',           'pngcje' ) => 'https://www.pngjudiciary.gov.pg/',
                                __( 'Dept of Justice & AG',    'pngcje' ) => 'https://www.justice.gov.pg/',
                                __( 'Magisterial Services',    'pngcje' ) => 'http://www.magisterialservices.gov.pg/',
                                __( 'PacLII',                  'pngcje' ) => 'http://www.paclii.org/',
                                __( 'PNG National Parliament', 'pngcje' ) => 'http://www.parliament.gov.pg/',
                            ];
                            echo '<ul>';
                            foreach ( $links as $label => $url ) {
                                $ext = ( strpos( $url, home_url() ) === false );
                                echo '<li><a href="' . esc_url( $url ) . '"' . ( $ext ? ' target="_blank" rel="noopener noreferrer"' : '' ) . '>' . esc_html( $label ) . '</a></li>';
                            }
                            echo '</ul>';
                        },
                    ] );
                  ?>

                    <!-- Contact Quick Info -->
                    <div style="margin-top:2rem;display:flex;flex-direction:column;gap:0.75rem;">
                        <?php
                        $phone   = get_theme_mod( 'pngcje_phone',   '+675 324 5700' );
                        $email   = get_theme_mod( 'pngcje_email',   'info@pngcje.gov.pg' );
                        $address = get_theme_mod( 'pngcje_address', 'PO Box 7018, Boroko, NCD, Papua New Guinea' );
                      ?>
                        <?php if ( $phone ) : ?>
                        <a href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $phone ) ); ?>" style="font-size:0.8rem;color:rgba(255,255,255,0.55);display:flex;align-items:center;gap:0.5rem;transition:color 0.25s;" onmouseover="this.style.color='#fff';" onmouseout="this.style.color='rgba(255,255,255,0.55)';">
                            📞 <?php echo esc_html( $phone ); ?>
                        </a>
                        <?php endif; ?>
                        <?php if ( $email ) : ?>
                        <a href="mailto:<?php echo esc_attr( $email ); ?>" style="font-size:0.8rem;color:rgba(255,255,255,0.55);display:flex;align-items:center;gap:0.5rem;transition:color 0.25s;" onmouseover="this.style.color='#fff';" onmouseout="this.style.color='rgba(255,255,255,0.55)';">
                            ✉️ <?php echo esc_html( $email ); ?>
                        </a>
                        <?php endif; ?>
                        <?php if ( $address ) : ?>
                        <span style="font-size:0.8rem;color:rgba(255,255,255,0.45);display:flex;align-items:flex-start;gap:0.5rem;">
                            📍 <?php echo esc_html( $address ); ?>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>

            </div><!-- .footer-grid -->
        </div><!-- .container -->
    </div><!-- .footer-main -->

    <!-- Footer Bottom Bar -->
    <div class="container">
        <div class="footer-bottom">
            <p>
                &copy; <?php echo date( 'Y' ); ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <?php echo esc_html( get_bloginfo( 'name' ) ); ?>
                </a>.
                <?php esc_html_e( 'All rights reserved.', 'pngcje' ); ?>
                <?php esc_html_e( 'An institution of the PNG Judiciary.', 'pngcje' ); ?>
            </p>
            <div style="display:flex;align-items:center;gap:1.5rem;">
                <a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">
                    <?php esc_html_e( 'Privacy Policy', 'pngcje' ); ?>
                </a>
                <a href="<?php echo esc_url( home_url( '/terms-of-use/' ) ); ?>">
                    <?php esc_html_e( 'Terms of Use', 'pngcje' ); ?>
                </a>
                <a href="<?php echo esc_url( home_url( '/accessibility/' ) ); ?>">
                    <?php esc_html_e( 'Accessibility', 'pngcje' ); ?>
                </a>
            </div>
        </div>
    </div>

</footer><!-- .site-footer -->

<?php wp_footer(); ?>
</body>
</html>
