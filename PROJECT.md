# RJB Portfolio — Project Reference

Deep reference for the two-repo WordPress portfolio system behind
**reubenjbrown.com** (Reuben J. Brown, multimedia journalist).

`CLAUDE.md` is the short always-loaded index. **This file is the authority on
design standards, tokens, structure and conventions** — read it before any
styling, template, or architecture work.

Audited and written 2026-09-02 against theme `e443fc4` / plugin `59565d7`.

---

## 1. The two repos

| | Path | Deployed to |
|---|---|---|
| **Theme** (`astra-child`) | `/Users/reubenj.brown/RJB-2025-Theme` | `wp-content/themes/astra-child` |
| **Plugin** (`reuben-portfolio-sections`) | `/Users/reubenj.brown/RJB-2025-portfolio-plugin` | `wp-content/plugins/reuben-portfolio-sections` |

**The split that decides where a change goes:**

- **Theme = chrome and page shells.** Header, footer, fonts, design tokens,
  page templates, story templates, story-page CSS.
- **Plugin = content sections.** Every shortcode, its PHP template, its CSS.
  Also the `story` post type, its taxonomy, its ACF fields, and the
  interactive data-viz islands.

> Rule of thumb: if it renders *inside* the page body as a section, it's the
> plugin. If it's the wrapper, the type system, or a story article layout, it's
> the theme. Both repos are separate git checkouts on `main`; a change touching
> both needs two commits and two pushes.

---

## 2. Design standards

### 2.1 Typographic system

Four typefaces, each with one job. Never introduce a fifth, and never use a
face outside its role.

| Role | Token | Family | Source | Used for |
|---|---|---|---|---|
| **Primary / UI** | `--primary-font` | Innovator Grotesk | local `fonts/*.woff2` | body copy in sections, nav, meta, captions, buttons |
| **Editorial serif** | `--serif-font` | Legitima | Adobe Fonts kit `grj8tmk` | `h2`, `h3`, story body prose |
| **Display compressed** | `--compressed-font` | PP Right Serif | local `fonts/*.woff2` | `h1`, `.display-headline`, drop caps, section titles |
| **Index / numeric** | `--index-font` | PP Right Grotesk | local `fonts/*.woff2` | `.index-font` utility (numerals, indexes) |

Weights actually shipped:

- Innovator Grotesk — 400 (+ italic), 600 (+ italic). `header-branded.php`
  additionally maps **500 → the 400 file**, so `font-weight: 500` silently
  renders regular.
- PP Right Serif — 400 (Tall Fine), 600 (Tall Regular).
- PP Right Grotesk — 500 (+ italic) only.

`.compressed-regular` / `.compressed-semibold` / `.index-font` are the utility
classes; `.index-font` also applies `letter-spacing: -0.04em`.

### 2.2 The semantic type scale

`base-sections.css` is the **single source of truth for global element
typography.** It sets `h1`–`h3`, `p`, `.caption`, `.display-headline` for the
whole site. Don't restate these in a section file — override only what differs.

| Element | Family | Desktop size | Mobile (≤768) | Notes |
|---|---|---|---|---|
| `h1` "Supermax" | compressed | `clamp(3rem, 6vw, 6rem)` | — | uppercase, `line-height: .9` |
| `h2` "Headline" | serif | `1.25rem` | `1rem` | |
| `h3` "Standfirst" | serif | `1.875rem` | `1.25rem` | |
| `p` "Body" | primary | `var(--fs-base)` | `var(--fs-sm)` | `line-height: 20px` |
| `.display-headline` | compressed | `8vw` | `min(16vw, 72px)` | `12vw` at ≤1200 |
| `.caption` / `.photo-credit` | primary | `var(--fs-xs)` | `0.6875rem` | right-aligned, muted |
| `.story-meta` | primary | inherits `p` | `--fs-xs` / `lh 14px` | muted; `<i>` inside goes full-contrast |
| `.article-note` | primary | `1rem`, 600 | — | editorial aside inside story prose |
| `.more-stories-head` | compressed | `4.5rem` | — | |
| `p.drop-cap::first-letter` | compressed | `5em` | — | 3-line float drop cap |

