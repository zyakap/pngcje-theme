<?php
/**
 * archive-tribe_events.php
 * The Events Calendar — Events Archive / Training Calendar
 */

get_header();
?>

<div <?php pngcje_page_hero_attrs(); ?>>
    <div class="container">
        <div class="page-hero__eyebrow"><?php pngcje_breadcrumbs(); ?></div>
        <h1 class="page-hero__title"><?php esc_html_e( 'Training Calendar & Events', 'pngcje' ); ?></h1>
        <p class="page-hero__desc">
            <?php esc_html_e( 'Browse upcoming judicial education programs, training sessions, conferences and events delivered by the PNGCJE.', 'pngcje' ); ?>
        </p>
    </div>
</div>

<section class="section">
    <div class="container">

        <!-- View Toggle -->
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2.5rem;flex-wrap:wrap;gap:1rem;">
            <div class="resources-filter" style="margin:0;">
                <a href="<?php echo esc_url( tribe_get_events_link() ); ?>" class="filter-btn active">
                    <?php esc_html_e( 'All Events', 'pngcje' ); ?>
                </a>
                <?php
                // If TEC categories exist
                if ( function_exists( 'tribe_get_event_categories' ) ) :
                    $event_cats = get_terms( [ 'taxonomy' => 'tribe_events_cat', 'hide_empty' => true ] );
                    if ( $event_cats && ! is_wp_error( $event_cats ) ) :
                        foreach ( $event_cats as $ec ) :
              ?>
                <a href="<?php echo esc_url( get_term_link( $ec ) ); ?>" class="filter-btn">
                    <?php echo esc_html( $ec->name ); ?>
                </a>
                <?php
                        endforeach;
                    endif;
                endif;
              ?>
            </div>
            <div style="font-size:0.8rem;color:var(--ink-light);">
                <?php printf( esc_html__( '%d upcoming event(s)', 'pngcje' ), wp_count_posts('tribe_events')->publish ?? 0 ); ?>
            </div>
        </div>

        <?php if ( have_posts() ) : ?>

        <!-- Upcoming Events Grid -->
        <div style="display:flex;flex-direction:column;gap:2rem;margin-bottom:4rem;">
            <?php while ( have_posts() ) : the_post();
                $start_date = tribe_get_start_date( null, false, 'l, j F Y' );
                $start_time = tribe_get_start_date( null, false, 'g:i A' );
                $end_time   = tribe_get_end_date(   null, false, 'g:i A' );
                $venue      = tribe_get_venue();
                $cost       = tribe_get_cost();
                $day        = tribe_get_start_date( null, false, 'j' );
                $month      = tribe_get_start_date( null, false, 'M' );
                $year       = tribe_get_start_date( null, false, 'Y' );
          ?>
            <article class="card" style="display:grid;grid-template-columns:100px 1fr auto;gap:0;overflow:hidden;min-height:140px;">

                <!-- Date Column -->
                <div style="background:var(--green-dark);display:flex;flex-direction:column;align-items:center;justify-content:center;padding:1.5rem 0.75rem;text-align:center;flex-shrink:0;">
                    <div style="font-size:2.25rem;font-weight:900;color:var(--white);line-height:1;"><?php echo esc_html( $day ); ?></div>
                    <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--gold-light);margin-top:0.15rem;"><?php echo esc_html( $month ); ?></div>
                    <div style="font-size:0.65rem;color:rgba(255,255,255,0.5);margin-top:0.1rem;"><?php echo esc_html( $year ); ?></div>
                </div>

                <!-- Details Column -->
                <div style="padding:1.5rem;display:flex;flex-direction:column;justify-content:space-between;border-left:none;">
                    <div>
                        <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.5rem;flex-wrap:wrap;">
                            <?php if ( $start_time ) : ?>
                            <span class="badge badge--green">🕐 <?php echo esc_html( $start_time ); ?><?php echo $end_time ? ' – ' . esc_html($end_time) : ''; ?></span>
                            <?php endif; ?>
                            <?php if ( $venue ) : ?>
                            <span class="badge badge--gray">📍 <?php echo esc_html( $venue ); ?></span>
                            <?php endif; ?>
                            <?php if ( $cost ) : ?>
                            <span class="badge badge--gold">💰 <?php echo esc_html( $cost ); ?></span>
                            <?php endif; ?>
                        </div>
                        <h2 style="font-size:var(--size-xl);font-weight:700;margin-bottom:0.5rem;">
                            <a href="<?php the_permalink(); ?>" style="color:var(--ink);transition:color 0.2s;" onmouseover="this.style.color='var(--green-dark)';" onmouseout="this.style.color='var(--ink)';">
                                <?php the_title(); ?>
                            </a>
                        </h2>
                        <?php if ( has_excerpt() || get_the_content() ) : ?>
                        <p style="font-size:0.875rem;color:var(--ink-light);line-height:1.6;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;margin:0;">
                            <?php echo esc_html( pngcje_excerpt( null, 20 ) ); ?>
                        </p>
                        <?php endif; ?>
                    </div>
                    <div style="margin-top:1rem;">
                        <a href="<?php the_permalink(); ?>" class="btn btn-ghost btn-arrow text-sm">
                            <?php esc_html_e( 'View Event Details', 'pngcje' ); ?>
                        </a>
                    </div>
                </div>

                <!-- Featured Image Column -->
                <?php if ( has_post_thumbnail() ) : ?>
                <div style="width:180px;flex-shrink:0;overflow:hidden;display:none;">
                    <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                        <?php the_post_thumbnail( 'pngcje-card-sm', [
                            'alt'   => '',
                            'style' => 'width:180px;height:100%;object-fit:cover;',
                        ] ); ?>
                    </a>
                </div>
                <?php endif; ?>

            </article>
            <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <div style="display:flex;justify-content:center;">
            <?php
            if ( function_exists( 'tribe_events_the_loop_pagination' ) ) {
                tribe_events_the_loop_pagination();
            } else {
                the_posts_pagination( [
                    'prev_text' => '&larr; ' . __( 'Previous Events', 'pngcje' ),
                    'next_text' => __( 'Upcoming Events', 'pngcje' ) . ' &rarr;',
                ] );
            }
          ?>
        </div>

        <?php else : ?>

        <div style="text-align:center;padding:5rem 2rem;">
            <div style="font-size:4rem;margin-bottom:1rem;" aria-hidden="true">📅</div>
            <h2 style="font-size:var(--size-2xl);color:var(--ink-mid);margin-bottom:0.75rem;">
                <?php esc_html_e( 'No upcoming events', 'pngcje' ); ?>
            </h2>
            <p style="color:var(--ink-light);max-width:400px;margin:0 auto 2rem;">
                <?php esc_html_e( 'There are no scheduled events at this time. Check back soon for our upcoming training calendar.', 'pngcje' ); ?>
            </p>
            <a href="<?php echo esc_url( home_url('/prospectus/training-calendar/') ); ?>" class="btn btn-primary">
                <?php esc_html_e( 'View Training Prospectus', 'pngcje' ); ?>
            </a>
        </div>

        <?php endif; ?>

    </div>
</section>

<?php get_footer(); ?>
