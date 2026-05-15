<?php
/**
 * page.php — Default page template
 */
get_header();
while ( have_posts() ) : the_post();
?>
<div <?php pngcje_page_hero_attrs(); ?>>
    <div class="container">
        <div class="page-hero__eyebrow"><?php pngcje_breadcrumbs(); ?></div>
        <h1 class="page-hero__title"><?php the_title(); ?></h1>
        <?php if ( has_excerpt() ) : ?>
        <p class="page-hero__desc"><?php the_excerpt(); ?></p>
        <?php endif; ?>
    </div>
</div>
<section class="section">
    <div class="container" style="max-width:900px;">
        <div class="entry-content" style="font-size:var(--size-md);line-height:var(--leading-loose);color:var(--ink-mid);">
            <?php the_content(); ?>
        </div>
    </div>
</section>
<?php
endwhile;
get_footer();
