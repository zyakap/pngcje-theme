<?php
/**
 * Template Name: Customer Service
 * Customer Service Charter page.
 */
get_header();

$expectations = [
    'Meet with you and discuss your training needs',
    'Develop curriculum, plan, design and deliver training programs',
    'Monitor and evaluate your progress after training',
    'Provide state of the art training facilities',
    'Support your transition into your new career and constantly monitor your professional development',
    'Engage subject matter experts to plan, design and deliver tailored training programs for your specific needs',
];

$clients = [
    'Judges',
    'Magistrates',
    'Judges associates, court interpreters, attendants and reporters',
    'Registry staff',
    "Sheriff's officers and staff",
    'Corporate management staff',
    'Law and Justice Sector agencies staff engaged in court processes',
    'Village Courts Magistrates and lay or non-lawyer trained Court Officers',
    'Regional Pacific Island Countries and international Judges and court support staff',
    'Other stakeholders on an ad hoc basis, including lawyers, the public, legislature, executive and non-judicial members of administrative tribunals and school children/students',
];

$feedback_channels = [
    [
        'title' => 'Website',
        'text'  => "Click and send us a comment via Contact Us on our website.",
        'url'   => home_url( '/contact-us/' ),
    ],
    [
        'title' => 'Evaluation forms',
        'text'  => 'Fill out our evaluation forms after every training activity.',
    ],
    [
        'title' => 'Program Officers',
        'text'  => 'Send an email to our Program Officers.',
    ],
    [
        'title' => 'Phone',
        'text'  => 'Call us on +675 324 5500.',
        'url'   => 'tel:+6753245500',
    ],
];

$program_officers = new WP_Query( [
    'post_type'      => 'pngcje_prog_officer',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => [
        'menu_order' => 'ASC',
        'title'      => 'ASC',
    ],
] );
?>

<div <?php pngcje_page_hero_attrs(); ?>>
    <div class="container">
        <div class="page-hero__eyebrow"><?php pngcje_breadcrumbs(); ?></div>
        <h1 class="page-hero__title"><?php the_title(); ?></h1>
        <p class="page-hero__desc">
            <?php esc_html_e( 'Your professional development is our business. This charter sets out the service standards you can expect from PNGCJE when engaging with our judicial education programs.', 'pngcje' ); ?>
        </p>
    </div>
</div>

