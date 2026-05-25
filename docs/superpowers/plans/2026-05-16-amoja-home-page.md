# Amoja Resort Home Page Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Faithfully rebuild the Amoja Resort "Home 1" page as a standalone hand-coded site (plain HTML/CSS/JS, no build step).

**Architecture:** A single `index.html` holding all ten sections, one `styles.css` carrying `:root` design tokens plus base + section styles, and one `script.js` with vanilla-JS sliders, a mobile menu, sticky header, and animated stat counters. Images are hotlinked from `demo2.wpopal.com/amoja`. There is no test framework — this is a static site, so each task is verified by opening the page in a browser and comparing against the live demo and `design-extract-output-amoja/screenshots/full-page.png`.

**Tech Stack:** HTML5, CSS3 (custom properties, flexbox, grid), vanilla JavaScript (ES6, `IntersectionObserver`), Jost from Google Fonts.

---

## Reference Material

- Original page HTML (for exact copy, image URLs, counter values): `reference/amoja-source.html`
- Extracted design tokens: `design-extract-output-amoja/demo2-wpopal-com-variables.css`
- Visual target: `design-extract-output-amoja/screenshots/full-page.png`
- Live demo for side-by-side comparison: `https://demo2.wpopal.com/amoja/`

**Confirmed content (from `reference/amoja-source.html`):**
- Nav: Demos · Rooms & Suites · Dining · Wellness · Experiences · Weddings & Events · News · "Book Your Stay"
- Section eyebrows/headings: `01 the resort` → "Enjoy summer in the lap of luxury"; `02 rooms & suites` → "Discover our rooms"; `03 wellness & spa` → "Rebalance yourself in a timeless space"; `04 Fine Dining` → "Taste the best of traditional cuisine"; `05 Customers Reviews` → "Hear what our past guests have to say"; `06 news & events` → "Most recent articles"
- Hero: "A Sanctuary For Your Senses" / "Relax under swaying palms and walk along the pristine white-sand beach."
- Counters: `524` "luxury rooms", `74k` "guests", "five star ratings", "served breakfast" (pull every `data-to-value` / `data-from-value` / suffix from the reference HTML during Task 8)
- Testimonials: "1,859 reviews", avatars `avatar1.png` / `avatar2.png`, `tripadvisor-logo.svg`
- Blog titles: "These are the top 7 luxury hotels in the world" · "Four Seasons, Milan: luxury in Italy's most stylish city" · "Get the best night's sleep of your life at these hotels" · "These are the 7 best eco-hotels in 2023" (images `blog_12`–`blog_15.jpg`)
- Footer: "Award-winning resort in the paradise island"; Contact Info "54 Longbranch Ave. Brandon, FL 33510"; Reservations "1-800-123-4567"; "Subscribe to the Newsletter"; social: Facebook, Instagram, Tripadvisor, Tiktok

**Image base URL:** `https://demo2.wpopal.com/amoja/wp-content/uploads/2024/11/`
Key files: `logo.svg`, `logo-white.svg`, `room_1.jpg`–`room_5.jpg` + `room_7.jpg`, `blog_12.jpg`–`blog_15.jpg`, `avatar1.png`, `avatar2.png`, `tripadvisor-logo.svg`, `h1_img1.jpg`, `h1_imgbox1.jpg`–`h1_imgbox6.jpg`, `menu-full-img1.jpg`–`menu-full-img6.jpg`, `menu_h1.jpg`–`menu_h4.jpg`, `special_offers1.jpg`, `special_offers2.jpg`.

---

## File Structure

```
index.html      All ten sections in document order
styles.css      :root tokens, reset/base, then one block of styles per section
script.js       Sticky header, mobile menu, rooms slider, testimonials slider, stat counters
```

`reference/` and `design-extract-output-amoja/` already exist and are read-only references — do not modify them.

---

## Task 1: Project skeleton, git, and design tokens

**Files:**
- Create: `index.html`, `styles.css`, `script.js`
- Create: `.gitignore`

- [ ] **Step 1: Initialize git**

Run from the project root:
```bash
git init
```

- [ ] **Step 2: Create `.gitignore`**

```
.DS_Store
```

- [ ] **Step 3: Create `index.html` skeleton**

```html
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Amoja — Resort &amp; Hotel</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <!-- sections added in later tasks -->
  <script src="script.js" defer></script>
</body>
</html>
```

- [ ] **Step 4: Create `styles.css` with tokens, reset, and base typography**

