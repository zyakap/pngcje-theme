<?php
/**
 * Template Name: Executive Director Speeches
 * Refreshed hard-coded content for the PNGCJE redesign.
 */
get_header();

$page_label = "Executive Director Speeches";
$page_title = "Executive Director Speeches";
$page_desc = "Speeches and papers from PNGCJE leadership are preserved for reference, learning and institutional memory.";
$page_sidebar = "ourwork";
$page_resource_type = "executive-director-speeches";
$page_resource_label = "Speeches and Papers";
$page_sections = json_decode( <<<'JSON'
[
  {
    "label": "Leadership Voice",
    "title": "Addresses from PNGCJE leadership",
    "body": [
      "The Executive Director speaks at judicial education conferences, regional meetings and legal sector forums. These speeches capture institutional direction, lessons from programs and emerging priorities in judicial education."
    ]
  }
]
JSON, true );

$public_page_template = locate_template( 'template-parts/content-public-page.php' );
if ( $public_page_template ) {
    require $public_page_template;
}
get_footer();
