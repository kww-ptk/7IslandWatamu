# Amoja Resort Home Page — Faithful Rebuild

> Design spec · 2026-05-16

## Context

We extracted the design language of the **Amoja – Resort & Hotel WordPress
Theme** (live demo: `https://demo2.wpopal.com/amoja/`) using the `designlang`
tool. The goal is now to faithfully recreate that theme's **Home 1** page as a
standalone, hand-coded site — no WordPress, no Elementor, no build step.

This is a visual/structural reproduction exercise: match the original layout,
typography, color, and section flow as closely as practical with plain web
tech. Output of the design extraction lives in `design-extract-output-amoja/`;
a full copy of the original page HTML is saved at `reference/amoja-source.html`
for pulling exact copy, image URLs, and counter values during implementation.

## Goals

- A pixel-faithful (within reason) rebuild of the Amoja Home 1 page.
- Plain HTML + CSS + light vanilla JS — opens directly in a browser.
- Responsive across desktop / tablet / mobile.

## Non-Goals

- No WordPress, framework, bundler, or npm dependency.
- No other inner pages (Rooms, Dining, News, etc.) — home page only.
- No backend: the newsletter form and "Book Your Stay" are non-functional UI.

## Stack & Decisions

| Decision | Choice |
|----------|--------|
| Tech | Plain `index.html` + `styles.css` + `script.js` |
| Images | Hotlinked from `demo2.wpopal.com/amoja/wp-content/uploads/...` |
| Body/UI font | **Jost** (Google Fonts, full weight range) |
| Display/heading font | **Jost weight 300** — substitute for the paid "Ortica Linear" |
| Icons | Inline SVG (amenity icons, arrows, social) — `amoja-icon` font not available |

## Design Tokens

Source: `design-extract-output-amoja/demo2-wpopal-com-variables.css`. Define in
`:root`:

```
--primary: #e49951;        --primary-hover: #cd8948;
--accent:  #153d4b;        (deep teal-navy — dominant text/headers)
--bg:      #ffffff;        --bg-light: #f2f2ec;
--border:  #e0dfd8;
--text:    #153d4b;        --text-light: #838a8d;
--white:   #ffffff;        --dark: #000000;
--container: 1320px;       --gutter: 30px;
spacing base unit: 5px
radius: button/pill 30px · card 10px · large 15px · circular 50%
shadow-card: 0 4px 30px rgba(0,0,0,0.1)
```

Body text: 18px / 26px line-height. Headings: Jost weight 300, tight tracking
(-1px to -2px), large editorial scale (up to ~110px on the hero).

## Page Structure (top → bottom)

All sections wrap content in a `1320px` max-width container with `30px` gutters.

1. **Header / Nav** — logo (`logo.svg` / `logo-white.svg`), horizontal menu
   (Demos, Rooms & Suites, Dining, Wellness, Experiences, Weddings & Events,
   News), and a pill **"Book Your Stay"** button. Sticky on scroll. Collapses
   to a hamburger toggle ≤1024px.
2. **Hero** — full-viewport background image, headline *"A Sanctuary For Your
   Senses"*, intro line, CTA pill, scroll cue.
3. **Intro + Amenities** — *"Enjoy summer in the lap of luxury"* with body copy
   and a row of amenity items (icon + label).
4. **Rooms** — *"Discover our rooms"*; room cards using `room_1.jpg`–`room_7.jpg`
   in a horizontal **slider** (prev/next controls). Each card: image, room
   name, short meta, price, link.
5. **Spa / Timeless Space** — *"Rebalance yourself in a timeless space"* with
   the **Refresh / Relax / Renew** triad and supporting imagery.
6. **Cuisine / Dining** — *"Taste the best of traditional cuisine"*; image
   gallery of the restaurant/menu shots (`menu-full-img1`–`6`, `menu_h*`).
7. **Stats Counter** — animated counters (e.g. value `524`, `74k`, …; exact
   `data-to-value` / suffix / labels pulled from `reference/amoja-source.html`)
   on a dark `--accent` band.
8. **Testimonials** — *"Hear what our past guests have to say"*; quote
   **slider** with guest avatars (`avatar1.png`, `avatar2.png`) and a
   TripAdvisor logo (`tripadvisor-logo.svg`).
9. **Recent Articles** — *"Most recent articles"*; 4 blog cards
   (`blog_12`–`blog_15.jpg`) with the real post titles:
   - "These are the top 7 luxury hotels in the world"
   - "Four Seasons, Milan: luxury in Italy's most stylish city"
   - "Get the best night's sleep of your life at these hotels"
   - "These are the 7 best eco-hotels in 2023"
10. **Footer** — *"Award-winning resort in the paradise island"*, newsletter
    signup form, Contact Info / Reservations / Location columns, social links.

## Interactivity (`script.js`, vanilla JS)

- **Rooms slider** — track translate on prev/next; loops; touch-drag optional.
- **Testimonials slider** — same mechanism; dot pagination.
- **Mobile menu** — hamburger toggles an off-canvas / dropdown nav ≤1024px.
- **Stat counters** — `IntersectionObserver` triggers a count-up animation from
  `data-from-value` to `data-to-value` when the section scrolls into view.
- **Sticky header** — add a shrunk/solid class after scrolling past the hero.

## Responsive Behavior

| Breakpoint | Behavior |
|------------|----------|
| ≥1025px | Full desktop layout, multi-column grids, inline nav |
| 768–1024px | Reduced columns (3→2), hamburger nav, smaller heading scale |
| ≤767px | Single column, stacked sections, hero text scaled down |

## File Layout

```
index.html      all section markup
styles.css      :root tokens + base + section/component styles
script.js       sliders, mobile menu, counters, sticky header
reference/amoja-source.html   original page (content/asset reference only)
```

## Verification

1. Open `index.html` directly in a browser — page renders with hotlinked
   images, Jost font loads from Google Fonts.
2. Compare side-by-side with `https://demo2.wpopal.com/amoja/` and with
   `design-extract-output-amoja/screenshots/full-page.png` — section order,
   color, type, and spacing should match.
3. Resize the window through the three breakpoints — no overflow, nav collapses
   correctly, grids reflow.
4. Scroll to the stats section — counters animate once. Operate both sliders
   and the mobile menu toggle.
5. Check the browser console — no JS errors.