**Story pages differ:** `story-templates.css` re-points story body prose to
`--serif-font` and caps text blocks at **760px**. Section pages use
`--primary-font` for body copy. Keep that distinction — it's the deliberate
signal that you're reading an article vs. browsing an index.

### 2.3 The `--fs-*` numeric scale

A fixed-px scale from the Innovator Grotesk foundry manual, defined once in
`style.css`. **Use a token, never a raw px value, for any primary-font text.**

| Token | px | Live use |
|---|---|---|
| `--fs-3xs` | 8.5 | rare |
| `--fs-2xs` | 9.9 | small meta |
| `--fs-xs` | 11.3 | captions, credits, mobile meta |
| `--fs-sm` | 12.74 | mobile body, copyright, mobile nav |
| `--fs-base` | 14.15 | **default body**, pills, story meta |
| `--fs-md` | 17 | desktop nav |
| `--fs-lg` | 18.4 | unused |
| `--fs-xl` | 19.8 | unused |
| `--fs-2xl` | 21.2 | viz callouts only |
| `--fs-3xl` | 22.5 | unused |
| `--fs-4xl` | 24 | unused |
| `--fs-5xl` | 26.9 | unused |
| `--fs-6xl` | 29.7 | viz titles only |
| `--fs-7xl` | 32.5 | unused |
| `--fs-8xl` | 36.8 | unused |

Display type (`h1`, `.display-headline`) deliberately sits **outside** this
scale and uses `clamp()`/`vw` instead — the scale is for text, not for display.

### 2.4 Colour

**Brand palette** (`--cr-*`, defined once in `style.css`). These are raw
pigments; never paint UI directly from them in a way that breaks dark mode —
route through a semantic token or a per-page accent.

```
--cr-lime     #39e58f   the house green (= default highlight)
--cr-sprite   #d1ffe8   pale mint
--cr-palm     #38b04a
--cr-cherry   #ff193b
--cr-sakura   #ffccd4   Japan special report accent
--cr-mango    #ffba1a   Stories nav hover
--cr-page     #FFF8ED   warm paper
--cr-jaffna   #ff811a   story archive accent
--cr-soil     #805533
--cr-navy     #0a2066
--cr-cobalt   #003cff   Photography + Sun special report accent
--cr-delft    #809eff
--cr-lavender #f5f9fc   special-report body ground
--cr-powder   #339fff
--cr-75grey   #bfbfbf   hairline rules
--cr-50grey   #808080   muted text (light mode)
```

**Per-page accent theming.** `--highlight-color` is the one knob that re-themes
a whole page. Override it (and usually `--link-hover-color`) in the template's
own `:root`/`body` block:

| Surface | Accent | Set in |
|---|---|---|
| Homepage / default | `#39e58f` lime | `style.css` |
| Story archive `/stories` | `#ff8119` jaffna | `archive-story.php:26` |
| Photography page | `#003CFF` cobalt | `page-photography.php:26` |
| Sun special report | `var(--cr-cobalt)` | `sun-story-special-report.css:10` |
| Homepage draft 2026 | `var(--cr-cobalt)` | `homepage-draft.css:27` |

`--bg-highlight-color` is a 5% tint **derived** from `--highlight-color` via
`rgb(from … / 0.05)`, so it follows any override automatically. There's a
literal-rgba fallback line above it for browsers without relative colour —
keep that pattern if you add more derived tints.

Nav links have their own per-destination hover colours in `header-branded.php`:
Stories → mango, Photography → cobalt, CV → lime.

### 2.5 Dark mode

`prefers-color-scheme: dark` only — there is **no manual toggle**, so no
`[data-theme]` handling is needed.

| Token | Light | Dark |
|---|---|---|
| `--content-bg` / `--main-content-bg` | `white` | `#050505` |
| `--text-color` | `#000` | `white` |
| `--text-color-muted` | `#808080` | `#a8a8a8` |
| `--link-color` | `#808080` | `white` |
| `--link-hover-color` | `#39e58f` | `#39e58f` |
| `--caption-bg` | `rgba(255,255,255,.9)` | `rgba(5,5,5,.9)` |
| `--h3-link-color` | `#808080` | `#a8a8a8` |
| `--section-heading-color` | `#808080` | `#808080` |
| `--header-text-color` | `white` | `white` (intentionally fixed) |

