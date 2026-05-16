<?php
/**
 * Template Name: Staff Directory
 * Team grid with photos, roles, department filtering
 */

get_header();
?>

<div <?php pngcje_page_hero_attrs(); ?>>
    <div class="container">
        <div class="page-hero__eyebrow"><?php pngcje_breadcrumbs(); ?></div>
        <h1 class="page-hero__title"><?php the_title(); ?></h1>
        <p class="page-hero__desc">
            <?php esc_html_e( 'Meet the dedicated team behind the Papua New Guinea Centre for Judicial Excellence.', 'pngcje' ); ?>
        </p>
    </div>
</div>

<section class="section">
    <div class="container">

        <?php
        // Department filter tabs
        $departments = get_terms( [ 'taxonomy' => 'department', 'hide_empty' => true ] );
        if ( $departments && ! is_wp_error( $departments ) ) :
       ?>
        <div class="resources-filter" style="margin-bottom:3rem;" role="tablist" aria-label="<?php esc_attr_e( 'Filter staff by department', 'pngcje' ); ?>">
            <button class="filter-btn active" data-filter="*" role="tab" aria-selected="true">
                <?php esc_html_e( 'All Staff', 'pngcje' ); ?>
            </button>
            <?php foreach ( $departments as $dept ) : ?>
            <button class="filter-btn" data-filter="<?php echo esc_attr( $dept->slug ); ?>" role="tab" aria-selected="false">
                <?php echo esc_html( $dept->name ); ?>
            </button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php
        // Leadership first
        $leadership = new WP_Query( [
            'post_type'      => 'member',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
            'tax_query'      => [
                [
                    'taxonomy' => 'department',
                    'field'    => 'slug',
                    'terms'    => 'leadership',
                ],
            ],
        ] );

        if ( $leadership->have_posts() ) :
       ?>
        <div class="section-header" style="margin-bottom:2rem;">
            <div class="section-label"><?php esc_html_e( 'Leadership', 'pngcje' ); ?></div>
            <h2 class="section-title" style="font-size:var(--size-2xl);"><?php esc_html_e( 'Executive Team', 'pngcje' ); ?></h2>
        </div>
        <div class="staff-grid staff-grid--leadership" style="display:grid;grid-template-columns:repeat(3,1fr);gap:2rem;margin-bottom:4rem;">
            <?php while ( $leadership->have_posts() ) : $leadership->the_post();
                $role       = get_post_meta( get_the_ID(), '_pngcje_staff_role',  true );
                $email      = get_post_meta( get_the_ID(), '_pngcje_staff_email', true );
                $phone      = get_post_meta( get_the_ID(), '_pngcje_staff_phone', true );
                $dept_terms = get_the_terms( get_the_ID(), 'department' );
                $dept_slug  = $dept_terms ? $dept_terms[0]->slug : '';
           ?>
            <div class="staff-card staff-card--featured resource-item reveal" data-categories="<?php echo esc_attr( $dept_slug ); ?>">
                <div class="staff-card__photo">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'pngcje-staff', [ 'alt' => get_the_title() ] ); ?>
                    <?php else : ?>
                        <div class="staff-card__avatar" aria-label="<?php the_title(); ?>">
                            <?php echo esc_html( mb_strtoupper( mb_substr( get_the_title(), 0, 1 ) ) ); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="staff-card__body">
                    <h3 class="staff-card__name"><?php the_title(); ?></h3>
                    <?php if ( $role ) : ?>
                    <p class="staff-card__role"><?php echo esc_html( $role ); ?></p>
                    <?php endif; ?>
                    <?php if ( $email ) : ?>
                    <a href="mailto:<?php echo esc_attr( $email ); ?>" class="staff-card__contact">
                        ✉️ <?php echo esc_html( $email ); ?>
                    </a>
                    <?php endif; ?>
                    <?php if ( $phone ) : ?>
                    <a href="tel:<?php echo esc_attr( preg_replace('/[^+\d]/','',$phone) ); ?>" class="staff-card__contact">
                        📞 <?php echo esc_html( $phone ); ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php endif; ?>

        <?php
        // All other staff
        $all_staff = new WP_Query( [
            'post_type'      => 'member',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
            'tax_query'      => [
                [
                    'taxonomy' => 'department',
                    'field'    => 'slug',
                    'terms'    => 'leadership',
                    'operator' => 'NOT IN',
                ],
            ],
        ] );

        if ( $all_staff->have_posts() ) :
       ?>
        
        <div class="staff-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:1.5rem;">
            <?php while ( $all_staff->have_posts() ) : $all_staff->the_post();
                $role       = get_post_meta( get_the_ID(), '_pngcje_staff_role',  true );
                $email      = get_post_meta( get_the_ID(), '_pngcje_staff_email', true );
                $dept_terms = get_the_terms( get_the_ID(), 'department' );
                $dept_slug  = $dept_terms ? $dept_terms[0]->slug : '';
           ?>
            <div class="staff-card resource-item reveal" data-categories="<?php echo esc_attr( $dept_slug ); ?>">
                <div class="staff-card__photo staff-card__photo--sm">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'pngcje-staff', [ 'alt' => get_the_title() ] ); ?>
                    <?php else : ?>
                        <div class="staff-card__avatar staff-card__avatar--sm">
                            <?php echo esc_html( mb_strtoupper( mb_substr( get_the_title(), 0, 1 ) ) ); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="staff-card__body">
                    <h3 class="staff-card__name" style="font-size:var(--size-base);"><?php the_title(); ?></h3>
                    <?php if ( $role ) : ?>
                    <p class="staff-card__role"><?php echo esc_html( $role ); ?></p>
                    <?php endif; ?>
                    <?php if ( $email ) : ?>
                    <a href="mailto:<?php echo esc_attr( $email ); ?>" class="staff-card__contact" style="font-size:var(--size-xs);">
                        ✉️ <?php echo esc_html( $email ); ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php else : ?>
        <p style="text-align:center;color:var(--ink-light);padding:3rem 0;">
            <?php esc_html_e( 'Staff profiles coming soon.', 'pngcje' ); ?>
        </p>
        <?php endif; ?>

    </div>