```css
:root {
  --primary: #e49951;
  --primary-hover: #cd8948;
  --accent: #153d4b;
  --bg: #ffffff;
  --bg-light: #f2f2ec;
  --border: #e0dfd8;
  --text: #153d4b;
  --text-light: #838a8d;
  --white: #ffffff;
  --dark: #000000;
  --container: 1320px;
  --gutter: 30px;
  --radius-pill: 30px;
  --radius-card: 10px;
  --shadow-card: 0 4px 30px rgba(0, 0, 0, 0.1);
  --ease: cubic-bezier(0.65, 0.05, 0.36, 1);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

html { scroll-behavior: smooth; }

body {
  font-family: "Jost", "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 18px;
  line-height: 26px;
  font-weight: 400;
  color: var(--text);
  background: var(--bg);
}

img { max-width: 100%; display: block; }
a { color: inherit; text-decoration: none; }
ul { list-style: none; }

h1, h2, h3, h4, h5, h6 {
  font-weight: 300;
  letter-spacing: -1px;
  line-height: 1.1;
  color: var(--accent);
}

.container {
  max-width: var(--container);
  margin: 0 auto;
  padding: 0 var(--gutter);
}

/* eyebrow used above every section heading: "01 the resort" etc. */
.eyebrow {
  display: flex;
  align-items: center;
  gap: 12px;
  font-size: 12px;
  font-weight: 600;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--text-light);
  margin-bottom: 20px;
}
.eyebrow::before {
  content: "";
  width: 40px;
  height: 1px;
  background: var(--primary);
}

.btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 15px 35px;
  border-radius: var(--radius-pill);
  font-size: 12px;
  font-weight: 600;
  letter-spacing: 2px;
  text-transform: uppercase;
  cursor: pointer;
  transition: background 0.3s var(--ease), color 0.3s var(--ease), border-color 0.3s var(--ease);
}
.btn--primary { background: var(--primary); color: var(--white); border: 1px solid var(--primary); }
.btn--primary:hover { background: var(--primary-hover); border-color: var(--primary-hover); }
.btn--outline { background: transparent; color: var(--accent); border: 1px solid var(--border); }
.btn--outline:hover { background: var(--accent); color: var(--white); border-color: var(--accent); }

.section { padding: 120px 0; }
```

- [ ] **Step 5: Create empty `script.js`**

```js
// Interactivity added in later tasks.
document.addEventListener("DOMContentLoaded", () => {});
```

- [ ] **Step 6: Verify**

Open `index.html` in a browser. Expected: a blank white page, no console errors, the Jost font request succeeds in the Network tab.

- [ ] **Step 7: Commit**

```bash
git add .gitignore index.html styles.css script.js
git commit -m "chore: scaffold Amoja home page with design tokens"
```

---

## Task 2: Header / navigation

**Files:**
- Modify: `index.html` (add `<header>` as first child of `<body>`)
- Modify: `styles.css` (append header styles)
- Modify: `script.js` (sticky header + mobile menu toggle)

- [ ] **Step 1: Add header markup** inside `<body>`, before the script tag

```html
<header class="site-header" id="siteHeader">
  <div class="container site-header__inner">
    <a class="site-header__logo" href="#top">Amoja</a>
    <nav class="site-nav" id="siteNav">
      <ul>
        <li><a href="#">Demos</a></li>
        <li><a href="#rooms">Rooms &amp; Suites</a></li>
        <li><a href="#dining">Dining</a></li>
        <li><a href="#wellness">Wellness</a></li>
        <li><a href="#">Experiences</a></li>
        <li><a href="#">Weddings &amp; Events</a></li>
        <li><a href="#news">News</a></li>
      </ul>
    </nav>
    <a class="btn btn--primary site-header__cta" href="#">Book Your Stay</a>
    <button class="site-header__burger" id="navBurger" aria-label="Toggle menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </div>
</header>
```

- [ ] **Step 2: Append header styles to `styles.css`**

```css
.site-header {
  position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
  background: transparent;
  transition: background 0.3s var(--ease), box-shadow 0.3s var(--ease);
}
.site-header.is-stuck { background: var(--bg-light); box-shadow: var(--shadow-card); }
.site-header__inner {
  display: flex; align-items: center; justify-content: space-between;
  height: 90px;
}
.site-header__logo { font-size: 28px; font-weight: 600; letter-spacing: 1px; color: var(--accent); }
.site-nav ul { display: flex; gap: 32px; }
.site-nav a { font-size: 15px; transition: color 0.3s var(--ease); }
.site-nav a:hover { color: var(--primary); }
.site-header__burger { display: none; flex-direction: column; gap: 5px; background: none; border: 0; cursor: pointer; }
.site-header__burger span { width: 26px; height: 2px; background: var(--accent); }

@media (max-width: 1024px) {
  .site-nav {
    position: fixed; inset: 90px 0 auto 0;
    background: var(--bg-light);
    padding: 24px var(--gutter);
    transform: translateY(-120%);
    transition: transform 0.4s var(--ease);
    box-shadow: var(--shadow-card);
  }
  .site-nav.is-open { transform: translateY(0); }
  .site-nav ul { flex-direction: column; gap: 18px; }
  .site-header__cta { display: none; }
  .site-header__burger { display: flex; }
}
```

- [ ] **Step 3: Add sticky header + mobile menu logic to `script.js`** (inside the `DOMContentLoaded` callback)

```js
const header = document.getElementById("siteHeader");
const nav = document.getElementById("siteNav");
const burger = document.getElementById("navBurger");

window.addEventListener("scroll", () => {
  header.classList.toggle("is-stuck", window.scrollY > 80);
});

burger.addEventListener("click", () => {
  const open = nav.classList.toggle("is-open");
  burger.setAttribute("aria-expanded", String(open));
});
```