Rules when adding colour:

1. Prefer an existing semantic token. Add a new pair only if the concept is
   genuinely new, and define it in **both** blocks of `style.css`.
2. The footer logo ships as a black PNG and is **inverted in CSS** for dark
   mode (`filter: invert(1)`), except when `.over-full-bleed` already swapped
   in the white asset.
3. **The special-report templates force light mode.** Both
   `sun-story-special-report.css` and `japan-story-special-report.css` re-assert
   the light values inside their own `@media (prefers-color-scheme: dark)`
   block. That's deliberate — the printed-report look doesn't invert.

### 2.6 Breakpoints

The canonical list lives in a comment block at the top of
`assets/base-sections.css`. **Copy those media queries verbatim; don't invent
new ones.**

```css
/* Tablet      */ @media (max-width: 1200px)
/* Mobile      */ @media (max-width: 768px), ((max-width: 1200px) and (max-height: 768px))
/* Small mobile*/ @media (max-width: 480px), ((max-width: 1200px) and (max-height: 480px))
/* Small tablet*/ @media (max-width: 900px), ((max-width: 1200px) and (max-height: 900px))
```

The compound `max-height` clause exists so a short landscape tablet
(1000×700) gets **mobile** styles rather than tablet ones. Dropping it is the
single most common way to break the layout on landscape phones/iPads.

### 2.7 Spacing and insets

- **Horizontal page inset: `2vw` desktop/tablet, `4vw` mobile.** Header,
  footer, `.main-content`, and story containers all honour this. New full-width
  surfaces must match.
- Mobile adds `env(safe-area-inset-*)` on top of the `4vw` for header padding,
  footer padding, and the footer blur layer. Safari notch/home-bar handling
  depends on it.
- `--header-height: calc(48px + 2vw)` (desktop). Tablet/mobile heights are set
  per breakpoint, not via the token.
- Section rhythm: `.section-heading` uses `padding: 96px 0 64px`;
  `.strategy-intro` uses `padding: 200px 0 6rem` desktop, tightening at each
  breakpoint. `.more-stories-section` = `72px` top margin (→ `3rem` mobile).
- Full-bleed escape hatch: `width: 100vw; margin-left: calc(-50vw + 50%)`.

### 2.8 Chrome behaviour (header / footer)

Both live in the theme (`header-branded.php`, `footer-branded.php`) with their
CSS **inline** in those files. All branded pages call
`get_header('branded')` / `get_footer('branded')`.

- Header is `position: fixed`, transparent, with a **three-layer gradient
  backdrop-blur** (`::before` 8px, `::after` 3px, `.header-content::before`
  5px, each with its own mask). Footer mirrors it and is `position: sticky`.
- `.over-full-bleed` is toggled on `.site-header` and `.site-footer` by JS in
  `header-branded.php` whenever a `.featured-story-full-bleed` or
  `.story-hero-full-bleed` element is under the chrome. It kills the blur,
  forces white text, and swaps the footer logo to the white asset.
  **Any new full-bleed hero must carry one of those two class names** to inherit
  this behaviour.
- On single stories the header also renders `.story-header-scrolled` — a
  compact "Reuben J. Brown / headline / contact →" layer, styled in
  `story-templates.css`, which crossfades in as the header leaves the hero.
- The contact pill flips between `contact ↓` and `top ↑` based on
  `.contact-section` visibility.

---

## 3. Variable tokens — where they live

**All global tokens are defined in exactly one place: `style.css` `:root`
(plus its dark-mode block).** Plugin CSS defines none — it consumes them. Both
`base-sections.css` and `story-templates.css` carry an explicit comment saying
so. Preserve that: a token redefined in a section file is a bug.

Three legitimate exceptions:

1. **`header-branded.php` inline `:root`** re-declares `--highlight-color`,
   `--primary-font`, `--serif-font`, `--compressed-font`,
   `--compressed-italic-font`, `--compressed-semibold-font`,
   `--header-text-color`, `--text-color`, `--header-height`. It loads *after*
   `wp_head()`, so **these win on every branded page.**
   - ⚠️ `--primary-font` differs between the two: `style.css` has a bare
     `'Innovator Grotesk'` with **no fallback stack**; `header-branded.php` has
     the full `-apple-system, …` stack. Branded pages get the good one. If you
     touch either, fix both.
   - `--compressed-italic-font` and `--compressed-semibold-font` are aliases —
     all three resolve to `'PP Right Serif', serif`.
