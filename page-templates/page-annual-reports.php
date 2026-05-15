<?php
/**
 * Template Name: Annual Reports
 * Refreshed hard-coded content for the PNGCJE redesign.
 */
get_header();

$page_label = "Annual Reports";
$page_title = "Annual Reports";
$page_desc = "Annual reports provide transparency over PNGCJE programs, partnerships, activities and institutional progress.";
$page_sidebar = "ourwork";
$page_resource_type = "annual-reports";
$page_resource_label = "Annual Report Documents";
$page_sections = json_decode( <<<'JSON'
[
  {
    "label": "Accountability",
    "title": "Reporting on programs and outcomes",
    "body": [
      "Annual reports record PNGCJE activities, training outcomes, partnerships and institutional development. They provide a public record of the Centre’s contribution to judicial education and service improvement."
    ]
  }
]
JSON, true );

$public_page_template = locate_template( 'template-parts/content-public-page.php' );
if ( $public_page_template ) {
    require $public_page_template;
}
get_footer();