- [ ] **Step 4: Verify**

Reload `index.html`. Expected: fixed header with logo, 7 nav links, and an amber "Book Your Stay" pill. Scrolling past 80px gives the header a solid `#f2f2ec` background. Narrow the window below 1024px — links collapse into a hamburger that toggles a dropdown. No console errors.

- [ ] **Step 5: Commit**

```bash
git add index.html styles.css script.js
git commit -m "feat: add sticky header and responsive navigation"
```

---

## Task 3: Hero section

**Files:**
- Modify: `index.html` (add `<section class="hero">` after `</header>`)
- Modify: `styles.css`

- [ ] **Step 1: Add hero markup**

```html
<section class="hero" id="top">
  <div class="container hero__inner">
    <p class="hero__eyebrow">Award-winning resort</p>
    <h1 class="hero__title">A Sanctuary For Your Senses</h1>
    <p class="hero__text">Relax under swaying palms and walk along the pristine white-sand beach.</p>
    <a class="btn btn--primary" href="#rooms">Book Your Stay</a>
  </div>
  <a class="hero__scroll" href="#resort" aria-label="Scroll down">&#8595;</a>
</section>
```

- [ ] **Step 2: Append hero styles**

```css
.hero {
  position: relative;
  min-height: 100vh;
  display: flex;
  align-items: center;
  color: var(--white);
  background:
    linear-gradient(rgba(21, 61, 75, 0.35), rgba(21, 61, 75, 0.45)),
    url("https://demo2.wpopal.com/amoja/wp-content/uploads/2024/11/h1_img1.jpg") center/cover no-repeat;
}
.hero__inner { max-width: 720px; }
.hero__eyebrow {
  font-size: 12px; font-weight: 600; letter-spacing: 3px; text-transform: uppercase;
  margin-bottom: 24px;
}
.hero__title { font-size: 96px; color: var(--white); letter-spacing: -2px; margin-bottom: 24px; }
.hero__text { font-size: 20px; margin-bottom: 36px; }
.hero__scroll {
  position: absolute; left: 50%; bottom: 36px; transform: translateX(-50%);
  width: 48px; height: 48px; border: 1px solid rgba(255, 255, 255, 0.4);
  border-radius: 50%; display: grid; place-items: center; font-size: 20px;
}
@media (max-width: 1024px) { .hero__title { font-size: 64px; } }
@media (max-width: 767px)  { .hero__title { font-size: 44px; } .hero__text { font-size: 17px; } }
```

- [ ] **Step 3: Verify**

Reload. Expected: a full-viewport hero with the resort photo, dark teal overlay, white headline "A Sanctuary For Your Senses", subtext, amber CTA, and a circular scroll cue at the bottom. Headline scales down at 1024px and 767px.

- [ ] **Step 4: Commit**

```bash
git add index.html styles.css
git commit -m "feat: add hero section"
```

---

## Task 4: Intro + amenities section

**Files:**
- Modify: `index.html` (add `<section id="resort">` after the hero)
- Modify: `styles.css`

- [ ] **Step 1: Add intro markup**

```html
<section class="section resort" id="resort">
  <div class="container resort__grid">
    <div class="resort__media">
      <img src="https://demo2.wpopal.com/amoja/wp-content/uploads/2024/11/h1_imgbox1.jpg" alt="Resort interior">
    </div>
    <div class="resort__body">
      <p class="eyebrow">01 &mdash; the resort</p>
      <h2 class="resort__title">Enjoy summer in the lap of luxury</h2>
      <p class="resort__text">Welcome to our oasis of luxury and tranquility, where every aspect of your experience is meticulously crafted to exceed your expectations. Our resort is a perfect combination of distinctly designed rooms in a setting of rare natural beauty which only Samui has to offer.</p>
      <ul class="amenities">
        <li><span class="amenities__icon">&#9733;</span> Private Beach</li>
        <li><span class="amenities__icon">&#9733;</span> Infinity Pool</li>
        <li><span class="amenities__icon">&#9733;</span> Spa &amp; Wellness</li>
        <li><span class="amenities__icon">&#9733;</span> Fine Dining</li>
      </ul>
    </div>
  </div>
</section>
```

- [ ] **Step 2: Append intro styles**

```css
.resort__grid { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; }
.resort__media img { border-radius: var(--radius-card); }
.resort__title { font-size: 56px; margin-bottom: 24px; }
.resort__text { color: var(--text-light); margin-bottom: 32px; }
.amenities { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.amenities li { display: flex; align-items: center; gap: 10px; font-size: 16px; }
.amenities__icon { color: var(--primary); }
@media (max-width: 767px) {
  .resort__grid { grid-template-columns: 1fr; gap: 32px; }
  .resort__title { font-size: 38px; }
}
```

- [ ] **Step 3: Verify**

Reload. Expected: a two-column section — image left, eyebrow "01 — the resort", heading "Enjoy summer in the lap of luxury", paragraph, and a 2×2 amenities list with amber star icons. Collapses to one column below 767px.

- [ ] **Step 4: Commit**