2. **Per-page accent overrides** — see §2.4.
3. **Scoped local tokens** — fine when genuinely local:
   `--story-hero-color` (from a per-post meta box, `single-story-split.php`),
   `--404-*` (`404.php`), `--reveal-*` / `--offset`
   (`photography-draft.css`), `--card-ratio` (floating gallery, set inline per
   card from real image dimensions), `--noil-scale` (Nigeria viz).

### Currently-unused tokens

Defined but never consumed — safe to reach for, don't assume they're wired up:
`--cr-sprite`, `--cr-palm`, `--cr-soil`, `--cr-navy`, `--cr-delft`,
`--cr-powder`, `--fs-lg`, `--fs-xl`, `--fs-3xl`, `--fs-4xl`, `--fs-5xl`,
`--fs-7xl`, `--fs-8xl`.

### SVG text inside viz islands

`--fs-*` are **fixed px**. Dropped into a small `viewBox`, an SVG `<text>`
sized `var(--fs-base)` renders enormous, because user units ≠ px. Scale it:

```js
`calc(var(--fs-base) * ${vbWidth / refWidth})`
```

The Nigeria island instead renders HTML overlays and applies
`calc(var(--fs-sm, 16px) * var(--noil-scale, 1))` — a per-breakpoint scalar
knob. Both approaches are fine; a bare `var(--fs-*)` inside a small viewBox is
not.

---

## 4. Structure

### 4.1 Theme

```
RJB-2025-Theme/
├── style.css                     ← @font-face + ALL design tokens (v1.1.2)
├── functions.php                 ← enqueues, story queries, meta boxes, AJAX
├── header-branded.php            ← <head>, OG tags, inline @font-face + tokens,
│                                   header markup/CSS/JS  (741 lines)
├── footer-branded.php            ← footer markup + inline CSS + logo JS
├── .htaccess                     ← denies .md/.json/.DS_Store in theme dir
├── deploy.sh                     ← git pull both repos over SSH + purge cache
│
├── fonts/                        ← 8 .woff2 (Innovator ×4, PP Right Serif ×2,
│                                   PP Right Grotesk ×2)
│
├── page-portfolio.php            ← THE HOMEPAGE. "Portfolio Page" template
├── page-photography.php          ← "Photography Page" (cobalt accent)
├── archive-story.php             ← /stories index (jaffna accent, AJAX more)
├── taxonomy-story_category.php   ← 6-line shim → template-parts/
├── template-parts/story-archive-content.php
├── 404.php                       ← the ONE template using body_class()
│
├── single-story.php              ← "Full Bleed Hero Story"
├── single-story-split.php        ← "Split Hero Story" (per-post hero colour)
├── single-story-video.php        ← "Video Hero Story"
├── single-story-video-top.php    ← "Video Hero Story (Top)"
├── sun-story-special-report.php  ← "Sun Story — Special Report" (cobalt)
├── japan-story-special-report.php← "Japan Story — Special Report" (sakura)
├── story-templates/
│   ├── story-templates.css       ← shared story-article CSS (890 lines)
│   ├── story-templates.js        ← image caption/credit extraction
│   ├── sun-story-special-report.css
│   └── japan-story-special-report.css
│
├── page-homepage-draft-2026.php   ┐ unlinked private drafts:
├── 2026-homepage-draft/           │ thin page template + content partial
├── page-photography-draft-2026.php│ + own CSS. Not live, not in nav.
├── 2026-photography-draft/        ┘
└── page-test-video.php            ← scratch template
```

### 4.2 Plugin

