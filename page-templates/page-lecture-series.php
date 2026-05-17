<?php
/**
 * Template Name: Lecture Series
 * Refreshed hard-coded content for the PNGCJE redesign.
 */
get_header();

$page_label = get_the_title();
$page_title = get_the_title();
$page_desc = "The lecture series brings leading jurists and practitioners into conversation with the judiciary, legal profession and law students.";
$page_sidebar = "ourwork";
$page_resource_type = "lecture-series";
$page_resource_label = "Lecture Series Materials";
$page_sections = json_decode( <<<'JSON'
[
  {
    "label": "Public Legal Education",
    "title": "Special lectures on law and judicial practice",
    "body": [
      "The PNGCJE Lecture Series provides a platform for substantive lectures on law, judicial practice, ethics, leadership and regional justice issues. Materials are preserved for continued reference after each session."
    ]
  }
]
JSON, true );

$public_page_template = locate_template( 'template-parts/content-public-page.php' );
if ( $public_page_template ) {
    require $public_page_template;
}
get_footer();