```bash
git add index.html styles.css
git commit -m "feat: add resort intro and amenities section"
```

---

## Task 5: Rooms slider section

**Files:**
- Modify: `index.html` (add `<section id="rooms">`)
- Modify: `styles.css`
- Modify: `script.js` (rooms slider logic)

- [ ] **Step 1: Add rooms markup** — 6 cards using `room_1.jpg`–`room_5.jpg` and `room_7.jpg`. Room names: pull the real titles from `reference/amoja-source.html` if present near each `room_N.jpg`; otherwise use these luxury room names in order: "Deluxe Garden Room", "Ocean View Suite", "Beachfront Villa", "Pool Access Room", "Family Suite", "Presidential Villa".

```html
<section class="section rooms" id="rooms">
  <div class="container">
    <div class="rooms__head">
      <div>
        <p class="eyebrow">02 &mdash; rooms &amp; suites</p>
        <h2 class="rooms__title">Discover our rooms</h2>
      </div>
      <div class="slider__nav">
        <button class="slider__btn" data-rooms-prev aria-label="Previous">&#8592;</button>
        <button class="slider__btn" data-rooms-next aria-label="Next">&#8594;</button>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="slider" data-rooms-viewport>
      <div class="slider__track" data-rooms-track>
        <!-- repeat this card 6 times, swapping image + name -->
        <article class="room-card">
          <img src="https://demo2.wpopal.com/amoja/wp-content/uploads/2024/11/room_1.jpg" alt="Deluxe Garden Room">
          <div class="room-card__body">
            <h3 class="room-card__name">Deluxe Garden Room</h3>
            <p class="room-card__meta">2 Guests &middot; 1 King Bed &middot; 45m&sup2;</p>
            <a class="room-card__link" href="#">View Details &#8594;</a>
          </div>
        </article>
        <!-- room_2.jpg "Ocean View Suite", room_3.jpg "Beachfront Villa",
             room_4.jpg "Pool Access Room", room_5.jpg "Family Suite",
             room_7.jpg "Presidential Villa" -->
      </div>
    </div>
  </div>
</section>
```

- [ ] **Step 2: Append rooms + shared slider styles**

```css
.rooms__head { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 50px; }
.rooms__title { font-size: 56px; }
.slider__nav { display: flex; gap: 12px; }
.slider__btn {
  width: 50px; height: 50px; border-radius: 50%;
  border: 1px solid var(--border); background: transparent; cursor: pointer;
  font-size: 18px; color: var(--accent);
  transition: background 0.3s var(--ease), color 0.3s var(--ease);
}
.slider__btn:hover { background: var(--primary); color: var(--white); border-color: var(--primary); }

.slider { overflow: hidden; }
.slider__track { display: flex; gap: 30px; transition: transform 0.5s var(--ease); }

.room-card { flex: 0 0 calc((100% - 60px) / 3); }
.room-card img { border-radius: var(--radius-card); aspect-ratio: 3 / 4; object-fit: cover; }
.room-card__body { padding-top: 20px; }
.room-card__name { font-size: 26px; margin-bottom: 8px; }
.room-card__meta { color: var(--text-light); font-size: 15px; margin-bottom: 12px; }
.room-card__link { font-size: 13px; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; color: var(--primary); }

@media (max-width: 1024px) { .room-card { flex-basis: calc((100% - 30px) / 2); } }
@media (max-width: 767px)  { .room-card { flex-basis: 100%; } .rooms__title { font-size: 38px; } }
```

- [ ] **Step 3: Add rooms slider logic to `script.js`**

```js
function initSlider(viewport, track, prevBtn, nextBtn) {
  if (!track) return;
  let index = 0;
  const step = () => {
    const card = track.children[0];
    if (!card) return 0;
    const gap = parseInt(getComputedStyle(track).gap) || 0;
    return card.getBoundingClientRect().width + gap;
  };
  const maxIndex = () => {
    const visible = Math.round(viewport.clientWidth / step());
    return Math.max(0, track.children.length - visible);
  };
  const apply = () => { track.style.transform = `translateX(-${index * step()}px)`; };
  prevBtn.addEventListener("click", () => { index = Math.max(0, index - 1); apply(); });
  nextBtn.addEventListener("click", () => { index = Math.min(maxIndex(), index + 1); apply(); });
  window.addEventListener("resize", () => { index = Math.min(index, maxIndex()); apply(); });
}

initSlider(
  document.querySelector("[data-rooms-viewport]"),
  document.querySelector("[data-rooms-track]"),
  document.querySelector("[data-rooms-prev]"),
  document.querySelector("[data-rooms-next]")
);
```

- [ ] **Step 4: Verify**

Reload. Expected: eyebrow "02 — rooms & suites", heading "Discover our rooms", two round arrow buttons, and a row of 3 visible room cards (3:4 images, name, meta, amber link). Next/prev arrows slide the track; arrows hover to amber. 2 cards at ≤1024px, 1 card at ≤767px. No console errors.

- [ ] **Step 5: Commit**

```bash
git add index.html styles.css script.js
git commit -m "feat: add rooms slider section"
```