<section class="section charter-page">
    <div class="container">
        <div class="charter-layout">
            <main>
                <section class="charter-intro reveal">
                    <article class="charter-statement charter-statement--vision">
                        <span><?php esc_html_e( 'Vision', 'pngcje' ); ?></span>
                        <h2><?php esc_html_e( 'An avenue where experts come together', 'pngcje' ); ?></h2>
                        <p><?php esc_html_e( 'An Avenue where Experts come together to share knowledge, skills and expertise to be the best in their roles, enabling access to Justice and public confidence in the Judicial system.', 'pngcje' ); ?></p>
                    </article>
                    <article class="charter-statement">
                        <span><?php esc_html_e( 'Mission', 'pngcje' ); ?></span>
                        <h2><?php esc_html_e( 'Excellence in Court Business', 'pngcje' ); ?></h2>
                        <p><?php esc_html_e( 'Promote a high standard of Excellence in all Court Business.', 'pngcje' ); ?></p>
                    </article>
                </section>

                <section class="charter-band reveal">
                    <div>
                        <div class="section-label"><?php esc_html_e( 'Purpose', 'pngcje' ); ?></div>
                        <h2 class="section-title"><?php esc_html_e( 'Clear service delivery for every training engagement', 'pngcje' ); ?></h2>
                    </div>
                    <p><?php esc_html_e( 'The charter entails the service delivery process that Judges, Magistrates and Court staff can observe when engaging in our training programs.', 'pngcje' ); ?></p>
                </section>

                <section class="charter-section reveal">
                    <div class="section-label"><?php esc_html_e( 'Our Objective', 'pngcje' ); ?></div>
                    <h2 class="section-title"><?php esc_html_e( 'Professional development is our business', 'pngcje' ); ?></h2>
                    <p><?php esc_html_e( 'We will strive to ensure that all Judicial Officers, magistrates, court staff and officers of the Law and Justice sector are equipped with sound knowledge and prepared to manage the unique challenges of their Judicial role.', 'pngcje' ); ?></p>
                </section>

                <section class="charter-section reveal">
                    <div class="section-label"><?php esc_html_e( 'Our Service', 'pngcje' ); ?></div>
                    <h2 class="section-title"><?php esc_html_e( 'Planning, designing and delivering judicial education', 'pngcje' ); ?></h2>
                    <p><?php esc_html_e( 'We plan, design and deliver training programs, seminars, workshops, forums, conferences and webinars for Judges, Magistrates and Court Officers.', 'pngcje' ); ?></p>
                </section>

                <section class="charter-section reveal">
                    <div class="section-label"><?php esc_html_e( 'What To Expect', 'pngcje' ); ?></div>
                    <h2 class="section-title"><?php esc_html_e( 'At PngCJE, we are prepared', 'pngcje' ); ?></h2>
                    <div class="charter-check-grid">
                        <?php foreach ( $expectations as $item ) : ?>
                            <div class="charter-check">
                                <span aria-hidden="true"></span>
                                <p><?php echo esc_html( $item ); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <section class="charter-section reveal">
                    <div class="section-label"><?php esc_html_e( 'Our Clients', 'pngcje' ); ?></div>
                    <h2 class="section-title"><?php esc_html_e( 'PngCJE services are extended to', 'pngcje' ); ?></h2>
                    <div class="charter-client-list">
                        <?php foreach ( $clients as $item ) : ?>
                            <p><?php echo esc_html( $item ); ?></p>
                        <?php endforeach; ?>
                    </div>
                </section>

                <section class="charter-section reveal" id="program-officers">
                    <div class="section-label"><?php esc_html_e( 'Our Program Officers', 'pngcje' ); ?></div>
                    <h2 class="section-title"><?php esc_html_e( 'Practical support for your training needs', 'pngcje' ); ?></h2>
                    <p><?php esc_html_e( 'You will find our Program Officers useful in facilitating your training programs. They are more than happy to assist in designing and delivering training programs to suit your training needs. Feel free to send an email to any of the following.', 'pngcje' ); ?></p>

                    <?php if ( $program_officers->have_posts() ) : ?>
                        <div class="program-officer-grid">
                            <?php while ( $program_officers->have_posts() ) : $program_officers->the_post(); ?>
                                <?php
                                $role  = get_post_meta( get_the_ID(), '_pngcje_program_officer_role', true );
                                $email = get_post_meta( get_the_ID(), '_pngcje_program_officer_email', true );
                                $phone = get_post_meta( get_the_ID(), '_pngcje_program_officer_phone', true );
                                ?>
                                <article class="program-officer-card">
                                    <div class="program-officer-card__photo">
                                        <?php if ( has_post_thumbnail() ) : ?>
                                            <?php the_post_thumbnail( 'pngcje-staff', [ 'alt' => get_the_title() ] ); ?>
                                        <?php else : ?>
                                            <div class="program-officer-card__avatar">
                                                <?php echo esc_html( mb_strtoupper( mb_substr( get_the_title(), 0, 1 ) ) ); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="program-officer-card__body">
                                        <h3><?php the_title(); ?></h3>
                                        <?php if ( $role ) : ?>
                                            <p class="program-officer-card__role"><?php echo esc_html( $role ); ?></p>
                                        <?php endif; ?>
                                        <?php if ( $email ) : ?>
                                            <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
                                        <?php endif; ?>
                                        <?php if ( $phone ) : ?>
                                            <a href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
                                        <?php endif; ?>
                                    </div>
                                </article>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </div>
                    <?php else : ?>
                        <div class="charter-empty">
                            <p><?php esc_html_e( 'Program Officers will appear here once they are added from the WordPress dashboard.', 'pngcje' ); ?></p>
                        </div>
                    <?php endif; ?>
                </section>

                <section class="charter-board reveal">
                    <div>
                        <div class="section-label"><?php esc_html_e( 'PngCJE Board of Directors', 'pngcje' ); ?></div>
                        <h2 class="section-title"><?php esc_html_e( 'Oversight for training programs', 'pngcje' ); ?></h2>
                        <p><?php esc_html_e( 'The PngCJE Board of Directors chaired by the Chief Justice of Papua New Guinea examine and approve all training programs planned by PngCJE.', 'pngcje' ); ?></p>
                    </div>
                    <a class="btn btn-primary" href="<?php echo esc_url( home_url( '/about/governance/' ) ); ?>">
                        <?php esc_html_e( 'View Board of Directors', 'pngcje' ); ?>
                    </a>
                </section>

                <section class="charter-section charter-important reveal">
                    <div class="section-label"><?php esc_html_e( 'Most Importantly', 'pngcje' ); ?></div>
                    <h2 class="section-title"><?php esc_html_e( 'Committed to judicial education and public confidence', 'pngcje' ); ?></h2>
                    <p><?php esc_html_e( 'We are committed to doing our very best in delivering our mandated task to provide judicial education for court staff and Judicial officers for your Professional development.', 'pngcje' ); ?></p>
                    <p><?php esc_html_e( 'We want our court officers to be well equipped with the necessary skills, knowledge and attitudes in dispensing justice for the wellbeing and development of the society at large.', 'pngcje' ); ?></p>
                </section>

                <section class="charter-section reveal">
                    <div class="section-label"><?php esc_html_e( 'Feedback', 'pngcje' ); ?></div>
                    <h2 class="section-title"><?php esc_html_e( 'Help us serve you better', 'pngcje' ); ?></h2>
                    <p><?php esc_html_e( 'Your feedback on our service is our tool for perfection. Compliments or complaints can be sent to us through the following channels.', 'pngcje' ); ?></p>

                    <div class="feedback-grid">
                        <?php foreach ( $feedback_channels as $channel ) : ?>
                            <<?php echo ! empty( $channel['url'] ) ? 'a' : 'div'; ?>
                                class="feedback-card"
                                <?php if ( ! empty( $channel['url'] ) ) : ?>href="<?php echo esc_url( $channel['url'] ); ?>"<?php endif; ?>>
                                <h3><?php echo esc_html( $channel['title'] ); ?></h3>
                                <p><?php echo esc_html( $channel['text'] ); ?></p>
                            </<?php echo ! empty( $channel['url'] ) ? 'a' : 'div'; ?>>
                        <?php endforeach; ?>
                    </div>

                    <p><?php esc_html_e( 'We will review and renew this customer service charter regularly to meet and exceed your expectations.', 'pngcje' ); ?></p>
                    <p><?php esc_html_e( 'PngCJE is ultimately responsible for your Professional development. If you are not satisfied with the outcome of our service, please do not hesitate to contact us. We will ensure that we respond to your concern in a timely manner.', 'pngcje' ); ?></p>
                </section>
            </main>

            <aside class="charter-aside">
                <div class="charter-aside__panel">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/pngcje_logo.png' ); ?>" alt="<?php esc_attr_e( 'PNGCJE', 'pngcje' ); ?>">
                    <p><?php esc_html_e( 'Customer Service Charter', 'pngcje' ); ?></p>
                    <a href="#program-officers"><?php esc_html_e( 'Contact Program Officers', 'pngcje' ); ?></a>
                </div>
                <?php get_template_part( 'template-parts/sidebar', 'ourwork' ); ?>
            </aside>
        </div>
    </div>
