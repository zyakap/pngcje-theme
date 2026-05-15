<?php
/**
 * single-pngcje_event.php
 * PNGCJE Custom Events — Single Event Detail
 */

get_header();
while (have_posts()) : the_post();

$ev_id        = get_the_ID();
$start_date   = pngcje_event_get('start_date');
$start_time   = pngcje_event_get('start_time');
$end_date     = pngcje_event_get('end_date');
$end_time     = pngcje_event_get('end_time');
$venue        = pngcje_event_get('venue');
$city         = pngcje_event_get('city');
$address      = pngcje_event_get('address');
$organiser    = pngcje_event_get('organiser') ?: 'PNGCJE';
$org_email    = pngcje_event_get('organiser_email');
$cost         = pngcje_event_get('cost');
$website      = pngcje_event_get('website');
$status       = pngcje_event_get('status') ?: 'scheduled';
$reg_enabled  = pngcje_event_get('registration_enabled');
$reg_form_id  = pngcje_event_get('registration_form_id');
$reg_url      = pngcje_event_get('registration_url');
$capacity     = pngcje_event_get('capacity');
$formatted    = pngcje_event_formatted_date($ev_id);
$ical_url     = add_query_arg(['pngcje_ical'=>1,'event_id'=>$ev_id], home_url('/'));
$gcal_url     = pngcje_event_gcal_url($ev_id);
$categories   = get_the_terms($ev_id,'event_category');
$category_names = $categories && ! is_wp_error($categories) ? wp_list_pluck($categories, 'name') : [];
$logo_groups  = function_exists('pngcje_event_logo_groups') ? pngcje_event_logo_groups($ev_id) : [];
?>

<!-- Page Hero -->
<div <?php pngcje_page_hero_attrs(); ?>>
    <div class="container">
        <div class="page-hero__eyebrow">
            <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home','pngcje'); ?></a>
            <span>›</span>
            <a href="<?php echo esc_url(get_post_type_archive_link('pngcje_event')); ?>"><?php esc_html_e('Events','pngcje'); ?></a>
            <span>›</span>
            <span><?php the_title(); ?></span>
        </div>

        <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;margin-bottom:1rem;">
            <?php echo wp_kses_post(pngcje_event_status_badge($ev_id)); ?>
            <?php if ($categories && !is_wp_error($categories)) : ?>
            <span class="badge badge--gold"><?php echo esc_html($categories[0]->name); ?></span>
            <?php endif; ?>
        </div>

        <h1 class="page-hero__title"><?php the_title(); ?></h1>

        <?php if ($formatted) : ?>
        <p class="page-hero__desc">📅 <?php echo esc_html($formatted); ?></p>
        <?php endif; ?>
        <?php if ($venue || $city) : ?>
        <p class="page-hero__desc" style="margin-top:.5rem;">📍 <?php echo esc_html(implode(', ',array_filter([$venue,$city]))); ?></p>
        <?php endif; ?>
    </div>
</div>

