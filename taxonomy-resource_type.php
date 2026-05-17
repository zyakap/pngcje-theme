<?php
/**
 * taxonomy-resource_type.php
 * Resources filtered by type — Bench Books, Case Notes, Handbooks, etc.
 */
get_header();

$term        = get_queried_object();
$type_slug   = $term->slug ?? '';
$type_name   = $term->name ?? 'Resources';
$type_desc   = $term->description ?? '';
$icon        = pngcje_resource_icon( $type_slug );
$total       = $term->count ?? 0;
?>

<div <?php pngcje_page_hero_attrs(); ?>>
    <div class="container">
        <div class="page-hero__eyebrow">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
            <span>›</span>
            <a href="<?php echo esc_url( get_post_type_archive_link( 'pngcje_resource' ) ); ?>">Resources</a>
            <span>›</span>
            <span><?php echo esc_html( $type_name ); ?></span>
        </div>
        <div style="display:flex;align-items:center;gap:1.5rem;margin-bottom:1rem;">
            <div style="font-size:3rem;" aria-hidden="true"><?php echo esc_html( $icon ); ?></div>
            <div>
                <h1 class="page-hero__title" style="margin-bottom:.25rem;"><?php echo esc_html( $type_name ); ?></h1>
                <span style="color:rgba(255,255,255,.55);font-size:.85rem;"><?php echo esc_html( $total ); ?> publication<?php echo $total !== 1 ? 's' : ''; ?></span>
            </div>
        </div>
        <?php if ( $type_desc ) : ?>
        <p class="page-hero__desc"><?php echo esc_html( $type_desc ); ?></p>
        <?php endif; ?>
    </div>
</div>

<section class="section">
    <div class="container">

        <!-- Type filter pills -->
        <div class="resources-filter" style="margin-bottom:2.5rem;">
            <a href="<?php echo esc_url( get_post_type_archive_link( 'pngcje_resource' ) ); ?>" class="filter-btn">
                All Resources
            </a>
            <?php
            $all_types = get_terms( [ 'taxonomy' => 'resource_type', 'hide_empty' => true ] );
            if ( $all_types && ! is_wp_error( $all_types ) ) :
                foreach ( $all_types as $t ) : ?>
            <a href="<?php echo esc_url( get_term_link( $t ) ); ?>"
               class="filter-btn <?php echo $t->term_id === $term->term_id ? 'active' : ''; ?>">
                <?php echo esc_html( pngcje_resource_icon( $t->slug ) . ' ' . $t->name ); ?>
                <span style="opacity:.5;">(<?php echo esc_html( $t->count ); ?>)</span>
            </a>
            <?php endforeach; endif; ?>
        </div>

        <!-- Year filter -->
        <?php
        $years = [];
        $year_q = new WP_Query( [
            'post_type'      => 'pngcje_resource',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'tax_query'      => [ [ 'taxonomy' => 'resource_type', 'field' => 'term_id', 'terms' => $term->term_id ] ],
            'fields'         => 'ids',
        ] );
        foreach ( $year_q->posts as $pid ) {
            $y = get_post_meta( $pid, '_pngcje_resource_year', true );
            if ( $y ) $years[ $y ] = true;
        }
        krsort( $years );
        $filter_year = isset( $_GET['year'] ) ? absint( $_GET['year'] ) : 0;
        if ( ! empty( $years ) ) : ?>
        <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;margin-bottom:2rem;">
            <span style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--ink-light);">Filter by Year:</span>
            <a href="<?php echo esc_url( get_term_link( $term ) ); ?>"
               style="font-size:.8rem;padding:.3rem .75rem;border-radius:var(--radius-full);background:<?php echo ! $filter_year ? 'var(--ember-primary)' : 'var(--surface)'; ?>;color:<?php echo ! $filter_year ? '#fff' : 'var(--ink-mid)'; ?>;text-decoration:none;font-weight:600;border:1.5px solid <?php echo ! $filter_year ? 'var(--ember-primary)' : 'var(--border)'; ?>;">
                All Years
            </a>
            <?php foreach ( $years as $yr => $_ ) : ?>
            <a href="<?php echo esc_url( add_query_arg( 'year', $yr, get_term_link( $term ) ) ); ?>"
               style="font-size:.8rem;padding:.3rem .75rem;border-radius:var(--radius-full);background:<?php echo $filter_year === (int)$yr ? 'var(--ember-primary)' : 'var(--surface)'; ?>;color:<?php echo $filter_year === (int)$yr ? '#fff' : 'var(--ink-mid)'; ?>;text-decoration:none;font-weight:600;border:1.5px solid <?php echo $filter_year === (int)$yr ? 'var(--ember-primary)' : 'var(--border)'; ?>;">
                <?php echo esc_html( $yr ); ?>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Resources grid -->
        <?php
        $query_args = [
            'post_type'      => 'pngcje_resource',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'tax_query'      => [ [ 'taxonomy' => 'resource_type', 'field' => 'term_id', 'terms' => $term->term_id ] ],
            'orderby'        => 'date',
            'order'          => 'DESC',
        ];
        if ( $filter_year ) {
            $query_args['meta_query'] = [ [ 'key' => '_pngcje_resource_year', 'value' => $filter_year, 'compare' => '=', 'type' => 'NUMERIC' ] ];
        }
        $resources = new WP_Query( $query_args );
        ?>

        <?php if ( $resources->have_posts() ) : ?>
        <div class="resource-grid grid grid-3">
            <?php while ( $resources->have_posts() ) : $resources->the_post();
                $yr = get_post_meta( get_the_ID(), '_pngcje_resource_year', true );
                $link = get_permalink();
            ?>
            <a href="<?php echo esc_url( $link ); ?>" class="card resource-card reveal" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
                <?php if ( has_post_thumbnail() ) : ?>
                <span class="resource-card__media">
                    <?php the_post_thumbnail( 'medium_large', [ 'loading' => 'lazy', 'decoding' => 'async', 'class' => 'resource-card__image', 'alt' => esc_attr( wp_strip_all_tags( get_the_title() ) ) ] ); ?>
                </span>
                <?php else : ?>
                <div class="resource-card__placeholder">
                    <span style="font-size:2rem;color:var(--ember-primary);"><?php echo esc_html( $icon ); ?></span>
                </div>
                <?php endif; ?>
                <div class="card__body">
                    <?php if ( $yr ) : ?>
                    <span class="badge badge--gray" style="margin-bottom:.35rem;display:inline-block;"><?php echo esc_html( $yr ); ?></span>
                    <?php endif; ?>
                    <h3 style="font-size:var(--size-base);font-weight:800;color:var(--ink);margin:0;"><?php the_title(); ?></h3>
                </div>
            </a>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php else : ?>
        <div style="text-align:center;padding:4rem 0;">
            <div style="font-size:3rem;margin-bottom:1rem;"><?php echo esc_html( $icon ); ?></div>
            <p style="color:var(--ink-light);">No <?php echo esc_html( strtolower( $type_name ) ); ?> found<?php echo $filter_year ? ' for ' . esc_html( $filter_year ) : ''; ?>.</p>
        </div>
        <?php endif; ?>

    </div>
</section>

<?php get_footer(); ?>