</section>

<style>
/* Staff Cards */
.staff-card {
    background: var(--white);
    border-radius: var(--radius-md);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-light);
    transition: all var(--transition-base);
}
.staff-card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-4px);
}
.staff-card__photo {
    width: 100%;
    aspect-ratio: 3/4;
    overflow: hidden;
    background: var(--green-subtle);
    position: relative;
}
.staff-card__photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top center;
    transition: transform var(--transition-slow);
}
.staff-card:hover .staff-card__photo img { transform: scale(1.04); }
.staff-card__photo--sm { aspect-ratio: 1/1; }

.staff-card__avatar {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    font-weight: 900;
    color: var(--green-dark);
    background: linear-gradient(135deg, var(--green-subtle), #d4f0df);
}
.staff-card__avatar--sm { font-size: 2rem; }

.staff-card__body {
    padding: 1.25rem;
    border-top: 3px solid var(--green-dark);
}
.staff-card--featured .staff-card__body {
    border-top-color: var(--gold-primary);
}
.staff-card__name {
    font-size: var(--size-lg);
    font-weight: var(--weight-bold);
    color: var(--ink);
    margin-bottom: 0.25rem;
    line-height: 1.3;
}
.staff-card__role {
    font-size: var(--size-sm);
    color: var(--green-dark);
    font-weight: var(--weight-semibold);
    margin-bottom: 0.75rem;
    line-height: 1.4;
}
.staff-card__contact {
    display: block;
    font-size: var(--size-xs);
    color: var(--ink-light);
    margin-bottom: 0.3rem;
    transition: color var(--transition-fast);
    word-break: break-all;
}
.staff-card__contact:hover { color: var(--green-dark); }

@media (max-width: 1024px) {
    .staff-grid { grid-template-columns: repeat(3,1fr) !important; }
    .staff-grid--leadership { grid-template-columns: repeat(2,1fr) !important; }
}
@media (max-width: 768px) {
    .staff-grid { grid-template-columns: 1fr !important; }
    .staff-grid--leadership { grid-template-columns: 1fr !important; }
    .staff-card__image { aspect-ratio: 4/3; }
}
</style>

<?php get_footer(); ?>
