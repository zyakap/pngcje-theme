<?php
/**
 * Template Name: Resources Hub
 * Displays all resource types with filtering
 */

get_header();
?>

<!-- Page Hero -->
<div <?php pngcje_page_hero_attrs(); ?>>
    <div class="container">
        <div class="page-hero__eyebrow">
            <?php pngcje_breadcrumbs(); ?>
        </div>
        <h1 class="page-hero__title"><?php the_title(); ?></h1>
        <?php if ( has_excerpt() ) : ?>
        <p class="page-hero__desc"><?php the_excerpt(); ?></p>
        <?php endif; ?>
    </div>
</div>

<!-- Resources Hub -->
<section class="section resources-hub">
    <div class="container">

        <!-- Filter Bar -->
        <div class="resources-filter" role="group" aria-label="<?php esc_attr_e( 'Filter resources by type', 'pngcje' ); ?>">
            <span style="font-size:0.8rem;font-weight:600;color:var(--ink-light);text-transform:uppercase;letter-spacing:0.08em;margin-right:0.5rem;">
                <?php esc_html_e( 'Filter:', 'pngcje' ); ?>
            </span>
            <button class="filter-btn active" data-filter="*" aria-pressed="true">
                <?php esc_html_e( 'All Resources', 'pngcje' ); ?>
            </button>
            <?php
            $terms = get_terms( [ 'taxonomy' => 'resource_type', 'hide_empty' => true ] );
            if ( $terms && ! is_wp_error( $terms ) ) :
                foreach ( $terms as $term ) :
           ?>
            <button class="filter-btn" data-filter="<?php echo esc_attr( $term->slug ); ?>" aria-pressed="false">
                <?php echo esc_html( $term->name ); ?>
                <span style="margin-left:0.25rem;opacity:0.6;">(<?php echo esc_html( $term->count ); ?>)</span>
            </button>
            <?php
                endforeach;
            endif;
           ?>
        </div>

        <!-- Resources Grid -->
        <div class="resources-grid" id="resources-grid">
            <?php
            $resources_query = new WP_Query( [
                'post_type'      => 'pngcje_resource',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'orderby'        => 'date',
                'order'          => 'DESC',
            ] );

            if ( $resources_query->have_posts() ) :
                while ( $resources_query->have_posts() ) : $resources_query->the_post();
                    $terms    = get_the_terms( get_the_ID(), 'resource_type' );
                    $type     = $terms ? $terms[0] : null;
                    $slug     = $type ? $type->slug : '';
                    $icon     = pngcje_resource_icon( $slug );
                    $file_url = get_post_meta( get_the_ID(), '_pngcje_resource_file', true );
                    $year     = get_post_meta( get_the_ID(), '_pngcje_resource_year', true );
                    $size_raw = get_post_meta( get_the_ID(), '_pngcje_resource_filesize', true );
                    $size     = $size_raw ? pngcje_file_size( $size_raw ) : '';
                    $link     = get_permalink();
                    $cat_str  = $type ? $type->slug : '';
           ?>
            <a
                href="<?php echo esc_url( $link ); ?>"
                class="card card--resource resource-item reveal"
                data-categories="<?php echo esc_attr( $cat_str ); ?>"
                aria-label="<?php echo esc_attr( get_the_title() ); ?>"
            >
                <?php if ( has_post_thumbnail() ) : ?>
                <span class="card__media card__media--resource-thumb">
                    <?php
                    echo get_the_post_thumbnail( get_the_ID(), 'pngcje-card-sm', [
                        'loading'  => 'lazy',
                        'decoding' => 'async',
                        'alt'      => esc_attr( wp_strip_all_tags( get_the_title() ) ),
                    ] );
                    ?>
                </span>
                <?php else : ?>
                <span class="card__icon" aria-hidden="true"><?php echo esc_html( $icon ); ?></span>
                <?php endif; ?>
                <div class="card--resource__body">
                    <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.5rem;">
                        <?php if ( $type ) : ?>
                        <span class="badge badge--green"><?php echo esc_html( $type->name ); ?></span>
                        <?php endif; ?>
                        <?php if ( $year ) : ?>
                        <span class="badge badge--gray"><?php echo esc_html( $year ); ?></span>
                        <?php endif; ?>
                    </div>
                    <h3 style="font-size:1rem;font-weight:600;color:var(--ink);line-height:1.4;margin-bottom:0.25rem;">
                        <?php the_title(); ?>
                    </h3>
                    <?php if ( has_excerpt() ) : ?>
                    <p style="font-size:0.85rem;color:var(--ink-light);line-height:1.5;margin:0;">
                        <?php echo esc_html( pngcje_excerpt( null, 12 ) ); ?>
                    </p>
                    <?php endif; ?>
                </div>
                <div style="flex-shrink:0;display:flex;flex-direction:column;align-items:flex-end;gap:0.5rem;">
                    <?php if ( $file_url ) : ?>
                    <span style="font-size:0.75rem;color:var(--gold-primary);font-weight:700;text-transform:uppercase;letter-spacing:0.06em;display:flex;align-items:center;gap:0.25rem;">
                        <?php esc_html_e( 'View', 'pngcje' ); ?>
                    </span>
                    <?php endif; ?>
                    <?php if ( $size ) : ?>
                    <span style="font-size:0.7rem;color:var(--ink-light);"><?php echo esc_html( $size ); ?></span>
                    <?php endif; ?>
                </div>
            </a>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
           ?>
            <p style="color:var(--ink-light);grid-column:1/-1;text-align:center;padding:3rem 0;">
                <?php esc_html_e( 'No resources found. Check back soon.', 'pngcje' ); ?>
            </p>
            <?php endif; ?>
        </div>

    </div>
</section>

<?php get_footer(); ?>
