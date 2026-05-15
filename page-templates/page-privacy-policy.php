<?php
/**
 * Template Name: Privacy Policy
 * Refreshed hard-coded policy content for the PNGCJE redesign.
 */
get_header();

$page_label = "Website Policy";
$page_title = "Privacy Policy";
$page_desc = "How PNGCJE handles personal information submitted through this website and its online services.";
$page_sidebar = '';
$page_resource_type = '';
$page_resource_label = 'Available Documents';
$page_sections = json_decode( <<<'JSON'
[
  {
    "label": "Our Commitment",
    "title": "Respecting privacy and responsible information handling",
    "body": [
      "The Papua New Guinea Centre for Judicial Excellence (PNGCJE) respects the privacy of people who visit this website, contact the Centre, register for programs, subscribe to updates or use online forms.",
      "This policy explains the types of information that may be collected through the website and how that information is used to support communication, training administration and public service delivery."
    ]
  },
  {
    "label": "Information We May Collect",
    "title": "Website enquiries, registrations and technical information",
    "list": [
      "Contact details submitted through forms, such as name, email address, phone number, organisation and message content.",
      "Training or event registration details submitted for PNGCJE programs and workshops.",
      "Newsletter subscription details, where a user chooses to subscribe.",
      "Basic technical information such as browser type, device type, pages visited and approximate usage patterns for website improvement and security."
    ]
  },
  {
    "label": "Use of Information",
    "title": "Why information is collected",
    "body": [
      "Information submitted through this website is used to respond to enquiries, administer training and events, provide requested resources, improve website services and communicate relevant PNGCJE updates.",
      "PNGCJE does not sell personal information. Information may be shared only where required for legitimate program administration, legal obligations, security, or with authorised service providers supporting the website and related systems."
    ]
  },
  {
    "label": "Security and Retention",
    "title": "Protecting submitted information",
    "body": [
      "Reasonable administrative and technical measures are used to protect information submitted through this website. Users should avoid submitting sensitive personal information unless it is necessary for the purpose of the enquiry or registration.",
      "Information is retained only for as long as reasonably required for the purpose it was collected, for record keeping, or to meet legal and administrative requirements."
    ]
  },
  {
    "label": "Contact",
    "title": "Privacy enquiries",
    "body": [
      "For questions about this privacy policy or information submitted through the website, contact PNGCJE at info@pngcje.gov.pg or through the Contact Us page."
    ]
  }
]
JSON, true );

$public_page_template = locate_template( 'template-parts/content-public-page.php' );
if ( $public_page_template ) {
    require $public_page_template;
}
get_footer();
