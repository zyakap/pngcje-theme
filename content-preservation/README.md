# PNGCJE Content Preservation

This folder contains a preservation export from `https://pngcje.gov.pg` for the redesign.

## Primary Files

- `wp-rest-content-export.json` contains the combined WordPress REST export.
- `pages.json` and `posts.json` preserve original page/post content, including rendered HTML and old shortcode markup.
- `media.json` preserves media-library metadata and source URLs.
- `taxonomies.json` preserves categories and tags.
- `content-inventory.md` lists all captured pages, posts, and media files.

## Readable Copy

- `pages-readable/` contains cleaned Markdown snapshots for page copy migration.
- `posts-readable/` contains cleaned Markdown snapshots for news/post copy migration.
- `pages-readable-index.md` and `posts-readable-index.md` link to the cleaned Markdown files.

The readable Markdown removes most legacy builder shortcode wrappers. Keep the JSON files as the source of truth if a page needs exact legacy markup or media references.
