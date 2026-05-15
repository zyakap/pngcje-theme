<?php
/**
 * single.php — Single post (News article)
 */
get_header();
while ( have_posts() ) : the_post();
?>
<div <?php pngcje_page_hero_attrs(); ?>>
    <div class="container">
        <div class="page-hero__eyebrow"><?php pngcje_breadcrumbs(); ?></div>
        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1rem;">
            <span class="card__date" style="color:var(--gold-light);"><?php echo get_the_date( 'j F Y' ); ?></span>
            <?php
            $cats = get_the_category();
            if ( $cats ) echo '<span class="badge badge--gold">' . esc_html( $cats[0]->name ) . '</span>';
          ?>
        </div>
        <h1 class="page-hero__title"><?php the_title(); ?></h1>
    </div>
</div>
<section class="section">
    <div class="container" style="max-width:860px;">
        <?php if ( has_post_thumbnail() ) : ?>
        <div style="margin-bottom:2.5rem;border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-lg);">
            <?php the_post_thumbnail( 'pngcje-wide', [ 'style' => 'width:100%;aspect-ratio:16/7;object-fit:cover;' ] ); ?>
        </div>
        <?php endif; ?>
        <div class="entry-content" style="font-size:var(--size-md);line-height:var(--leading-loose);color:var(--ink-mid);">
            <?php the_content(); ?>
        </div>
        <div style="margin-top:3rem;padding-top:2rem;border-top:1px solid var(--border-light);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
            <?php previous_post_link( '<span class="btn btn-outline">&larr; %link</span>' ); ?>
            <a href="<?php echo esc_url( home_url( '/news/' ) ); ?>" class="btn btn-ghost">
                <?php esc_html_e( '← Back to News', 'pngcje' ); ?>
            </a>
            <?php next_post_link( '<span class="btn btn-outline">%link &rarr;</span>' ); ?>
        </div>
    </div>
</section>
<?php
endwhile;
get_footer();
