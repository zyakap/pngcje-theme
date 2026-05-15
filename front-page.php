<?php
/**
 * front-page.php — PNGCJE Homepage
 * Ember branding · Real content from pngcje.gov.pg
 */
get_header(); ?>

<!-- HERO CAROUSEL -->
<?php
$hero_query = new WP_Query( [
    'post_type'      => 'pngcje_hero_slide',
    'posts_per_page' => 5,
    'post_status'    => 'publish',
    'orderby'        => [
        'menu_order' => 'ASC',
        'date'       => 'DESC',
    ],
] );

$hero_slides = [];
if ( $hero_query->have_posts() ) {
    while ( $hero_query->have_posts() ) {
        $hero_query->the_post();
        $hero_slides[] = [
            'image'       => get_the_post_thumbnail_url( get_the_ID(), 'pngcje-hero' ),
            'subheading'  => get_post_meta( get_the_ID(), '_pngcje_hero_subheading', true ) ?: __( 'Supreme and National Courts of Papua New Guinea', 'pngcje' ),
            'heading'     => get_post_meta( get_the_ID(), '_pngcje_hero_heading', true ) ?: get_the_title(),
            'intro'       => get_post_meta( get_the_ID(), '_pngcje_hero_intro', true ),
            'button_text' => get_post_meta( get_the_ID(), '_pngcje_hero_button_text', true ),
            'button_url'  => get_post_meta( get_the_ID(), '_pngcje_hero_button_url', true ),
        ];
    }
    wp_reset_postdata();
}

if ( empty( $hero_slides ) ) {
    $hero_slides[] = [
        'image'       => pngcje_theme_asset_url( 'assets/images/hero-default.jpg' ),
        'subheading'  => __( 'Supreme and National Courts of Papua New Guinea', 'pngcje' ),
        'heading'     => __( 'The Leading Judicial Education Institution in the Pacific', 'pngcje' ),
        'intro'       => __( 'Coordinating and delivering judicial education and training for the PNG Judiciary, Magisterial Services and the Department of Justice since 2010.', 'pngcje' ),
        'button_text' => __( 'About PNGCJE', 'pngcje' ),
        'button_url'  => home_url( '/about/' ),
    ];
}
?>
<section class="hero hero-carousel" aria-label="<?php esc_attr_e( 'Homepage Hero Carousel', 'pngcje' ); ?>" data-hero-carousel>
    <div class="hero-carousel__track">
        <?php foreach ( $hero_slides as $index => $slide ) : ?>
            <article class="hero-carousel__slide <?php echo 0 === $index ? 'is-active' : ''; ?>" data-hero-slide aria-hidden="<?php echo 0 === $index ? 'false' : 'true'; ?>">
                <div class="hero__bg" aria-hidden="true">
                    <?php if ( ! empty( $slide['image'] ) ) : ?>
                        <img src="<?php echo esc_url( $slide['image'] ); ?>" alt="" width="1920" height="960">
                    <?php else : ?>
                        <div class="hero__bg-fallback"></div>
                    <?php endif; ?>
                </div>
                <div class="hero__overlay" aria-hidden="true"></div>
                <div class="hero__accent" aria-hidden="true"></div>
                <div class="container">
                    <div class="hero__content">
                        <?php if ( ! empty( $slide['subheading'] ) ) : ?>
                            <div class="hero__eyebrow"><?php echo esc_html( $slide['subheading'] ); ?></div>
                        <?php endif; ?>
                        <h1 class="hero__title"><?php echo esc_html( $slide['heading'] ); ?></h1>
                        <?php if ( ! empty( $slide['intro'] ) ) : ?>
                            <p class="hero__subtitle"><?php echo esc_html( $slide['intro'] ); ?></p>
                        <?php endif; ?>
                        <div class="hero__actions">
                            <?php if ( ! empty( $slide['button_text'] ) && ! empty( $slide['button_url'] ) ) : ?>
                                <a href="<?php echo esc_url( $slide['button_url'] ); ?>" class="btn btn-primary btn-lg btn-arrow"><?php echo esc_html( $slide['button_text'] ); ?></a>
                            <?php endif; ?>
                            <a href="<?php echo esc_url( home_url( '/our-work/' ) ); ?>" class="btn btn-outline-white btn-lg"><?php esc_html_e( 'Our Work', 'pngcje' ); ?></a>
                        </div>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>

    <?php if ( count( $hero_slides ) > 1 ) : ?>
        <div class="hero-carousel__controls" aria-label="<?php esc_attr_e( 'Hero carousel controls', 'pngcje' ); ?>">
            <button type="button" class="hero-carousel__nav" data-hero-prev aria-label="<?php esc_attr_e( 'Previous slide', 'pngcje' ); ?>">‹</button>
            <div class="hero-carousel__dots" role="tablist" aria-label="<?php esc_attr_e( 'Hero slides', 'pngcje' ); ?>">
                <?php foreach ( $hero_slides as $index => $slide ) : ?>
                    <button
                        type="button"
                        class="hero-carousel__dot <?php echo 0 === $index ? 'is-active' : ''; ?>"
                        data-hero-dot="<?php echo esc_attr( $index ); ?>"
                        aria-label="<?php echo esc_attr( sprintf( __( 'Show slide %d', 'pngcje' ), $index + 1 ) ); ?>"
                        aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>"
                    ></button>
                <?php endforeach; ?>
            </div>
            <button type="button" class="hero-carousel__nav" data-hero-next aria-label="<?php esc_attr_e( 'Next slide', 'pngcje' ); ?>">›</button>
        </div>
    <?php endif; ?>

    <div class="hero__scroll" aria-hidden="true"><div class="hero__scroll-line"></div><span>Scroll</span></div>