---

## Task 6: Wellness / "timeless space" section

**Files:**
- Modify: `index.html` (add `<section id="wellness">`)
- Modify: `styles.css`

- [ ] **Step 1: Add wellness markup**

```html
<section class="section wellness" id="wellness">
  <div class="container wellness__grid">
    <div class="wellness__body">
      <p class="eyebrow">03 &mdash; wellness &amp; spa</p>
      <h2 class="wellness__title">Rebalance yourself in a timeless space</h2>
      <ul class="wellness__triad">
        <li><h3>Refresh</h3><p>Begin each day renewed by the sea breeze and morning light.</p></li>
        <li><h3>Relax</h3><p>Unwind with signature treatments in our beachfront spa.</p></li>
        <li><h3>Renew</h3><p>Leave restored in body and mind, carrying calm home with you.</p></li>
      </ul>
      <a class="btn btn--outline" href="#">Discover More</a>
    </div>
    <div class="wellness__media">
      <img src="https://demo2.wpopal.com/amoja/wp-content/uploads/2024/11/h1_imgbox2.jpg" alt="Spa">
    </div>
  </div>
</section>
```

- [ ] **Step 2: Append wellness styles**

```css
.wellness { background: var(--bg-light); }
.wellness__grid { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; }
.wellness__title { font-size: 56px; margin-bottom: 32px; }
.wellness__triad { display: grid; gap: 24px; margin-bottom: 32px; }
.wellness__triad h3 { font-size: 24px; margin-bottom: 6px; }
.wellness__triad p { color: var(--text-light); font-size: 16px; }
.wellness__media img { border-radius: var(--radius-card); aspect-ratio: 3 / 4; object-fit: cover; }
@media (max-width: 767px) {
  .wellness__grid { grid-template-columns: 1fr; gap: 32px; }
  .wellness__title { font-size: 38px; }
}
```

- [ ] **Step 3: Verify**

Reload. Expected: a section on the warm `#f2f2ec` background — eyebrow "03 — wellness & spa", heading "Rebalance yourself in a timeless space", a Refresh/Relax/Renew triad, an outline button, and a spa image on the right. One column below 767px.

- [ ] **Step 4: Commit**

```bash
git add index.html styles.css
git commit -m "feat: add wellness section"
```

---

## Task 7: Dining / cuisine gallery section

**Files:**
- Modify: `index.html` (add `<section id="dining">`)
- Modify: `styles.css`

- [ ] **Step 1: Add dining markup**

```html
<section class="section dining" id="dining">
  <div class="container">
    <div class="dining__head">
      <p class="eyebrow">04 &mdash; fine dining</p>
      <h2 class="dining__title">Taste the best of traditional cuisine</h2>
      <p class="dining__text">Let yourself be delighted with the range of gourmet choices available at the Amoja Resort throughout the day.</p>
    </div>
    <div class="dining__gallery">
      <img src="https://demo2.wpopal.com/amoja/wp-content/uploads/2024/11/menu-full-img1.jpg" alt="Restaurant">
      <img src="https://demo2.wpopal.com/amoja/wp-content/uploads/2024/11/menu-full-img2.jpg" alt="Dish">
      <img src="https://demo2.wpopal.com/amoja/wp-content/uploads/2024/11/menu-full-img3.jpg" alt="Bar">
      <img src="https://demo2.wpopal.com/amoja/wp-content/uploads/2024/11/menu-full-img4.jpg" alt="Lounge">
    </div>
  </div>
</section>
```

- [ ] **Step 2: Append dining styles**

```css
.dining__head { max-width: 680px; margin: 0 auto 50px; text-align: center; }
.dining__head .eyebrow { justify-content: center; }
.dining__title { font-size: 56px; margin-bottom: 16px; }
.dining__text { color: var(--text-light); }
.dining__gallery { display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px; }
.dining__gallery img {
  border-radius: var(--radius-card); aspect-ratio: 3 / 4; object-fit: cover;
  transition: transform 0.4s var(--ease);
}
.dining__gallery img:hover { transform: translateY(-8px); }
@media (max-width: 1024px) { .dining__gallery { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 767px)  { .dining__gallery { grid-template-columns: 1fr; } .dining__title { font-size: 38px; } }
```

- [ ] **Step 3: Verify**

Reload. Expected: a centered heading block ("04 — fine dining" / "Taste the best of traditional cuisine") above a 4-up image gallery; images lift on hover. 2 columns at ≤1024px, 1 at ≤767px.

- [ ] **Step 4: Commit**

```bash
git add index.html styles.css
git commit -m "feat: add dining gallery section"
```

---

## Task 8: Stats counter section

**Files:**
- Modify: `index.html` (add `<section class="stats">`)
- Modify: `styles.css`
- Modify: `script.js` (count-up on scroll)

- [ ] **Step 1: Confirm counter values.** Open `reference/amoja-source.html` and find every `elementor-counter-number` span plus the heading directly after it. Known: `524` "luxury rooms", `74` + suffix `k` "guests". Find the remaining two ("five star ratings", "served breakfast") and their `data-to-value` / suffix. If a value cannot be found, use `data-to-value`s `4900`+suffix `` and `18` + suffix `k` respectively as reasonable stand-ins.

