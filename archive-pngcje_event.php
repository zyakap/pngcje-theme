<?php
/**
 * archive-pngcje_event.php
 * PNGCJE Custom Events — Archive (Training Calendar)
 */

get_header();

// Separate upcoming from past
$upcoming = pngcje_get_events(['count' => 50, 'future' => true]);
$past     = pngcje_get_events([
    'count'  => 20,
    'future' => false,
]);
// Past = events where timestamp < now
$past = get_posts([
    'post_type'      => 'pngcje_event',
    'post_status'    => 'publish',
    'posts_per_page' => 20,
    'meta_key'       => '_pngcje_event_start_timestamp',
    'orderby'        => 'meta_value_num',
    'order'          => 'DESC',
    'meta_query'     => [[
        'key'     => '_pngcje_event_start_timestamp',
        'value'   => time(),
        'compare' => '<',
        'type'    => 'NUMERIC',
    ]],
]);

$categories = get_terms(['taxonomy'=>'event_category','hide_empty'=>true]);
$ical_url   = add_query_arg('pngcje_ical','1',home_url('/'));
?>

<div <?php pngcje_page_hero_attrs(); ?>>
    <div class="container">
        <div class="page-hero__eyebrow"><?php pngcje_breadcrumbs(); ?></div>
        <h1 class="page-hero__title"><?php esc_html_e('Training Calendar & Events','pngcje'); ?></h1>
        <p class="page-hero__desc">
            <?php esc_html_e('Browse all upcoming PNGCJE judicial education programs, training sessions, conferences and events.','pngcje'); ?>
        </p>
    </div>
</div>

