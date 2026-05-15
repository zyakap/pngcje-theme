<?php
/**
 * Template Name: Sitemap
 * Full site structure map
 */
get_header(); ?>

<div <?php pngcje_page_hero_attrs(); ?>>
    <div class="container">
        <div class="page-hero__eyebrow"><?php pngcje_breadcrumbs(); ?></div>
        <h1 class="page-hero__title">Sitemap</h1>
        <p class="page-hero__desc">A full directory of all pages and sections on the PNGCJE website.</p>
    </div>
</div>

<section class="section">
    <div class="container" style="max-width:960px;">

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;">

            <?php
            $sections = [
                [
                    'icon'  => '🏠',
                    'title' => 'Home',
                    'url'   => home_url( '/' ),
                    'links' => [],
                ],
                [
                    'icon'  => 'ℹ️',
                    'title' => 'About the PNGCJE',
                    'url'   => home_url( '/about/' ),
                    'links' => [
                        [ 'Our Staff',  home_url( '/about/staff/' ) ],
                        [ 'Governance', home_url( '/about/governance/' ) ],
                        [ 'Sitemap',    home_url( '/about/sitemap/' ) ],
                    ],
                ],
                [
                    'icon'  => '📚',
                    'title' => 'Our Work',
                    'url'   => home_url( '/our-work/' ),
                    'links' => [
                        [ 'Case Notes',         pngcje_get_resource_type_url( 'case-notes' ) ],
                        [ 'Bench Books',        pngcje_get_resource_type_url( 'bench-books' ) ],
                        [ 'Judicial Handbook',  pngcje_get_resource_type_url( 'judicial-handbook' ) ],
                        [ 'CPD Lectures',       pngcje_get_resource_type_url( 'cpd-lectures' ) ],
                        [ 'Executive Director Speeches', pngcje_get_resource_type_url( 'executive-director-speeches' ) ],
                        [ 'Prospectus',         home_url( '/prospectus/' ) ],
                        [ 'Lecture Series',     home_url( '/lecture-series/' ) ],
                        [ 'Training Calendar',  home_url( '/prospectus/training-calendar/' ) ],
                        [ 'Customer Service',   home_url( '/customer-service/' ) ],
                    ],
                ],
                [
                    'icon'  => '📰',
                    'title' => 'News',
                    'url'   => home_url( '/news/' ),
                    'links' => [
                        [ 'Annual Reports', home_url( '/prospectus/annual-reports/' ) ],
                        [ 'Newsletters',    home_url( '/newsletters/' ) ],
                    ],
                ],
                [
                    'icon'  => '📅',
                    'title' => 'Events & Programs',
                    'url'   => home_url( '/integrity-and-judicial-well-being-2-2/' ),
                    'links' => [
                        [ 'Upcoming Events', home_url( '/integrity-and-judicial-well-being-2-2/' ) ],
                        [ 'Training Calendar', home_url( '/prospectus/training-calendar/' ) ],
                        [ 'Integrity and Judicial Well-being', home_url( '/integrity-and-judicial-well-being-2/' ) ],
                    ],
                ],
                [
                    'icon'  => '🌊',
                    'title' => 'Pacific CJE',
                    'url'   => home_url( '/pacific-island-centre-for-judicial-excellence/' ),
                    'links' => [
                        [ 'Training Program Schedule', home_url( '/pacific-island-centre-for-judicial-excellence/#training' ) ],
                        [ 'Member Countries',          home_url( '/pacific-island-centre-for-judicial-excellence/#members' ) ],
                    ],
                ],
                [
                    'icon'  => '🎓',
                    'title' => 'LMS Portal',
                    'url'   => 'https://learn.pngcje.gov.pg',
                    'links' => [],
                    'ext'   => true,
                ],
                [
                    'icon'  => '📞',
                    'title' => 'Contact Us',
                    'url'   => home_url( '/contact-us/' ),
                    'links' => [],
                ],
                [
                    'icon'  => '🔍',
                    'title' => 'Search',
                    'url'   => home_url( '/?s=' ),
                    'links' => [],
                ],
                [
                    'icon'  => '📄',
                    'title' => 'Website Policies',
                    'url'   => home_url( '/privacy-policy/' ),
                    'links' => [
                        [ 'Privacy Policy', home_url( '/privacy-policy/' ) ],
                        [ 'Terms of Use', home_url( '/terms-of-use/' ) ],
                        [ 'Accessibility', home_url( '/accessibility/' ) ],
                    ],
                ],
            ];
            foreach ( $sections as $sec ) :
                $ext = ! empty( $sec['ext'] );
            ?>
            <div class="reveal">
                <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem;padding-bottom:.75rem;border-bottom:2px solid var(--border-light);">
                    <span style="font-size:1.4rem;" aria-hidden="true"><?php echo esc_html( $sec['icon'] ); ?></span>
                    <a href="<?php echo esc_url( $sec['url'] ); ?>"
                       style="font-size:var(--size-lg);font-weight:700;color:var(--ember-primary);text-decoration:none;"
                       <?php echo $ext ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                        <?php echo esc_html( $sec['title'] ); ?>
                        <?php echo $ext ? ' ↗' : ''; ?>
                    </a>
                </div>
                <?php if ( ! empty( $sec['links'] ) ) : ?>
                <ul style="display:flex;flex-direction:column;gap:.4rem;padding-left:2rem;">
                    <?php foreach ( $sec['links'] as $link ) : ?>
                    <li>
                        <a href="<?php echo esc_url( $link[1] ); ?>"
                           style="font-size:.9rem;color:var(--ink-mid);text-decoration:none;display:flex;align-items:center;gap:.4rem;transition:color .2s;"
                           onmouseover="this.style.color='var(--ember-primary)';"
                           onmouseout="this.style.color='var(--ink-mid)';">
                            <span style="color:var(--gold-primary);">›</span>
                            <?php echo esc_html( $link[0] ); ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>

        </div>

        <!-- External partner links -->
        <div class="reveal" style="margin-top:4rem;padding-top:3rem;border-top:1px solid var(--border-light);">
            <div class="section-label" style="margin-bottom:1.5rem;">External Links &amp; Partners</div>
            <div style="display:flex;flex-wrap:wrap;gap:.75rem;">
                <?php foreach ( [
                    [ 'PNG Judiciary',         'https://www.pngjudiciary.gov.pg/' ],
                    [ 'Dept of Justice & AG',  'https://www.justice.gov.pg/' ],
                    [ 'Magisterial Services',  'http://www.magisterialservices.gov.pg/' ],
                    [ 'PacLII',                'http://www.paclii.org/' ],
                    [ 'PNG National Parliament','http://www.parliament.gov.pg/' ],
                ] as $pl ) : ?>
                <a href="<?php echo esc_url( $pl[1] ); ?>"
                   target="_blank" rel="noopener noreferrer"
                   class="partners-strip__item">
                    <?php echo esc_html( $pl[0] ); ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</section>

<?php get_footer(); ?>