```
RJB-2025-portfolio-plugin/
├── reuben-portfolio-sections.php  ← ALL shortcodes, `story` CPT,
│                                    `story_category` taxonomy, ACF field group,
│                                    CSS bundler, viz runtime loader (857 lines)
├── fix-domain-urls.php            ← Tools → "Fix Domain URLs" admin utility
│                                    (required_once at the bottom of the above)
├── assets/                        ← one CSS file per section, no tokens defined
│   ├── base-sections.css          ← breakpoint reference + GLOBAL TYPOGRAPHY
│   ├── about-section.css   features-section.css   stories-section.css
│   ├── cv-section.css      featured-story-full-bleed.css
│   ├── photographs-section.css    reviews-section.css
│   ├── reporting-section.css      solar-section.css
│   ├── video-projects-section.css ← (the 11 files in the bundle, in order)
│   ├── photograph-page.css        ← NOT bundled; page-photography.php only
│   ├── cv-dropdown.js
│   └── admin/photo-floating-cards-admin.{css,js}
├── templates/                     ← one PHP partial per shortcode
├── photography-sections/photo-section.php
├── mockups/                       ← design JPGs (reference only)
└── interactive/                   ← Svelte + D3 viz islands (see §7)
```

### 4.3 How a branded page renders

```
WP resolves template  (page-portfolio.php / single-story*.php / archive-story.php)
   │
   ├─ dequeues astra-theme-css (+ astra-theme-js, wp-block-library, emoji on homepage)
   ├─ registers its own wp_head hook: inline <style> + hand-echoed <link>s
   │
   ├─ get_header('branded')  → header-branded.php
   │     <head>: OG/Twitter meta, typekit async, font preload,
   │             wp_head()  ← plugin CSS bundle lands HERE
   │             inline <style>: @font-face, :root overrides, header CSS
   │     <body>  ← BARE, no body_class()
   │     header markup + smooth-scroll / over-full-bleed / contact-pill JS
   │
   ├─ page body: do_shortcode('[…]') per section  → plugin templates/*.php
   │
   └─ get_footer('branded') → footer-branded.php  (markup + inline CSS + JS + wp_footer)
```

**Cascade order to keep in mind:** parent `style.css` → child `style.css` →
plugin CSS bundle (all via `wp_head`) → **header-branded.php inline `<style>`**
→ template-specific inline `<style>` → footer-branded.php inline `<style>`.
Anything inline in a template beats everything enqueued.

### 4.4 CSS architecture rules

1. **Global element typography → `assets/base-sections.css`.** It's the single
   source of truth for `h1`–`h3`, `p`, `.caption`, `.display-headline`.
2. **Section-specific → that section's own `assets/*.css`.**
3. **Story-article-specific → `story-templates/story-templates.css`.**
4. **Tokens → `style.css` only.**
5. **Reuse before you add.** Check `stories-section.css`, `base-sections.css`,
   `style.css` first. `.story-item`, `.story-link`, `.story-image`,
   `.story-content`, `.story-meta`, `.caption`, `.strategy-intro`,
   `.display-headline`, `.content-section`, `.section-container` are the shared
   vocabulary — the AJAX handler in `functions.php:361` emits exactly this
   markup, so archives and homepage grids stay identical for free.
6. **Never edit `wp-content/uploads/reuben-portfolio/portfolio-combined.css`.**
   It's generated. Edit the source file in `assets/`.
7. New shared class → the appropriate shared stylesheet. Genuinely one-off →
   inline in the template is acceptable.

### 4.5 The CSS bundle

`combined_portfolio_css()` (plugin, ~line 405) concatenates the 11 files from
`portfolio_css_files()` into
`wp-content/uploads/reuben-portfolio/portfolio-combined.css`, keyed on the
newest source `filemtime()`, and enqueues that one file. Falls back to
individual enqueues if it can't write.

- **`base-sections.css` must stay first in the array** — everything else
  depends on its resets and element rules.
- Order in the array *is* cascade order. Reordering changes rendering.
- Loads on: any `is_page()`, `page-portfolio.php`, `is_post_type_archive('story')`,
  `is_tax('story_category')`.
- Single stories get `base-sections` + `stories-section` standalone (for
  `[more_stories]`), guarded so the bundle never double-loads.
- `photograph-page.css` is separate and loads only on `page-photography.php`.

---

## 5. Content model

### 5.1 `story` custom post type

Registered in the **plugin**. `public`, `has_archive`, slug `stories`,
`show_in_rest`, supports title/editor/thumbnail/excerpt/custom-fields.
Taxonomy: **`story_category`**, hierarchical, slug `story-category`.

