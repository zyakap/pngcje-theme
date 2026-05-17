<?php
/**
 * Template Name: Prospectus
 * Refreshed hard-coded content for the PNGCJE redesign.
 */
get_header();

$page_label = "Training Programs";
$page_title = "Prospectus";
$page_desc = "The PNGCJE prospectus provides a forward view of programs, workshops, events and training priorities for judicial officers and law and justice sector practitioners.";
$page_sidebar = "ourwork";
$page_resource_type = "prospectus";
$page_resource_label = "Prospectus Documents";
$page_sections = json_decode( <<<'JSON'
[
  {
    "label": "Annual Planning",
    "title": "Training priorities for the year",
    "body": [
      "The annual prospectus outlines PNGCJE training programs, schedules and priority areas. It helps judicial officers, court officers and partner agencies plan participation in professional development activities."
    ]
  },
  {
    "label": "Related Sections",
    "title": "Plan, review and report",
    "cards": [
      {
        "title": "Training Calendar",
        "desc": "See scheduled training activities, target participants and delivery locations.",
        "url": "/training-calendar/"
      },
      {
        "title": "Annual Reports",
        "desc": "Review completed activities, program outcomes and institutional reporting.",
        "url": "/annual-reports/"
      }
    ]
  }
]
JSON, true );

$public_page_template = locate_template( 'template-parts/content-public-page.php' );
if ( $public_page_template ) {
    require $public_page_template;
}
get_footer();
