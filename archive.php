<?php
/**
 * archive.php — News & Posts Archive with sidebar
 */

get_header();

$current_cat = get_queried_object();
$is_category = is_category();
?>

<div <?php pngcje_page_hero_attrs(); ?>>
    <div class="container">
        <div class="page-hero__eyebrow"><?php pngcje_breadcrumbs(); ?></div>
        <h1 class="page-hero__title">
            <?php
            if ( is_category() ) {
                echo esc_html( single_cat_title( '', false ) );
            } elseif ( is_tag() ) {
                echo esc_html( single_tag_title( __( 'Tag: ', 'pngcje' ), false ) );
            } elseif ( is_date() ) {
                echo esc_html( get_the_date( 'F Y' ) );
            } else {
                esc_html_e( 'News & Updates', 'pngcje' );
            }
          ?>
        </h1>
        <?php if ( is_category() && category_description() ) : ?>
        <p class="page-hero__desc"><?php echo esc_html( category_description() ); ?></p>
        <?php endif; ?>
    </div>
</div>

<section class="section">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 320px;gap:4rem;align-items:start;">

            <!-- Main: Posts Grid -->
            <div>
                <!-- Category Filter Pills -->
                <div class="resources-filter" style="margin-bottom:2.5rem;">
                    <a href="<?php echo esc_url( get_permalink( get_option('page_for_posts') ) ?: home_url('/news/') ); ?>"
                        class="filter-btn <?php echo ! is_category() ? 'active' : ''; ?>">
                        <?php esc_html_e( 'All News', 'pngcje' ); ?>
                    </a>
                    <?php
                    $cats = get_categories( [ 'hide_empty' => true, 'number' => 8 ] );
                    foreach ( $cats as $cat ) :
                        $active = ( is_category( $cat->term_id ) ) ? 'active' : '';
                  ?>
                    <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"
                        class="filter-btn <?php echo esc_attr( $active ); ?>">
                        <?php echo esc_html( $cat->name ); ?>
                        <span style="opacity:0.5;">(<?php echo esc_html( $cat->count ); ?>)</span>
                    </a>
                    <?php endforeach; ?>
                </div>

                <!-- Posts -->
                <?php if ( have_posts() ) : ?>
                <div style="display:flex;flex-direction:column;gap:2rem;">
                    <?php while ( have_posts() ) : the_post(); ?>
                    <article class="card" style="display:grid;grid-template-columns:280px 1fr;min-height:200px;">
                        <?php if ( has_post_thumbnail() ) : ?>
                        <div style="overflow:hidden;border-radius:var(--radius-md) 0 0 var(--radius-md);">
                            <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                                <?php the_post_thumbnail( 'pngcje-card-sm', [
                                    'alt'   => '',
                                    'style' => 'width:100%;height:100%;object-fit:cover;transition:transform 0.4s ease;',
                                ] ); ?>
                            </a>
                        </div>
                        <?php endif; ?>
                        <div class="card__body" style="display:flex;flex-direction:column;justify-content:space-between;">
                            <div>
                                <div class="card__meta" style="margin-bottom:0.75rem;">
                                    <span class="card__date"><?php echo get_the_date( 'j F Y' ); ?></span>
                                    <?php
                                    $cats = get_the_category();
                                    if ( $cats ) echo '<a href="' . esc_url( get_category_link($cats[0]->term_id) ) . '" class="card__category">' . esc_html($cats[0]->name) . '</a>';
                                  ?>
                                </div>
                                <h2 class="card__title" style="font-size:var(--size-xl);margin-bottom:0.75rem;">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                                <p class="card__excerpt"><?php echo esc_html( pngcje_excerpt( null, 25 ) ); ?></p>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="btn btn-ghost btn-arrow text-sm">
                                <?php esc_html_e( 'Read More', 'pngcje' ); ?>
                            </a>
                        </div>
                    </article>
                    <?php endwhile; ?>
                </div>

                <!-- Pagination -->
                <div style="margin-top:3rem;display:flex;justify-content:center;">
                    <?php
                    the_posts_pagination( [
                        'mid_size'  => 2,
                        'prev_text' => '&larr; ' . __( 'Previous', 'pngcje' ),
                        'next_text' => __( 'Next', 'pngcje' ) . ' &rarr;',
                    ] );
                  ?>
                </div>

                <?php else : ?>
                <div style="text-align:center;padding:5rem 2rem;">
                    <div style="font-size:3rem;margin-bottom:1rem;">📰</div>
                    <h2 style="font-size:var(--size-xl);color:var(--ink-mid);margin-bottom:0.5rem;">
                        <?php esc_html_e( 'No posts found', 'pngcje' ); ?>
                    </h2>
                    <p style="color:var(--ink-light);"><?php esc_html_e( 'There are no news items at this time. Please check back soon.', 'pngcje' ); ?></p>
                    <a href="<?php echo esc_url( home_url('/') ); ?>" class="btn btn-primary" style="margin-top:1.5rem;">
                        <?php esc_html_e( '← Return Home', 'pngcje' ); ?>
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <aside>
                <!-- Search -->
                <div class="card" style="margin-bottom:1.5rem;">
                    <div class="card__body">
                        <h3 style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--green-dark);margin-bottom:1rem;">
                            <?php esc_html_e( 'Search News', 'pngcje' ); ?>
                        </h3>
                        <form role="search" method="get" action="<?php echo esc_url( home_url('/') ); ?>">
                            <div style="display:flex;gap:0.5rem;">
                                <label for="sidebar-search" class="sr-only"><?php esc_html_e( 'Search', 'pngcje' ); ?></label>
                                <input
                                    type="search"
                                    id="sidebar-search"
                                    name="s"
                                    placeholder="<?php esc_attr_e( 'Search…', 'pngcje' ); ?>"
                                    style="flex:1;border:1.5px solid var(--border);border-radius:4px;padding:0.5rem 0.75rem;font-size:0.875rem;font-family:inherit;"
                                >
                                <button type="submit" class="btn btn-primary btn-sm" aria-label="<?php esc_attr_e( 'Search', 'pngcje' ); ?>">→</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Recent Posts -->
                <div class="card" style="margin-bottom:1.5rem;">
                    <div class="card__body">
                        <h3 style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--green-dark);margin-bottom:1rem;">
                            <?php esc_html_e( 'Recent News', 'pngcje' ); ?>
                        </h3>
                        <?php
                        $recent = new WP_Query( [ 'posts_per_page' => 5, 'post_status' => 'publish' ] );
                        if ( $recent->have_posts() ) :
                      ?>
                        <ul style="display:flex;flex-direction:column;gap:1rem;">
                            <?php while ( $recent->have_posts() ) : $recent->the_post(); ?>
                            <li style="display:flex;gap:0.75rem;align-items:flex-start;">
                                <?php if ( has_post_thumbnail() ) : ?>
                                <a href="<?php the_permalink(); ?>" style="flex-shrink:0;width:56px;height:56px;border-radius:4px;overflow:hidden;display:block;">
                                    <?php the_post_thumbnail( 'thumbnail', [ 'style' => 'width:56px;height:56px;object-fit:cover;', 'alt' => '' ] ); ?>
                                </a>
                                <?php endif; ?>
                                <div>
                                    <a href="<?php the_permalink(); ?>" style="font-size:0.8rem;font-weight:600;color:var(--ink);line-height:1.4;display:block;transition:color 0.2s;" onmouseover="this.style.color='var(--green-dark)';" onmouseout="this.style.color='var(--ink)';">
                                        <?php the_title(); ?>
                                    </a>
                                    <span style="font-size:0.7rem;color:var(--ink-light);"><?php echo get_the_date('j M Y'); ?></span>
                                </div>
                            </li>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Categories -->
                <div class="card" style="margin-bottom:1.5rem;">
                    <div class="card__body">
                        <h3 style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--green-dark);margin-bottom:1rem;">
                            <?php esc_html_e( 'Categories', 'pngcje' ); ?>
                        </h3>
                        <ul style="display:flex;flex-direction:column;gap:0.4rem;">
                            <?php
                            $cats = get_categories( [ 'hide_empty' => true ] );
                            foreach ( $cats as $cat ) :
                          ?>
                            <li>
                                <a href="<?php echo esc_url( get_category_link($cat->term_id) ); ?>"
                                    style="display:flex;justify-content:space-between;align-items:center;font-size:0.875rem;color:var(--ink-mid);padding:0.35rem 0;border-bottom:1px solid var(--border-light);transition:color 0.2s;"
                                    onmouseover="this.style.color='var(--green-dark)';"
                                    onmouseout="this.style.color='var(--ink-mid)';"
                                >
                                    <span><span style="color:var(--gold-primary);margin-right:0.4rem;">›</span><?php echo esc_html($cat->name); ?></span>
                                    <span style="font-size:0.7rem;background:var(--surface);padding:0.1rem 0.4rem;border-radius:99px;color:var(--ink-light);"><?php echo esc_html($cat->count); ?></span>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <!-- Quick Resources -->
                <div class="card" style="border-top:3px solid var(--gold-primary);">
                    <div class="card__body">
                        <h3 style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--green-dark);margin-bottom:1rem;">
                            <?php esc_html_e( 'Quick Resources', 'pngcje' ); ?>
                        </h3>
                        <?php
                        $ql = [
                            [ '📖', __( 'Bench Books',        'pngcje' ), pngcje_get_resource_type_url( 'bench-books' ) ],
                            [ '📗', __( 'Judicial Handbook',  'pngcje' ), pngcje_get_resource_type_url( 'judicial-handbook' ) ],
                            [ '⚖️', __( 'Case Notes',         'pngcje' ), pngcje_get_resource_type_url( 'case-notes' ) ],
                            [ '📅', __( 'Training Calendar',  'pngcje' ), home_url('/training-calendar/') ],
                        ];
                        echo '<ul style="display:flex;flex-direction:column;gap:0.5rem;">';
                        foreach ( $ql as [$icon, $label, $url] ) {
                            echo '<li><a href="' . esc_url($url) . '" style="display:flex;align-items:center;gap:0.5rem;font-size:0.875rem;color:var(--ink-mid);padding:0.4rem 0;transition:color 0.2s;" onmouseover="this.style.color=\'var(--green-dark)\';" onmouseout="this.style.color=\'var(--ink-mid)\';"><span>' . esc_html($icon) . '</span>' . esc_html($label) . '</a></li>';
                        }
                        echo '</ul>';
                      ?>
                    </div>
                </div>

            </aside>
        </div>
    </div>
</section>

<style>
@media (max-width: 1024px) {
    .news-archive-grid { grid-template-columns: 1fr !important; }
}
@media (max-width: 768px) {
    .news-archive-layout { grid-template-columns: 1fr !important; }
    .news-post-card { grid-template-columns: 1fr !important; }
}
</style>

<?php get_footer(); ?>
