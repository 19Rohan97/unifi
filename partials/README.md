# Shared partials — single source of truth

These three blocks are **common to every page** of the WLV site:

| Partial | File | Renders |
| --- | --- | --- |
| Site header | `site-header.html` | Logo, primary nav with dropdowns, phone + Get a Quote CTA |
| Breadcrumbs | `breadcrumbs.html` | Trail below the header; keep in sync with BreadcrumbList JSON-LD |
| Page hero | `page-hero.html` | Standard interior-page hero (eyebrow / title / lead / framed photo) |

## Rules

1. **Never fork these blocks.** When a page needs the header, breadcrumbs, or a
   standard hero, copy the canonical markup from this folder verbatim, then fill
   only the documented variation points (marked with comments inside each file).
2. **A change to a partial is a change to every page.** Edit the canonical file
   first, then propagate to all pages that embed it. Currently embedded in:
   - `../index.html` (header, breadcrumbs — uses its own bespoke hub hero)
   - `../contact.html` (header, breadcrumbs, page-hero)
3. **Header variation points** (the only two):
   - `is-current` + `aria-current="page"` on the current page's top-level link
   - the `.header-quote` href → page's own form anchor, else `/contact/#contact-form`
4. **Hero exceptions:** hub/landing pages (like `/unifi/`) may use a bespoke hero.
   Everything else uses `page-hero`.
5. All styling for these blocks lives in the shared `../styles.css`; behavior in
   `../script.js`. Page-specific stylesheets must not restyle them.

## WordPress mapping (when these are ported)

- `site-header.html` → `header.php` / a header template part; nav becomes a
  `wp_nav_menu` with the dropdown markup via a custom walker (or the theme
  builder's mega-menu).
- `breadcrumbs.html` → breadcrumb template part (or Rank Math/Yoast breadcrumbs
  styled with `.breadcrumbs`), which also emits BreadcrumbList schema — drop the
  hand-written JSON-LD then.
- `page-hero.html` → a template part fed by ACF fields
  (eyebrow, title, lead, photo, caption) or a reusable block/pattern.
- Enqueue `styles.css` / `script.js` with a version string — the static pages
  already use `?v=YYYYMMDD` for cache busting.