- [ ] **Step 2: Add stats markup** (use the four confirmed value/suffix/label triples)

```html
<section class="stats">
  <div class="container stats__grid">
    <div class="stat"><span class="stat__num" data-to="524">0</span><span class="stat__suffix"></span><p class="stat__label">luxury rooms</p></div>
    <div class="stat"><span class="stat__num" data-to="74">0</span><span class="stat__suffix">k</span><p class="stat__label">guests</p></div>
    <div class="stat"><span class="stat__num" data-to="4900">0</span><span class="stat__suffix"></span><p class="stat__label">five star ratings</p></div>
    <div class="stat"><span class="stat__num" data-to="18">0</span><span class="stat__suffix">k</span><p class="stat__label">served breakfast</p></div>
  </div>
</section>
```

- [ ] **Step 3: Append stats styles**

```css
.stats { background: var(--accent); color: var(--white); padding: 90px 0; }
.stats__grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px; text-align: center; }
.stat { display: flex; flex-direction: column; align-items: center; }
.stat__num, .stat__suffix { font-size: 64px; font-weight: 300; line-height: 1; }
.stat__suffix { color: var(--primary); }
.stat:has(.stat__suffix) { flex-direction: row; flex-wrap: wrap; justify-content: center; }
.stat__label { width: 100%; margin-top: 12px; color: rgba(255,255,255,0.7); font-size: 16px; }
@media (max-width: 767px) { .stats__grid { grid-template-columns: 1fr 1fr; gap: 40px 30px; } }
```

- [ ] **Step 4: Add count-up logic to `script.js`**

```js
const statNums = document.querySelectorAll(".stat__num");
const countObserver = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (!entry.isIntersecting) return;
    const el = entry.target;
    const target = parseInt(el.dataset.to, 10);
    const duration = 2000;
    const start = performance.now();
    const tick = (now) => {
      const p = Math.min((now - start) / duration, 1);
      el.textContent = Math.floor(p * target).toLocaleString();
      if (p < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
    countObserver.unobserve(el);
  });
}, { threshold: 0.5 });
statNums.forEach((el) => countObserver.observe(el));
```

- [ ] **Step 5: Verify**

Reload and scroll to the stats band. Expected: a dark teal band with 4 stats; numbers count up from 0 to their targets once when scrolled into view; `k` suffixes render in amber. 2×2 grid below 767px.

- [ ] **Step 6: Commit**

```bash
git add index.html styles.css script.js
git commit -m "feat: add animated stats counter section"
```

---

## Task 9: Testimonials slider section

**Files:**
- Modify: `index.html` (add `<section class="testimonials">`)
- Modify: `styles.css`
- Modify: `script.js` (reuse `initSlider`)

- [ ] **Step 1: Add testimonials markup** — 3 slides, alternating avatars `avatar1.png` / `avatar2.png`

```html
<section class="section testimonials">
  <div class="container">
    <div class="testimonials__head">
      <div>
        <p class="eyebrow">05 &mdash; customers reviews</p>
        <h2 class="testimonials__title">Hear what our past guests have to say</h2>
        <p class="testimonials__meta">
          <img src="https://demo2.wpopal.com/amoja/wp-content/uploads/2024/11/tripadvisor-logo.svg" alt="Tripadvisor" height="22">
          1,859 reviews
        </p>
      </div>
      <div class="slider__nav">
        <button class="slider__btn" data-tm-prev aria-label="Previous">&#8592;</button>
        <button class="slider__btn" data-tm-next aria-label="Next">&#8594;</button>
      </div>
    </div>
    <div class="slider" data-tm-viewport>
      <div class="slider__track" data-tm-track>
        <article class="quote-card">
          <p class="quote-card__text">"An unforgettable escape. Every detail, from the beachfront villa to the spa, was flawless. We are already planning our return."</p>
          <div class="quote-card__author">
            <img src="https://demo2.wpopal.com/amoja/wp-content/uploads/2024/11/avatar1.png" alt="">
            <span><strong>Emma Richardson</strong><br>United Kingdom</span>
          </div>
        </article>
        <article class="quote-card">
          <p class="quote-card__text">"The most relaxing holiday we have ever had. The staff treated us like family and the food was exceptional throughout."</p>
          <div class="quote-card__author">
            <img src="https://demo2.wpopal.com/amoja/wp-content/uploads/2024/11/avatar2.png" alt="">
            <span><strong>James Carter</strong><br>Australia</span>
          </div>
        </article>
        <article class="quote-card">
          <p class="quote-card__text">"A true sanctuary for the senses. Waking up to the ocean every morning was pure magic. Highly recommended."</p>
          <div class="quote-card__author">
            <img src="https://demo2.wpopal.com/amoja/wp-content/uploads/2024/11/avatar1.png" alt="">
            <span><strong>Sofia Almeida</strong><br>Portugal</span>
          </div>
        </article>
      </div>
    </div>
  </div>
</section>
```

- [ ] **Step 2: Append testimonials styles**