<section class="section">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 360px;gap:4rem;align-items:start;">

            <!-- Main Content -->
            <div>

                <?php if (has_post_thumbnail()) : ?>
                <div style="border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-lg);margin-bottom:2.5rem;">
                    <?php the_post_thumbnail('pngcje-wide',['style'=>'width:100%;max-height:450px;object-fit:cover;','alt'=>'']); ?>
                </div>
                <?php endif; ?>

                <!-- Description -->
                <?php if (get_the_content()) : ?>
                <div class="entry-content" style="font-size:var(--size-md);line-height:var(--leading-loose);color:var(--ink-mid);margin-bottom:3rem;">
                    <?php the_content(); ?>
                </div>
                <?php endif; ?>

                <?php if ($logo_groups) : ?>
                <div style="margin-bottom:3rem;display:flex;flex-direction:column;gap:1.25rem;">
                    <?php foreach ($logo_groups as $logo_group) : ?>
                    <section style="background:var(--surface);border:1px solid var(--border-light);border-radius:var(--radius-lg);padding:1.25rem 1.5rem;overflow:hidden;">
                        <h2 style="font-size:.75rem;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:var(--green-dark);margin-bottom:1rem;">
                            <?php echo esc_html($logo_group['label']); ?>
                        </h2>
                        <div style="display:flex;gap:1rem;align-items:center;overflow-x:auto;padding-bottom:.25rem;">
                            <?php foreach ($logo_group['items'] as $logo_item) : ?>
                                <?php if ('logo' === $logo_item['type']) : ?>
                                <div style="min-width:130px;height:76px;border:1px solid var(--border-light);border-radius:var(--radius-md);background:#fff;display:flex;align-items:center;justify-content:center;padding:.75rem;">
                                    <?php echo wp_kses_post($logo_item['value']); ?>
                                </div>
                                <?php else : ?>
                                <div style="min-width:130px;min-height:76px;border:1px solid var(--border-light);border-radius:var(--radius-md);background:#fff;display:flex;align-items:center;justify-content:center;padding:.75rem;text-align:center;font-size:.9rem;font-weight:700;color:var(--ink);">
                                    <?php echo esc_html($logo_item['value']); ?>
                                </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </section>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Registration / Sign Up -->
                <?php if ($status !== 'cancelled' && $status !== 'completed') : ?>
                <div id="register" style="scroll-margin-top:120px;">

                    <?php if (!empty($reg_url)) : ?>
                    <!-- External registration link -->
                    <div style="background:var(--green-subtle);border-radius:var(--radius-lg);padding:2.5rem;text-align:center;">
                        <div style="font-size:2.5rem;margin-bottom:1rem;" aria-hidden="true">📝</div>
                        <h2 style="font-size:var(--size-2xl);margin-bottom:.75rem;color:var(--green-dark);"><?php esc_html_e('Register for This Event','pngcje'); ?></h2>
                        <a href="<?php echo esc_url($reg_url); ?>" class="btn btn-gold btn-lg" target="_blank" rel="noopener noreferrer">
                            <?php esc_html_e('Register Now','pngcje'); ?> →
                        </a>
                    </div>

                    <?php elseif (!empty($reg_enabled) && $reg_enabled == '1') : ?>
                    <!-- Built-in form -->
                    <div style="background:var(--surface);border-radius:var(--radius-lg);padding:2.5rem;">
                        <h2 style="font-size:var(--size-2xl);margin-bottom:.5rem;color:var(--green-dark);"><?php esc_html_e('Register for This Event','pngcje'); ?></h2>
                        <p style="color:var(--ink-light);font-size:.9rem;margin-bottom:2rem;"><?php esc_html_e('Complete the form below to register your attendance.','pngcje'); ?></p>
                        <?php
                        $form_id = $reg_form_id ?: 3; // Default to form ID 3
                        echo do_shortcode('[pngcje_form id="' . (int)$form_id . '"]');
                      ?>
                    </div>
                    <?php endif; ?>

                </div>
                <?php elseif ($status === 'cancelled') : ?>
                <div style="background:#FFEBEE;border:1.5px solid var(--red-mid);border-radius:var(--radius-md);padding:1.5rem;display:flex;align-items:center;gap:1rem;">
                    <span style="font-size:2rem;" aria-hidden="true">❌</span>
                    <div>
                        <strong style="color:var(--red-mid);display:block;margin-bottom:.25rem;"><?php esc_html_e('This event has been cancelled.','pngcje'); ?></strong>
                        <span style="font-size:.875rem;color:var(--ink-mid);"><?php esc_html_e('For enquiries please contact us.','pngcje'); ?></span>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Navigation -->
                <div style="margin-top:3rem;padding-top:2rem;border-top:1px solid var(--border-light);display:flex;gap:1rem;flex-wrap:wrap;">
                    <a href="<?php echo esc_url(get_post_type_archive_link('pngcje_event')); ?>" class="btn btn-outline">
                        ← <?php esc_html_e('Back to Events','pngcje'); ?>
                    </a>
                    <?php if ($website) : ?>
                    <a href="<?php echo esc_url($website); ?>" class="btn btn-primary" target="_blank" rel="noopener noreferrer">
                        <?php esc_html_e('Event Website','pngcje'); ?> →
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sidebar -->
            <aside>

                <!-- Event Details Card -->
                <div class="card" style="border-top:4px solid var(--gold-primary);margin-bottom:1.5rem;">
                    <div class="card__body">
                        <h2 style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--green-dark);margin-bottom:1.5rem;">
                            <?php esc_html_e('Event Details','pngcje'); ?>
                        </h2>
                        <div style="display:flex;flex-direction:column;gap:1.25rem;">

                            <?php if ($start_date) : ?>
                            <div style="display:flex;gap:.75rem;align-items:flex-start;">
                                <div style="width:36px;height:36px;background:var(--green-subtle);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;">📅</div>
                                <div>
                                    <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--gold-primary);margin-bottom:.2rem;"><?php esc_html_e('Date','pngcje'); ?></div>
                                    <div style="font-size:.875rem;font-weight:600;color:var(--ink);"><?php echo esc_html(date('l, j F Y',strtotime($start_date))); ?></div>
                                    <?php if ($end_date && $end_date !== $start_date) : ?>
                                    <div style="font-size:.8rem;color:var(--ink-light);">to <?php echo esc_html(date('l, j F Y',strtotime($end_date))); ?></div>
                                    <?php endif; ?>
                                    <?php if ($start_time || $end_time) : ?>
                                    <div style="font-size:.8rem;color:var(--ink-light);margin-top:.2rem;">
                                        <?php echo $start_time ? esc_html(date('g:i A',strtotime($start_time))) : esc_html__('Start time not set','pngcje'); ?>
                                        <?php echo $end_time ? ' – ' . esc_html(date('g:i A',strtotime($end_time))) : ''; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if ($venue || $address || $city) : ?>
                            <div style="display:flex;gap:.75rem;align-items:flex-start;">
                                <div style="width:36px;height:36px;background:var(--green-subtle);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;">📍</div>
                                <div>
                                    <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--gold-primary);margin-bottom:.2rem;"><?php esc_html_e('Venue','pngcje'); ?></div>
                                    <?php if ($venue) : ?><div style="font-size:.875rem;font-weight:600;color:var(--ink);"><?php echo esc_html($venue); ?></div><?php endif; ?>
                                    <?php if ($address) : ?><div style="font-size:.8rem;color:var(--ink-light);"><?php echo esc_html($address); ?></div><?php endif; ?>
                                    <?php if ($city) : ?><div style="font-size:.8rem;color:var(--ink-light);"><?php echo esc_html($city); ?></div><?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div style="display:flex;gap:.75rem;align-items:flex-start;">
                                <div style="width:36px;height:36px;background:var(--green-subtle);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;">ℹ️</div>
                                <div>
                                    <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--gold-primary);margin-bottom:.2rem;"><?php esc_html_e('Status','pngcje'); ?></div>
                                    <div><?php echo wp_kses_post(pngcje_event_status_badge($ev_id)); ?></div>
                                </div>
                            </div>

                            <?php if ($category_names) : ?>
                            <div style="display:flex;gap:.75rem;align-items:flex-start;">
                                <div style="width:36px;height:36px;background:var(--green-subtle);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;">🏷️</div>
                                <div>
                                    <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--gold-primary);margin-bottom:.2rem;"><?php esc_html_e('Category','pngcje'); ?></div>
                                    <div style="font-size:.875rem;font-weight:600;color:var(--ink);"><?php echo esc_html(implode(', ', $category_names)); ?></div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if ($cost) : ?>
                            <div style="display:flex;gap:.75rem;align-items:flex-start;">
                                <div style="width:36px;height:36px;background:var(--green-subtle);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;">💰</div>
                                <div>
                                    <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--gold-primary);margin-bottom:.2rem;"><?php esc_html_e('Cost','pngcje'); ?></div>
                                    <div style="font-size:.875rem;font-weight:600;color:var(--ink);"><?php echo esc_html($cost); ?></div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if ($website) : ?>
                            <div style="display:flex;gap:.75rem;align-items:flex-start;">
                                <div style="width:36px;height:36px;background:var(--green-subtle);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;">🔗</div>
                                <div>
                                    <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--gold-primary);margin-bottom:.2rem;"><?php esc_html_e('Event Website','pngcje'); ?></div>
                                    <a href="<?php echo esc_url($website); ?>" target="_blank" rel="noopener noreferrer" style="font-size:.8rem;color:var(--green-dark);word-break:break-word;"><?php echo esc_html($website); ?></a>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if ($organiser) : ?>
                            <div style="display:flex;gap:.75rem;align-items:flex-start;">
                                <div style="width:36px;height:36px;background:var(--green-subtle);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;">👤</div>
                                <div>
                                    <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--gold-primary);margin-bottom:.2rem;"><?php esc_html_e('Organiser','pngcje'); ?></div>
                                    <div style="font-size:.875rem;font-weight:600;color:var(--ink);"><?php echo esc_html($organiser); ?></div>
                                    <?php if ($org_email) : ?>
                                    <a href="mailto:<?php echo esc_attr($org_email); ?>" style="font-size:.8rem;color:var(--green-dark);"><?php echo esc_html($org_email); ?></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if ($reg_enabled || $reg_form_id || $reg_url || '' !== $capacity) : ?>
                            <div style="display:flex;gap:.75rem;align-items:flex-start;">
                                <div style="width:36px;height:36px;background:var(--green-subtle);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;">📝</div>
                                <div>
                                    <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--gold-primary);margin-bottom:.2rem;"><?php esc_html_e('Registration','pngcje'); ?></div>
                                    <?php if ($reg_url) : ?>
                                    <a href="<?php echo esc_url($reg_url); ?>" target="_blank" rel="noopener noreferrer" style="font-size:.8rem;color:var(--green-dark);word-break:break-word;"><?php echo esc_html($reg_url); ?></a>
                                    <?php elseif ($reg_enabled) : ?>
                                    <div style="font-size:.875rem;font-weight:600;color:var(--ink);"><?php esc_html_e('Built-in registration form enabled','pngcje'); ?></div>
                                    <?php endif; ?>
                                    <?php if ($reg_form_id) : ?>
                                    <div style="font-size:.8rem;color:var(--ink-light);"><?php echo esc_html(sprintf(__('Form ID: %s','pngcje'), $reg_form_id)); ?></div>
                                    <?php endif; ?>
                                    <?php if ('' !== $capacity) : ?>
                                    <div style="font-size:.8rem;color:var(--ink-light);">
                                        <?php echo $capacity ? esc_html(sprintf(__('Capacity: %s','pngcje'), $capacity)) : esc_html__('Capacity: Unlimited','pngcje'); ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

                <!-- Add to Calendar -->
                <div class="card" style="margin-bottom:1.5rem;border-left:4px solid var(--gold-primary);">
                    <div class="card__body">
                        <h3 style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--ink);margin-bottom:1rem;"><?php esc_html_e('Add to Calendar','pngcje'); ?></h3>
                        <div style="display:flex;flex-direction:column;gap:.5rem;">
                            <a href="<?php echo esc_url($ical_url); ?>" class="btn btn-outline" download style="justify-content:center;font-size:.8rem;">
                                📅 <?php esc_html_e('Download .ics','pngcje'); ?>
                            </a>
                            <?php if ($gcal_url) : ?>
                            <a href="<?php echo esc_url($gcal_url); ?>" class="btn btn-outline" target="_blank" rel="noopener noreferrer" style="justify-content:center;font-size:.8rem;">
                                📅 <?php esc_html_e('Add to Google Calendar','pngcje'); ?>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Register CTA (if form exists) -->
                <?php if (($reg_enabled == '1' || $reg_url) && $status === 'open') : ?>
                <div class="card bg-green" style="text-align:center;">
                    <div class="card__body" style="padding:2rem;">
                        <div style="font-size:2.5rem;margin-bottom:.75rem;" aria-hidden="true">📝</div>
                        <h3 style="color:var(--white);font-size:var(--size-xl);margin-bottom:.75rem;"><?php esc_html_e('Registrations Open','pngcje'); ?></h3>
                        <p style="color:rgba(255,255,255,.7);font-size:.875rem;margin-bottom:1.5rem;"><?php esc_html_e('Secure your spot at this event.','pngcje'); ?></p>
                        <?php if ($reg_url) : ?>
                        <a href="<?php echo esc_url($reg_url); ?>" class="btn btn-gold" target="_blank" rel="noopener noreferrer" style="width:100%;justify-content:center;">
                            <?php esc_html_e('Register Now','pngcje'); ?> →
                        </a>
                        <?php else : ?>
                        <a href="#register" class="btn btn-gold" style="width:100%;justify-content:center;">
                            <?php esc_html_e('Register Now','pngcje'); ?> ↓
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

            </aside>
        </div>
    </div>
</section>

<?php endwhile; get_footer(); ?>
