<?php
/**
 * Template Name: Newsletters
 * Refreshed hard-coded content for the PNGCJE redesign.
 */
get_header();

$page_label = "Newsletters";
$page_title = "Newsletters";
$page_desc = "Newsletters share program highlights, announcements and stories from PNGCJE training and outreach activities.";
$page_sidebar = "ourwork";
$page_resource_type = "newsletters";
$page_resource_label = "Newsletter Issues";
$page_sections = json_decode( <<<'JSON'
[
  {
    "label": "Updates",
    "title": "Regular news from the Centre",
    "body": [
      "PNGCJE newsletters capture training updates, court officer programs, regional activities, staff news and partner engagement. They provide a readable record of the Centre’s work over time."
    ]
  }
]
JSON, true );

$public_page_template = locate_template( 'template-parts/content-public-page.php' );
if ( $public_page_template ) {
    require $public_page_template;
}
get_footer();