```css
.testimonials { background: var(--bg-light); }
.testimonials__head { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 50px; }
.testimonials__title { font-size: 56px; margin-bottom: 16px; }
.testimonials__meta { display: flex; align-items: center; gap: 10px; color: var(--text-light); font-size: 15px; }
.quote-card {
  flex: 0 0 calc((100% - 60px) / 3);
  background: var(--white); border-radius: var(--radius-card);
  padding: 40px; box-shadow: var(--shadow-card);
}
.quote-card__text { font-size: 18px; margin-bottom: 24px; }
.quote-card__author { display: flex; align-items: center; gap: 14px; }
.quote-card__author img { width: 54px; height: 54px; border-radius: 50%; object-fit: cover; }
@media (max-width: 1024px) { .quote-card { flex-basis: calc((100% - 30px) / 2); } }
@media (max-width: 767px)  { .quote-card { flex-basis: 100%; } .testimonials__title { font-size: 38px; } }
```

- [ ] **Step 3: Wire the testimonials slider in `script.js`** (reuse `initSlider` from Task 5)

```js
initSlider(
  document.querySelector("[data-tm-viewport]"),
  document.querySelector("[data-tm-track]"),
  document.querySelector("[data-tm-prev]"),
  document.querySelector("[data-tm-next]")
);
```

- [ ] **Step 4: Verify**

Reload. Expected: a section on `#f2f2ec` with eyebrow "05 — customers reviews", heading, a Tripadvisor logo + "1,859 reviews", round arrows, and 3 white quote cards with soft shadow + circular avatars. Arrows slide the track. Responsive like the rooms slider.

- [ ] **Step 5: Commit**

```bash
git add index.html styles.css script.js
git commit -m "feat: add testimonials slider section"
```

---

## Task 10: Recent articles section

**Files:**
- Modify: `index.html` (add `<section id="news">`)
- Modify: `styles.css`

- [ ] **Step 1: Add news markup** — 4 blog cards

```html
<section class="section news" id="news">
  <div class="container">
    <div class="news__head">
      <p class="eyebrow">06 &mdash; news &amp; events</p>
      <h2 class="news__title">Most recent articles</h2>
    </div>
    <div class="news__grid">
      <article class="post-card">
        <img src="https://demo2.wpopal.com/amoja/wp-content/uploads/2024/11/blog_12.jpg" alt="">
        <p class="post-card__date">November 12, 2024</p>
        <h3 class="post-card__title">These are the top 7 luxury hotels in the world</h3>
      </article>
      <article class="post-card">
        <img src="https://demo2.wpopal.com/amoja/wp-content/uploads/2024/11/blog_13.jpg" alt="">
        <p class="post-card__date">November 10, 2024</p>
        <h3 class="post-card__title">Four Seasons, Milan: luxury in Italy's most stylish city</h3>
      </article>
      <article class="post-card">
        <img src="https://demo2.wpopal.com/amoja/wp-content/uploads/2024/11/blog_14.jpg" alt="">
        <p class="post-card__date">November 8, 2024</p>
        <h3 class="post-card__title">Get the best night's sleep of your life at these hotels</h3>
      </article>
      <article class="post-card">
        <img src="https://demo2.wpopal.com/amoja/wp-content/uploads/2024/11/blog_15.jpg" alt="">
        <p class="post-card__date">November 5, 2024</p>
        <h3 class="post-card__title">These are the 7 best eco-hotels in 2023</h3>
      </article>
    </div>
  </div>
</section>
```

- [ ] **Step 2: Append news styles**

```css
.news__head { text-align: center; margin-bottom: 50px; }
.news__head .eyebrow { justify-content: center; }
.news__title { font-size: 56px; }
.news__grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px; }
.post-card img { border-radius: var(--radius-card); aspect-ratio: 4 / 3; object-fit: cover; }
.post-card__date { color: var(--text-light); font-size: 13px; letter-spacing: 1px; text-transform: uppercase; margin: 18px 0 8px; }
.post-card__title { font-size: 22px; transition: color 0.3s var(--ease); cursor: pointer; }
.post-card__title:hover { color: var(--primary); }
@media (max-width: 1024px) { .news__grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 767px)  { .news__grid { grid-template-columns: 1fr; } .news__title { font-size: 38px; } }
```

- [ ] **Step 3: Verify**

Reload. Expected: centered "06 — news & events" / "Most recent articles" above 4 blog cards (4:3 image, uppercase date, title that turns amber on hover). 2 columns at ≤1024px, 1 at ≤767px.

- [ ] **Step 4: Commit**

```bash
git add index.html styles.css
git commit -m "feat: add recent articles section"
```

---

## Task 11: Footer

**Files:**
- Modify: `index.html` (add `<footer>` before the script tag)
- Modify: `styles.css`

- [ ] **Step 1: Add footer markup**

