<?php
/**
 * Template Name: Pacific Centre
 * Real content from pngcje.gov.pg/pacific-island-centre-for-judicial-excellence/
 * Distinct navy branding + ember accent
 */
get_header(); ?>

<div class="pacific-section" style="padding-block:var(--space-20);">
    <div class="container" style="position:relative;z-index:1;">

        <!-- Hero -->
        <div style="text-align:center;max-width:800px;margin:0 auto var(--space-16);" class="reveal">
            <div class="pacific-badge">🌊 Pacific Initiative</div>
            <h1 style="font-size:clamp(2rem,4vw,3.5rem);font-weight:900;color:var(--white);margin-bottom:1.25rem;line-height:1.15;">
                Pacific Islands Centre for Judicial Excellence
            </h1>
            <div style="width:60px;height:3px;background:linear-gradient(90deg,var(--ember-primary),var(--gold-primary));border-radius:99px;margin:0 auto 1.5rem;"></div>
            <p style="color:rgba(255,255,255,.72);font-size:var(--size-md);line-height:1.9;">
                Building judicial capacity and excellence across the Pacific Islands region, led by the Papua New Guinea Centre for Judicial Excellence.
            </p>
        </div>

        <!-- About PICCJE -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:start;margin-bottom:5rem;">
            <div class="reveal">
                <div class="section-label" style="color:var(--gold-light);">Background</div>
                <h2 style="font-size:var(--size-2xl);font-weight:800;color:var(--white);margin-bottom:1.5rem;line-height:1.2;">About PICCJE</h2>
                <div style="color:rgba(255,255,255,.75);line-height:1.9;font-size:.95rem;">
                    <p>The Pacific Island Countries Centre for Judicial Excellence was formed from PNGCJE's growing regional work and the need for judicial education programs developed within the Pacific for Pacific judiciaries.</p>
                    <p>Since PNGCJE was established in 2010, the vision has been to strengthen judicial education in Papua New Guinea and extend practical support to Pacific Island jurisdictions according to their specific needs.</p>
                    <p>PCJE plays an integral role in building regional judicial capacity through structured training for Judges, Magistrates, Court Officers and officers in the Law and Justice Sector.</p>
                </div>
            </div>
            <div class="reveal reveal-delay-2">
                <div class="section-label" style="color:var(--gold-light);">Three Development Phases</div>
                <h2 style="font-size:var(--size-xl);font-weight:800;color:var(--white);margin-bottom:1.5rem;">How PICCJE Was Built</h2>
                <?php foreach([
                    ['Phase 1','Strengthening institutional capacity of PNGCJE, establishing professional staff and arranging transitional requirements.'],
                    ['Phase 2','Transformation of PNGCJE structure to accommodate Pacific-wide programs and governance.'],
                    ['Phase 3','Full transformation into PCJE as the lead regional judicial education body for Pacific island judiciaries.'],
                ] as $i=>$_di): $ph=$_di[0]; $desc=$_di[1]; ?>
                <div style="display:flex;gap:1rem;align-items:flex-start;margin-bottom:1.25rem;">
                    <div style="width:36px;height:36px;background:var(--ember-primary);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:900;color:var(--white);flex-shrink:0;"><?php echo $i+1; ?></div>
                    <div><div style="font-size:.85rem;font-weight:700;color:var(--gold-light);margin-bottom:.25rem;"><?php echo esc_html($ph); ?></div><p style="font-size:.85rem;color:rgba(255,255,255,.65);line-height:1.6;margin:0;"><?php echo esc_html($desc); ?></p></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Member Countries -->
        <div class="reveal" style="margin-bottom:4rem;">
            <div style="text-align:center;margin-bottom:2.5rem;">
                <div class="section-label" style="color:var(--gold-light);justify-content:center;">Member Countries</div>
                <h2 style="font-size:var(--size-2xl);font-weight:800;color:var(--white);">Pacific Island Jurisdictions</h2>
                <p style="color:rgba(255,255,255,.6);font-size:.9rem;margin-top:.75rem;">Cook Islands, Fiji, Federated States of Micronesia, Kiribati, Marshall Islands, Nauru, Niue, Palau, Samoa, Solomon Islands, Tokelau, Tonga, Tuvalu and Vanuatu</p>
            </div>
            <?php
            $members_q = new WP_Query(['post_type'=>'pngcje_pacific','posts_per_page'=>-1,'post_status'=>'publish','orderby'=>'menu_order','order'=>'ASC']);
            $has_cpt = $members_q->have_posts();
           ?>
            <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:1rem;">
                <?php if($has_cpt):
                    while($members_q->have_posts()):$members_q->the_post();
                        $flag=get_post_meta(get_the_ID(),'_pngcje_flag_emoji',true);
                        $name=get_post_meta(get_the_ID(),'_pngcje_country_name',true)?:get_the_title();
                        $url=get_post_meta(get_the_ID(),'_pngcje_country_url',true);
                        $tag=$url?'a':'div';
                        $href=$url?' href="'.esc_url($url).'" target="_blank" rel="noopener noreferrer"':'';
                        echo "<{$tag}{$href} class=\"pacific-member-card\"><div class=\"pacific-member-card__flag\">".esc_html($flag?:'🏝️')."</div><div class=\"pacific-member-card__name\">".esc_html($name)."</div></{$tag}>";
                    endwhile; wp_reset_postdata();
                else:
                    foreach([['🇵🇬','Papua New Guinea'],['🇫🇯','Fiji'],['🇸🇧','Solomon Islands'],['🇻🇺','Vanuatu'],['🇼🇸','Samoa'],['🇹🇴','Tonga'],['🇰🇮','Kiribati'],['🇳🇷','Nauru'],['🇨🇰','Cook Islands'],['🇵🇼','Palau'],['🇫🇲','Micronesia'],['🇲🇭','Marshall Islands'],['🇹🇻','Tuvalu'],['🇳🇺','Niue'],['🇹🇰','Tokelau']] as $_di): $f=$_di[0]; $n=$_di[1];
                        echo "<div class=\"pacific-member-card\"><div class=\"pacific-member-card__flag\">".esc_html($f)."</div><div class=\"pacific-member-card__name\">".esc_html($n)."</div></div>";
                    endforeach;
                endif; ?>
            </div>
        </div>

        <!-- Latest Pacific News -->
        <?php
        $pacific_news = new WP_Query(['post_type'=>'post','posts_per_page'=>4,'post_status'=>'publish','orderby'=>'date','order'=>'DESC']);
        if($pacific_news->have_posts()): ?>
        <div class="reveal" style="margin-bottom:4rem;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
                <div><div class="section-label" style="color:var(--gold-light);">Regional Updates</div><h2 style="font-size:var(--size-2xl);font-weight:800;color:var(--white);">Latest Pacific News</h2></div>
                <a href="<?php echo esc_url(home_url('/news/')); ?>" class="btn btn-outline-white btn-arrow">All News</a>
            </div>
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1.5rem;">
                <?php while($pacific_news->have_posts()):$pacific_news->the_post(); ?>
                <a href="<?php the_permalink(); ?>" style="display:block;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);border-radius:var(--radius-md);padding:1.5rem;text-decoration:none;transition:all .25s;" onmouseover="this.style.background='rgba(255,255,255,.11)';this.style.borderColor='var(--ember-primary)';" onmouseout="this.style.background='rgba(255,255,255,.07)';this.style.borderColor='rgba(255,255,255,.12)';">
                    <div style="font-size:.72rem;font-weight:700;color:var(--gold-light);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.5rem;"><?php echo get_the_date('j F Y'); ?></div>
                    <h3 style="font-size:.95rem;font-weight:700;color:var(--white);line-height:1.4;margin-bottom:.5rem;"><?php the_title(); ?></h3>
                    <p style="font-size:.8rem;color:rgba(255,255,255,.55);line-height:1.6;margin:0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;"><?php echo esc_html(pngcje_excerpt(null,16)); ?></p>
                </a>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- LMS CTA -->
        <div style="text-align:center;" class="reveal">
            <div style="display:inline-flex;flex-direction:column;align-items:center;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);border-radius:var(--radius-xl);padding:3rem 4rem;gap:1.5rem;">
                <div style="font-size:3rem;" aria-hidden="true">🎓</div>
                <div>
                    <h2 style="font-size:var(--size-2xl);font-weight:900;color:var(--white);margin-bottom:.5rem;">Access the PICCJE Learning Portal</h2>
                    <p style="color:rgba(255,255,255,.65);font-size:.95rem;">Complete online training programs for Pacific judicial officers through the LMS platform.</p>
                </div>
                <a href="https://learn.pngcje.gov.pg" class="btn btn-primary btn-lg" target="_blank" rel="noopener noreferrer">
                    Access LMS Portal →
                </a>
            </div>
        </div>

    </div>
</div>

<?php get_footer(); ?>
