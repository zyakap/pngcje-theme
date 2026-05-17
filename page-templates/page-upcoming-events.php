<?php
/**
 * Template Name: Upcoming Events
 * Refreshed events landing page.
 */
get_header();
$events = function_exists( 'pngcje_get_upcoming_events' ) ? pngcje_get_upcoming_events( 12 ) : [];
?>
<div <?php pngcje_page_hero_attrs(); ?>>
    <div class="container">
        <div class="page-hero__eyebrow"><?php pngcje_breadcrumbs(); ?></div>
        <h1 class="page-hero__title"><?php the_title(); ?></h1>
        <p class="page-hero__desc">Upcoming training activities, forums and programs delivered by PNGCJE and its partners.</p>
    </div>
</div>
<section class="section">
    <div class="container">
        <div class="reveal" style="max-width:860px;margin-bottom:3rem;">
            <div class="section-label">Training and Events</div>
            <h2 class="section-title" style="font-size:var(--size-2xl);">A forward view of PNGCJE activities</h2>
            <p style="color:var(--ink-mid);line-height:1.8;">PNGCJE delivers and supports workshops, judicial education programs, conferences, webinars and regional training events throughout the year. This page highlights upcoming opportunities and directs users to the full training calendar.</p>
        </div>
        <?php if ( ! empty( $events ) && function_exists( 'pngcje_homepage_events' ) ) : ?>
            <div class="events-list" style="margin-bottom:3rem;">
                <?php echo pngcje_homepage_events( 12 ); ?>
            </div>
        <?php else : ?>
            <div class="card" style="margin-bottom:3rem;">
                <div class="card__body" style="text-align:center;padding:2.5rem;">
                    <h2 style="font-size:var(--size-xl);margin-bottom:.75rem;">No upcoming events published yet</h2>
                    <p style="color:var(--ink-light);line-height:1.7;margin-bottom:1.5rem;">Use the training calendar to review planned programs and check back for newly published event details.</p>
                    <a href="<?php echo esc_url( home_url( '/training-calendar/' ) ); ?>" class="btn btn-primary btn-arrow">View Training Calendar</a>
                </div>
            </div>
        <?php endif; ?>
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1.25rem;">
            <a href="<?php echo esc_url( home_url( '/training-calendar/' ) ); ?>" class="card" style="display:block;text-decoration:none;border-top:3px solid var(--ember-primary);"><div class="card__body"><h3 style="color:var(--ember-primary);font-size:var(--size-base);">Training Calendar</h3><p style="color:var(--ink-light);font-size:.88rem;line-height:1.65;margin:0;">View the broader annual schedule by topic, recipient group and location.</p></div></a>
            <a href="<?php echo esc_url( home_url( '/prospectus/' ) ); ?>" class="card" style="display:block;text-decoration:none;border-top:3px solid var(--gold-primary);"><div class="card__body"><h3 style="color:var(--ember-primary);font-size:var(--size-base);">Prospectus</h3><p style="color:var(--ink-light);font-size:.88rem;line-height:1.65;margin:0;">Review annual program priorities and available training resources.</p></div></a>
        </div>
    </div>
</section>
<?php get_footer();