```html
<footer class="footer">
  <div class="container footer__top">
    <h2 class="footer__headline">Award-winning resort in the paradise island</h2>
    <a class="btn btn--primary" href="#">Book Your Stay</a>
  </div>
  <div class="container footer__grid">
    <div class="footer__col">
      <h3 class="footer__logo">Amoja</h3>
      <p>A sanctuary for your senses on the white-sand shores of Samui.</p>
    </div>
    <div class="footer__col">
      <h4>Contact Info</h4>
      <p>54 Longbranch Ave.<br>Brandon, FL 33510</p>
    </div>
    <div class="footer__col">
      <h4>Reservations</h4>
      <p>1-800-123-4567</p>
      <ul class="footer__social">
        <li><a href="#">Facebook</a></li>
        <li><a href="#">Instagram</a></li>
        <li><a href="#">Tripadvisor</a></li>
        <li><a href="#">Tiktok</a></li>
      </ul>
    </div>
    <div class="footer__col">
      <h4>Subscribe to the Newsletter</h4>
      <form class="footer__form" onsubmit="return false">
        <input type="email" placeholder="Your email address" aria-label="Email address" required>
        <button class="btn btn--primary" type="submit">Subscribe</button>
      </form>
    </div>
  </div>
  <div class="container footer__bottom">
    <p>&copy; 2026 Amoja Resort. All rights reserved.</p>
  </div>
</footer>
```

- [ ] **Step 2: Append footer styles**

```css
.footer { background: var(--bg-light); padding-top: 100px; }
.footer__top {
  display: flex; justify-content: space-between; align-items: center; gap: 30px;
  padding-bottom: 60px; border-bottom: 1px solid var(--border);
}
.footer__headline { font-size: 56px; max-width: 640px; }
.footer__grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px; padding: 60px 0; }
.footer__col h4 { font-size: 18px; font-weight: 600; margin-bottom: 16px; }
.footer__col p { color: var(--text-light); font-size: 15px; }
.footer__logo { font-size: 26px; font-weight: 600; margin-bottom: 14px; }
.footer__social { display: flex; flex-wrap: wrap; gap: 14px; margin-top: 16px; }
.footer__social a { font-size: 14px; transition: color 0.3s var(--ease); }
.footer__social a:hover { color: var(--primary); }
.footer__form { display: flex; gap: 10px; flex-wrap: wrap; }
.footer__form input {
  flex: 1; min-width: 160px; padding: 14px 16px;
  border: 1px solid var(--border); border-radius: var(--radius-pill);
  background: var(--white); font: inherit; font-size: 15px;
}
.footer__bottom { padding: 30px 0; border-top: 1px solid var(--border); font-size: 14px; color: var(--text-light); }
@media (max-width: 1024px) { .footer__grid { grid-template-columns: 1fr 1fr; } }
@media (max-width: 767px) {
  .footer__top { flex-direction: column; align-items: flex-start; }
  .footer__headline { font-size: 36px; }
  .footer__grid { grid-template-columns: 1fr; }
}
```

- [ ] **Step 3: Verify**

Reload. Expected: a footer on `#f2f2ec` — a headline row with "Award-winning resort in the paradise island" + CTA, a 4-column block (about, Contact Info, Reservations + social, Newsletter form with pill input), and a copyright bar. Newsletter form does not navigate on submit. Collapses to 2 then 1 column.

- [ ] **Step 4: Commit**

```bash
git add index.html styles.css
git commit -m "feat: add footer"
```

---

## Task 12: Full-page polish and final verification

**Files:**
- Modify: `index.html`, `styles.css` as needed

- [ ] **Step 1: Side-by-side review.** Open `index.html` and `https://demo2.wpopal.com/amoja/` side by side, and compare against `design-extract-output-amoja/screenshots/full-page.png`. Walk every section top to bottom: section order, heading text, colors, fonts, spacing. Note any mismatch.

- [ ] **Step 2: Fix issues found.** Adjust spacing, font sizes, or colors so the page matches the target. Common touch-ups: vertical rhythm between sections (`.section` padding), heading sizes, image aspect ratios.

- [ ] **Step 3: Responsive sweep.** Resize through 3 widths — ≥1025px, 768–1024px, ≤767px. Confirm: no horizontal scrollbar, header collapses to hamburger and the menu toggles, every grid reflows, hero text scales.

- [ ] **Step 4: Interaction + console check.** Operate both sliders, the mobile menu, and scroll the stats into view (counters animate once). Open DevTools console — expect zero errors. Confirm all hotlinked images load (no broken-image icons in the Network tab).

- [ ] **Step 5: Commit**

```bash
git add index.html styles.css
git commit -m "polish: final spacing and responsive fixes"
```

---

## Self-Review Notes

- **Spec coverage:** All 10 spec sections map to Tasks 2–11; tokens → Task 1; interactivity (sliders, mobile menu, sticky header, counters) → Tasks 2/5/8/9; responsive behavior → per-task media queries + Task 12; verification → Task 12. Covered.
- **Type consistency:** `initSlider(viewport, track, prevBtn, nextBtn)` defined in Task 5 and reused with matching argument order in Task 9. Shared classes `.slider`, `.slider__track`, `.slider__btn`, `.slider__nav`, `.eyebrow`, `.btn` defined once and reused.
- **No test framework:** intentional — static site; verification is browser-based per task.
