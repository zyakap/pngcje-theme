<?php
/**
 * Template Name: Governance
 * Refreshed hard-coded content for the PNGCJE redesign.
 */
get_header();

$page_label = "Governance";
$page_title = "Governance";
$page_desc = "The PNGCJE is governed through a Board, Secretariat and Faculty of Trainers established to support accountability, strategic direction and judicial education delivery.";
$page_sidebar = "about";
$page_resource_type = "";
$page_resource_label = "Available Documents";
$page_sections = json_decode( <<<'JSON'
[
  {
    "label": "Governance Framework",
    "title": "Board, Secretariat and Faculty of Trainers",
    "body": [
      "The PNGCJE was established under a Memorandum of Understanding that created a Board with membership from law and justice sector agencies concerned with judicial administration and development.",
      "The Secretariat is headed by an Executive Director who also serves as Chief Executive Officer and Secretary to the Board. The Faculty of Trainers supports delivery of structured training programs across the judiciary and law and justice sector."
    ]
  },
  {
    "label": "Institutional Development",
    "title": "Built to support a permanent centre of excellence",
    "body": [
      "The organizational structure implemented under the first PNGCJE Business Plan consists of the Board, the Secretariat and the Faculty of Trainers. In the long term, institutionalization by legislation will support a permanent structure and the regional Pacific Centre for Judicial Excellence."
    ]
  },
  {
    "label": "Board Membership",
    "title": "Board Membership",
    "intro": "The Board brings together senior representatives from the judiciary, law and justice sector institutions, legal education and the legal profession to guide PNGCJE's strategic direction and accountability.",
    "board_members": true
  }
]
JSON, true );

$public_page_template = locate_template( 'template-parts/content-public-page.php' );
if ( $public_page_template ) {
    require $public_page_template;
}
get_footer();
