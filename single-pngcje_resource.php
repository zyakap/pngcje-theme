<?php
/**
 * single-pngcje_resource.php
 * Single Resource / Document view
 */
get_header();
while ( have_posts() ) : the_post();

$post_type = get_post_type();
$is_annual_report = 'pngcje_annual_report' === $post_type;
$file_url  = get_post_meta( get_the_ID(), '_pngcje_resource_file',     true );
$year      = get_post_meta( get_the_ID(), '_pngcje_resource_year',     true );
$filetype  = get_post_meta( get_the_ID(), '_pngcje_resource_filetype', true ) ?: 'PDF';
$size_raw  = get_post_meta( get_the_ID(), '_pngcje_resource_filesize', true );
$filesize  = $size_raw ? pngcje_file_size( $size_raw ) : '';
$types     = $is_annual_report ? false : get_the_terms( get_the_ID(), 'resource_type' );
$type      = $types && ! is_wp_error( $types ) ? $types[0] : null;
$icon      = $is_annual_report ? pngcje_resource_icon( 'annual-reports' ) : pngcje_resource_icon( $type ? $type->slug : '' );
$post_body = trim( (string) get_post_field( 'post_content', get_the_ID() ) );
$type_link = $type ? get_term_link( $type ) : '';
$document_type_label = $is_annual_report ? __( 'Annual Report', 'pngcje' ) : ( $type ? $type->name : '' );
?>

<div <?php pngcje_page_hero_attrs(); ?>>
    <div class="container">
        <div class="page-hero__eyebrow"><?php pngcje_breadcrumbs(); ?></div>
        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1rem;flex-wrap:wrap;">
            <?php if ( $document_type_label ) echo '<span class="badge badge--green">' . esc_html( $document_type_label ) . '</span>'; ?>
            <?php if ( $year  ) echo '<span class="badge badge--gold">' . esc_html( $year ) . '</span>'; ?>
            <?php echo '<span class="badge" style="background:rgba(255,255,255,.15);color:#fff;">' . esc_html( strtoupper( $filetype ) ) . '</span>'; ?>
        </div>
        <h1 class="page-hero__title"><?php the_title(); ?></h1>
        <?php if ( has_excerpt() ) : ?>
        <p class="page-hero__desc"><?php the_excerpt(); ?></p>
        <?php endif; ?>
    </div>
</div>

