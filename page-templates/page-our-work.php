<?php
/**
 * Template Name: Our Work Hub
 * Refreshed hard-coded content for the PNGCJE redesign.
 */
get_header();

$page_label = "Our Work";
$page_title = "Our Work";
$page_desc = "Explore PNGCJE programs, publications and services supporting judicial education, court administration and professional development.";
$page_sidebar = "";
$page_resource_type = "";
$page_resource_label = "Available Documents";
$page_sections = json_decode( <<<'JSON'
[
  {
    "label": "Program Areas",
    "title": "Resources and services for the courts",
    "body": [
      "The PNGCJE supports judicial officers, court officers and partner agencies through practical publications, training programs, professional development lectures, reports, newsletters and service information."
    ]
  },
  {
    "label": "Quick Access",
    "title": "Choose an area of work",
    "cards": [
      {
        "title": "Case Notes",
        "desc": "Supreme Court and National Court case notes for legal reference and continuing learning.",
        "url": "/papua-new-guinea-supreme-court-national-court-case-notes/"
      },
      {
        "title": "Bench Books",
        "desc": "Reference materials that support judicial officers in court practice.",
        "url": "/bench-books/"
      },
      {
        "title": "Judicial Handbook",
        "desc": "A practical guide for judicial procedure, administration and responsibilities.",
        "url": "/handbook/"
      },
      {
        "title": "CPD Lectures",
        "desc": "Continuing Professional Development lectures and learning resources.",
        "url": "/continuing-professional-development-lectures/"
      },
      {
        "title": "Executive Director Speeches",
        "desc": "Speeches, papers and addresses from PNGCJE leadership.",
        "url": "/executive-director-speeches/"
      },
      {
        "title": "Prospectus",
        "desc": "Annual program information, training priorities and schedules.",
        "url": "/prospectus/"
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
