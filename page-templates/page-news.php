<?php
/**
 * Template Name: News Landing
 * Refreshed news archive landing page.
 */
$request_path = isset( $_SERVER['REQUEST_URI'] ) ? trim( (string) parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ), '/' ) : '';
if ( preg_match( '#^newsletters(?:/page/[0-9]+)?$#', $request_path ) ) {
    require get_template_directory() . '/page-templates/page-newsletters.php';
    return;
}

get_header();
$paged = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
$news_query = new WP_Query( [
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 9,
    'paged'          => $paged,
] );
?>
<div <?php pngcje_page_hero_attrs(); ?>>
    <div class="container">
        <div class="page-hero__eyebrow"><?php pngcje_breadcrumbs(); ?></div>
        <h1 class="page-hero__title"><?php esc_html_e( 'News', 'pngcje' ); ?></h1>
        <p class="page-hero__desc">Latest PNGCJE training news, judicial education updates, regional engagement and institutional announcements.</p>
    </div>
</div>
<section class="section bg-white">
    <div class="container">
        <?php if ( $news_query->have_posts() ) : ?>
            <div class="news-grid">
                <?php while ( $news_query->have_posts() ) : $news_query->the_post(); ?>
                    <article class="card reveal">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="card__image"><a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php the_post_thumbnail( 'pngcje-card', [ 'alt' => '' ] ); ?></a></div>
                        <?php endif; ?>
                        <div class="card__body">
                            <div class="card__meta"><span class="card__date"><?php echo esc_html( get_the_date( 'j M Y' ) ); ?></span></div>
                            <h2 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            <p class="card__excerpt"><?php echo esc_html( pngcje_excerpt( null, 22 ) ); ?></p>
                            <a href="<?php the_permalink(); ?>" class="btn btn-ghost btn-arrow text-sm">Read More</a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            <div style="margin-top:3rem;text-align:center;">
                <?php echo paginate_links( [ 'total' => $news_query->max_num_pages, 'current' => $paged ] ); ?>
            </div>
            <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <div class="card"><div class="card__body" style="text-align:center;"><?php esc_html_e( 'No news items are available yet.', 'pngcje' ); ?></div></div>
        <?php endif; ?>
    </div>
</section>
<?php get_footer();
