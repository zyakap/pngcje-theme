<?php
/**
 * Template Name: CPD Lectures
 * Refreshed hard-coded content for the PNGCJE redesign.
 */
get_header();

$page_label = "Continuing Professional Development Lectures";
$page_title = "Continuing Professional Development Lectures";
$page_desc = "CPD lectures provide accessible learning opportunities for judicial officers and law and justice sector practitioners.";
$page_sidebar = "ourwork";
$page_resource_type = "cpd-lectures";
$page_resource_label = "CPD Lecture Materials";
$page_sections = json_decode( <<<'JSON'
[
  {
    "label": "Continuing Learning",
    "title": "Focused lectures for professional growth",
    "body": [
      "The CPD lecture program supports ongoing learning on law, procedure, ethics, leadership and judicial practice. Sessions connect officers with experienced jurists, practitioners and subject matter experts."
    ]
  }
]
JSON, true );

$public_page_template = locate_template( 'template-parts/content-public-page.php' );
if ( $public_page_template ) {
    require $public_page_template;
}
get_footer();
