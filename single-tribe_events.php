<?php
/**
 * single-tribe_events.php
 * The Events Calendar — Single Event Template
 */

get_header();

while ( have_posts() ) : the_post();

$start_date  = tribe_get_start_date( null, false, 'l, j F Y' );
$start_time  = tribe_get_start_date( null, false, 'g:i A' );
$end_date    = tribe_get_end_date( null, false, 'l, j F Y' );
$end_time    = tribe_get_end_date( null, false, 'g:i A' );
$venue       = tribe_get_venue();
$venue_addr  = tribe_get_full_address();
$organizer   = tribe_get_organizer();
$org_email   = tribe_get_organizer_email();
$org_phone   = tribe_get_organizer_phone();
$website     = tribe_get_event_website_url();
$cost        = tribe_get_cost();

?>

<div <?php pngcje_page_hero_attrs(); ?>>
    <div class="container">
        <div class="page-hero__eyebrow">
            <a href="<?php echo esc_url( home_url('/') ); ?>"><?php esc_html_e( 'Home', 'pngcje' ); ?></a>
            <span>›</span>
            <a href="<?php echo esc_url( tribe_get_events_link() ); ?>"><?php esc_html_e( 'Events', 'pngcje' ); ?></a>
            <span>›</span>
            <span><?php the_title(); ?></span>
        </div>

        <!-- Event date badge -->
        <?php if ( $start_date ) : ?>
        <div style="display:inline-flex;align-items:center;gap:0.75rem;background:rgba(212,150,10,0.15);border:1px solid rgba(212,150,10,0.30);border-radius:var(--radius-full);padding:0.4rem 1rem;margin-bottom:1rem;">
            <span style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--gold-light);">
                📅 <?php echo esc_html( $start_date ); ?>
            </span>
        </div>
        <?php endif; ?>

        <h1 class="page-hero__title"><?php the_title(); ?></h1>

        <?php if ( $venue ) : ?>
        <p class="page-hero__desc">📍 <?php echo esc_html( $venue ); ?><?php echo $venue_addr ? ' — ' . esc_html( $venue_addr ) : ''; ?></p>
        <?php endif; ?>
    </div>
</div>

