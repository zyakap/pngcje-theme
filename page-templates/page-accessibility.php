<?php
/**
 * Template Name: Accessibility
 * Refreshed hard-coded policy content for the PNGCJE redesign.
 */
get_header();

$page_label = "Website Policy";
$page_title = get_the_title();
$page_desc = "PNGCJE is committed to making website information easier to access, read and use across devices and user needs.";
$page_sidebar = '';
$page_resource_type = '';
$page_resource_label = 'Available Documents';
$page_sections = json_decode( <<<'JSON'
[
  {
    "label": "Accessibility Commitment",
    "title": "A more usable public website",
    "body": [
      "PNGCJE aims to provide a website that is accessible, readable and usable by the widest possible audience, including people using mobile devices, assistive technologies, keyboard navigation and slower internet connections.",
      "The redesigned theme uses responsive layouts, clearer spacing, stronger contrast, readable typography and structured headings to improve access to public information."
    ]
  },
  {
    "label": "Accessibility Features",
    "title": "Built into the refreshed theme",
    "list": [
      "Mobile-responsive layouts for phones, tablets and desktop screens.",
      "Keyboard-accessible navigation, search, drawer menu and carousel controls.",
      "Skip-to-content link for faster keyboard navigation.",
      "Readable colour contrast with dark overlays on image-backed headings.",
      "Structured headings, semantic landmarks and ARIA labels where appropriate.",
      "Reduced-motion support for the homepage hero carousel."
    ]
  },
  {
    "label": "Ongoing Improvement",
    "title": "Accessibility is a continuing process",
    "body": [
      "Accessibility will continue to improve as content is added, documents are uploaded and new services are introduced. Uploaded PDFs, images and media should include clear titles, meaningful alternative text where applicable and accessible document formatting wherever possible.",
      "Content editors are encouraged to use plain language, descriptive link text, proper headings and concise page structures when updating the website."
    ]
  },
  {
    "label": "Feedback",
    "title": "Report an accessibility issue",
    "body": [
      "If you experience difficulty accessing information or using a feature on this website, please contact PNGCJE through the Contact Us page or email info@pngcje.gov.pg with details of the page and issue encountered."
    ]
  }
]
JSON, true );

$public_page_template = locate_template( 'template-parts/content-public-page.php' );
if ( $public_page_template ) {
    require $public_page_template;
}
get_footer();