</section>

<style>
.charter-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 320px;
    gap: 4rem;
    align-items: start;
}
.charter-intro {
    display: grid;
    grid-template-columns: minmax(0, 1.2fr) minmax(0, .8fr);
    gap: 1.25rem;
    margin-bottom: 3rem;
}
.charter-statement,
.charter-band,
.charter-board,
.charter-important,
.charter-aside__panel {
    background: var(--white);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-sm);
}
.charter-statement {
    padding: 2rem;
    border-top: 4px solid var(--green-dark);
}
.charter-statement--vision {
    border-top-color: var(--gold-primary);
}
.charter-statement span,
.feedback-card h3 {
    display: block;
    color: var(--ember-primary);
    font-size: var(--size-xs);
    font-weight: 800;
    letter-spacing: .08em;
    margin-bottom: .75rem;
    text-transform: uppercase;
}
.charter-statement h2 {
    color: var(--ink);
    font-size: var(--size-xl);
    line-height: 1.25;
    margin-bottom: .85rem;
}
.charter-statement p,
.charter-band p,
.charter-section p,
.charter-board p {
    color: var(--ink-mid);
    font-size: var(--size-md);
    line-height: 1.85;
}
.charter-band,
.charter-board {
    display: grid;
    grid-template-columns: minmax(0, .8fr) minmax(0, 1.2fr);
    gap: 2rem;
    margin-bottom: 3rem;
    padding: 2rem;
}
.charter-section {
    margin-bottom: 3rem;
}
.charter-section .section-title,
.charter-band .section-title,
.charter-board .section-title {
    font-size: var(--size-2xl);
    margin-bottom: 1rem;
}
.charter-check-grid,
.feedback-grid,
.program-officer-grid {
    display: grid;
    gap: 1rem;
    margin-top: 1.5rem;
}
.charter-check-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
}
.charter-check,
.feedback-card {
    background: var(--white);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-sm);
    padding: 1.25rem;
}
.charter-check {
    display: flex;
    gap: .85rem;
}
.charter-check span {
    background: var(--green-dark);
    border-radius: 999px;
    flex: 0 0 auto;
    height: .7rem;
    margin-top: .5rem;
    width: .7rem;
}
.charter-check p,
.feedback-card p {
    font-size: .92rem;
    line-height: 1.65;
    margin: 0;
}
.charter-client-list {
    column-count: 2;
    column-gap: 1.5rem;
    margin-top: 1.5rem;
}
.charter-client-list p {
    background: var(--green-subtle);
    border-left: 3px solid var(--green-dark);
    break-inside: avoid;
    margin: 0 0 .8rem;
    padding: .85rem 1rem;
}
.program-officer-grid {
    grid-template-columns: repeat(3, minmax(0, 1fr));
}
.program-officer-card {
    background: var(--white);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}
