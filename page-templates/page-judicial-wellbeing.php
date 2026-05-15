<?php
/**
 * Template Name: Integrity and Judicial Well-being
 * Refreshed hard-coded content for the PNGCJE redesign.
 */
get_header();

$page_label = "Judicial Well-being";
$page_title = "Integrity and Judicial Well-being";
$page_desc = "A focused program area supporting ethical leadership, professional resilience and the well-being of judicial officers.";
$page_sidebar = "";
$page_sections = json_decode( <<<'JSON'
[
  {
    "label": "Program Focus",
    "title": "Integrity and well-being belong together",
    "body": [
      "The Integrity and Judicial Well-being program recognises that ethical, independent and effective judging requires both strong professional standards and sustainable personal well-being.",
      "The program supports judicial officers through conversations, training and international engagement on integrity, wellness, leadership and the pressures of judicial work."
    ]
  },
  {
    "label": "Key Themes",
    "title": "What the program strengthens",
    "list": [
      "Judicial ethics, integrity and public confidence.",
      "Well-being, resilience and healthy judicial workplaces.",
      "Leadership conversations with regional and international partners.",
      "Practical support for judges and court leaders facing demanding workloads."
    ]
  },
  {
    "label": "Public Updates",
    "title": "Follow related announcements",
    "cards": [
      {
        "title": "News and Updates",
        "desc": "Read the latest PNGCJE stories about integrity, well-being, GBV training and regional engagement.",
        "url": "/news/"
      },
      {
        "title": "Upcoming Events",
        "desc": "See scheduled forums, workshops and regional training activities.",
        "url": "/integrity-and-judicial-well-being-2-2/"
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