</section>

<!-- STATS -->
<div class="stats-strip">
    <div class="container">
        <div class="stats-strip__inner">
            <?php
            $counter_defaults = [
                1 => [ 'number' => '2010', 'metric' => 'Established' ],
                2 => [ 'number' => '15',   'metric' => 'Pacific Jurisdictions' ],
                3 => [ 'number' => '500',  'metric' => 'Officers Trained Annually' ],
                4 => [ 'number' => '6',    'metric' => 'International Partners' ],
            ];
            for ( $i = 1; $i <= 4; $i++ ) :
                $counter_number = get_option( 'pngcje_counter_' . $i . '_number', $counter_defaults[$i]['number'] );
                $counter_metric = get_option( 'pngcje_counter_' . $i . '_metric', $counter_defaults[$i]['metric'] );
                $counter_number = '' !== $counter_number ? $counter_number : $counter_defaults[$i]['number'];
                $counter_metric = '' !== $counter_metric ? $counter_metric : $counter_defaults[$i]['metric'];
            ?>
            <div class="stats-strip__item reveal">
                <div class="stats-strip__number"><?php echo esc_html( $counter_number ); ?></div>
                <div class="stats-strip__label"><?php echo esc_html( $counter_metric ); ?></div>
            </div>
            <?php endfor; ?>
        </div>
    </div>
</div>

