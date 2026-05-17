# PNGCJE WordPress Theme

Custom WordPress theme for the Papua New Guinea Centre for Judicial Excellence website refresh.

## Presentation Summary

This theme now provides a refreshed, mobile-optimised PNGCJE website with preserved content from the existing live site and a cleaner administrative workflow.

Completed work includes:

- Full public page template migration using preserved website content as the baseline.
- Refreshed hard-coded page copy for core public pages, removing dependence on old Visual Composer shortcode content.
- Mobile optimisation across the homepage, templates, grids, staff views, resource cards and shared layout sections.
- Admin-managed homepage hero carousel with up to five rotating slides.
- Resource library, staff directory, Pacific Centre, events, popups and custom forms built into the theme.
- Safer form handling with AJAX submissions, file upload support, basic rate limiting and private submission storage.
- SEO and structured data improvements, including Organization, WebSite and Event schema.
- Logo references updated to `assets/img/pngcje_logo.png`, with Customizer logo support and text fallback.
- Preserved content export stored under `content-preservation/` for future migration/reference.

## Brand Direction

The visual system is based on the PNGCJE identity:

- Ember orange: `#D4581A`
- Burnt ember: `#B84A00`
- Gold accent: `#C8920A`
- Forest green: `#1A5C2A`
- Typography: Montserrat via Google Fonts

## Quick Setup

1. Upload and activate the theme:

   - Go to WP Admin → Appearance → Themes → Add New → Upload Theme.
   - Upload `pngcje-theme.zip`.
   - Activate the theme.

2. Set the homepage:

   - Go to Settings → Reading.
   - Choose "A static page".
   - Set Homepage to `Home`.
   - Set Posts page to `News` if a News page exists.
   - WordPress will automatically use `front-page.php` for the homepage.

3. Confirm the logo:

   - Preferred packaged logo path: `assets/img/pngcje_logo.png`.
   - Or set the logo in Appearance → Customize → Site Identity.
   - The Customizer logo takes priority over the packaged logo.

4. Set up menus:

   - Appearance → Menus.
   - Assign menus to:
     - Primary Navigation
     - Top Bar Links
     - Footer: Quick Links
     - Footer: Our Work
     - Footer: Pacific

5. Configure theme settings:

   - Appearance → Customize → PNGCJE Theme Settings.
   - Configure announcement bar, contact information and social links.

## Homepage Hero Carousel

The homepage hero is now managed from the WordPress dashboard.

Go to WP Admin → Hero Slides → Add New Hero Slide.

For each slide:

- Set the slide title for admin reference.
- Set Featured Image for the banner image.
- Fill in:
  - Subheading
  - Heading
  - Intro Text
  - Button Text
  - Button URL
- Publish the slide.

The homepage automatically rotates the latest five published hero slides. If no slides exist, the theme displays a fallback PNGCJE hero message.

## Public Page Coverage

The theme includes slug-based wrappers, so the following public URLs can resolve automatically without manually assigning templates in the editor:

- `/`
- `/about/`
- `/about/staff/`
- `/about/governance/`
- `/about/sitemap/`
- `/contact-us/`
- `/our-work/`
- `/bench-books/`
- `/handbook/`
- `/papua-new-guinea-supreme-court-national-court-case-notes/`
- `/continuing-professional-development-lectures/`
- `/executive-director-speeches/`
- `/lecture-series/`
- `/prospectus/`
- `/prospectus/training-calendar/`
- `/annual-reports/`
- `/newsletters/`
- `/customer-service/`
- `/news/`
- `/pacific-island-centre-for-judicial-excellence/`
- `/integrity-and-judicial-well-being-2/`
- `/integrity-and-judicial-well-being-2-2/`

Transactional/plugin pages were intentionally left to plugin/default rendering:

- Shop
- Cart
- Checkout
- My Account
- Register
- LawAsia Registration
- Hotel Confirmation
- Payment Response

## Built-In Admin Systems

### Resources

Use Resources for PDFs and public documents such as bench books, case notes, handbooks, CPD lectures, speeches, prospectus documents, newsletters and customer service documents. Annual reports are managed separately under the Annual Reports dashboard menu.

Recommended Resource Types:

- `bench-books`
- `case-notes`
- `handbook`
- `cpd-lectures`
- `speeches`
- `prospectus`
- `lecture-series`
- `newsletters`
- `customer-service`

### Annual Reports

Use Annual Reports for yearly institutional reports. They have their own dashboard menu and are not a Resource Type.

### Staff Directory

Use Staff Members for leadership and staff profiles. Staff can include role, email, phone, photo and department taxonomy.

### Pacific Members

Use Pacific Members for country/member cards in the Pacific Centre page.

### Forms

The theme includes a custom form builder.

Suggested forms:

- Form 1: Newsletter signup
- Form 2: Contact enquiry
- Form 3: Event registration

Shortcode:

```text
[pngcje_form id="1"]
```

### Popups

Use Popups for announcements, vacancies and campaign notices. Suggested setup:

- LAWASIA or major event announcement: show on homepage once per session.
- Vacancy or urgent notice: show across all pages with frequency limits.

### Events

Use Events for training programs, workshops and public event listings.

Useful shortcodes:

```text
[pngcje_events count="3"]
[pngcje_events count="5" style="list"]
[pngcje_events count="3" style="mini"]
[pngcje_events category="training"]
[pngcje_calendar_link]
```

## Content Preservation

Preserved live-site content is stored in `content-preservation/`.

Important files:

- `content-preservation/content-inventory.md`
- `content-preservation/pages.json`
- `content-preservation/posts.json`
- `content-preservation/media.json`
- `content-preservation/pages-readable/`
- `content-preservation/posts-readable/`
- `content-preservation/public-page-template-coverage.md`

Captured content includes:

- 31 pages
- 121 posts/news items
- 484 media records
- 7 categories

## Important Files

```text
pngcje-theme/
├── front-page.php               Homepage with admin-managed hero carousel
├── home.php                     News/posts index
├── functions.php                Theme setup, CPTs, taxonomies, schema
├── header.php                   Topbar, logo, navigation, search, mobile menu
├── footer.php                   Footer navigation and contact information
├── style.css                    Design system, components, responsive rules
├── assets/js/main.js            Navigation, search, reveal effects, carousel
├── inc/meta-boxes.php           Resource, staff, Pacific and hero slide fields
├── inc/admin.php                Admin branding and dashboard widget
├── inc/systems.php              Forms, popups and events loader
├── page-templates/              Refreshed hard-coded public page templates
├── template-parts/              Shared page sections and sidebars
└── content-preservation/        Preserved source content from live site
```

## Final Pre-Presentation Checklist

- Confirm `assets/img/pngcje_logo.png` exists, or upload the logo via Customizer.
- Create at least three Hero Slides with strong images and clear headings.
- Set Homepage to `Home` and Posts page to `News`.
- Check the primary navigation and footer menus.
- Add or confirm Resource Types before uploading public documents.
- Review the homepage, About, Our Work, News, Pacific Centre and Contact pages on mobile.
- If moving to a server, visit Settings → Permalinks and click Save Changes once after activation.

## Verification Already Run

- PHP syntax checks passed for edited theme files.
- Cursor lints reported no errors after the latest implementation work.
- Public page template coverage was checked, with 21 public wrappers covered.

## Compatibility

- Recommended PHP: 7.4+
- WordPress: modern supported versions
- Browsers: Chrome, Edge, Firefox, Safari and current mobile browsers

© Papua New Guinea Centre for Judicial Excellence. Custom WordPress theme.
