<?php
/**
 * Template Name: Customer Service
 * Refreshed hard-coded content for the PNGCJE redesign.
 */
get_header();

$page_label = "Customer Service";
$page_title = "Customer Service";
$page_desc = "The PNGCJE is committed to responsive, respectful and practical support for judicial officers, court officers, partners and the public.";
$page_sidebar = "ourwork";
$page_resource_type = "customer-service";
$page_resource_label = "Customer Service Documents";
$page_sections = json_decode( <<<'JSON'
[
  {
    "label": "Service Commitment",
    "title": "How we support enquiries and program users",
    "body": [
      "The Centre responds to enquiries about training programs, resources, partnerships, staff support and access to learning platforms. Clear service information helps users know where to go and what to expect."
    ],
    "list": [
      "Training and program enquiries",
      "Resource and publication requests",
      "LMS access support",
      "Partnership and stakeholder engagement"
    ]
  }
]
JSON, true );

$public_page_template = locate_template( 'template-parts/content-public-page.php' );
if ( $public_page_template ) {
    require $public_page_template;
}
get_footer();