<!-- WELCOME + VIDEOS -->
<section class="section bg-white">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 420px;gap:5rem;align-items:center;">
            <div class="reveal">
                <div class="section-label">Welcome</div>
                <h2 class="section-title">Welcome to the Papua New Guinea Centre for Judicial Excellence</h2>
                <div class="divider"></div>
                <p style="color:var(--ink-mid);line-height:1.9;font-size:1.05rem;margin-bottom:1.25rem;">This is the official website of the Papua New Guinea Centre for Judicial Excellence (PNGCJE). The PNGCJE was established in 2010 and coordinates judicial education and training for the Papua New Guinea Judiciary, Magisterial Services and the Department of Justice and Attorney General.</p>
                <p style="color:var(--ink-mid);line-height:1.9;font-size:1.05rem;margin-bottom:2rem;">The purpose of the PNGCJE is to facilitate and coordinate structured professional training to all judicial officers, court officers and officers of the law and justice sector to help improve delivery of judicial services to the people.</p>
                <a href="<?php echo esc_url(home_url('/about/')); ?>" class="btn btn-primary btn-arrow">Learn More About Us</a>
            </div>
            <div class="reveal reveal-delay-2">
                <?php
                $youtube_url   = get_option( 'pngcje_youtube_video_url', '' );
                $youtube_embed = function_exists( 'pngcje_youtube_embed_url' ) ? pngcje_youtube_embed_url( $youtube_url ) : '';
                $youtube_live  = (int) get_option( 'pngcje_youtube_is_live', 0 );
                ?>
                <?php if ( $youtube_embed ) : ?>
                <div style="background:var(--ink);border-radius:var(--radius-lg);padding:1rem;margin-bottom:1.25rem;box-shadow:var(--shadow-lg);position:relative;overflow:hidden;">
                    <?php if ( $youtube_live ) : ?>
                    <style>
                        @keyframes pngcjeLivePulse {
                            0%, 100% { transform:scale(1); opacity:1; }
                            50% { transform:scale(1.08); opacity:.72; }
                        }
                    </style>
                    <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.75rem;color:#fff;font-size:.78rem;font-weight:900;text-transform:uppercase;letter-spacing:.12em;">
                        <span style="width:10px;height:10px;background:#f44336;border-radius:999px;display:inline-block;animation:pngcjeLivePulse 1s ease-in-out infinite;"></span>
                        <span style="background:#f44336;color:#fff;border-radius:999px;padding:.25rem .65rem;animation:pngcjeLivePulse 1s ease-in-out infinite;"><?php esc_html_e( 'LIVE', 'pngcje' ); ?></span>
                        <span><?php esc_html_e( 'Now Streaming', 'pngcje' ); ?></span>
                    </div>
                    <?php endif; ?>
                    <div style="position:relative;width:100%;padding-top:56.25%;border-radius:var(--radius-md);overflow:hidden;background:#000;">
                        <iframe src="<?php echo esc_url( $youtube_embed ); ?>" title="<?php esc_attr_e( 'PNGCJE YouTube video', 'pngcje' ); ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy" style="position:absolute;inset:0;width:100%;height:100%;border:0;"></iframe>
                    </div>
                </div>
                <?php endif; ?>
                <div style="background:var(--ember-subtle);border-radius:var(--radius-lg);padding:2rem;">
                    <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--ember-primary);margin-bottom:1.25rem;">📹 Video Messages</div>
                    <?php
                    $vids=[
                        ['Sir Gibbs Salika GCL, KBE, CSM, OBE','Chief Justice & Board Chairman, PNGCJE','https://pngcje.gov.pg/wp-content/uploads/2020/03/CJ_Salika_2020.mp4','⚖️'],
                        ['Hon. Justice John Carey','Judge Responsible for PNGCJE','https://pngcje.gov.pg/wp-content/uploads/2023/07/Justice-Dr.-John-Carey.mp4','🏛️'],
                    ];
                    foreach($vids as $v): ?>
                    <a href="<?php echo esc_url($v[2]); ?>" style="display:flex;align-items:center;gap:1rem;padding:1rem;background:var(--white);border-radius:var(--radius-md);margin-bottom:.75rem;text-decoration:none;border:1.5px solid var(--border-light);transition:all .25s;" onmouseover="this.style.borderColor='var(--ember-primary)';" onmouseout="this.style.borderColor='var(--border-light)';">
                        <div style="width:52px;height:52px;background:var(--ember-primary);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0;"><?php echo esc_html($v[3]); ?></div>
                        <div style="flex:1;"><div style="font-size:.875rem;font-weight:700;color:var(--ink);"><?php echo esc_html($v[0]); ?></div><div style="font-size:.72rem;color:var(--ink-light);margin-top:.1rem;"><?php echo esc_html($v[1]); ?></div></div>
                        <div style="color:var(--ember-primary);font-size:1.25rem;">▶</div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- QUICK ACCESS -->