<section class="section">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 340px;gap:4rem;align-items:start;">

            <!-- Event Content -->
            <div>
                <?php if ( has_post_thumbnail() ) : ?>
                <div style="margin-bottom:2.5rem;border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-lg);">
                    <?php the_post_thumbnail( 'pngcje-wide', [ 'style' => 'width:100%;max-height:480px;object-fit:cover;', 'alt' => '' ] ); ?>
                </div>
                <?php endif; ?>

                <div class="entry-content" style="font-size:var(--size-md);line-height:var(--leading-loose);color:var(--ink-mid);">
                    <?php the_content(); ?>
                </div>

                <?php if ( $website ) : ?>
                <div style="margin-top:2rem;">
                    <a href="<?php echo esc_url( $website ); ?>" class="btn btn-primary btn-arrow" target="_blank" rel="noopener noreferrer">
                        <?php esc_html_e( 'Event Website', 'pngcje' ); ?>
                    </a>
                </div>
                <?php endif; ?>

                <div style="margin-top:3rem;padding-top:2rem;border-top:1px solid var(--border-light);display:flex;gap:1rem;flex-wrap:wrap;">
                    <a href="<?php echo esc_url( tribe_get_events_link() ); ?>" class="btn btn-outline">
                        ← <?php esc_html_e( 'Back to Events', 'pngcje' ); ?>
                    </a>
                </div>
            </div>

            <!-- Event Details Sidebar -->
            <aside>
                <div class="card" style="border-top:4px solid var(--gold-primary);margin-bottom:1.5rem;">
                    <div class="card__body">
                        <h2 style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--green-dark);margin-bottom:1.5rem;">
                            <?php esc_html_e( 'Event Details', 'pngcje' ); ?>
                        </h2>

                        <div style="display:flex;flex-direction:column;gap:1.25rem;">

                            <!-- Date & Time -->
                            <div style="display:flex;gap:0.75rem;align-items:flex-start;">
                                <div style="width:36px;height:36px;background:var(--green-subtle);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;" aria-hidden="true">📅</div>
                                <div>
                                    <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--gold-primary);margin-bottom:0.2rem;">
                                        <?php esc_html_e( 'Date & Time', 'pngcje' ); ?>
                                    </div>
                                    <div style="font-size:0.875rem;font-weight:600;color:var(--ink);"><?php echo esc_html( $start_date ); ?></div>
                                    <?php if ( $start_time ) : ?>
                                    <div style="font-size:0.8rem;color:var(--ink-light);"><?php echo esc_html( $start_time ); ?><?php echo $end_time ? ' – ' . esc_html( $end_time ) : ''; ?></div>
                                    <?php endif; ?>
                                    <?php if ( $end_date && $end_date !== $start_date ) : ?>
                                    <div style="font-size:0.75rem;color:var(--ink-light);margin-top:0.2rem;">
                                        <?php printf( esc_html__( 'Ends: %s', 'pngcje' ), esc_html( $end_date ) ); ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Venue -->
                            <?php if ( $venue ) : ?>
                            <div style="display:flex;gap:0.75rem;align-items:flex-start;">
                                <div style="width:36px;height:36px;background:var(--green-subtle);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;" aria-hidden="true">📍</div>
                                <div>
                                    <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--gold-primary);margin-bottom:0.2rem;">
                                        <?php esc_html_e( 'Venue', 'pngcje' ); ?>
                                    </div>
                                    <div style="font-size:0.875rem;font-weight:600;color:var(--ink);"><?php echo esc_html( $venue ); ?></div>
                                    <?php if ( $venue_addr ) : ?>
                                    <div style="font-size:0.78rem;color:var(--ink-light);"><?php echo esc_html( $venue_addr ); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Cost -->
                            <?php if ( $cost ) : ?>
                            <div style="display:flex;gap:0.75rem;align-items:flex-start;">
                                <div style="width:36px;height:36px;background:var(--green-subtle);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;" aria-hidden="true">💰</div>
                                <div>
                                    <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--gold-primary);margin-bottom:0.2rem;">
                                        <?php esc_html_e( 'Cost', 'pngcje' ); ?>
                                    </div>
                                    <div style="font-size:0.875rem;font-weight:600;color:var(--ink);"><?php echo esc_html( $cost ); ?></div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Organizer -->
                            <?php if ( $organizer ) : ?>
                            <div style="display:flex;gap:0.75rem;align-items:flex-start;">
                                <div style="width:36px;height:36px;background:var(--green-subtle);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;" aria-hidden="true">👤</div>
                                <div>
                                    <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--gold-primary);margin-bottom:0.2rem;">
                                        <?php esc_html_e( 'Organiser', 'pngcje' ); ?>
                                    </div>
                                    <div style="font-size:0.875rem;font-weight:600;color:var(--ink);"><?php echo esc_html( $organizer ); ?></div>
                                    <?php if ( $org_email ) : ?>
                                    <a href="mailto:<?php echo esc_attr( $org_email ); ?>" style="font-size:0.78rem;color:var(--green-dark);"><?php echo esc_html( $org_email ); ?></a>
                                    <?php endif; ?>
                                    <?php if ( $org_phone ) : ?>
                                    <div style="font-size:0.78rem;color:var(--ink-light);"><?php echo esc_html( $org_phone ); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                        </div>

                        <!-- Registration / Enquiry via Gravity Forms -->
                        <div style="margin-top:2rem;padding-top:1.5rem;border-top:1px solid var(--border-light);">
                            <?php if ( function_exists( 'gravity_form' ) ) : ?>
                                <h3 style="font-size:0.8rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--ink);margin-bottom:1rem;">
                                    <?php esc_html_e( 'Register / Enquire', 'pngcje' ); ?>
                                </h3>
                                <?php
                                // Form ID 3 = Event Registration form
                                gravity_form( 3, false, false, false, [
                                    'event_id'    => get_the_ID(),
                                    'event_title' => get_the_title(),
                                ], true );
                              ?>
                            <?php else : ?>
                                <a href="<?php echo esc_url( home_url('/contact-us/') ); ?>" class="btn btn-gold" style="width:100%;justify-content:center;">
                                    <?php esc_html_e( 'Enquire About This Event', 'pngcje' ); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Add to Calendar -->
                <div class="card" style="border-left:4px solid var(--gold-primary);">
                    <div class="card__body" style="text-align:center;">
                        <div style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--ink-light);margin-bottom:1rem;">
                            <?php esc_html_e( 'Add to Calendar', 'pngcje' ); ?>
                        </div>
                        <div style="display:flex;gap:0.5rem;justify-content:center;flex-wrap:wrap;">
                            <?php
                            $ical_url = tribe_get_single_ical_link();
                            if ( $ical_url ) :
                          ?>
                            <a href="<?php echo esc_url( $ical_url ); ?>" class="btn btn-outline btn-sm">
                                📅 iCal
                            </a>
                            <?php endif; ?>
                            <?php if ( function_exists( 'tribe_get_gcal_link' ) ) : ?>
                            <a href="<?php echo esc_url( tribe_get_gcal_link() ); ?>" class="btn btn-outline btn-sm" target="_blank" rel="noopener noreferrer">
                                📅 Google
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </aside>
        </div>
    </div>
</section>

<?php
endwhile;
get_footer();
?>