**Category conventions:**

- `photo-*` prefixed terms (`photo-stories`, `photo-portraits`,
  `photo-infrastructure`, `photo-politics`, `photo-cities`, `photo-landscapes`)
  are **auto-excluded** from the `/stories` archive and from any
  `get_portfolio_stories()` call with no explicit category. That exclusion is
  driven by the `photo-` prefix in `get_photo_category_slugs()` —
  naming a new photo term without the prefix silently leaks it into the archive.
- Content categories in use: `features`, `reporting`, `reviews`, `profiles`,
  `architecture`, `photographs`, `cracking-the-sun`, `energy`.
- Archive filter pills are ordered `energy, features, reporting,
  reynolds center`, then everything else (`archive-story.php:153`).

**Archive paging:** 24 per page, then AJAX `load_more_stories` with explicit
`offset`/`limit` (never a page number — the offset is derived from what's
actually on screen).

### 5.2 ACF fields

Registered in code as group `group_story_metadata` (plugin,
`register_acf_fields()`):

`publication` · `medium` · `publish_date` (free text, e.g. "June 2024") ·
`external_url` · `short_headline` · `photo_credit` · `original_image_url` ·
`homepage_thumbnail` · `photo_gallery_urls` · `photo_description` ·
`photo_read_more_button` · `hero_video_url`

**Fields used by templates but NOT registered in code** — they exist only in
the ACF admin UI / database, so they won't appear on a fresh install:

`special_report` · `long_headline` · `specific_publish_date` · `reading_time` ·
`publication_external_url` · `more_in_special_report` · `photo_gallery` ·
`original_image_picker`

Featured-image resolution order (`get_story_featured_image()`):
WP thumbnail → `original_image_picker` → `original_image_url` (relative paths
get `home_url()` prefixed) → `''`.

### 5.3 Post meta (hand-built meta boxes, not ACF)

| Key | Where | Purpose |
|---|---|---|
| `story_hero_color` | theme `functions.php` | colour picker → `--story-hero-color` on the split hero |
| `story_lead_feature` | theme `functions.php` | checkbox: lead (large, left) story in the features grid |
| `_photo_floating_cards` | plugin | sortable repeater for the photography-draft flip-card gallery |
| `_media_source` | plugin | media-library "source" field, exposed via REST |

### 5.4 Key helper functions (theme `functions.php`)

- `get_portfolio_stories($category, $limit, $exclude_photo, $order)` — the
  standard section query. Photo categories auto-excluded when `$category` is
  empty.
- `get_stories_archive_query_args($category, $per_page, $offset)` — shared by
  the SSR archive render **and** the AJAX handler so they can't drift.
- `get_photo_category_slugs()` — all `photo-*` terms.
- `get_story_featured_image($id, $size)` / `get_story_metadata($id)`.

---

## 6. Shortcode reference

Composed sections (each `include`s `templates/<name>.php`):

| Shortcode | Template | Notes |
|---|---|---|
| `[reuben_about]` | `about-section.php` | copy hardcoded in template |
| `[reuben_features]` | `features.php` | category `features`, limit 5; honours `story_lead_feature` |
| `[reuben_reviews]` | `reviews.php` | `reviews` (5) + `profiles` scroller |
| `[reuben_profiles]` | `architecture.php` | category `architecture`, 12 |
| `[reuben_photographs]` | `photographs.php` | category `photographs`, 12 |
| `[reuben_reporting]` | `reporting.php` | category `reporting`, 12 + nested `[reuben_features]` |
| `[reuben_solar]` | `solar.php` | "Cracking the Sun" wrapper → `[reuben_dynamic_stories]` |
| `[reuben_video_projects]` | `video-projects.php` | lightbox grid |
| `[reuben_cv]` | `cv-section.php` | + `cv-dropdown.js` |
| `[featured_story_full_bleed]` | `featured-story-full-bleed.php` | homepage hero; triggers `.over-full-bleed` |
| `[more_stories]` | `more-stories.php` | `limit=5 tag="" heading="More stories"` |
| `[photo_section]` | `photography-sections/photo-section.php` | `type=` one of stories/portraits/infrastructure/politics/cities/landscapes → the matching `photo-*` category |
| `[photo_floating_gallery]` | `photo-floating-gallery.php` | reads `_photo_floating_cards` |
| `[reuben_dynamic_stories]` | `dynamic-stories-section.php` | the general-purpose one — see below |
| `[reuben_viz]` | inline | data-viz island, see §7 |

