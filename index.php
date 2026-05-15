<?php
/**
 * index.php — Blog / News Archive fallback
 */
get_header();
?>
<div <?php pngcje_page_hero_attrs(); ?>>
    <div class="container">
        <div class="page-hero__eyebrow"><?php pngcje_breadcrumbs(); ?></div>
        <h1 class="page-hero__title">
            <?php is_home() ? esc_html_e( 'News & Updates', 'pngcje' ) : the_archive_title(); ?>
        </h1>
    </div>
</div>
<section class="section">
    <div class="container">
        <div class="grid grid-3">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <article class="card reveal">
                <?php if ( has_post_thumbnail() ) : ?>
                <div class="card__image">
                    <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                        <?php the_post_thumbnail( 'pngcje-card', [ 'alt' => '' ] ); ?>
                    </a>
                </div>
                <?php endif; ?>
                <div class="card__body">
                    <div class="card__meta">
                        <span class="card__date"><?php echo get_the_date( 'j M Y' ); ?></span>
                    </div>
                    <h2 class="card__title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    <p class="card__excerpt"><?php echo esc_html( pngcje_excerpt( null, 20 ) ); ?></p>
                    <a href="<?php the_permalink(); ?>" class="btn btn-ghost btn-arrow text-sm">
                        <?php esc_html_e( 'Read More', 'pngcje' ); ?>
                    </a>
                </div>
            </article>
            <?php endwhile; else : ?>
            <p style="grid-column:1/-1;text-align:center;color:var(--ink-light);padding:3rem 0;">
                <?php esc_html_e( 'No posts found.', 'pngcje' ); ?>
            </p>
            <?php endif; ?>
        </div>
        <div style="margin-top:3rem;display:flex;justify-content:center;gap:1rem;">
            <?php the_posts_pagination( [
                'prev_text' => '&larr; ' . __( 'Newer', 'pngcje' ),
                'next_text' => __( 'Older', 'pngcje' ) . ' &rarr;',
                'class'     => 'pagination',
            ] ); ?>
        </div>
    </div>
</section>
<?php get_footer(); ?>
