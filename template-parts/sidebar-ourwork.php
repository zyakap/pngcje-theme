<?php
/**
 * template-parts/sidebar-ourwork.php
 * Shared sidebar for Our Work sub-pages
 */
$current_url = trailingslashit( get_permalink() ?: '' );
$ourwork_links = [
    [ 'Case Notes',        pngcje_get_resource_type_url( 'case-notes' ) ],
    [ 'Bench Books',       pngcje_get_resource_type_url( 'bench-books' ) ],
    [ 'Judicial Handbook', pngcje_get_resource_type_url( 'judicial-handbook' ) ],
    [ 'CPD Lectures',      pngcje_get_resource_type_url( 'cpd-lectures' ) ],
    [ 'ED Speeches',       pngcje_get_resource_type_url( 'executive-director-speeches' ) ],
    [ 'Prospectus',       home_url( '/prospectus/' ) ],
    [ 'Lecture Series',   home_url( '/lecture-series/' ) ],
    [ 'Training Calendar',home_url( '/training-calendar/' ) ],
    [ 'Annual Reports',   home_url( '/annual-reports/' ) ],
    [ 'Newsletters',      home_url( '/newsletters/' ) ],
    [ 'Customer Service', home_url( '/customer-service/' ) ],
];
?>

<!-- Our Work Navigation -->
<div class="card" style="margin-bottom:1.5rem;border-top:3px solid var(--ember-primary);">
    <div class="card__body">
        <h3 style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--ember-primary);margin-bottom:1rem;">Our Work</h3>
        <ul style="display:flex;flex-direction:column;gap:.2rem;">
            <?php foreach ( $ourwork_links as $item ) :
                $label = $item[0]; $url = $item[1];
                $active = trailingslashit( $url ) === $current_url;
            ?>
            <li>
                <a href="<?php echo esc_url( $url ); ?>"
                   style="display:flex;align-items:center;gap:.5rem;padding:.55rem .75rem;border-radius:4px;font-size:.85rem;font-weight:<?php echo $active ? '700' : '500'; ?>;color:<?php echo $active ? 'var(--ember-primary)' : 'var(--ink-mid)'; ?>;background:<?php echo $active ? 'var(--ember-subtle)' : 'transparent'; ?>;transition:all .2s;text-decoration:none;"
                   onmouseover="this.style.background='var(--ember-subtle)';this.style.color='var(--ember-primary)';"
                   onmouseout="this.style.background='<?php echo $active ? 'var(--ember-subtle)' : 'transparent'; ?>';this.style.color='<?php echo $active ? 'var(--ember-primary)' : 'var(--ink-mid)'; ?>';">
                    <span style="color:var(--gold-primary);font-size:.9em;">›</span>
                    <?php echo esc_html( $label ); ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<!-- LMS CTA -->
<div class="card" style="background:var(--ember-primary);margin-bottom:1.5rem;">
    <div class="card__body" style="text-align:center;padding:1.75rem;">
        <div style="font-size:2rem;margin-bottom:.75rem;">🎓</div>
        <h3 style="color:#fff;font-size:var(--size-base);margin-bottom:.5rem;">Access LMS Portal</h3>
        <p style="color:rgba(255,255,255,.75);font-size:.8rem;line-height:1.6;margin-bottom:1.25rem;">Online training programs available through the PICCJE Learning Management System.</p>
        <a href="https://learn.pngcje.gov.pg"
           class="btn btn-gold"
           style="width:100%;justify-content:center;"
           target="_blank" rel="noopener noreferrer">
            Access LMS
        </a>
    </div>
</div>

<!-- Contact -->
<div class="card" style="border-left:4px solid var(--gold-primary);">
    <div class="card__body">
        <h3 style="font-size:.85rem;font-weight:700;margin-bottom:1rem;">Need Help?</h3>
        <p style="font-size:.8rem;color:var(--ink-light);line-height:1.6;margin-bottom:1.25rem;">Can't find what you're looking for? Our Program Officers are happy to assist.</p>
        <a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>" class="btn btn-outline" style="width:100%;justify-content:center;font-size:.8rem;">Contact Us</a>
    </div>
</div>