.program-officer-card__photo {
    aspect-ratio: 1 / 1;
    background: var(--green-subtle);
    overflow: hidden;
}
.program-officer-card__photo img {
    height: 100%;
    object-fit: cover;
    object-position: top center;
    width: 100%;
}
.program-officer-card__avatar {
    align-items: center;
    color: var(--green-dark);
    display: flex;
    font-size: 2.25rem;
    font-weight: 900;
    height: 100%;
    justify-content: center;
    width: 100%;
}
.program-officer-card__body {
    border-top: 3px solid var(--gold-primary);
    padding: 1.25rem;
}
.program-officer-card h3 {
    color: var(--ink);
    font-size: var(--size-base);
    line-height: 1.3;
    margin-bottom: .35rem;
}
.program-officer-card__role {
    color: var(--green-dark);
    font-size: var(--size-sm);
    font-weight: 700;
    margin-bottom: .75rem;
}
.program-officer-card a {
    color: var(--ink-light);
    display: block;
    font-size: var(--size-xs);
    line-height: 1.45;
    margin-top: .35rem;
    overflow-wrap: anywhere;
}
.charter-empty {
    background: var(--ember-subtle);
    border: 1.5px dashed rgba(212, 88, 26, .3);
    border-radius: var(--radius-md);
    margin-top: 1.5rem;
    padding: 1.5rem;
}
.charter-board {
    align-items: center;
    grid-template-columns: minmax(0, 1fr) auto;
}
.charter-important {
    border-top: 4px solid var(--ember-primary);
    padding: 2rem;
}
.feedback-grid {
    grid-template-columns: repeat(4, minmax(0, 1fr));
}
.feedback-card {
    display: block;
    text-decoration: none;
}
.charter-aside {
    position: sticky;
    top: 110px;
}
.charter-aside__panel {
    margin-bottom: 1.25rem;
    padding: 1.5rem;
    text-align: center;
}
.charter-aside__panel img {
    height: auto;
    margin: 0 auto 1rem;
    max-width: 110px;
}
.charter-aside__panel p {
    color: var(--ink);
    font-weight: 800;
    margin-bottom: 1rem;
}
.charter-aside__panel a {
    color: var(--green-dark);
    font-size: var(--size-sm);
    font-weight: 800;
    text-decoration: none;
}
@media (max-width: 1100px) {
    .charter-layout {
        grid-template-columns: 1fr;
    }
    .charter-aside {
        position: static;
    }
}
@media (max-width: 820px) {
    .charter-intro,
    .charter-band,
    .charter-board,
    .charter-check-grid,
    .program-officer-grid,
    .feedback-grid {
        grid-template-columns: 1fr;
    }
    .charter-client-list {
        column-count: 1;
    }
}
</style>

<?php get_footer(); ?>
