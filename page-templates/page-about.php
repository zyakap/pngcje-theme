<?php
/**
 * Template Name: About Page
 * Refreshed hard-coded content for the PNGCJE redesign.
 */
get_header();

$page_label = "About PNGCJE";
$page_title = "About the PNGCJE";
$page_desc = "Established in 2010, the Papua New Guinea Centre for Judicial Excellence coordinates judicial education and professional development for the courts and the wider law and justice sector.";
$page_sidebar = "about";
$page_resource_type = "";
$page_resource_label = "Available Documents";
$page_sections = json_decode( <<<'JSON'
[
  {
    "label": "Institutional Mandate",
    "title": "A centre established for judicial excellence",
    "body": [
      "The Papua New Guinea Centre for Judicial Excellence (PNGCJE) was established in 2010 under a Memorandum of Understanding between the Chief Justice, the Chief Magistrate and the Secretary for the Department of Justice and Attorney General.",
      "The Centre delivers structured training programs for Judges, Magistrates, Court Officers and other officers of law and justice sector agencies involved in the court process. Its work supports more consistent, responsive and professional delivery of judicial services to the people of Papua New Guinea."
    ]
  },
  {
    "label": "Core Objectives",
    "title": "Three objectives guide the Centre",
    "list": [
      "Promote judicial excellence across the Judiciary and Magisterial Services.",
      "Promote professional development and practical training for officers of the law and justice sector.",
      "Foster awareness of judicial administration, developments in the law, and social and community issues that affect justice delivery."
    ]
  },
  {
    "label": "Regional Role",
    "title": "A Papua New Guinea institution with Pacific reach",
    "body": [
      "The PNG Judiciary, through PNGCJE, is a major contributor to judicial capacity building in the Pacific region. The Centre works with courts, training institutions and partner judiciaries to strengthen judicial education across neighbouring Pacific jurisdictions.",
      "The PNGCJE also works closely with institutions such as the Commonwealth Judicial Education Institute, Judicial Commission of New South Wales, Pacific Judicial Strengthening Initiative, National Judicial College of Australia, Institute of Judicial Studies New Zealand and the UK Judicial College."
    ]
  }
]
JSON, true );

$public_page_template = locate_template( 'template-parts/content-public-page.php' );
if ( $public_page_template ) {
    require $public_page_template;
}
get_footer();