<section class="section">
    <div class="container">

        <!-- Toolbar -->
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:2.5rem;">

            <!-- Category Filters -->
            <div class="resources-filter" style="margin:0;">
                <a href="<?php echo esc_url(get_post_type_archive_link('pngcje_event')); ?>"
                   class="filter-btn <?php echo !is_tax('event_category') ? 'active' : ''; ?>">
                    <?php esc_html_e('All Events','pngcje'); ?>
                </a>
                <?php if ($categories && !is_wp_error($categories)) : foreach ($categories as $cat) : ?>
                <a href="<?php echo esc_url(get_term_link($cat)); ?>"
                   class="filter-btn <?php echo is_tax('event_category',$cat->term_id) ? 'active' : ''; ?>">
                    <?php echo esc_html($cat->name); ?>
                </a>
                <?php endforeach; endif; ?>
            </div>

            <!-- iCal Export -->
            <div style="display:flex;gap:.75rem;align-items:center;">
                <a href="<?php echo esc_url($ical_url); ?>" class="btn btn-outline btn-sm" download>
                    📅 <?php esc_html_e('Export Calendar (.ics)','pngcje'); ?>
                </a>
            </div>
        </div>

        <!-- UPCOMING EVENTS -->
        <?php if (!empty($upcoming)) : ?>
        <div style="margin-bottom:4rem;">
            <div class="section-header" style="margin-bottom:2rem;">
                <div class="section-label"><?php esc_html_e('Coming Up','pngcje'); ?></div>
                <h2 class="section-title" style="font-size:var(--size-2xl);"><?php esc_html_e('Upcoming Events','pngcje'); ?></h2>
            </div>
            <div style="display:flex;flex-direction:column;gap:1.5rem;">
                <?php foreach ($upcoming as $ev) :
                    $start_date = pngcje_event_get('start_date',$ev->ID);
                    $start_time = pngcje_event_get('start_time',$ev->ID);
                    $end_time   = pngcje_event_get('end_time',  $ev->ID);
                    $end_date   = pngcje_event_get('end_date',  $ev->ID);
                    $venue      = pngcje_event_get('venue',     $ev->ID);
                    $city       = pngcje_event_get('city',      $ev->ID);
                    $cost       = pngcje_event_get('cost',      $ev->ID);
                    $status     = pngcje_event_get('status',    $ev->ID) ?: 'scheduled';
                    $cats       = get_the_terms($ev->ID,'event_category');
                    $has_image  = has_post_thumbnail($ev->ID);
              ?>
                <article class="card reveal" style="display:grid;grid-template-columns:<?php echo $has_image ? '100px 180px 1fr' : '100px 1fr'; ?>;overflow:hidden;min-height:130px;">

                    <!-- Date block -->
                    <div style="background:var(--green-dark);display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:1rem .5rem;flex-shrink:0;">
                        <?php if ($start_date) : ?>
                        <div style="font-size:2rem;font-weight:900;color:var(--white);line-height:1;"><?php echo esc_html(date('j',strtotime($start_date))); ?></div>
                        <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--gold-light);"><?php echo esc_html(date('M',strtotime($start_date))); ?></div>
                        <div style="font-size:.6rem;color:rgba(255,255,255,.5);margin-top:.1rem;"><?php echo esc_html(date('Y',strtotime($start_date))); ?></div>
                        <?php if ($end_date && $end_date !== $start_date) : ?>
                        <div style="font-size:.55rem;color:rgba(255,255,255,.4);margin-top:.3rem;text-transform:uppercase;">to <?php echo esc_html(date('j M',strtotime($end_date))); ?></div>
                        <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <?php if ($has_image) : ?>
                    <a href="<?php echo esc_url(get_permalink($ev->ID)); ?>" style="display:block;min-height:130px;background:var(--surface);overflow:hidden;">
                        <?php
                        echo get_the_post_thumbnail($ev->ID,'pngcje-card',[
                            'loading'  => 'lazy',
                            'decoding' => 'async',
                            'alt'      => esc_attr(get_the_title($ev->ID)),
                            'style'    => 'width:100%;height:100%;min-height:130px;object-fit:cover;display:block;',
                        ]);
                        ?>
                    </a>
                    <?php endif; ?>

                    <!-- Details -->
                    <div class="card__body" style="display:flex;flex-direction:column;justify-content:space-between;">
                        <div>
                            <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.5rem;flex-wrap:wrap;">
                                <?php echo wp_kses_post(pngcje_event_status_badge($ev->ID)); ?>
                                <?php if ($start_time) : ?>
                                <span class="badge badge--green">🕐 <?php echo esc_html(date('g:i A',strtotime($start_time))); ?><?php echo $end_time ? '–'.date('g:i A',strtotime($end_time)) : ''; ?></span>
                                <?php endif; ?>
                                <?php if ($cats && !is_wp_error($cats)) : ?>
                                <span class="badge badge--gray"><?php echo esc_html($cats[0]->name); ?></span>
                                <?php endif; ?>
                            </div>
                            <h2 style="font-size:var(--size-xl);font-weight:700;margin-bottom:.4rem;">
                                <a href="<?php echo esc_url(get_permalink($ev->ID)); ?>" style="color:var(--ink);transition:color .2s;" onmouseover="this.style.color='var(--green-dark)';" onmouseout="this.style.color='var(--ink)';">
                                    <?php echo esc_html(get_the_title($ev->ID)); ?>
                                </a>
                            </h2>
                            <?php if ($venue || $city) : ?>
                            <p style="font-size:.85rem;color:var(--ink-light);margin:0;">
                                📍 <?php echo esc_html(implode(', ',array_filter([$venue,$city]))); ?>
                            </p>
                            <?php endif; ?>
                            <?php if ($cost) : ?>
                            <p style="font-size:.8rem;color:var(--gold-primary);font-weight:600;margin:.25rem 0 0;">💰 <?php echo esc_html($cost); ?></p>
                            <?php endif; ?>
                        </div>
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:.75rem;padding-top:.75rem;border-top:1px solid var(--border-light);flex-wrap:wrap;gap:.5rem;">
                            <a href="<?php echo esc_url(get_permalink($ev->ID)); ?>" class="btn btn-ghost btn-arrow text-sm">
                                <?php esc_html_e('View Details','pngcje'); ?>
                            </a>
                            <a href="<?php echo esc_url(add_query_arg(['pngcje_ical'=>1,'event_id'=>$ev->ID],home_url('/'))); ?>"
                               class="btn btn-outline btn-sm" download style="font-size:.72rem;">
                                📅 <?php esc_html_e('Add to Calendar','pngcje'); ?>
                            </a>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
        <?php else : ?>
        <div style="text-align:center;padding:4rem 2rem;background:var(--surface);border-radius:var(--radius-lg);margin-bottom:4rem;">
            <div style="font-size:4rem;margin-bottom:1rem;" aria-hidden="true">📅</div>
            <h2 style="font-size:var(--size-2xl);color:var(--ink-mid);margin-bottom:.75rem;"><?php esc_html_e('No upcoming events','pngcje'); ?></h2>
            <p style="color:var(--ink-light);max-width:400px;margin:0 auto 2rem;"><?php esc_html_e('There are no scheduled events at this time. Check back soon for our upcoming training calendar.','pngcje'); ?></p>
            <a href="<?php echo esc_url($ical_url); ?>" class="btn btn-outline" download><?php esc_html_e('Subscribe to Calendar','pngcje'); ?></a>
        </div>
        <?php endif; ?>

        <!-- PAST EVENTS -->
        <?php if (!empty($past)) : ?>
        <div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
                <h2 style="font-size:var(--size-xl);color:var(--ink-mid);"><?php esc_html_e('Past Events','pngcje'); ?></h2>
                <button type="button" id="toggle-past" class="btn btn-ghost text-sm">
                    <?php esc_html_e('Show past events ▼','pngcje'); ?>
                </button>
            </div>
            <div id="past-events-list" style="display:none;display:grid;grid-template-columns:repeat(3,1fr);gap:1.25rem;">
                <?php foreach ($past as $ev) :
                    $d = pngcje_event_get('start_date',$ev->ID);
                    $venue = pngcje_event_get('venue',$ev->ID);
              ?>
                <a href="<?php echo esc_url(get_permalink($ev->ID)); ?>" class="card" style="opacity:.75;">
                    <?php if (has_post_thumbnail($ev->ID)) : ?>
                    <div class="card__image"><?php echo get_the_post_thumbnail($ev->ID,'pngcje-card',['alt'=>'']); ?></div>
                    <?php endif; ?>
                    <div class="card__body">
                        <?php if ($d) : ?>
                        <div class="card__date"><?php echo esc_html(date('j F Y',strtotime($d))); ?></div>
                        <?php endif; ?>
                        <h3 class="card__title" style="font-size:var(--size-base);"><?php echo esc_html(get_the_title($ev->ID)); ?></h3>
                        <?php if ($venue) : ?>
                        <p style="font-size:.8rem;color:var(--ink-light);margin:0;">📍 <?php echo esc_html($venue); ?></p>
                        <?php endif; ?>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <script>
        jQuery(function($){
            var $list = $('#past-events-list');
            var $btn  = $('#toggle-past');
            var shown = false;
            $list.hide();
            $btn.on('click',function(){
                shown = !shown;
                shown ? $list.slideDown(250) : $list.slideUp(250);
                $btn.text(shown ? '<?php esc_js(esc_html__('Hide past events ▲','pngcje')); ?>' : '<?php esc_js(esc_html__('Show past events ▼','pngcje')); ?>');
            });
        });
        </script>
        <?php endif; ?>

    </div>
</section>

<?php get_footer(); ?>