<section class="section--sm" style="background:var(--ember-subtle);">
    <div class="container">
        <div class="section-header section-header--center reveal" style="margin-bottom:2.5rem;">
            <div class="section-label">Quick Access</div>
            <h2 class="section-title">What Are You Looking For?</h2>
        </div>
        <div class="quick-access__grid">
            <?php foreach([
                ['📖','Bench Books', pngcje_get_resource_type_url( 'bench-books' ), false ],
                ['📗','Judicial Handbook', pngcje_get_resource_type_url( 'judicial-handbook' ), false ],
                ['⚖️','Case Notes', pngcje_get_resource_type_url( 'case-notes' ), false ],
                ['🎓','CPD Lectures', pngcje_get_resource_type_url( 'cpd-lectures' ), false ],
                ['📅','Training Calendar',home_url('/training-calendar/'),false],
                ['🎓','Access LMS','https://learn.pngcje.gov.pg',true],
                ['📊','Annual Reports',home_url('/prospectus/annual-reports/'),false],
                ['📰','Newsletters',home_url('/newsletters/'),false],
                ['🎤','ED Speeches', pngcje_get_resource_type_url( 'executive-director-speeches' ), false ],
                ['📋','Prospectus',home_url('/prospectus/'),false],
            ] as $i=>$ql_item): $icon=$ql_item[0]; $label=$ql_item[1]; $url=$ql_item[2]; $ext=$ql_item[3]; ?>
            <a href="<?php echo esc_url($url); ?>" class="quick-access__item reveal reveal-delay-<?php echo($i%4)+1; ?>" <?php echo $ext?'target="_blank" rel="noopener noreferrer"':''; ?>>
                <div class="quick-access__icon" aria-hidden="true"><?php echo esc_html($icon); ?></div>
                <span class="quick-access__label"><?php echo esc_html($label); ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- LATEST NEWS -->
<section class="section bg-white">
    <div class="container">
        <div class="section-header flex-between reveal" style="margin-bottom:3rem;">
            <div><div class="section-label">Latest Updates</div><h2 class="section-title" style="margin-bottom:0;">News &amp; Updates</h2></div>
            <a href="<?php echo esc_url(home_url('/news/')); ?>" class="btn btn-outline btn-arrow" style="flex-shrink:0;">All News</a>
        </div>
        <?php $nq=new WP_Query(['post_type'=>'post','posts_per_page'=>5,'post_status'=>'publish','orderby'=>'date','order'=>'DESC']);
        if($nq->have_posts()): ?><div class="news-grid">
            <?php $c=0; while($nq->have_posts()):$nq->the_post();$c++; ?>
            <article class="card reveal reveal-delay-<?php echo min($c,4); ?>">
                <?php if(has_post_thumbnail()): ?><div class="card__image"><a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php the_post_thumbnail('pngcje-card',['alt'=>'']); ?></a></div><?php endif; ?>
                <div class="card__body">
                    <div class="card__meta"><span class="card__date"><?php echo get_the_date('j M Y'); ?></span><?php $cats=get_the_category();if($cats) echo '<span class="card__category">'.esc_html($cats[0]->name).'</span>'; ?></div>
                    <h3 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <p class="card__excerpt"><?php echo esc_html(pngcje_excerpt(null,18)); ?></p>
                    <a href="<?php the_permalink(); ?>" class="btn btn-ghost btn-arrow text-sm">Read More</a>
                </div>
            </article>
            <?php endwhile; wp_reset_postdata(); ?></div>
        <?php endif; ?>
    </div>
</section>

<!-- EVENTS -->
<section class="events-strip">
    <div class="container">
        <div class="events-strip__header">
            <div><div class="section-label">Training &amp; Events</div><h2 class="section-title">Upcoming Events</h2></div>
            <a href="<?php echo esc_url(get_post_type_archive_link('pngcje_event')?:home_url('/events/')); ?>" class="btn btn-outline-white btn-arrow">View All Events</a>
        </div>
        <div class="events-list">
            <?php $ev=pngcje_get_upcoming_events(3);
            if(!empty($ev)) echo pngcje_homepage_events(3);
            else echo '<div class="event-card" style="grid-column:1/-1;text-align:center;padding:2rem;"><p style="color:rgba(255,255,255,.55);">No upcoming events. Check back soon.</p><a href="'.esc_url(home_url('/prospectus/training-calendar/')).'" class="btn btn-outline-white" style="margin-top:1rem;">View Training Calendar</a></div>'; ?>
        </div>
    </div>
</section>

