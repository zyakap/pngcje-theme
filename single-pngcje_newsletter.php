<?php
get_header();
while ( have_posts() ) : the_post();
$downloads = function_exists( 'pngcje_newsletter_get_downloads' ) ? pngcje_newsletter_get_downloads( get_the_ID() ) : [];
$sent_at   = get_post_meta( get_the_ID(), '_pngcje_newsletter_sent_at', true );
?>

<div <?php pngcje_page_hero_attrs(); ?>>
    <div class="container">
        <div class="page-hero__eyebrow"><?php pngcje_breadcrumbs(); ?></div>
        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1rem;flex-wrap:wrap;">
            <span class="badge badge--gold"><?php echo esc_html( get_the_date() ); ?></span>
            <?php if ( $sent_at ) : ?><span class="badge" style="background:rgba(255,255,255,.15);color:#fff;"><?php esc_html_e( 'Published Newsletter', 'pngcje' ); ?></span><?php endif; ?>
        </div>
        <h1 class="page-hero__title"><?php the_title(); ?></h1>
        <?php if ( has_excerpt() ) : ?><p class="page-hero__desc"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
    </div>
</div>

<section class="section">
    <div class="container resource-single-layout">
        <div class="resource-single-main">
            <?php if ( has_post_thumbnail() ) : ?>
            <figure class="resource-single__figure">
                <?php the_post_thumbnail( 'pngcje-wide', [ 'loading' => 'eager', 'decoding' => 'async', 'alt' => esc_attr( wp_strip_all_tags( get_the_title() ) ) ] ); ?>
            </figure>
            <?php endif; ?>

            <?php if ( $downloads ) : ?>
            <div class="resource-single__cta-strip" role="region" aria-label="<?php esc_attr_e( 'Newsletter downloads', 'pngcje' ); ?>">
                <span class="resource-single__cta-strip-meta"><span aria-hidden="true">📥</span><?php echo esc_html( sprintf( _n( '%d download available', '%d downloads available', count( $downloads ), 'pngcje' ), count( $downloads ) ) ); ?></span>
                <a href="<?php echo esc_url( $downloads[0]['url'] ); ?>" class="btn btn-primary" target="_blank" rel="noopener noreferrer" download><?php echo esc_html( $downloads[0]['label'] ); ?></a>
            </div>
            <?php endif; ?>

            <article class="resource-single__article resource-single__prose entry-content">
                <?php the_content(); ?>
            </article>

            <nav class="resource-single__nav-links" aria-label="<?php esc_attr_e( 'Newsletter navigation', 'pngcje' ); ?>">
                <a href="<?php echo esc_url( home_url( '/newsletters/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'All newsletters', 'pngcje' ); ?></a>
            </nav>
        </div>

        <aside class="resource-single__aside">
            <div class="card" style="border-top:4px solid var(--ember-primary);margin-bottom:1.5rem;">
                <div class="card__body">
                    <h3 style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--ember-primary);margin-bottom:1.25rem;"><?php esc_html_e( 'Newsletter details', 'pngcje' ); ?></h3>
                    <dl style="display:flex;flex-direction:column;gap:.85rem;">
                        <div style="display:flex;justify-content:space-between;align-items:baseline;font-size:.85rem;border-bottom:1px solid var(--border-light);padding-bottom:.85rem;"><dt style="color:var(--ink-light);font-weight:500;"><?php esc_html_e( 'Published', 'pngcje' ); ?></dt><dd style="font-weight:700;color:var(--ink);text-align:right;"><?php echo esc_html( get_the_date() ); ?></dd></div>
                        <div style="display:flex;justify-content:space-between;align-items:baseline;font-size:.85rem;border-bottom:1px solid var(--border-light);padding-bottom:.85rem;"><dt style="color:var(--ink-light);font-weight:500;"><?php esc_html_e( 'Downloads', 'pngcje' ); ?></dt><dd style="font-weight:700;color:var(--ink);text-align:right;"><?php echo esc_html( count( $downloads ) ); ?></dd></div>
                    </dl>
                </div>
            </div>

            <?php if ( $downloads ) : ?>
            <div class="card" style="border-left:4px solid var(--gold-primary);">
                <div class="card__body">
                    <h3 style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--ink);margin-bottom:1rem;"><?php esc_html_e( 'Download links', 'pngcje' ); ?></h3>
                    <ul style="display:flex;flex-direction:column;gap:.65rem;">
                        <?php foreach ( $downloads as $download ) : ?>
                        <li><a href="<?php echo esc_url( $download['url'] ); ?>" target="_blank" rel="noopener noreferrer" download style="display:flex;align-items:flex-start;gap:.5rem;font-size:.85rem;color:var(--ink-mid);text-decoration:none;"><span style="color:var(--gold-primary);flex-shrink:0;">›</span><span><?php echo esc_html( $download['label'] ); ?></span></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </aside>
    </div>
</section>

<?php endwhile; get_footer(); ?>
