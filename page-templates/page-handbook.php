<?php
/**
 * Template Name: Judicial Handbook
 * Refreshed hard-coded content for the PNGCJE redesign.
 */
get_header();

$page_label = "Judicial Handbook";
$page_title = get_the_title();
$page_desc = "The PNG Judicial Handbook is a practical reference for judicial officers and court users seeking guidance on judicial administration and procedure.";
$page_sidebar = "ourwork";
$page_resource_type = "judicial-handbook";
$page_resource_label = "Handbook Documents";
$page_sections = json_decode( <<<'JSON'
[
  {
    "label": "Reference Guide",
    "title": "A practical guide for judicial officers",
    "body": [
      "The Handbook brings together guidance on judicial roles, court procedure, administration and professional responsibilities. It supports officers with a concise reference point for day-to-day court work."
    ]
  }
]
JSON, true );

$public_page_template = locate_template( 'template-parts/content-public-page.php' );
if ( $public_page_template ) {
    require $public_page_template;
}
get_footer();
