<?php
/**
 * taxonomy-event_category.php
 * Events filtered by category (Training, Conference, Workshop etc.)
 */
get_header();

$term      = get_queried_object();
$cat_name  = $term->name ?? 'Events';
$cat_desc  = $term->description ?? '';
$ical_url  = add_query_arg( [ 'pngcje_ical' => 1, 'event_category' => $term->slug ], home_url( '/' ) );

$upcoming = get_posts( [
    'post_type'      => 'pngcje_event',
    'post_status'    => 'publish',
    'posts_per_page' => 50,
    'meta_key'       => '_pngcje_event_start_timestamp',
    'orderby'        => 'meta_value_num',
    'order'          => 'ASC',
    'tax_query'      => [ [ 'taxonomy' => 'event_category', 'field' => 'term_id', 'terms' => $term->term_id ] ],
    'meta_query'     => [ [ 'key' => '_pngcje_event_start_timestamp', 'value' => time(), 'compare' => '>=', 'type' => 'NUMERIC' ] ],
] );
?>

<div <?php pngcje_page_hero_attrs(); ?>>
    <div class="container">
        <div class="page-hero__eyebrow">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
            <span>›</span>
            <a href="<?php echo esc_url( get_post_type_archive_link( 'pngcje_event' ) ); ?>">Events</a>
            <span>›</span>
            <span><?php echo esc_html( $cat_name ); ?></span>
        </div>
        <h1 class="page-hero__title"><?php echo esc_html( $cat_name ); ?></h1>
        <?php if ( $cat_desc ) echo '<p class="page-hero__desc">' . esc_html( $cat_desc ) . '</p>'; ?>
    </div>
</div>

<section class="section">
    <div class="container">

        <!-- Category pills -->
        <div class="resources-filter" style="margin-bottom:2.5rem;">
            <a href="<?php echo esc_url( get_post_type_archive_link( 'pngcje_event' ) ); ?>" class="filter-btn">All Events</a>
            <?php
            $all_cats = get_terms( [ 'taxonomy' => 'event_category', 'hide_empty' => true ] );
            if ( $all_cats && ! is_wp_error( $all_cats ) ) :
                foreach ( $all_cats as $c ) : ?>
            <a href="<?php echo esc_url( get_term_link( $c ) ); ?>"
               class="filter-btn <?php echo $c->term_id === $term->term_id ? 'active' : ''; ?>">
                <?php echo esc_html( $c->name ); ?>
            </a>
            <?php endforeach; endif; ?>
        </div>

        <?php if ( ! empty( $upcoming ) ) : ?>
        <div style="display:flex;flex-direction:column;gap:1.5rem;">
            <?php foreach ( $upcoming as $ev ) :
                $start  = pngcje_event_get( 'start_date', $ev->ID );
                $time   = pngcje_event_get( 'start_time', $ev->ID );
                $venue  = pngcje_event_get( 'venue',      $ev->ID );
                $city   = pngcje_event_get( 'city',       $ev->ID );
                $cost   = pngcje_event_get( 'cost',       $ev->ID );
            ?>
            <article class="card reveal" style="display:grid;grid-template-columns:100px 1fr;overflow:hidden;min-height:130px;">
                <div style="background:var(--ember-primary);display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:1rem .5rem;flex-shrink:0;">
                    <?php if ( $start ) : ?>
                    <div style="font-size:2rem;font-weight:900;color:#fff;line-height:1;"><?php echo esc_html( date( 'j', strtotime( $start ) ) ); ?></div>
                    <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.8);"><?php echo esc_html( date( 'M', strtotime( $start ) ) ); ?></div>
                    <div style="font-size:.6rem;color:rgba(255,255,255,.5);"><?php echo esc_html( date( 'Y', strtotime( $start ) ) ); ?></div>
                    <?php endif; ?>
                </div>
                <div class="card__body" style="display:flex;flex-direction:column;justify-content:space-between;">
                    <div>
                        <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.5rem;flex-wrap:wrap;">
                            <?php echo wp_kses_post( pngcje_event_status_badge( $ev->ID ) ); ?>
                            <?php if ( $time ) echo '<span class="badge badge--green">🕐 ' . esc_html( date( 'g:i A', strtotime( $time ) ) ) . '</span>'; ?>
                        </div>
                        <h2 style="font-size:var(--size-xl);font-weight:700;margin-bottom:.4rem;">
                            <a href="<?php echo esc_url( get_permalink( $ev->ID ) ); ?>" style="color:var(--ink);transition:color .2s;" onmouseover="this.style.color='var(--ember-primary)';" onmouseout="this.style.color='var(--ink)';">
                                <?php echo esc_html( get_the_title( $ev->ID ) ); ?>
                            </a>
                        </h2>
                        <?php if ( $venue || $city ) echo '<p style="font-size:.85rem;color:var(--ink-light);margin:0;">📍 ' . esc_html( implode( ', ', array_filter( [ $venue, $city ] ) ) ) . '</p>'; ?>
                    </div>
                    <div style="display:flex;align-items:center;gap:1rem;margin-top:.75rem;padding-top:.75rem;border-top:1px solid var(--border-light);flex-wrap:wrap;">
                        <a href="<?php echo esc_url( get_permalink( $ev->ID ) ); ?>" class="btn btn-ghost btn-arrow text-sm">View Details</a>
                        <a href="<?php echo esc_url( add_query_arg( [ 'pngcje_ical' => 1, 'event_id' => $ev->ID ], home_url( '/' ) ) ); ?>" class="btn btn-outline btn-sm" download style="font-size:.72rem;">📅 Add to Calendar</a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php else : ?>
        <div style="text-align:center;padding:4rem 0;">
            <div style="font-size:3.5rem;margin-bottom:1rem;">📅</div>
            <h2 style="font-size:var(--size-xl);color:var(--ink-mid);margin-bottom:.75rem;">No upcoming <?php echo esc_html( strtolower( $cat_name ) ); ?> events</h2>
            <p style="color:var(--ink-light);margin-bottom:2rem;">Check back soon for upcoming events in this category.</p>
            <a href="<?php echo esc_url( get_post_type_archive_link( 'pngcje_event' ) ); ?>" class="btn btn-primary">View All Events</a>
        </div>
        <?php endif; ?>

    </div>
</section>

<?php get_footer(); ?>
