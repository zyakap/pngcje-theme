<?php
/**
 * 404.php — Not Found
 */

get_header();
?>

<section style="min-height:70vh;display:flex;align-items:center;justify-content:center;padding:4rem 0;background:var(--surface);">
    <div class="container" style="text-align:center;max-width:600px;">

        <!-- 404 Visual -->
        <div style="font-size:7rem;font-weight:900;color:var(--green-dark);opacity:0.12;line-height:1;margin-bottom:-1rem;letter-spacing:-0.05em;">
            404
        </div>
        <div style="font-size:3rem;margin-bottom:1rem;" aria-hidden="true">⚖️</div>

        <h1 style="font-size:clamp(1.75rem,3vw,2.5rem);font-weight:900;color:var(--ink);margin-bottom:1rem;">
            <?php esc_html_e( 'Page Not Found', 'pngcje' ); ?>
        </h1>
        <p style="font-size:1.1rem;color:var(--ink-light);line-height:1.8;margin-bottom:2.5rem;">
            <?php esc_html_e( 'The page you are looking for may have been moved, removed, or is temporarily unavailable. Please use the navigation above or search below to find what you need.', 'pngcje' ); ?>
        </p>

        <!-- Search -->
        <form role="search" method="get" action="<?php echo esc_url( home_url('/') ); ?>" style="margin-bottom:2rem;">
            <div style="display:flex;gap:0.75rem;max-width:440px;margin:0 auto;">
                <label for="error-search" class="sr-only"><?php esc_html_e( 'Search', 'pngcje' ); ?></label>
                <input
                    type="search"
                    id="error-search"
                    name="s"
                    placeholder="<?php esc_attr_e( 'Search PNGCJE…', 'pngcje' ); ?>"
                    style="flex:1;border:1.5px solid var(--border);border-radius:4px;padding:0.75rem 1.25rem;font-family:inherit;font-size:1rem;"
                >
                <button type="submit" class="btn btn-primary">🔍</button>
            </div>
        </form>

        <!-- Quick Links -->
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
            <a href="<?php echo esc_url( home_url('/') ); ?>" class="btn btn-primary">
                🏠 <?php esc_html_e( 'Return Home', 'pngcje' ); ?>
            </a>
            <a href="<?php echo esc_url( home_url('/our-work/') ); ?>" class="btn btn-outline">
                <?php esc_html_e( 'Browse Resources', 'pngcje' ); ?>
            </a>
            <a href="<?php echo esc_url( home_url('/contact-us/') ); ?>" class="btn btn-outline">
                <?php esc_html_e( 'Contact Us', 'pngcje' ); ?>
            </a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
