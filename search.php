<?php
/**
 * search.php — Search Results
 */

get_header();

$query = get_search_query();
$count = $wp_query->found_posts;
?>

<div <?php pngcje_page_hero_attrs(); ?>>
    <div class="container">
        <div class="page-hero__eyebrow"><?php pngcje_breadcrumbs(); ?></div>
        <h1 class="page-hero__title">
            <?php
            if ( $query ) {
                printf( esc_html__( 'Results for: "%s"', 'pngcje' ), esc_html( $query ) );
            } else {
                esc_html_e( 'Search', 'pngcje' );
            }
          ?>
        </h1>
        <?php if ( $count > 0 ) : ?>
        <p class="page-hero__desc">
            <?php printf( esc_html( _n( '%d result found.', '%d results found.', $count, 'pngcje' ) ), esc_html( $count ) ); ?>
        </p>
        <?php endif; ?>
    </div>
</div>

<section class="section">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 320px;gap:4rem;align-items:start;">
            <div>

                <!-- Search form -->
                <form role="search" method="get" action="<?php echo esc_url( home_url('/') ); ?>" style="margin-bottom:3rem;">
                    <div style="display:flex;gap:0.75rem;">
                        <label for="search-results-input" class="sr-only"><?php esc_html_e( 'Search', 'pngcje' ); ?></label>
                        <input
                            type="search"
                            id="search-results-input"
                            name="s"
                            value="<?php echo esc_attr( $query ); ?>"
                            placeholder="<?php esc_attr_e( 'Search PNGCJE…', 'pngcje' ); ?>"
                            style="flex:1;border:1.5px solid var(--border);border-radius:4px;padding:0.75rem 1.25rem;font-size:1rem;font-family:inherit;"
                        >
                        <button type="submit" class="btn btn-primary" aria-label="<?php esc_attr_e( 'Search', 'pngcje' ); ?>">
                            🔍 <?php esc_html_e( 'Search', 'pngcje' ); ?>
                        </button>
                    </div>
                </form>

                <!-- Results -->
                <?php if ( have_posts() ) : ?>
                <div style="display:flex;flex-direction:column;gap:2rem;">
                    <?php while ( have_posts() ) : the_post(); ?>
                    <article style="padding-bottom:2rem;border-bottom:1px solid var(--border-light);">
                        <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.5rem;">
                            <?php
                            $post_type = get_post_type();
                            $type_labels = [
                                'post'             => __( 'News', 'pngcje' ),
                                'page'             => __( 'Page', 'pngcje' ),
                                'pngcje_resource'  => __( 'Resource', 'pngcje' ),
                                'tribe_events'     => __( 'Event', 'pngcje' ),
                                'member'           => __( 'Staff', 'pngcje' ),
                            ];
                            $type_label = $type_labels[ $post_type ] ?? ucfirst( $post_type );
                          ?>
                            <span class="badge badge--green"><?php echo esc_html( $type_label ); ?></span>
                            <span style="font-size:0.75rem;color:var(--ink-light);"><?php echo get_the_date('j M Y'); ?></span>
                        </div>
                        <h2 style="font-size:var(--size-xl);margin-bottom:0.5rem;">
                            <a href="<?php the_permalink(); ?>" style="color:var(--ink);transition:color 0.2s;" onmouseover="this.style.color='var(--green-dark)';" onmouseout="this.style.color='var(--ink)';">
                                <?php the_title(); ?>
                            </a>
                        </h2>
                        <p style="font-size:0.9rem;color:var(--ink-light);line-height:1.6;margin-bottom:0.75rem;">
                            <?php echo esc_html( pngcje_excerpt( null, 30 ) ); ?>
                        </p>
                        <div style="display:flex;align-items:center;gap:1rem;">
                            <a href="<?php the_permalink(); ?>" class="btn btn-ghost btn-arrow text-sm">
                                <?php esc_html_e( 'View', 'pngcje' ); ?>
                            </a>
                            <span style="font-size:0.75rem;color:var(--ink-light);">
                                <?php echo esc_url( get_permalink() ); ?>
                            </span>
                        </div>
                    </article>
                    <?php endwhile; ?>
                </div>

                <div style="margin-top:3rem;display:flex;justify-content:center;">
                    <?php the_posts_pagination( [
                        'prev_text' => '&larr; ' . __( 'Previous', 'pngcje' ),
                        'next_text' => __( 'Next', 'pngcje' ) . ' &rarr;',
                    ] ); ?>
                </div>

                <?php else : ?>

                <div style="text-align:center;padding:4rem 2rem;">
                    <div style="font-size:4rem;margin-bottom:1rem;" aria-hidden="true">🔍</div>
                    <h2 style="font-size:var(--size-2xl);color:var(--ink-mid);margin-bottom:0.75rem;">
                        <?php esc_html_e( 'No results found', 'pngcje' ); ?>
                    </h2>
                    <p style="color:var(--ink-light);max-width:400px;margin:0 auto 2rem;">
                        <?php printf( esc_html__( 'No results for "%s". Try a different search term or browse our resources below.', 'pngcje' ), esc_html( $query ) ); ?>
                    </p>
                    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
                        <a href="<?php echo esc_url( home_url('/our-work/') ); ?>" class="btn btn-primary">
                            <?php esc_html_e( 'Browse Resources', 'pngcje' ); ?>
                        </a>
                        <a href="<?php echo esc_url( home_url('/news/') ); ?>" class="btn btn-outline">
                            <?php esc_html_e( 'Latest News', 'pngcje' ); ?>
                        </a>
                    </div>
                </div>

                <?php endif; ?>

            </div>

            <!-- Sidebar -->
            <aside>
                <div class="card" style="border-top:3px solid var(--green-dark);margin-bottom:1.5rem;">
                    <div class="card__body">
                        <h3 style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--green-dark);margin-bottom:1rem;">
                            <?php esc_html_e( 'Browse By Type', 'pngcje' ); ?>
                        </h3>
                        <?php
                        $browse = [
                            [ '📰', __('News & Updates',  'pngcje'), home_url('/news/') ],
                            [ '📖', __('Bench Books',     'pngcje'), home_url('/bench-books/') ],
                            [ '⚖️', __('Case Notes',      'pngcje'), home_url('/papua-new-guinea-supreme-court-national-court-case-notes/') ],
                            [ '📅', __('Events',          'pngcje'), home_url('/events/') ],
                            [ '👤', __('Our Staff',       'pngcje'), home_url('/about/our-staff/') ],
                        ];
                        echo '<ul style="display:flex;flex-direction:column;gap:0.4rem;">';
                        foreach ( $browse as [$icon, $label, $url] ) {
                            echo '<li><a href="' . esc_url($url) . '" style="display:flex;align-items:center;gap:0.6rem;font-size:0.875rem;color:var(--ink-mid);padding:0.5rem 0.5rem;border-radius:4px;transition:all 0.2s;" onmouseover="this.style.background=\'var(--green-subtle)\';this.style.color=\'var(--green-dark)\';" onmouseout="this.style.background=\'transparent\';this.style.color=\'var(--ink-mid)\';"><span>' . esc_html($icon) . '</span>' . esc_html($label) . '</a></li>';
                        }
                        echo '</ul>';
                      ?>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php get_footer(); ?>
