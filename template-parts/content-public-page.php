<?php
/**
 * Shared layout for refreshed hard-coded public pages.
 *
 * Expected variables:
 * $page_label, $page_title, $page_desc, $page_sections, $page_sidebar,
 * $page_resource_type, $page_resource_label
 */
defined( 'ABSPATH' ) || exit;

$page_label         = $page_label ?? '';
$page_title         = $page_title ?? get_the_title();
$page_desc          = $page_desc ?? '';
$page_sections      = $page_sections ?? [];
$page_sidebar       = $page_sidebar ?? '';
$page_resource_type = $page_resource_type ?? '';
$page_resource_label = $page_resource_label ?? __( 'Resources', 'pngcje' );
$page_resource_layout = $page_resource_layout ?? '';
?>

<div <?php pngcje_page_hero_attrs(); ?>>
    <div class="container">
        <div class="page-hero__eyebrow"><?php pngcje_breadcrumbs(); ?></div>
        <h1 class="page-hero__title"><?php echo esc_html( $page_title ); ?></h1>
        <?php if ( $page_desc ) : ?>
            <p class="page-hero__desc"><?php echo esc_html( $page_desc ); ?></p>
        <?php endif; ?>
    </div>
</div>

<section class="section">
    <div class="container">
        <div style="display:grid;grid-template-columns:<?php echo $page_sidebar ? '1fr 320px' : '1fr'; ?>;gap:4rem;align-items:start;">
            <div>
                <?php foreach ( $page_sections as $index => $section ) : ?>
                    <section class="reveal" style="margin-bottom:3rem;">
                        <?php if ( ! empty( $section['label'] ) ) : ?>
                            <div class="section-label"><?php echo esc_html( $section['label'] ); ?></div>
                        <?php endif; ?>
                        <?php if ( ! empty( $section['title'] ) ) : ?>
                            <h2 class="section-title" style="font-size:var(--size-2xl);margin-bottom:1rem;"><?php echo esc_html( $section['title'] ); ?></h2>
                        <?php endif; ?>
                        <?php if ( ! empty( $section['intro'] ) ) : ?>
                            <p style="font-size:var(--size-lg);line-height:1.8;color:var(--ink-mid);margin-bottom:1.5rem;"><?php echo esc_html( $section['intro'] ); ?></p>
                        <?php endif; ?>
                        <?php if ( ! empty( $section['body'] ) ) : ?>
                            <div style="font-size:var(--size-md);line-height:1.9;color:var(--ink-mid);">
                                <?php foreach ( $section['body'] as $paragraph ) : ?>
                                    <p><?php echo esc_html( $paragraph ); ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <?php if ( ! empty( $section['list'] ) ) : ?>
                            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;margin-top:1.5rem;">
                                <?php foreach ( $section['list'] as $item ) : ?>
                                    <div class="card" style="border-left:4px solid var(--ember-primary);">
                                        <div class="card__body" style="padding:1.25rem;">
                                            <p style="font-size:.92rem;line-height:1.65;color:var(--ink-mid);margin:0;"><?php echo esc_html( $item ); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <?php if ( ! empty( $section['cards'] ) ) : ?>
                            <div style="display:grid;grid-template-columns:repeat(<?php echo count( $section['cards'] ) > 2 ? 3 : 2; ?>,1fr);gap:1.25rem;margin-top:1.5rem;">
                                <?php foreach ( $section['cards'] as $card ) : ?>
                                    <?php
                                    $url = $card['url'] ?? '';
                                    if ( $url && '/' === substr( $url, 0, 1 ) && ':' !== substr( $url, 1, 1 ) ) {
                                        $url = function_exists( 'pngcje_home_url_via_resource_quick_path' )
                                            ? pngcje_home_url_via_resource_quick_path( $url )
                                            : home_url( $url );
                                    }
                                    ?>
                                    <<?php echo $url ? 'a' : 'div'; ?>
                                        <?php if ( $url ) : ?>href="<?php echo esc_url( $url ); ?>"<?php endif; ?>
                                        class="card"
                                        style="display:block;text-decoration:none;border-top:3px solid var(--ember-primary);">
                                        <div class="card__body">
                                            <h3 style="font-size:var(--size-base);font-weight:800;color:var(--ember-primary);margin-bottom:.5rem;"><?php echo esc_html( $card['title'] ?? '' ); ?></h3>
                                            <p style="font-size:.88rem;line-height:1.65;color:var(--ink-light);margin:0;"><?php echo esc_html( $card['desc'] ?? '' ); ?></p>
                                        </div>
                                    </<?php echo $url ? 'a' : 'div'; ?>>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <?php if ( ! empty( $section['board_members'] ) ) : ?>
                            <?php
                            $board_members = new WP_Query( [
                                'post_type'      => 'pngcje_board_member',
                                'posts_per_page' => -1,
                                'post_status'    => 'publish',
                                'orderby'        => [
                                    'menu_order' => 'ASC',
                                    'title'      => 'ASC',
                                ],
                            ] );
                            ?>
                            <?php if ( $board_members->have_posts() ) : ?>
                                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.25rem;margin-top:1.5rem;">
                                    <?php while ( $board_members->have_posts() ) : $board_members->the_post(); ?>
                                        <?php
                                        $role         = get_post_meta( get_the_ID(), '_pngcje_board_role', true );
                                        $organisation = get_post_meta( get_the_ID(), '_pngcje_board_organisation', true );
                                        $email        = get_post_meta( get_the_ID(), '_pngcje_board_email', true );
                                        $phone        = get_post_meta( get_the_ID(), '_pngcje_board_phone', true );
                                        ?>
                                        <article class="card" style="overflow:hidden;border-top:3px solid var(--ember-primary);">
                                            <?php if ( has_post_thumbnail() ) : ?>
                                                <div style="aspect-ratio:4/3;overflow:hidden;background:var(--ember-subtle);">
                                                    <?php the_post_thumbnail( 'pngcje-staff', [ 'alt' => get_the_title(), 'style' => 'width:100%;height:100%;object-fit:cover;object-position:top center;' ] ); ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="card__body">
                                                <h3 style="font-size:var(--size-base);font-weight:800;color:var(--ink);margin-bottom:.35rem;"><?php the_title(); ?></h3>
                                                <?php if ( $role ) : ?>
                                                    <p style="font-size:.88rem;font-weight:700;color:var(--ember-primary);line-height:1.45;margin-bottom:.35rem;"><?php echo esc_html( $role ); ?></p>
                                                <?php endif; ?>
                                                <?php if ( $organisation ) : ?>
                                                    <p style="font-size:.82rem;color:var(--ink-light);line-height:1.55;margin-bottom:.75rem;"><?php echo esc_html( $organisation ); ?></p>
                                                <?php endif; ?>
                                                <?php if ( has_excerpt() || get_the_content() ) : ?>
                                                    <div style="font-size:.82rem;color:var(--ink-mid);line-height:1.65;margin-bottom:.85rem;">
                                                        <?php echo wp_kses_post( wpautop( get_the_excerpt() ?: wp_trim_words( get_the_content(), 28 ) ) ); ?>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ( $email ) : ?>
                                                    <a href="mailto:<?php echo esc_attr( $email ); ?>" style="display:block;font-size:.78rem;color:var(--ink-light);word-break:break-word;margin-bottom:.25rem;">✉️ <?php echo esc_html( $email ); ?></a>
                                                <?php endif; ?>
                                                <?php if ( $phone ) : ?>
                                                    <a href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $phone ) ); ?>" style="display:block;font-size:.78rem;color:var(--ink-light);">📞 <?php echo esc_html( $phone ); ?></a>
                                                <?php endif; ?>
                                            </div>
                                        </article>
                                    <?php endwhile; wp_reset_postdata(); ?>
                                </div>
                            <?php else : ?>
                                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.25rem;margin-top:1.5rem;">
                                    <div class="card" style="border-top:3px solid var(--ember-primary);"><div class="card__body"><h3 style="font-size:var(--size-base);font-weight:800;color:var(--ember-primary);margin-bottom:.5rem;"><?php esc_html_e( 'Board Chair', 'pngcje' ); ?></h3><p style="font-size:.88rem;line-height:1.65;color:var(--ink-light);margin:0;"><?php esc_html_e( 'Hon. Chief Justice Sir Gibuma GCL, KBE, CSM, OBE - Chief Justice of Papua New Guinea', 'pngcje' ); ?></p></div></div>
                                    <div class="card" style="border-top:3px solid var(--ember-primary);"><div class="card__body"><h3 style="font-size:var(--size-base);font-weight:800;color:var(--ember-primary);margin-bottom:.5rem;"><?php esc_html_e( 'Board Secretary', 'pngcje' ); ?></h3><p style="font-size:.88rem;line-height:1.65;color:var(--ink-light);margin:0;"><?php esc_html_e( 'Hon. Justice John Carey - Judge Administrator, PNGCJE', 'pngcje' ); ?></p></div></div>
                                    <div class="card" style="border-top:3px solid var(--ember-primary);"><div class="card__body"><h3 style="font-size:var(--size-base);font-weight:800;color:var(--ember-primary);margin-bottom:.5rem;"><?php esc_html_e( 'Board Members', 'pngcje' ); ?></h3><p style="font-size:.88rem;line-height:1.65;color:var(--ink-light);margin:0;"><?php esc_html_e( 'Deputy Chief Justice, NJSS Secretary, Registrar, Chief Magistrate, Deputy Chief Magistrates, DJAG Secretary, PNG Law Society, Legal Training Institute and UPNG School of Law representatives.', 'pngcje' ); ?></p></div></div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </section>
                <?php endforeach; ?>

                <?php if ( $page_resource_type ) : ?>
                    <?php
                    $resource_terms = function_exists( 'pngcje_resource_type_query_terms' )
                        ? pngcje_resource_type_query_terms( $page_resource_type )
                        : [ $page_resource_type ];
                    $resources = new WP_Query( [
                        'post_type'      => 'pngcje_resource',
                        'posts_per_page' => -1,
                        'post_status'    => 'publish',
                        'tax_query'      => [ [
                            'taxonomy' => 'resource_type',
                            'field'    => 'slug',
                            'terms'    => $resource_terms,
                        ] ],
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                    ] );
                    ?>
                    <section class="reveal" style="margin-bottom:3rem;">
                        <div class="section-label"><?php echo esc_html( $page_resource_label ); ?></div>
                        <?php if ( $resources->have_posts() ) : ?>
                                <?php
                                $resource_grid_classes = 'resource-grid grid grid-3';
                                if ( 'tiles' === $page_resource_layout ) {
                                    $resource_grid_classes .= ' resource-grid--tiles';
                                }
                                ?>
                                <div class="<?php echo esc_attr( $resource_grid_classes ); ?>">
                                    <?php while ( $resources->have_posts() ) : $resources->the_post(); ?>
                                        <?php
                                        $year = get_post_meta( get_the_ID(), '_pngcje_resource_year', true );
                                        $link = get_permalink();
                                        ?>
                                        <a href="<?php echo esc_url( $link ); ?>" class="card resource-card">
                                            <?php if ( has_post_thumbnail() ) : ?>
                                                <span class="resource-card__media">
                                                    <?php the_post_thumbnail( 'medium_large', [ 'loading' => 'lazy', 'decoding' => 'async', 'class' => 'resource-card__image', 'alt' => esc_attr( wp_strip_all_tags( get_the_title() ) ) ] ); ?>
                                                </span>
                                            <?php else : ?>
                                                <div class="resource-card__placeholder">
                                                    <span style="font-size:2rem;color:var(--ember-primary);">PDF</span>
                                                </div>
                                            <?php endif; ?>
                                            <div class="card__body">
                                                <?php if ( $year ) : ?>
                                                    <span class="badge badge--gray" style="margin-bottom:.35rem;display:inline-block;"><?php echo esc_html( $year ); ?></span>
                                                <?php endif; ?>
                                                <h3 style="font-size:var(--size-base);font-weight:800;color:var(--ink);margin:0;"><?php the_title(); ?></h3>
                                            </div>
                                        </a>
                                    <?php endwhile; wp_reset_postdata(); ?>
                                </div>
                        <?php else : ?>
                            <div style="background:var(--ember-subtle);border-radius:var(--radius-lg);padding:2rem;text-align:center;border:1.5px dashed rgba(212,88,26,.3);">
                                <p style="color:var(--ink-light);margin:0;"><?php esc_html_e( 'Documents will appear here once they are uploaded to the resource library.', 'pngcje' ); ?></p>
                            </div>
                        <?php endif; ?>
                    </section>
                <?php endif; ?>
            </div>

            <?php if ( $page_sidebar ) : ?>
                <aside>
                    <?php
                    if ( 'ourwork' === $page_sidebar ) {
                        get_template_part( 'template-parts/sidebar', 'ourwork' );
                    } elseif ( 'about' === $page_sidebar ) {
                        get_template_part( 'template-parts/sidebar', 'about' );
                    }
                    ?>
                </aside>
            <?php endif; ?>
        </div>
    </div>
</section>
