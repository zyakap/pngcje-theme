<?php
/**
 * Posts index template for the News page.
 */
$request_path        = isset( $_SERVER['REQUEST_URI'] ) ? trim( (string) parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ), '/' ) : '';
$posts_page_id       = (int) get_option( 'page_for_posts' );
$posts_page_slug     = $posts_page_id ? get_post_field( 'post_name', $posts_page_id ) : '';
$posts_page_template = $posts_page_id ? get_page_template_slug( $posts_page_id ) : '';

if (
    preg_match( '#^newsletters(?:/page/[0-9]+)?$#', $request_path )
    || 'newsletters' === $posts_page_slug
    || 'page-templates/page-newsletters.php' === $posts_page_template
) {
    require get_template_directory() . '/page-templates/page-newsletters.php';
    return;
}

require get_template_directory() . '/page-templates/page-news.php';
