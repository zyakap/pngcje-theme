<?php
get_header();

$newsletters_page    = get_page_by_path( 'newsletters' );
$newsletters_page_id = $newsletters_page ? $newsletters_page->ID : null;
?>

<div <?php pngcje_page_hero_attrs( $newsletters_page_id ); ?>>
    <div class="container">
        <div class="page-hero__eyebrow"><?php pngcje_breadcrumbs(); ?></div>
        <h1 class="page-hero__title"><?php esc_html_e( 'Newsletters', 'pngcje' ); ?></h1>
        <p class="page-hero__desc"><?php esc_html_e( 'Read the latest PNGCJE newsletters, updates and publications.', 'pngcje' ); ?></p>
    </div>
</div>

<section class="section">
    <div class="container">
        <?php if ( have_posts() ) : ?>
        <div class="grid grid-4" style="gap:1.5rem;">
            <?php while ( have_posts() ) : the_post();
                $downloads = function_exists( 'pngcje_newsletter_get_downloads' ) ? pngcje_newsletter_get_downloads( get_the_ID() ) : [];
            ?>
            <a href="<?php the_permalink(); ?>" class="card reveal" style="display:flex;flex-direction:column;text-decoration:none;overflow:hidden;">
                <?php if ( has_post_thumbnail() ) : ?>
                <span class="card__media" style="overflow:hidden;background:var(--cream);display:block;">
                    <?php echo get_the_post_thumbnail( get_the_ID(), 'pngcje-card', [ 'loading' => 'lazy', 'decoding' => 'async', 'style' => 'width:100%;height:auto;display:block;', 'alt' => esc_attr( wp_strip_all_tags( get_the_title() ) ) ] ); ?>
                </span>
                <?php else : ?>
                <span style="display:flex;align-items:center;justify-content:center;background:var(--cream);font-size:3rem;padding:2rem 0;" aria-hidden="true">📰</span>
                <?php endif; ?>
                <span class="card__body" style="display:flex;flex-direction:column;gap:.75rem;">
                    <span class="badge badge--gold" style="align-self:flex-start;"><?php echo esc_html( get_the_date() ); ?></span>
                    <span style="display:block;font-size:var(--size-lg);font-weight:800;color:var(--green-dark);line-height:1.25;"><?php the_title(); ?></span>
                    <?php if ( has_excerpt() ) : ?>
                    <span style="display:block;color:var(--ink-mid);font-size:.9rem;line-height:1.6;"><?php echo esc_html( pngcje_excerpt( null, 18 ) ); ?></span>
                    <?php endif; ?>
                    <span style="display:flex;align-items:center;justify-content:space-between;margin-top:auto;color:var(--ember-primary);font-size:.8rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em;">
                        <span><?php esc_html_e( 'Read newsletter', 'pngcje' ); ?></span>
                        <?php if ( $downloads ) : ?><span><?php echo esc_html( sprintf( _n( '%d download', '%d downloads', count( $downloads ), 'pngcje' ), count( $downloads ) ) ); ?></span><?php endif; ?>
                    </span>
                </span>
            </a>
            <?php endwhile; ?>
        </div>
        <div style="margin-top:2rem;">
            <?php the_posts_pagination(); ?>
        </div>
        <?php else : ?>
        <div class="card"><div class="card__body"><p style="margin:0;color:var(--ink-mid);"><?php esc_html_e( 'No newsletters have been published yet.', 'pngcje' ); ?></p></div></div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