<section class="section">
    <div class="container resource-single-layout">

            <div class="resource-single-main">
                <div class="resource-single__feature">
                    <?php if ( has_post_thumbnail() ) : ?>
                    <figure class="resource-single__figure">
                        <?php
                        the_post_thumbnail(
                            'medium_large',
                            [
                                'loading'  => 'eager',
                                'decoding' => 'async',
                                'alt'      => esc_attr( wp_strip_all_tags( get_the_title() ) ),
                            ]
                        );
                        ?>
                    </figure>
                    <?php endif; ?>

                    <article class="resource-single__article resource-single__prose entry-content">
                        <h2 class="resource-single__prose-heading"><?php esc_html_e( 'About this publication', 'pngcje' ); ?></h2>
                        <?php if ( '' !== $post_body ) : ?>
                            <?php the_content(); ?>
                        <?php elseif ( has_excerpt() ) : ?>
                            <p><?php echo esc_html( get_the_excerpt() ); ?></p>
                        <?php else : ?>
                            <p class="resource-single__empty-note">
                                <?php
                                echo $file_url
                                    ? esc_html__( 'No extended description is available. Use the download link above for the full publication.', 'pngcje' )
                                    : esc_html__( 'No extended description is available.', 'pngcje' );
                                ?>
                            </p>
                        <?php endif; ?>
                    </article>
                </div>

                <?php if ( $file_url ) : ?>
                <div class="resource-single__cta-strip<?php echo $is_annual_report ? ' annual-report-single__cta' : ''; ?>" role="region" aria-label="<?php esc_attr_e( 'Download publication', 'pngcje' ); ?>">
                    <span class="resource-single__cta-strip-meta">
                        <span aria-hidden="true"><?php echo esc_html( $icon ); ?></span>
                        <?php if ( $year ) : ?>
                        <span><?php echo esc_html( $year ); ?></span>
                        <?php endif; ?>
                        <?php if ( $filetype ) : ?>
                        <span><?php echo esc_html( strtoupper( $filetype ) ); ?></span>
                        <?php endif; ?>
                        <?php if ( $filesize ) : ?>
                        <span><?php echo esc_html( $filesize ); ?></span>
                        <?php endif; ?>
                    </span>
                    <a href="<?php echo esc_url( $file_url ); ?>"
                       class="btn btn-primary"
                       target="_blank"
                       rel="noopener noreferrer"
                       download>
                        <?php
                        echo esc_html(
                            sprintf(
                                __( 'Download %s', 'pngcje' ),
                                strtoupper( $filetype )
                            )
                        );
                        ?>
                    </a>
                </div>
                <?php endif; ?>

                <nav class="resource-single__nav-links" aria-label="<?php esc_attr_e( 'Publication navigation', 'pngcje' ); ?>">
                    <?php if ( $is_annual_report ) : ?>
                    <a href="<?php echo esc_url( home_url( '/annual-reports/' ) ); ?>" class="btn btn-outline">
                        <?php esc_html_e( 'All annual reports', 'pngcje' ); ?>
                    </a>
                    <?php elseif ( $type && ! is_wp_error( $type_link ) ) : ?>
                    <a href="<?php echo esc_url( $type_link ); ?>" class="btn btn-outline">
                        <?php
                        echo esc_html(
                            sprintf(
                                __( 'Back to %s', 'pngcje' ),
                                $type->name
                            )
                        );
                        ?>
                    </a>
                    <?php endif; ?>
                    <?php if ( ! $is_annual_report ) : ?>
                        <a href="<?php echo esc_url( get_post_type_archive_link( 'pngcje_resource' ) ); ?>" class="btn btn-outline">
                            <?php esc_html_e( 'All resources', 'pngcje' ); ?>
                        </a>
                    <?php endif; ?>
                </nav>
            </div>

            <aside class="resource-single__aside">
                <div class="card" style="border-top:4px solid var(--ember-primary);margin-bottom:1.5rem;">
                    <div class="card__body">
                        <h3 style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--ember-primary);margin-bottom:1.25rem;">
                            <?php esc_html_e( 'Document details', 'pngcje' ); ?>
                        </h3>
                        <dl style="display:flex;flex-direction:column;gap:.85rem;">
                            <?php
                            $details = array_filter( [
                                __( 'Type', 'pngcje' )      => $document_type_label,
                                __( 'Year', 'pngcje' )      => $year,
                                __( 'Format', 'pngcje' )    => $filetype ? strtoupper( $filetype ) : '',
                                __( 'File size', 'pngcje' ) => $filesize,
                            ] );
                            foreach ( $details as $label => $val ) : ?>
                            <div style="display:flex;justify-content:space-between;align-items:baseline;font-size:.85rem;border-bottom:1px solid var(--border-light);padding-bottom:.85rem;">
                                <dt style="color:var(--ink-light);font-weight:500;"><?php echo esc_html( $label ); ?></dt>
                                <dd style="font-weight:700;color:var(--ink);text-align:right;"><?php echo esc_html( $val ); ?></dd>
                            </div>
                            <?php endforeach; ?>
                        </dl>
                        <?php if ( $file_url ) : ?>
                        <a href="<?php echo esc_url( $file_url ); ?>"
                           class="btn btn-primary"
                           style="width:100%;justify-content:center;margin-top:1.5rem;"
                           target="_blank" rel="noopener noreferrer" download>
                            <?php esc_html_e( 'Download', 'pngcje' ); ?>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php
                if ( $is_annual_report || $type ) :
                    $related_args = [
                        'post_type'      => $is_annual_report ? 'pngcje_annual_report' : 'pngcje_resource',
                        'posts_per_page' => 5,
                        'post_status'    => 'publish',
                        'post__not_in'   => [ get_the_ID() ],
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                    ];
                    if ( ! $is_annual_report && $type ) {
                        $related_args['tax_query'] = [ [ 'taxonomy' => 'resource_type', 'field' => 'term_id', 'terms' => $type->term_id ] ];
                    }
                    $related = new WP_Query( $related_args );
                    if ( $related->have_posts() ) : ?>
                <div class="card" style="border-left:4px solid var(--gold-primary);">
                    <div class="card__body">
                        <h3 style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--ink);margin-bottom:1rem;">
                            <?php
                            echo esc_html(
                                sprintf(
                                    /* translators: %s: resource type name */
                                    __( 'More %s', 'pngcje' ),
                                    $is_annual_report ? __( 'Annual Reports', 'pngcje' ) : $type->name
                                )
                            );
                            ?>
                        </h3>
                        <ul style="display:flex;flex-direction:column;gap:.6rem;">
                            <?php while ( $related->have_posts() ) : $related->the_post();
                                $r_file = get_post_meta( get_the_ID(), '_pngcje_resource_file', true );
                                $r_year = get_post_meta( get_the_ID(), '_pngcje_resource_year', true );
                                $r_link = $r_file ?: get_permalink();
                            ?>
                            <li>
                                <a href="<?php echo esc_url( $r_link ); ?>"
                                   style="display:flex;align-items:flex-start;gap:.5rem;font-size:.825rem;color:var(--ink-mid);text-decoration:none;transition:color .2s;"
                                   <?php echo $r_file ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
                                   onmouseover="this.style.color='var(--ember-primary)';"
                                   onmouseout="this.style.color='var(--ink-mid)';">
                                    <span style="color:var(--gold-primary);flex-shrink:0;">›</span>
                                    <span><?php the_title(); ?><?php echo $r_year ? ' <span style="color:var(--ink-light);font-size:.75rem;">(' . esc_html( $r_year ) . ')</span>' : ''; ?></span>
                                </a>
                            </li>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </ul>
                    </div>
                </div>
                    <?php endif; endif; ?>
            </aside>

    </div>
</section>

<?php endwhile; get_footer(); ?>
