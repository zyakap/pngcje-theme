<?php
/**
 * Template Name: Terms of Use
 * Refreshed hard-coded policy content for the PNGCJE redesign.
 */
get_header();

$page_label = "Website Policy";
$page_title = get_the_title();
$page_desc = "Conditions for using the PNGCJE website, public information, resources and online services.";
$page_sidebar = '';
$page_resource_type = '';
$page_resource_label = 'Available Documents';
$page_sections = json_decode( <<<'JSON'
[
  {
    "label": "Acceptance of Terms",
    "title": "Using this website",
    "body": [
      "By accessing and using this website, users agree to use it responsibly and in accordance with these Terms of Use. The website is provided to share information about PNGCJE, judicial education programs, public resources, news and related services.",
      "If a user does not agree with these terms, they should discontinue use of the website."
    ]
  },
  {
    "label": "Website Content",
    "title": "Information is provided for public reference",
    "body": [
      "The content on this website is provided for general information, public communication and education purposes. While PNGCJE aims to keep information accurate and current, content may change without notice.",
      "Website content should not be treated as legal advice. Users should seek appropriate professional or official advice for legal, procedural or case-specific matters."
    ]
  },
  {
    "label": "Acceptable Use",
    "title": "Users must not misuse the website",
    "list": [
      "Do not attempt to disrupt, damage, overload or gain unauthorised access to the website or related systems.",
      "Do not submit false, misleading, offensive, unlawful or malicious content through website forms.",
      "Do not copy, reproduce or redistribute website material in a misleading way or in a way that suggests endorsement by PNGCJE without permission.",
      "Do not use the website for spam, phishing, automated scraping or any activity that interferes with public access."
    ]
  },
  {
    "label": "Links and Third Parties",
    "title": "External websites and services",
    "body": [
      "This website may link to external websites, partner agencies, learning platforms or public legal resources. External links are provided for convenience and do not imply control over, or responsibility for, those third-party websites.",
      "Users should review the terms and privacy notices of any external websites they visit."
    ]
  },
  {
    "label": "Changes",
    "title": "Updates to these terms",
    "body": [
      "PNGCJE may update these Terms of Use from time to time to reflect changes in website services, policy or operational requirements. Continued use of the website after updates means the revised terms apply."
    ]
  }
]
JSON, true );

$public_page_template = locate_template( 'template-parts/content-public-page.php' );
if ( $public_page_template ) {
    require $public_page_template;
}
get_footer();