<!-- OBJECTIVES -->
<section class="section" style="background:var(--surface);">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:5rem;align-items:center;">
            <div class="reveal">
                <div class="section-label">Our Purpose</div>
                <h2 class="section-title">Three Key Objectives</h2>
                <div class="divider"></div>
                <p style="color:var(--ink-mid);line-height:1.9;margin-bottom:2rem;">The PNGCJE was established with three core objectives guiding everything from individual training programs to regional capacity building across the Pacific.</p>
                <div style="display:flex;flex-direction:column;gap:1rem;counter-reset:obj-counter;" class="mission-objectives">
                    <?php foreach(['Promote judicial excellence across all levels of the PNG Judiciary and Magisterial Services','Promote professional development and structured training programs','Foster awareness of judicial administration, developments in the law, and social and community issues'] as $obj): ?>
                    <div class="mission-objective-item"><?php echo esc_html($obj); ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="reveal reveal-delay-2" style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
                <?php foreach([
                    ['⚖️','Judges & Magistrates','Training judicial officers at all levels of the PNG courts system.'],
                    ['🏛️','Court Officers','Professional development for registry, secretarial and support staff.'],
                    ['🌊','Pacific Region','Extending judicial education programs to other Pacific island jurisdictions.'],
                    ['🎓','Continuing Education','CPD lectures, lecture series, orientation programs and workshops.'],
                ] as $i=>$obj_item): $icon=$obj_item[0]; $h=$obj_item[1]; $p=$obj_item[2]; ?>
                <div class="card reveal reveal-delay-<?php echo $i+1; ?>" style="border-top:3px solid var(--ember-primary);">
                    <div class="card__body" style="text-align:center;padding:1.5rem 1rem;">
                        <div style="font-size:2.25rem;margin-bottom:.75rem;"><?php echo esc_html($icon); ?></div>
                        <h3 style="font-size:.875rem;font-weight:700;color:var(--ember-primary);margin-bottom:.5rem;"><?php echo esc_html($h); ?></h3>
                        <p style="font-size:.8rem;color:var(--ink-light);line-height:1.6;margin:0;"><?php echo esc_html($p); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- INTERNATIONAL PARTNERS -->
<section class="section--sm bg-white" style="border-top:1px solid var(--border-light);">
    <div class="container">
        <div class="reveal" style="text-align:center;margin-bottom:2.5rem;">
            <div class="section-label" style="justify-content:center;">International Partners</div>
            <h2 class="section-title" style="font-size:var(--size-2xl);">We Work Closely With</h2>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:1rem;justify-content:center;">
            <?php foreach(['Commonwealth Judicial Education Institute (CJEI), Canada','Judicial Commission of New South Wales','Pacific Judicial Strengthening Initiative (PJSI)','National Judicial College of Australia (NJCA)','Institute of Judicial Studies New Zealand (IJS)','UK Judicial College'] as $p): ?>
            <div style="background:var(--ember-subtle);border:1.5px solid rgba(212,88,26,.2);border-radius:var(--radius-md);padding:.65rem 1.25rem;font-size:.8rem;font-weight:600;color:var(--ember-deep);"><?php echo esc_html($p); ?></div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- PARTNERS -->
<div class="partners-strip">
    <div class="container">
        <p class="partners-strip__label">Our Links &amp; Partners</p>
        <div class="partners-strip__list">
            <?php foreach([['PNG Judiciary','https://www.pngjudiciary.gov.pg/'],['Dept of Justice & AG','https://www.justice.gov.pg/'],['Magisterial Services','http://www.magisterialservices.gov.pg/'],['PacLII','http://www.paclii.org/'],['PNG National Parliament','http://www.parliament.gov.pg/']] as $pl): $n=$pl[0]; $u=$pl[1]; ?>
            <a href="<?php echo esc_url($u); ?>" class="partners-strip__item" target="_blank" rel="noopener noreferrer"><?php echo esc_html($n); ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- NEWSLETTER -->
<section class="newsletter-section">
    <div class="container newsletter-section__inner">
        <div class="section-label" style="color:var(--gold-light);justify-content:center;">Stay Informed</div>
        <h2>Subscribe to Our Newsletter</h2>
        <p>Receive the latest news, training updates and publications from the PNGCJE directly to your inbox.</p>
        <?php
        $pngcje_newsletter_gf_id      = absint( get_theme_mod( 'pngcje_newsletter_gravity_form_id', 0 ) );
        $pngcje_newsletter_native_id = absint( get_theme_mod( 'pngcje_newsletter_pngcje_form_id', 65 ) );
        if ( function_exists( 'gravity_form' ) && $pngcje_newsletter_gf_id > 0 ) :
            gravity_form( $pngcje_newsletter_gf_id, false, false, false, null, true );
        elseif ( $pngcje_newsletter_native_id > 0 ) :
            echo do_shortcode( '[pngcje_form id="' . $pngcje_newsletter_native_id . '"]' );
        endif;
        ?>
    </div>
</section>

<?php get_footer(); ?>
