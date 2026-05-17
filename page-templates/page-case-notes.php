<?php
/**
 * Template Name: Case Notes
 * Refreshed hard-coded content for the PNGCJE redesign.
 */
get_header();

$page_label = "Case Notes";
$page_title = get_the_title();
$page_desc = "Curated case notes preserve key legal developments and support continuing learning across the judiciary and legal profession.";
$page_sidebar = "ourwork";
$page_resource_type = "case-notes";
$page_resource_label = "Case Note Documents";
$page_sections = json_decode( <<<'JSON'
[
  {
    "label": "Legal Reference",
    "title": "Important decisions, easier to review",
    "body": [
      "Case notes help judicial officers, lawyers, researchers and students follow important decisions of the Supreme Court and National Court. They make key points of law easier to find, review and apply in practice."
    ]
  }
]
JSON, true );

$public_page_template = locate_template( 'template-parts/content-public-page.php' );
if ( $public_page_template ) {
    require $public_page_template;
}
get_footer();
