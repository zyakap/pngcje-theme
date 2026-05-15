<?php
/**
 * Template Name: Contact Page
 * Real content from pngcje.gov.pg — split layout, ember branding
 */
get_header(); ?>

<div <?php pngcje_page_hero_attrs( null, 'padding-block:var(--space-16);' ); ?>>
    <div class="container">
        <div class="page-hero__eyebrow"><?php pngcje_breadcrumbs(); ?></div>
        <h1 class="page-hero__title">Contact Us</h1>
        <p class="page-hero__desc">Please provide your contact details and an outline of your enquiry, and our team of Program Officers will respond to you.</p>
    </div>
</div>

<div class="contact-split">
    <!-- Left info panel -->
    <div class="contact-info" style="background:var(--ember-deep)!important;">
        <div>
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--gold-light);margin-bottom:.75rem;">Get In Touch</div>
            <h2 style="font-size:var(--size-3xl);font-weight:900;color:var(--white);margin-bottom:1rem;">We'd Love to Hear From You</h2>
            <p style="color:rgba(255,255,255,.65);font-size:var(--size-base);line-height:1.9;margin-bottom:2rem;">Our team of Program Officers are available to assist with training enquiries, resource requests, partnership discussions and general information about the PNGCJE and PICCJE programs.</p>
        </div>
        <div style="display:flex;flex-direction:column;gap:1.5rem;">
            <?php foreach([
                ['📍','Address','PO Box 7018, Boroko, NCD, Papua New Guinea',''],
                ['📞','Phone',get_theme_mod('pngcje_phone','+675 324 5700'),'tel:+67532457000'],
                ['✉️','Email',get_theme_mod('pngcje_email','info@pngcje.gov.pg'),'mailto:info@pngcje.gov.pg'],
            ] as $_di): $icon=$_di[0]; $label=$_di[1]; $val=$_di[2]; $href=$_di[3]; ?>
            <div class="contact-detail">
                <div class="contact-detail__icon" style="background:rgba(255,255,255,.1)!important;"><?php echo esc_html($icon); ?></div>
                <div>
                    <div class="contact-detail__label"><?php echo esc_html($label); ?></div>
                    <?php if($href): ?><a href="<?php echo esc_attr($href); ?>" class="contact-detail__value"><?php echo esc_html($val); ?></a>
                    <?php else: ?><div class="contact-detail__value"><?php echo esc_html($val); ?></div><?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div>
            <div style="width:40px;height:2px;background:rgba(255,255,255,.2);margin-bottom:1.25rem;"></div>
            <p style="color:rgba(255,255,255,.5);font-size:.825rem;margin-bottom:1rem;">Need to access the learning portal?</p>
            <a href="https://piccje.csod.com/login/render.aspx?id=defaultclp" class="btn btn-gold" target="_blank" rel="noopener noreferrer">Access LMS Portal</a>
        </div>
        <!-- Partner links -->
        <div>
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--gold-light);margin-bottom:.75rem;">Our Links</div>
            <?php foreach([['PNG Judiciary','https://www.pngjudiciary.gov.pg/'],['Dept of Justice & AG','https://www.justice.gov.pg/'],['Magisterial Services','http://www.magisterialservices.gov.pg/'],['PacLII','http://www.paclii.org/']] as $_di): $n=$_di[0]; $u=$_di[1]; ?>
            <a href="<?php echo esc_url($u); ?>" target="_blank" rel="noopener noreferrer" style="display:flex;align-items:center;gap:.5rem;font-size:.825rem;color:rgba(255,255,255,.6);padding:.4rem 0;border-bottom:1px solid rgba(255,255,255,.08);transition:color .2s;" onmouseover="this.style.color='var(--gold-light)';" onmouseout="this.style.color='rgba(255,255,255,.6)';">
                <span style="color:var(--gold-primary);">→</span><?php echo esc_html($n); ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Right form panel -->
    <div class="contact-form-wrap">
        <h2 style="font-size:var(--size-2xl);margin-bottom:var(--space-8);color:var(--ink);">Send Us a Message</h2>
        <?php
        $pngcje_contact_gf_id      = absint( get_theme_mod( 'pngcje_contact_gravity_form_id', 0 ) );
        $pngcje_contact_native_id = absint( get_theme_mod( 'pngcje_contact_pngcje_form_id', 124 ) );
        if ( function_exists( 'gravity_form' ) && $pngcje_contact_gf_id > 0 ) :
            gravity_form( $pngcje_contact_gf_id, false, false, false, null, true );
        elseif ( $pngcje_contact_native_id > 0 ) :
            echo do_shortcode( '[pngcje_form id="' . $pngcje_contact_native_id . '"]' );
        endif;
        ?>
    </div>
</div>

<?php get_footer(); ?>
