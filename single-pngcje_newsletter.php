<?php
get_header();
while ( have_posts() ) : the_post();
$post_id   = get_the_ID();
$downloads = function_exists( 'pngcje_newsletter_get_downloads' ) ? pngcje_newsletter_get_downloads( $post_id ) : [];
$sent_at   = get_post_meta( $post_id, '_pngcje_newsletter_sent_at', true );
$year      = get_post_meta( $post_id, '_pngcje_newsletter_year', true );
$issue     = get_post_meta( $post_id, '_pngcje_newsletter_issue', true );
$volume    = get_post_meta( $post_id, '_pngcje_newsletter_volume', true );
$body      = trim( (string) get_post_field( 'post_content', $post_id ) );
$details   = array_filter( [
    __( 'Published', 'pngcje' ) => get_the_date(),
    __( 'Year', 'pngcje' )      => $year,
    __( 'Volume', 'pngcje' )    => $volume,
    __( 'Issue', 'pngcje' )     => $issue,
    __( 'Downloads', 'pngcje' ) => count( $downloads ),
] );
?>

<div <?php pngcje_page_hero_attrs(); ?>>
    <div class="container">
        <div class="page-hero__eyebrow"><?php pngcje_breadcrumbs(); ?></div>
        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1rem;flex-wrap:wrap;">
            <span class="badge badge--gold"><?php echo esc_html( get_the_date() ); ?></span>
            <?php if ( $sent_at ) : ?><span class="badge" style="background:rgba(255,255,255,.15);color:#fff;"><?php esc_html_e( 'Published Newsletter', 'pngcje' ); ?></span><?php endif; ?>
        </div>
        <h1 class="page-hero__title"><?php the_title(); ?></h1>
        <?php if ( has_excerpt() ) : ?><p class="page-hero__desc"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
    </div>
</div>

<section class="section">
    <div class="container newsletter-single-layout">
        <main class="newsletter-single-main">
            <div class="newsletter-single__feature">
                <?php if ( has_post_thumbnail() ) : ?>
                <figure class="newsletter-single__figure">
                    <?php the_post_thumbnail( 'medium_large', [ 'loading' => 'eager', 'decoding' => 'async', 'class' => 'newsletter-single__image', 'alt' => esc_attr( wp_strip_all_tags( get_the_title() ) ) ] ); ?>
                </figure>
                <?php endif; ?>

                <article class="newsletter-single__article entry-content">
                    <h2><?php esc_html_e( 'About this newsletter', 'pngcje' ); ?></h2>
                    <?php if ( '' !== $body ) : ?>
                        <?php the_content(); ?>
                    <?php elseif ( has_excerpt() ) : ?>
                        <p><?php echo esc_html( get_the_excerpt() ); ?></p>
                    <?php else : ?>
                        <p><?php esc_html_e( 'No extended description is available. Use the download link for the full newsletter.', 'pngcje' ); ?></p>
                    <?php endif; ?>
                </article>
            </div>

            <?php if ( $downloads ) : ?>
            <div class="newsletter-single__cta" role="region" aria-label="<?php esc_attr_e( 'Newsletter downloads', 'pngcje' ); ?>">
                <span class="newsletter-single__cta-meta">
                    <?php echo esc_html( sprintf( _n( '%d download available', '%d downloads available', count( $downloads ), 'pngcje' ), count( $downloads ) ) ); ?>
                </span>
                <a href="<?php echo esc_url( $downloads[0]['url'] ); ?>" class="btn btn-primary" target="_blank" rel="noopener noreferrer" download><?php echo esc_html( $downloads[0]['label'] ); ?></a>
            </div>
            <?php endif; ?>

            <nav class="newsletter-single__nav" aria-label="<?php esc_attr_e( 'Newsletter navigation', 'pngcje' ); ?>">
                <a href="<?php echo esc_url( home_url( '/newsletters/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'All newsletters', 'pngcje' ); ?></a>
            </nav>
        </main>

        <aside class="newsletter-single-aside">
            <div class="card newsletter-single__panel">
                <div class="card__body">
                    <h3 class="newsletter-single__panel-title"><?php esc_html_e( 'Newsletter details', 'pngcje' ); ?></h3>
                    <dl class="newsletter-single__details">
                        <?php foreach ( $details as $label => $value ) : ?>
                        <div>
                            <dt><?php echo esc_html( $label ); ?></dt>
                            <dd><?php echo esc_html( $value ); ?></dd>
                        </div>
                        <?php endforeach; ?>
                    </dl>
                </div>
            </div>

            <?php if ( $downloads ) : ?>
            <div class="card newsletter-single__panel newsletter-single__panel--downloads">
                <div class="card__body">
                    <h3 class="newsletter-single__panel-title"><?php esc_html_e( 'Download links', 'pngcje' ); ?></h3>
                    <ul class="newsletter-single__downloads">
                        <?php foreach ( $downloads as $download ) : ?>
                        <li><a href="<?php echo esc_url( $download['url'] ); ?>" target="_blank" rel="noopener noreferrer" download><span aria-hidden="true">›</span><span><?php echo esc_html( $download['label'] ); ?></span></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </aside>
    </div>
</section>

<?php endwhile; get_footer(); ?>