`[reuben_dynamic_stories]` attributes:
`category=""` `limit=6` `layout="grid|list|featured"` `show_excerpt="true"`
`show_meta="true"` `show_view_all="false"` `show_numerals="false"`
`order="DESC"`

`[reuben_viz]` attributes: `id` (required, = island folder name) `src`
`title` `class`. Any extra attribute passes through as `data-*` → component
prop. Enclosed content becomes body prose inside the graphic.

> **⚠️ Registered but broken.** These eight shortcodes point at template files
> that don't exist in the repo — using one emits a PHP include warning:
> `[reuben_stories]`, `[reuben_interviews]`, `[reuben_strategy]`,
> `[story_list]`, `[story_grid]`, `[story_grid_2x2]`,
> `[featured_story_text]`, `[vertical_video]`.
> `[reuben_dynamic_stories]` is the working replacement for the grid/list ones.
> Note the `.strategy-intro*` CSS classes are very much alive (they're the
> standard section-intro block) even though `[reuben_strategy]` is not.

---

## 7. Interactive data-viz islands

Lives at `RJB-2025-portfolio-plugin/interactive/`. Svelte 5 + D3, built by Vite.
This is the **only part of either repo with a build step.**

```
src/mount.js                 island loader — the ONLY script WP enqueues (~2KB)
src/lib/data/load.js         JSON/CSV fetch + parse
src/lib/charts/responsive.js resize redraw helper
src/lib/scroll/scroller.js   scrollytelling engine
src/viz/<id>/index.js        one folder per chart; default-exports mount(el, props)
src/stories/<id>/index.js    one folder per scrollytelling piece
dist/rjb-viz.js              GENERATED, committed, enqueued. Never hand-edit.
dist/chunks/*                lazy per-island chunks
```

How it works: `[reuben_viz id="foo"]` prints `<div data-viz="foo">`.
`mount.js` globs `./viz/*/index.js` + `./stories/*/index.js` into a registry
keyed by folder name, then uses an `IntersectionObserver` (`rootMargin: 200px`)
to lazily `import()` and mount each island as it nears the viewport. So a page
downloads only the charts it actually contains, only when reached.

WordPress side: `enqueue_viz_runtime()` enqueues `dist/rjb-viz.js` with
`filemtime()` as the version, and `viz_module_type()` rewrites the tag to
`type="module"` (WP has no native flag for that).

**Workflow:**

```bash
cd /Users/reubenj.brown/RJB-2025-portfolio-plugin/interactive
npm install     # first time on a machine only
npm run dev     # localhost:5173, HMR — the ONLY live preview in this project
npm run build   # regenerates dist/ — MUST be run and committed before deploy
```

`dist/` is committed on purpose (the server has no Node build step). Editing a
`.svelte` file without running `npm run build` ships nothing.
`node_modules/` is gitignored and disposable.

Existing islands: `viz/Nigeria_Oil_Solar`, `stories/diamond-gas-sakura`.
Files with `_ARCHIVE` in the name are dead alternates kept for reference.

---

## 8. Environment and deploy

- **Live:** `reubenjbrown.com` — Hostinger shared hosting, LiteSpeed cache.
- **Staging domain:** `reubenjbrown-com-418819.hostingersite.com`
- **SSH:** `ssh -p 65002 u240680038@82.197.80.158` (key auth, `id_ed25519`)
- `wp-cli` is installed on the server.
- `hostinger` MCP server is configured in `.mcp.json`; its token must be in the
  `env` block of `~/.claude/settings.json` (not `~/.zshrc` — a GUI-launched
  Claude Code never sources that, and the symptom is `Unauthenticated`).

**Deploying.** Both server checkouts are plain git on `main`, so deploy =
`git pull --ff-only` + cache purge. Use the script, never the hPanel Deploy
button:

```bash
./deploy.sh            # both repos + cache
./deploy.sh theme
./deploy.sh plugin
./deploy.sh cache
```

