<?php
/**
 * Single Board Member profile.
 */
get_header();

while ( have_posts() ) : the_post();
    $role         = get_post_meta( get_the_ID(), '_pngcje_board_role', true );
    $organisation = get_post_meta( get_the_ID(), '_pngcje_board_organisation', true );
    $email        = get_post_meta( get_the_ID(), '_pngcje_board_email', true );
    $phone        = get_post_meta( get_the_ID(), '_pngcje_board_phone', true );
    ?>

    <div <?php pngcje_page_hero_attrs(); ?>>
        <div class="container">
            <div class="page-hero__eyebrow"><?php pngcje_breadcrumbs(); ?></div>
            <h1 class="page-hero__title"><?php the_title(); ?></h1>
            <?php if ( $role || $organisation ) : ?>
                <p class="page-hero__desc"><?php echo esc_html( trim( $role . ( $role && $organisation ? ' - ' : '' ) . $organisation ) ); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <section class="section">
        <div class="container">
            <div class="profile-layout profile-layout--board">
                <aside class="profile-sidebar">
                    <div class="profile-card">
                        <div class="profile-card__photo">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'pngcje-staff', [ 'alt' => get_the_title() ] ); ?>
                            <?php else : ?>
                                <div class="profile-card__avatar" aria-hidden="true">
                                    <?php echo esc_html( mb_strtoupper( mb_substr( get_the_title(), 0, 1 ) ) ); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="profile-card__body">
                            <h2><?php the_title(); ?></h2>
                            <?php if ( $role ) : ?>
                                <p class="profile-card__role"><?php echo esc_html( $role ); ?></p>
                            <?php endif; ?>
                            <?php if ( $organisation ) : ?>
                                <p class="profile-card__org"><?php echo esc_html( $organisation ); ?></p>
                            <?php endif; ?>

                            <dl class="profile-details">
                                <?php if ( $email ) : ?>
                                    <div>
                                        <dt><?php esc_html_e( 'Email', 'pngcje' ); ?></dt>
                                        <dd><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></dd>
                                    </div>
                                <?php endif; ?>
                                <?php if ( $phone ) : ?>
                                    <div>
                                        <dt><?php esc_html_e( 'Phone', 'pngcje' ); ?></dt>
                                        <dd><a href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></dd>
                                    </div>
                                <?php endif; ?>
                            </dl>
                        </div>
                    </div>

                    <a href="<?php echo esc_url( home_url( '/about/governance/' ) ); ?>" class="btn btn-outline" style="width:100%;justify-content:center;margin-top:1rem;">
                        <?php esc_html_e( 'Back to Governance', 'pngcje' ); ?>
                    </a>
                </aside>

                <div class="profile-main">
                    <div class="entry-content profile-content">
                        <?php if ( get_the_content() ) : ?>
                            <?php the_content(); ?>
                        <?php else : ?>
                            <p><?php esc_html_e( 'Board member details will be added soon.', 'pngcje' ); ?></p>
                        <?php endif; ?>
                    </div>

                    <?php
                    $related = new WP_Query( [
                        'post_type'      => 'pngcje_board_member',
                        'posts_per_page' => 3,
                        'post_status'    => 'publish',
                        'post__not_in'   => [ get_the_ID() ],
                        'orderby'        => 'menu_order',
                        'order'          => 'ASC',
                    ] );
                    if ( $related->have_posts() ) :
                        ?>
                        <section class="profile-related">
                            <div class="section-label"><?php esc_html_e( 'Other Board Members', 'pngcje' ); ?></div>
                            <div class="profile-related__grid">
                                <?php while ( $related->have_posts() ) : $related->the_post(); ?>
                                    <a href="<?php the_permalink(); ?>" class="card profile-related__card">
                                        <strong><?php the_title(); ?></strong>
                                        <?php $related_role = get_post_meta( get_the_ID(), '_pngcje_board_role', true ); ?>
                                        <?php if ( $related_role ) : ?>
                                            <span><?php echo esc_html( $related_role ); ?></span>
                                        <?php endif; ?>
                                    </a>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </div>
                        </section>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

<?php endwhile; ?>

<style>
.profile-layout{display:grid;grid-template-columns:340px 1fr;gap:4rem;align-items:start}
.profile-card{background:var(--white);border:1px solid var(--border-light);border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-md)}
.profile-card__photo{aspect-ratio:3/4;background:var(--ember-subtle);overflow:hidden}
.profile-card__photo img{width:100%;height:100%;object-fit:cover;object-position:top center}
.profile-card__avatar{width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:4rem;font-weight:900;color:var(--ember-primary);background:linear-gradient(135deg,var(--ember-subtle),#fff)}
.profile-card__body{padding:1.5rem;border-top:4px solid var(--ember-primary)}
.profile-card__body h2{font-size:var(--size-xl);margin-bottom:.35rem}
.profile-card__role{font-size:var(--size-sm);font-weight:700;color:var(--ember-primary);line-height:1.45;margin-bottom:.5rem}
.profile-card__org{font-size:.88rem;color:var(--ink-light);line-height:1.55;margin-bottom:1rem}
.profile-details{display:flex;flex-direction:column;gap:.9rem;margin-top:1.25rem}
.profile-details div{border-top:1px solid var(--border-light);padding-top:.9rem}
.profile-details dt{font-size:.7rem;text-transform:uppercase;letter-spacing:.08em;font-weight:800;color:var(--ink-light);margin-bottom:.2rem}
.profile-details dd{font-size:.9rem;color:var(--ink);word-break:break-word}
.profile-details a{color:var(--ember-primary);text-decoration:none}
.profile-main{min-width:0}
.profile-content{background:var(--white);border:1px solid var(--border-light);border-radius:var(--radius-lg);padding:2rem;box-shadow:var(--shadow-sm)}
.profile-related{margin-top:2.5rem}
.profile-related__grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-top:1rem}
.profile-related__card{display:block;text-decoration:none;padding:1.25rem;border-top:3px solid var(--ember-primary)}
.profile-related__card strong{display:block;color:var(--ink);margin-bottom:.35rem}
.profile-related__card span{display:block;font-size:.82rem;color:var(--ink-light);line-height:1.5}
@media (max-width:900px){.profile-layout{grid-template-columns:1fr;gap:2rem}.profile-sidebar{max-width:420px}.profile-related__grid{grid-template-columns:1fr}}
@media (max-width:520px){.profile-content{padding:1.25rem}.profile-card__photo{aspect-ratio:4/3}}
</style>

<?php get_footer(); ?>
