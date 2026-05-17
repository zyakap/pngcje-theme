<?php
/**
 * Shared sidebar for About section pages.
 */
defined( 'ABSPATH' ) || exit;

$current_url = trailingslashit( get_permalink() ?: '' );
$about_links = [
    [ __( 'About the PNGCJE', 'pngcje' ), home_url( '/about/' ) ],
    [ __( 'Our Staff', 'pngcje' ), home_url( '/our-staff/' ) ],
    [ __( 'Governance', 'pngcje' ), home_url( '/about/governance/' ) ],
    [ __( 'Sitemap', 'pngcje' ), home_url( '/about/sitemap/' ) ],
];
?>

<div class="card" style="margin-bottom:1.5rem;border-top:3px solid var(--ember-primary);">
    <div class="card__body">
        <h3 style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--ember-primary);margin-bottom:1rem;">
            <?php esc_html_e( 'In This Section', 'pngcje' ); ?>
        </h3>
        <ul style="display:flex;flex-direction:column;gap:.25rem;">
            <?php foreach ( $about_links as $item ) : ?>
                <?php $active = trailingslashit( $item[1] ) === $current_url; ?>
                <li>
                    <a href="<?php echo esc_url( $item[1] ); ?>"
                       style="display:flex;align-items:center;gap:.5rem;padding:.6rem .75rem;border-radius:4px;font-size:.875rem;font-weight:<?php echo $active ? '700' : '500'; ?>;color:<?php echo $active ? 'var(--ember-primary)' : 'var(--ink-mid)'; ?>;background:<?php echo $active ? 'var(--ember-subtle)' : 'transparent'; ?>;transition:all .2s;text-decoration:none;">
                        <span style="color:var(--gold-primary);">›</span><?php echo esc_html( $item[0] ); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<div class="card" style="border-left:4px solid var(--gold-primary);">
    <div class="card__body">
        <h3 style="font-size:.875rem;font-weight:700;margin-bottom:1rem;"><?php esc_html_e( 'Contact the PNGCJE', 'pngcje' ); ?></h3>
        <p style="font-size:.8rem;color:var(--ink-light);line-height:1.6;margin-bottom:1.25rem;">
            <?php esc_html_e( 'For governance, staff or general institutional enquiries, contact the Centre directly.', 'pngcje' ); ?>
        </p>
        <a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>" class="btn btn-primary" style="width:100%;justify-content:center;font-size:.8rem;">
            <?php esc_html_e( 'Contact Us', 'pngcje' ); ?>
        </a>
    </div>
</div>
