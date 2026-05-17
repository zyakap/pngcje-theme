<?php
/**
 * archive-pngcje_resource.php — Resource Post Type Archive
 * Filterable resource library with download links
 */

get_header();

$queried_type = get_queried_object();
?>

<div <?php pngcje_page_hero_attrs(); ?>>
    <div class="container">
        <div class="page-hero__eyebrow"><?php pngcje_breadcrumbs(); ?></div>
        <h1 class="page-hero__title"><?php esc_html_e( 'Resource Library', 'pngcje' ); ?></h1>
        <p class="page-hero__desc">
            <?php esc_html_e( 'Access all PNGCJE publications — Bench Books, Judicial Handbook, Case Notes, CPD Lectures, Annual Reports and more.', 'pngcje' ); ?>
        </p>
    </div>
</div>

<section class="section">
    <div class="container">

        <!-- Filter Bar -->
        <div class="resources-filter" style="margin-bottom:3rem;" role="group" aria-label="<?php esc_attr_e( 'Filter by resource type', 'pngcje' ); ?>">
            <button class="filter-btn active" data-filter="*">
                <?php esc_html_e( 'All Resources', 'pngcje' ); ?>
            </button>
            <?php
            $types = get_terms( [ 'taxonomy' => 'resource_type', 'hide_empty' => true ] );
            if ( $types && ! is_wp_error( $types ) ) :
                foreach ( $types as $type ) :
          ?>
            <button class="filter-btn" data-filter="<?php echo esc_attr( $type->slug ); ?>">
                <?php echo esc_html( pngcje_resource_icon( $type->slug ) ); ?>
                <?php echo esc_html( $type->name ); ?>
                <span style="opacity:0.5;">(<?php echo esc_html( $type->count ); ?>)</span>
            </button>
            <?php
                endforeach;
            endif;
          ?>
        </div>

        <?php
        // Group resources by type
        $all_types = get_terms( [ 'taxonomy' => 'resource_type', 'hide_empty' => true ] );

        if ( $all_types && ! is_wp_error( $all_types ) ) :
            foreach ( $all_types as $type ) :

                $resources = new WP_Query( [
                    'post_type'      => 'pngcje_resource',
                    'posts_per_page' => -1,
                    'post_status'    => 'publish',
                    'tax_query'      => [ [
                        'taxonomy' => 'resource_type',
                        'field'    => 'term_id',
                        'terms'    => $type->term_id,
                    ] ],
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                ] );

                if ( ! $resources->have_posts() ) continue;
      ?>
        <!-- Resource Type Group -->
        <div class="resource-group resource-item reveal" data-categories="<?php echo esc_attr( $type->slug ); ?>" style="margin-bottom:3.5rem;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:2px solid var(--border-light);">
                <h2 style="display:flex;align-items:center;gap:0.75rem;font-size:var(--size-xl);color:var(--green-dark);">
                    <span style="font-size:1.5rem;" aria-hidden="true"><?php echo esc_html( pngcje_resource_icon( $type->slug ) ); ?></span>
                    <?php echo esc_html( $type->name ); ?>
                    <span class="badge badge--green"><?php echo esc_html( $resources->found_posts ); ?></span>
                </h2>
                <?php if ( $type->description ) : ?>
                <span style="font-size:0.8rem;color:var(--ink-light);max-width:300px;text-align:right;">
                    <?php echo esc_html( $type->description ); ?>
                </span>
                <?php endif; ?>
            </div>

            <div class="resource-grid grid grid-3">
                <?php while ( $resources->have_posts() ) : $resources->the_post();
                    $year = get_post_meta( get_the_ID(), '_pngcje_resource_year', true );
                    $link = get_permalink();
              ?>
                <a href="<?php echo esc_url( $link ); ?>" class="card resource-card" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
                    <?php if ( has_post_thumbnail() ) : ?>
                    <span class="resource-card__media">
                        <?php the_post_thumbnail( 'medium_large', [ 'loading' => 'lazy', 'decoding' => 'async', 'class' => 'resource-card__image', 'alt' => esc_attr( wp_strip_all_tags( get_the_title() ) ) ] ); ?>
                    </span>
                    <?php else : ?>
                    <div class="resource-card__placeholder">
                        <span style="font-size:2rem;color:var(--ember-primary);"><?php echo esc_html( pngcje_resource_icon( $type->slug ) ); ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="card__body">
                        <?php if ( $year ) : ?>
                        <span class="badge badge--gray" style="margin-bottom:.35rem;display:inline-block;"><?php echo esc_html( $year ); ?></span>
                        <?php endif; ?>
                        <h3 style="font-size:var(--size-base);font-weight:800;color:var(--ink);margin:0;"><?php the_title(); ?></h3>
                    </div>
                </a>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
        <?php
            endforeach;
        else :
            // Fallback — no taxonomy groups, just show all
            if ( have_posts() ) :
      ?>
        <div class="resource-grid grid grid-3">
            <?php while ( have_posts() ) : the_post();
                $year = get_post_meta( get_the_ID(), '_pngcje_resource_year', true );
            ?>
            <a href="<?php the_permalink(); ?>" class="card resource-card">
                <?php if ( has_post_thumbnail() ) : ?>
                <span class="resource-card__media">
                    <?php the_post_thumbnail( 'medium_large', [ 'loading' => 'lazy', 'decoding' => 'async', 'class' => 'resource-card__image', 'alt' => esc_attr( wp_strip_all_tags( get_the_title() ) ) ] ); ?>
                </span>
                <?php else : ?>
                <div class="resource-card__placeholder">
                    <span style="font-size:2rem;color:var(--ember-primary);">📄</span>
                </div>
                <?php endif; ?>
                <div class="card__body">
                    <?php if ( $year ) : ?>
                    <span class="badge badge--gray" style="margin-bottom:.35rem;display:inline-block;"><?php echo esc_html( $year ); ?></span>
                    <?php endif; ?>
                    <h3 style="font-size:var(--size-base);font-weight:800;color:var(--ink);margin:0;"><?php the_title(); ?></h3>
                </div>
            </a>
            <?php endwhile; ?>
        </div>
        <?php
            endif;
        endif;
      ?>

    </div>
</section>

<?php get_footer(); ?>