Commit **and push** first — the server pulls from `origin/main`. `--ff-only` is
deliberate: a dirty or diverged server checkout fails loudly instead of merging.
Cache clear uses `wp litespeed-purge all`; Hostinger's API cache endpoint
returns HTTP 500 for this account (as of 2026-08-10) and is behind
`HOSTINGER_USE_API=1`.

For a run of visual iteration, use the `dev-mode` skill (Hostinger cacheless
mode) instead of clearing cache after every change — **ask before enabling it,
and always turn it off at the end.**

**Cache-busting.** Story CSS/JS are hand-echoed `<link>`/`<script>` tags with
`?v=<theme version>`. So **bumping `Version:` in `style.css` is the cache-bust
lever for everything in `story-templates/`** (currently `1.1.2`). Plugin assets
bust automatically on `filemtime()`.

**No local preview.** These are live WordPress PHP templates; there is no dev
server that renders them. The `.claude/launch.json` entries only start the
`interactive/` Vite server. Verification happens on the live/staging site by
the human. (`npm run dev` in `interactive/` is the one exception.)

**Git.** Always `git push` immediately after `git commit`, in both repos.
Never leave commits unpushed. Never chain diagnostic `git stash`/`reset` into a
"just checking" one-liner.

---

## 9. Known state, traps and gotchas

Things that will waste time if you don't know them.

1. **`<body>` is bare on every branded page.** `header-branded.php:572` emits
   `<body>` with **no `body_class()`** (only `404.php` uses it). Consequences:
   - Every `body.page-template-page-portfolio …` / `body.single-story …` /
     `body.post-type-archive-story …` selector in `header-branded.php` is
     **dead** — including the big `!important` Astra-hiding block and the
     `* { margin:0 …!important }` reset. The **unscoped** `* { margin:0;
     padding:0; box-sizing:border-box }` above it is the one that actually
     applies.
   - The `document.body.classList.contains('single-story')` check in
     `footer-branded.php` never fires.
   - To scope CSS to single stories, use **`body:has(.story-single-container)`**
     — that's the established workaround, already used throughout
     `story-templates.css`.
2. **`functions.php:36` enqueues a file that doesn't exist** —
   `get_stylesheet_directory_uri() . '/story-templates.css'`, but the file is at
   `story-templates/story-templates.css`. It's a 404 on every story page.
   Nothing breaks visually because each story template hand-echoes the correct
   path in `wp_head`.
3. **Eight registered shortcodes have no template file** — see the warning in §6.
4. **`--primary-font` has two different definitions** — `style.css` (no fallback
   stack) and `header-branded.php` (full stack, and it wins on branded pages).
5. **Duplicate `<meta name="theme-color">`** — `functions.php:257` via `wp_head`
   *and* `header-branded.php:6` hardcoded.
6. **`font-weight: 500` on Innovator Grotesk renders regular** —
   `header-branded.php` maps 500 to the Regular `.woff2`.
7. **The homepage LCP is a preloaded AVIF, not the video.** `header-branded.php`
   preloads the hero AVIF with `fetchpriority="high"` only on
   `page-portfolio.php`. The homepage-draft's `<video>` is deliberately deferred
   behind that fallback image — undoing that re-tanks LCP.
8. **Special reports force light mode** — see §2.5.
9. Astra is the parent theme and must be installed, but every branded template
   dequeues `astra-theme-css`; `page-portfolio.php` also drops `astra-theme-js`,
   `wp-block-library` and the emoji script.
10. `.htaccess` in the theme denies `.md`, `.json` and `.DS_Store`. **This file
    (`PROJECT.md`) and `CLAUDE.md` are therefore not publicly readable** — keep
    that `<FilesMatch>` block intact.
11. `templates/reporting.php` still carries "Cronkite" in comments; the category
    slug was renamed to `reporting`. Don't reintroduce `cronkite`.
12. `.copyright` in `footer-branded.php` has a duplicated `font-family` and two
    conflicting `color` declarations on one line. Cosmetic; `--text-color-muted`
    wins.
13. The `2026-homepage-draft/` and `2026-photography-draft/` pages are private,
    unlinked, and not in the nav. Don't wire them into navigation without being
    asked.
