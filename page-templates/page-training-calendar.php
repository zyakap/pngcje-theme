<?php
/**
 * Template Name: Training Calendar
 * Refreshed training calendar landing page.
 */
get_header();

$featured_programs = [
    [ 'Curriculum Development and Training Activity Development', '19 - 26 January', 'PNGCJE, HR and Registry Training Coordinators', 'Koitaki CC, Sogeri' ],
    [ 'IT Skills, Time Management and Productivity', '3 - 6 February', 'NJSS Staff and MS Staff', 'Kimbe, WNBP' ],
    [ 'Pacific Judicial Conference', '8 - 12 February', 'Selected Judicial and Court Staff', 'New Zealand' ],
    [ 'AI for Judges', '18 - 20 February', 'Selected Waigani-based Judges', 'Port Moresby' ],
    [ 'Fraud and Corruption', '25 - 27 March', 'Regional Court Staff', 'Port Moresby' ],
    [ 'Human Rights and Sorcery Related Issues', 'Q1, dates to be confirmed', 'All Magistrates', 'Four regions' ],
];
?>
<div <?php pngcje_page_hero_attrs(); ?>>
    <div class="container">
        <div class="page-hero__eyebrow"><?php pngcje_breadcrumbs(); ?></div>
        <div class="section-label" style="margin-bottom:.75rem;">Prospectus</div>
        <h1 class="page-hero__title">Training Calendar</h1>
        <p class="page-hero__desc">A refreshed overview of scheduled PNGCJE training activities, target participants and delivery locations.</p>
    </div>
</div>
<section class="section">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 320px;gap:4rem;align-items:start;">
            <div>
                <div class="reveal" style="margin-bottom:2.5rem;">
                    <div class="section-label">2025 Program Snapshot</div>
                    <h2 class="section-title" style="font-size:var(--size-2xl);">Featured training activities</h2>
                    <p style="color:var(--ink-mid);line-height:1.8;">The original calendar has been preserved and reorganised into a clearer program snapshot for the redesign. Detailed dates can continue to be maintained through the Events system or resource uploads.</p>
                </div>
                <div style="display:flex;flex-direction:column;gap:1rem;">
                    <?php foreach ( $featured_programs as $program ) : ?>
                        <article class="card card--resource reveal">
                            <div style="width:84px;flex-shrink:0;text-align:center;background:var(--ember-subtle);border-radius:var(--radius-md);padding:.75rem .5rem;">
                                <div style="font-size:.7rem;font-weight:800;color:var(--ember-primary);text-transform:uppercase;letter-spacing:.04em;">Date</div>
                                <div style="font-size:.78rem;font-weight:700;color:var(--ink);margin-top:.25rem;"><?php echo esc_html( $program[1] ); ?></div>
                            </div>
                            <div style="flex:1;">
                                <h3 style="font-size:var(--size-base);font-weight:800;color:var(--ink);margin-bottom:.35rem;"><?php echo esc_html( $program[0] ); ?></h3>
                                <p style="font-size:.85rem;color:var(--ink-light);margin:0;"><?php echo esc_html( $program[2] ); ?> - <?php echo esc_html( $program[3] ); ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
            <aside><?php get_template_part( 'template-parts/sidebar', 'ourwork' ); ?></aside>
        </div>
    </div>
</section>
<?php get_footer();
