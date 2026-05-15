<?php
/**
 * Template Name: Bench Books
 * Refreshed hard-coded content for the PNGCJE redesign.
 */
get_header();

$page_label = "Bench Books";
$page_title = "Bench Books";
$page_desc = "Practical reference publications for judicial officers, supporting consistent decision-making and court practice.";
$page_sidebar = "ourwork";
$page_resource_type = "bench-books";
$page_resource_label = "Bench Book Documents";
$page_sections = json_decode( <<<'JSON'
[
  {
    "label": "Judicial Reference",
    "title": "Practical guides for court practice",
    "body": [
      "Bench Books provide structured reference material for judicial officers. They are designed to support consistent approaches to court procedure, legal principles and everyday judicial responsibilities."
    ]
  }
]
JSON, true );

$public_page_template = locate_template( 'template-parts/content-public-page.php' );
if ( $public_page_template ) {
    require $public_page_template;
}
get_footer();
