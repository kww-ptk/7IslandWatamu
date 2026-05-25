# Design Language: Home 1 - Amoja - Resort & Hotel WP Theme

> Extracted from `https://demo2.wpopal.com/amoja/` on May 16, 2026
> 1865 elements analyzed

This document describes the complete design language of the website. It is structured for AI/LLM consumption — use it to faithfully recreate the visual design in any framework.

## Color Palette

### Primary Colors

| Role | Hex | RGB | HSL | Usage Count |
|------|-----|-----|-----|-------------|
| Primary | `#e49951` | rgb(228, 153, 81) | hsl(29, 73%, 61%) | 87 |
| Secondary | `#153d4b` | rgb(21, 61, 75) | hsl(196, 56%, 19%) | 2466 |
| Accent | `#ff0000` | rgb(255, 0, 0) | hsl(0, 100%, 50%) | 2 |

### Neutral Colors

| Hex | HSL | Usage Count |
|-----|-----|-------------|
| `#ffffff` | hsl(0, 0%, 100%) | 636 |
| `#000000` | hsl(0, 0%, 0%) | 320 |
| `#838a8d` | hsl(198, 4%, 53%) | 200 |
| `#e0dfd8` | hsl(53, 11%, 86%) | 52 |
| `#eeeeee` | hsl(0, 0%, 93%) | 11 |
| `#ccd6df` | hsl(208, 23%, 84%) | 10 |

### Background Colors

Used on large-area elements: `#f2f2ec`, `#ffffff`, `#153d4b`, `#000000`

### Text Colors

Text color palette: `#000000`, `#153d4b`, `#ffffff`, `#e49951`, `#838a8d`, `#ccd6df`, `#eeeeee`, `#e0dfd8`

### Full Color Inventory

| Hex | Contexts | Count |
|-----|----------|-------|
| `#153d4b` | text, border, background | 2466 |
| `#ffffff` | background, border, text | 636 |
| `#000000` | text, border, background | 320 |
| `#838a8d` | text, border | 200 |
| `#e49951` | border, text, background | 87 |
| `#e0dfd8` | border, text | 52 |
| `#eeeeee` | background, text, border | 11 |
| `#ccd6df` | text, border | 10 |
| `#ff0000` | background | 2 |

## Typography

### Font Families

- **Jost** — used for all (1603 elements)
- **Ortica Linear** — used for all (134 elements)
- **amoja-icon** — used for body (42 elements)

### Type Scale

| Size (px) | Size (rem) | Weight | Line Height | Letter Spacing | Used On |
|-----------|------------|--------|-------------|----------------|---------|
| 110px | 6.875rem | 300 | 110px | -2px | h2 |
| 96px | 6rem | 300 | 96px | -2px | div, span |
| 80px | 5rem | 400 | 80px | normal | div, svg, path |
| 74px | 4.625rem | 300 | 77.7px | -1px | h2 |
| 64px | 4rem | 400 | 64px | normal | span, i |
| 60px | 3.75rem | 400 | 60px | normal | span, svg, path |
| 56px | 3.5rem | 400 | 62px | -1px | div, a, h3 |
| 42px | 2.625rem | 300 | 60px | -1px | a, span, h4 |
| 36px | 2.25rem | 300 | 39.8571px | -1px | h3, div, h1, a |
| 32px | 2rem | 300 | 38px | -1px | h6, h5, h3, a |
| 26px | 1.625rem | 400 | 39px | normal | div |
| 25px | 1.5625rem | 400 | 25px | normal | div, i |
| 24px | 1.5rem | 300 | 38.4px | -1px | a, span, h6, i |
| 22px | 1.375rem | 400 | 34.1px | normal | p, div |
| 20px | 1.25rem | 600 | 26px | 2px | span, a, i |

### Heading Scale

```css
h2 { font-size: 110px; font-weight: 300; line-height: 110px; }
h2 { font-size: 74px; font-weight: 300; line-height: 77.7px; }
h3 { font-size: 56px; font-weight: 400; line-height: 62px; }
h4 { font-size: 42px; font-weight: 300; line-height: 60px; }
h3 { font-size: 36px; font-weight: 300; line-height: 39.8571px; }
h6 { font-size: 32px; font-weight: 300; line-height: 38px; }
h6 { font-size: 24px; font-weight: 300; line-height: 38.4px; }
h3 { font-size: 12px; font-weight: 600; line-height: 18px; }
```

### Body Text

```css
body { font-size: 18px; font-weight: 400; line-height: 26px; }
```

### Font Weights in Use

`400` (1401x), `600` (259x), `300` (116x), `500` (83x), `900` (4x), `700` (2x)

## Spacing

**Base unit:** 5px

| Token | Value | Rem |
|-------|-------|-----|
| spacing-1 | 1px | 0.0625rem |
| spacing-24 | 24px | 1.5rem |
| spacing-35 | 35px | 2.1875rem |
| spacing-50 | 50px | 3.125rem |
| spacing-55 | 55px | 3.4375rem |
| spacing-60 | 60px | 3.75rem |
| spacing-65 | 65px | 4.0625rem |
| spacing-70 | 70px | 4.375rem |
| spacing-75 | 75px | 4.6875rem |
| spacing-80 | 80px | 5rem |
| spacing-100 | 100px | 6.25rem |
| spacing-110 | 110px | 6.875rem |
| spacing-120 | 120px | 7.5rem |
| spacing-140 | 140px | 8.75rem |
| spacing-150 | 150px | 9.375rem |
| spacing-192 | 192px | 12rem |
| spacing-210 | 210px | 13.125rem |
| spacing-230 | 230px | 14.375rem |
| spacing-417 | 417px | 26.0625rem |

## Border Radii

| Label | Value | Count |
|-------|-------|-------|
| sm | 4px | 2 |
| md | 10px | 50 |
| lg | 15px | 10 |
| full | 30px | 31 |
| full | 40px | 1 |
| full | 50px | 24 |

## Box Shadows

**sm** — blur: 0px
```css
box-shadow: rgb(242, 242, 236) 0px 0px 0px 15px;
```

**xl** — blur: 30px
```css
box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 30px 0px;
```

## CSS Custom Properties

### Colors

```css
--wp--preset--color--black: #000000;
--wp--preset--color--cyan-bluish-gray: #abb8c3;
--wp--preset--color--white: #ffffff;
--wp--preset--color--pale-pink: #f78da7;
--wp--preset--color--vivid-red: #cf2e2e;
--wp--preset--color--luminous-vivid-orange: #ff6900;
--wp--preset--color--luminous-vivid-amber: #fcb900;
--wp--preset--color--light-green-cyan: #7bdcb5;
--wp--preset--color--vivid-green-cyan: #00d084;
--wp--preset--color--pale-cyan-blue: #8ed1fc;
--wp--preset--color--vivid-cyan-blue: #0693e3;
--wp--preset--color--vivid-purple: #9b51e0;
--primary: #E49951;
--primary_hover: #CD8948;
--accent: #153D4B;
--border: #E0DFD8;
--e-global-typography-accent-font-family: "Ortica Linear", "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
--swiper-theme-color: #007aff;
```

### Spacing

```css
--wp--preset--font-size--small: 13px;
--wp--preset--font-size--medium: 20px;
--wp--preset--font-size--large: 36px;
--wp--preset--font-size--x-large: 42px;
--wp--preset--spacing--20: 0.44rem;
--wp--preset--spacing--30: 0.67rem;
--wp--preset--spacing--40: 1rem;
--wp--preset--spacing--50: 1.5rem;
--wp--preset--spacing--60: 2.25rem;
--wp--preset--spacing--70: 3.38rem;
--wp--preset--spacing--80: 5.06rem;
--swiper-navigation-size: 44px;
```

### Typography

```css
--text: #153D4B;
--text_light: #838A8D;
--e-global-typography-text-font-family: "Jost", "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
```

### Shadows

```css
--wp--preset--shadow--natural: 6px 6px 9px rgba(0, 0, 0, 0.2);
--wp--preset--shadow--deep: 12px 12px 50px rgba(0, 0, 0, 0.4);
--wp--preset--shadow--sharp: 6px 6px 0px rgba(0, 0, 0, 0.2);
--wp--preset--shadow--outlined: 6px 6px 0px -3px rgba(255, 255, 255, 1), 6px 6px rgba(0, 0, 0, 1);
--wp--preset--shadow--crisp: 6px 6px 0px rgba(0, 0, 0, 1);
```

### Other

```css
--wp--preset--aspect-ratio--square: 1;
--wp--preset--aspect-ratio--4-3: 4/3;
--wp--preset--aspect-ratio--3-4: 3/4;
--wp--preset--aspect-ratio--3-2: 3/2;
--wp--preset--aspect-ratio--2-3: 2/3;
--wp--preset--aspect-ratio--16-9: 16/9;
--wp--preset--aspect-ratio--9-16: 9/16;
--wp--preset--gradient--vivid-cyan-blue-to-vivid-purple: linear-gradient(135deg,rgba(6,147,227,1) 0%,rgb(155,81,224) 100%);
--wp--preset--gradient--light-green-cyan-to-vivid-green-cyan: linear-gradient(135deg,rgb(122,220,180) 0%,rgb(0,208,130) 100%);
--wp--preset--gradient--luminous-vivid-amber-to-luminous-vivid-orange: linear-gradient(135deg,rgba(252,185,0,1) 0%,rgba(255,105,0,1) 100%);
--wp--preset--gradient--luminous-vivid-orange-to-vivid-red: linear-gradient(135deg,rgba(255,105,0,1) 0%,rgb(207,46,46) 100%);
--wp--preset--gradient--very-light-gray-to-cyan-bluish-gray: linear-gradient(135deg,rgb(238,238,238) 0%,rgb(169,184,195) 100%);
--wp--preset--gradient--cool-to-warm-spectrum: linear-gradient(135deg,rgb(74,234,220) 0%,rgb(151,120,209) 20%,rgb(207,42,186) 40%,rgb(238,44,130) 60%,rgb(251,105,98) 80%,rgb(254,248,76) 100%);
--wp--preset--gradient--blush-light-purple: linear-gradient(135deg,rgb(255,206,236) 0%,rgb(152,150,240) 100%);
--wp--preset--gradient--blush-bordeaux: linear-gradient(135deg,rgb(254,205,165) 0%,rgb(254,45,45) 50%,rgb(107,0,62) 100%);
--wp--preset--gradient--luminous-dusk: linear-gradient(135deg,rgb(255,203,112) 0%,rgb(199,81,192) 50%,rgb(65,88,208) 100%);
--wp--preset--gradient--pale-ocean: linear-gradient(135deg,rgb(255,245,203) 0%,rgb(182,227,212) 50%,rgb(51,167,181) 100%);
--wp--preset--gradient--electric-grass: linear-gradient(135deg,rgb(202,248,128) 0%,rgb(113,206,126) 100%);
--wp--preset--gradient--midnight: linear-gradient(135deg,rgb(2,3,129) 0%,rgb(40,116,252) 100%);
--background: #FFFFFF;
--background_light: #F2F2EC;
--white: #ffffff;
--dark: #000000;
--container: 1320px;
--gutter-width: 30px;
--page-title-display: none;
--scroll-bar: 8px;
```

### Semantic

```css
success: [object Object];
warning: [object Object];
error: [object Object];
info: [object Object];
```

## Breakpoints

| Name | Value | Type |
|------|-------|------|
| xs | 320px | max-width |
| 410px | 410px | min-width |
| sm | 479px | max-width |
| sm | 500px | max-width |
| sm | 512px | min-width |
| 564px | 564px | max-width |
| 567px | 567px | max-width |
| 568px | 568px | max-width |
| sm | 576px | min-width |
| sm | 600px | min-width |
| sm | 601px | min-width |
| md | 730px | min-width |
| md | 767px | max-width |
| md | 768px | min-width |
| md | 782px | max-width |
| md | 783px | min-width |
| md | 800px | max-width |
| 880px | 880px | max-width |
| 881px | 881px | min-width |
| 900px | 900px | max-width |
| lg | 992px | min-width |
| lg | 1023px | max-width |
| lg | 1024px | max-width |
| lg | 1025px | min-width |
| 1120px | 1120px | max-width |
| 1200px | 1200px | max-width |
| 1201px | 1201px | min-width |
| 1366px | 1366px | max-width |
| 1367px | 1367px | min-width |
| 1400px | 1400px | min-width |
| 99999px | 99999px | max-width |

## Transitions & Animations

**Easing functions:** `[object Object]`, `[object Object]`, `[object Object]`, `[object Object]`, `[object Object]`

**Durations:** `0.25s`, `0.3s`, `0.4s`, `0.5s`, `0s`, `1.5s`, `0.75s`, `0.7s`, `1s`, `0.2s`, `0.6s`, `0.35s`

### Common Transitions

```css
transition: all;
transition: 0.25s;
transition: background 0.3s, border 0.3s, box-shadow 0.3s, transform 0.4s;
transition: background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s, transform 0.4s;
transition: 0.3s;
transition: 0.5s cubic-bezier(0.4, 0, 0.2, 1);
transition: 0s 1.5s;
transition: 0.75s cubic-bezier(0.45, 0.05, 0.55, 0.95);
transition: 0.7s cubic-bezier(0.65, 0.05, 0.36, 1);
transition: 1s cubic-bezier(0.65, 0.05, 0.36, 1);
```

### Keyframe Animations

**fa-spin**
```css
@keyframes fa-spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(1turn); }
}
```

**fa-spin**
```css
@keyframes fa-spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(1turn); }
}
```

**spin**
```css
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
```

**blink**
```css
@keyframes blink {
  0% { opacity: 0; }
  50% { opacity: 1; }
  100% { opacity: 0; }
}
```

**opal-draw**
```css
@keyframes opal-draw {
  0%, 100% { clip-path: inset(0px); }
  42% { clip-path: inset(0px 0px 0px 100%); }
  43% { clip-path: inset(0px 100% 0px 0px); }
}
```

**cover_search_fade_out**
```css
@keyframes cover_search_fade_out {
  0% { opacity: 1; visibility: visible; }
  100% { opacity: 0; visibility: hidden; }
}
```

**cover_search_fade_in**
```css
@keyframes cover_search_fade_in {
  0% { opacity: 0; visibility: hidden; }
  100% { opacity: 1; visibility: visible; }
}
```

**fa-spin**
```css
@keyframes fa-spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(359deg); }
}
```

**fa-spin**
```css
@keyframes fa-spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(359deg); }
}
```

**mf-spin-fast**
```css
@keyframes mf-spin-fast {
  0% { transform: rotate(0deg); animation-timing-function: cubic-bezier(0.55, 0.055, 0.675, 0.19); }
  50% { transform: rotate(900deg); animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1); }
  100% { transform: rotate(1800deg); }
}
```

## Component Patterns

Detected UI component patterns and their most common styles:

### Buttons (37 instances)

```css
.button {
  background-color: rgb(228, 153, 81);
  color: rgb(0, 0, 0);
  font-size: 12px;
  font-weight: 600;
  padding-top: 0px;
  padding-right: 0px;
  border-radius: 30px;
}
```

### Inputs (14 instances)

```css
.input {
  background-color: rgb(255, 255, 255);
  color: rgb(255, 255, 255);
  border-color: rgb(255, 255, 255);
  border-radius: 0px;
  font-size: 18px;
  padding-top: 0px;
  padding-right: 0px;
}
```

### Links (195 instances)

```css
.link {
  color: rgb(21, 61, 75);
  font-size: 18px;
  font-weight: 400;
}
```

### Navigation (18 instances)

```css
.navigatio {
  background-color: rgb(242, 242, 236);
  color: rgb(21, 61, 75);
  padding-top: 0px;
  padding-bottom: 0px;
  padding-left: 0px;
  padding-right: 0px;
  position: static;
}
```

### Footer (11 instances)

```css
.foote {
  background-color: rgb(242, 242, 236);
  color: rgb(21, 61, 75);
  padding-top: 15px;
  padding-bottom: 0px;
  font-size: 18px;
}
```

### Modals (7 instances)

```css
.modal {
  background-color: rgb(21, 61, 75);
  border-radius: 0px;
  padding-top: 0px;
  padding-right: 0px;
  max-width: 100%;
}
```

### Dropdowns (185 instances)

```css
.dropdown {
  background-color: rgb(255, 255, 255);
  border-radius: 0px;
  box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 30px 0px;
  border-color: rgb(21, 61, 75);
  padding-top: 0px;
}
```

### Tabs (1 instances)

```css
.tab {
  color: rgb(21, 61, 75);
  font-size: 18px;
  font-weight: 400;
  padding-top: 25px;
  padding-right: 40px;
  border-color: rgb(21, 61, 75);
  border-radius: 0px;
}
```

### Switches (4 instances)

```css
.switche {
  border-radius: 30px;
  border-color: rgb(255, 255, 255);
}
```

## Component Clusters

Reusable component instances grouped by DOM structure and style similarity:

### Button — 10 instances, 1 variant

**Variant 1** (10 instances)

```css
  background: rgba(0, 0, 0, 0);
  color: rgb(21, 61, 75);
  padding: 0px 0px 0px 0px;
  border-radius: 0px;
  border: 0px none rgb(21, 61, 75);
  font-size: 18px;
  font-weight: 400;
```

### Button — 1 instance, 1 variant

**Variant 1** (1 instance)

```css
  background: rgba(0, 0, 0, 0);
  color: rgb(21, 61, 75);
  padding: 0px 0px 0px 0px;
  border-radius: 0px;
  border: 0px none rgb(21, 61, 75);
  font-size: 18px;
  font-weight: 400;
```

### Button — 1 instance, 1 variant

**Variant 1** (1 instance)

```css
  background: rgba(0, 0, 0, 0);
  color: rgb(255, 255, 255);
  padding: 5px 0px 5px 0px;
  border-radius: 30px;
  border: 0px none rgb(255, 255, 255);
  font-size: 12px;
  font-weight: 600;
```

### Button — 1 instance, 1 variant

**Variant 1** (1 instance)

```css
  background: rgba(0, 0, 0, 0);
  color: rgb(21, 61, 75);
  padding: 110px 0px 0px 0px;
  border-radius: 0px;
  border: 0px none rgb(21, 61, 75);
  font-size: 18px;
  font-weight: 400;
```

### Button — 1 instance, 1 variant

**Variant 1** (1 instance)

```css
  background: rgb(21, 61, 75);
  color: rgb(21, 61, 75);
  padding: 0px 0px 0px 0px;
  border-radius: 0px;
  border: 0px none rgb(21, 61, 75);
  font-size: 18px;
  font-weight: 400;
```

### Button — 22 instances, 2 variants

**Variant 1** (6 instances)

```css
  background: rgba(0, 0, 0, 0);
  color: rgb(21, 61, 75);
  padding: 0px 0px 0px 0px;
  border-radius: 0px;
  border: 0px none rgb(21, 61, 75);
  font-size: 18px;
  font-weight: 400;
```

**Variant 2** (16 instances)

```css
  background: rgba(0, 0, 0, 0);
  color: rgb(255, 255, 255);
  padding: 0px 0px 0px 0px;
  border-radius: 0px;
  border: 0px none rgb(255, 255, 255);
  font-size: 18px;
  font-weight: 400;
```

### Button — 22 instances, 2 variants

**Variant 1** (1 instance)

```css
  background: rgba(0, 0, 0, 0);
  color: rgb(255, 255, 255);
  padding: 15px 35px 15px 35px;
  border-radius: 30px;
  border: 1px solid rgba(255, 255, 255, 0.2);
  font-size: 12px;
  font-weight: 600;
```

**Variant 2** (21 instances)

```css
  background: rgba(0, 0, 0, 0);
  color: rgb(0, 0, 0);
  padding: 15px 35px 15px 35px;
  border-radius: 30px;
  border: 1px solid rgb(224, 223, 216);
  font-size: 12px;
  font-weight: 600;
```

### Button — 5 instances, 2 variants

**Variant 1** (1 instance)

```css
  background: rgba(0, 0, 0, 0);
  color: rgb(255, 255, 255);
  padding: 0px 0px 0px 0px;
  border-radius: 0px;
  border: 0px none rgb(255, 255, 255);
  font-size: 12px;
  font-weight: 600;
```

**Variant 2** (4 instances)

```css
  background: rgba(0, 0, 0, 0);
  color: rgb(0, 0, 0);
  padding: 0px 0px 0px 0px;
  border-radius: 0px;
  border: 0px none rgb(0, 0, 0);
  font-size: 12px;
  font-weight: 600;
```

### Button — 9 instances, 2 variants

**Variant 1** (1 instance)

```css
  background: rgba(0, 0, 0, 0);
  color: rgb(255, 255, 255);
  padding: 0px 0px 0px 0px;
  border-radius: 0px;
  border: 0px none rgb(255, 255, 255);
  font-size: 12px;
  font-weight: 600;
```

**Variant 2** (8 instances)

```css
  background: rgba(0, 0, 0, 0);
  color: rgb(21, 61, 75);
  padding: 0px 0px 0px 0px;
  border-radius: 0px;
  border: 0px none rgb(21, 61, 75);
  font-size: 11px;
  font-weight: 400;
```

### Button — 22 instances, 2 variants

**Variant 1** (17 instances)

```css
  background: rgba(0, 0, 0, 0);
  color: rgb(255, 255, 255);
  padding: 0px 0px 0px 0px;
  border-radius: 0px;
  border: 0px none rgb(255, 255, 255);
  font-size: 12px;
  font-weight: 600;
```

**Variant 2** (5 instances)

```css
  background: rgba(0, 0, 0, 0);
  color: rgb(0, 0, 0);
  padding: 0px 0px 0px 0px;
  border-radius: 0px;
  border: 0px none rgb(0, 0, 0);
  font-size: 12px;
  font-weight: 600;
```

### Input — 3 instances, 2 variants

**Variant 1** (2 instances)

```css
  background: rgba(0, 0, 0, 0);
  color: rgb(255, 255, 255);
  padding: 8px 0px 8px 0px;
  border-radius: 0px;
  border: 0px none rgb(255, 255, 255);
  font-size: 18px;
  font-weight: 400;
```

**Variant 2** (1 instance)

```css
  background: rgba(255, 255, 255, 0);
  color: rgb(255, 255, 255);
  padding: 24px 30px 20px 30px;
  border-radius: 0px;
  border: 0px 0px 1px solid rgb(255, 255, 255);
  font-size: 10px;
  font-weight: 600;
```

### Button — 1 instance, 1 variant

**Variant 1** (1 instance)

```css
  background: rgb(228, 153, 81);
  color: rgb(255, 255, 255);
  padding: 16px 15px 16px 15px;
  border-radius: 30px;
  border: 0px none rgb(255, 255, 255);
  font-size: 12px;
  font-weight: 600;
```

### Button — 4 instances, 2 variants

**Variant 1** (2 instances)

```css
  background: rgba(255, 255, 255, 0.5);
  color: rgb(0, 0, 0);
  padding: 0px 0px 0px 0px;
  border-radius: 50%;
  border: 0px solid rgba(255, 255, 255, 0.5);
  font-size: 24px;
  font-weight: 400;
```

**Variant 2** (2 instances)

```css
  background: rgba(0, 0, 0, 0);
  color: rgba(238, 238, 238, 0.9);
  padding: 0px 0px 0px 0px;
  border-radius: 50%;
  border: 1px solid rgb(224, 223, 216);
  font-size: 24px;
  font-weight: 400;
```

### Button — 17 instances, 1 variant

**Variant 1** (17 instances)

```css
  background: rgba(0, 0, 0, 0);
  color: rgb(0, 0, 0);
  padding: 0px 0px 0px 0px;
  border-radius: 0px;
  border: 0px none rgb(0, 0, 0);
  font-size: 12px;
  font-weight: 600;
```

### Button — 16 instances, 1 variant

**Variant 1** (16 instances)

```css
  background: rgba(0, 0, 0, 0);
  color: rgb(21, 61, 75);
  padding: 40px 40px 30px 40px;
  border-radius: 0px;
  border: 0px none rgb(21, 61, 75);
  font-size: 18px;
  font-weight: 400;
```

### Button — 5 instances, 2 variants

**Variant 1** (1 instance)

```css
  background: rgba(255, 255, 255, 0);
  color: rgb(255, 255, 255);
  padding: 20px 0px 20px 0px;
  border-radius: 0px;
  border: 0px 0px 1px solid rgb(255, 255, 255);
  font-size: 12px;
  font-weight: 600;
```

**Variant 2** (4 instances)

```css
  background: rgba(0, 0, 0, 0);
  color: rgb(255, 255, 255);
  padding: 7px 0px 7px 0px;
  border-radius: 30px;
  border: 0px none rgb(255, 255, 255);
  font-size: 12px;
  font-weight: 600;
```

## Layout System

**30 grid containers** and **285 flex containers** detected.

### Container Widths

| Max Width | Padding |
|-----------|---------|
| 100% | 0px |
| 1730px | 0px |
| min(100%, 1290px) | 0px |
| 1290px | 30px |
| 47.5% | 0px |

### Grid Column Patterns

| Columns | Usage Count |
|---------|-------------|
| 1-column | 24x |
| 3-column | 3x |
| 4-column | 2x |
| 6-column | 1x |

### Grid Templates

```css
grid-template-columns: 366.656px 366.672px 366.656px;
gap: 60px;
grid-template-columns: 1220px;
grid-template-columns: 305px 305px 305px 305px;
grid-template-columns: 1160px;
grid-template-columns: 267.5px 267.5px 267.5px 267.5px;
gap: 30px;
```

### Flex Patterns

| Direction/Wrap | Count |
|----------------|-------|
| row/wrap | 30x |
| column/nowrap | 101x |
| row/nowrap | 144x |
| row-reverse/nowrap | 5x |
| column-reverse/nowrap | 5x |

**Gap values:** `140px`, `15px`, `16px`, `30px`, `45px`, `5px`, `60px`, `8px`, `normal 17px`

## Accessibility (WCAG 2.1)

**Overall Score: 0%** — 0 passing, 4 failing color pairs

### Failing Color Pairs

| Foreground | Background | Ratio | Level | Used On |
|------------|------------|-------|-------|---------|
| `#ffffff` | `#ff0000` | 4:1 | FAIL | span (2x) |
| `#ffffff` | `#e49951` | 2.34:1 | FAIL | button (2x) |

## Design System Score

**Overall: 71/100 (Grade: C)**

| Category | Score |
|----------|-------|
| Color Discipline | 100/100 |
| Typography Consistency | 70/100 |
| Spacing System | 85/100 |
| Shadow Consistency | 100/100 |
| Border Radius Consistency | 90/100 |
| Accessibility | 0/100 |
| CSS Tokenization | 100/100 |

**Strengths:** Tight, disciplined color palette, Well-defined spacing scale, Clean elevation system, Consistent border radii, Good CSS variable tokenization

**Issues:**
- 24 distinct font sizes — consider a tighter type scale
- 4 WCAG contrast failures
- 196 !important rules — prefer specificity over overrides
- 84% of CSS is unused — consider purging
- 11147 duplicate CSS declarations

## Z-Index Map

**17 unique z-index values** across 4 layers.

| Layer | Range | Elements |
|-------|-------|----------|
| modal | 3001,99999 | div.d.a.t.e.r.a.n.g.e.p.i.c.k.e.r. .l.t.r. .a.u.t.o.-.a.p.p.l.y. .s.h.o.w.-.c.a.l.e.n.d.a.r. .o.p.e.n.s.r.i.g.h.t, div.e.l.e.m.e.n.t.o.r.-.e.l.e.m.e.n.t. .e.l.e.m.e.n.t.o.r.-.e.l.e.m.e.n.t.-.2.f.0.2.7.d.0. .e.-.c.o.n.-.f.u.l.l. .e.-.f.l.e.x. .e.-.c.o.n. .e.-.p.a.r.e.n.t. .e.-.l.a.z.y.l.o.a.d.e.d, div.e.l.e.m.e.n.t.o.r.-.e.l.e.m.e.n.t. .e.l.e.m.e.n.t.o.r.-.e.l.e.m.e.n.t.-.3.2.f.b.d.9.7. .e.-.f.l.e.x. .e.-.c.o.n.-.b.o.x.e.d. .e.-.c.o.n. .e.-.p.a.r.e.n.t. .e.-.l.a.z.y.l.o.a.d.e.d |
| dropdown | 997,999 | div.a.m.o.j.a.-.o.v.e.r.l.a.y, a.s.c.r.o.l.l.u.p |
| sticky | 10,99 | div.b.u.t.t.o.n.-.p.o.p.u.p.-.m.e.n.u.-.c.o.n.t.e.n.t.-.i.n.n.e.r, div.s.w.i.p.e.r.-.p.a.g.i.n.a.t.i.o.n. .s.w.i.p.e.r.-.p.a.g.i.n.a.t.i.o.n.-.f.r.a.c.t.i.o.n. .s.w.i.p.e.r.-.p.a.g.i.n.a.t.i.o.n.-.h.o.r.i.z.o.n.t.a.l, div.s.w.i.p.e.r.-.p.a.g.i.n.a.t.i.o.n. .s.w.i.p.e.r.-.p.a.g.i.n.a.t.i.o.n.-.c.l.i.c.k.a.b.l.e. .s.w.i.p.e.r.-.p.a.g.i.n.a.t.i.o.n.-.b.u.l.l.e.t.s. .s.w.i.p.e.r.-.p.a.g.i.n.a.t.i.o.n.-.h.o.r.i.z.o.n.t.a.l |
| base | -1000,9 | span.s.w.i.p.e.r.-.n.o.t.i.f.i.c.a.t.i.o.n, span.s.w.i.p.e.r.-.n.o.t.i.f.i.c.a.t.i.o.n, span.s.w.i.p.e.r.-.n.o.t.i.f.i.c.a.t.i.o.n |

**Issues:**
- [object Object]

## SVG Icons

**2 unique SVG icons** detected. Dominant style: **filled**.

| Size Class | Count |
|------------|-------|
| xl | 2 |

**Icon colors:** `rgb(21, 61, 75)`, `rgba(21, 61, 75, 0.2)`

## Font Files

| Family | Source | Weights | Styles |
|--------|--------|---------|--------|
| Font Awesome 5 Brands | self-hosted | 400, normal | normal |
| Font Awesome 5 Free | self-hosted | 400, 900 | normal |
| Ortica Linear | self-hosted | 300 | normal |
| amoja-icon | self-hosted | 400, normal | normal |
| swiper-icons | self-hosted | 400 | normal |
| Jost | google-fonts | 100, 200, 300, 400, 400 600, 500, 600, 700, 800, 900 | normal, italic |

**Google Fonts URL:** `https://fonts.googleapis.com/`

## Image Style Patterns

| Pattern | Count | Key Styles |
|---------|-------|------------|
| general | 29 | objectFit: fill, borderRadius: 10px, shape: rounded |
| hero | 14 | objectFit: cover, borderRadius: 0px, shape: square |
| thumbnail | 5 | objectFit: fill, borderRadius: 0px, shape: square |
| avatar | 4 | objectFit: cover, borderRadius: 50%, shape: circular |

**Aspect ratios:** 3:4 (21x), 16:9 (8x), 2:3 (8x), 3:2 (6x), 1:1 (4x), 4.15:1 (3x), 5.66:1 (1x), 4.55:1 (1x)

## Motion Language

**Feel:** mixed · **Scroll-linked:** yes

### Duration Tokens

| name | value | ms |
|---|---|---|
| `sm` | `200ms` | 200 |
| `md` | `300ms` | 300 |
| `lg` | `500ms` | 500 |
| `xl` | `750ms` | 750 |
| `xxl` | `1.5s` | 1500 |

### Easing Families

- **custom** (44 uses) — `cubic-bezier(0.4, 0, 0.2, 1)`, `cubic-bezier(0.45, 0.05, 0.55, 0.95)`, `cubic-bezier(0.65, 0.05, 0.36, 1)`
- **ease-in-out** (34 uses) — `ease`
- **linear** (8 uses) — `linear`

### Keyframes In Use

| name | kind | properties | uses |
|---|---|---|---|
| `opalMoveUp` | slide-y | transform, opacity | 2 |
| `opalMoveUp` | slide-y | transform, opacity | 2 |

## Component Anatomy

### button — 137 instances

**Slots:** label, icon
**Variants:** outline · link · secondary · primary
**Sizes:** sm

| variant | count | sample label |
|---|---|---|
| default | 88 | Home 01
                                 |
| link | 23 | BOOK YOUR STAY |
| outline | 21 | BOOK YOUR STAY |
| secondary | 4 |  |
| primary | 1 | SEARCH |

### input — 3 instances


## Brand Voice

**Tone:** friendly · **Pronoun:** third-person · **Headings:** Sentence case (balanced)

### Top CTA Verbs

- **learn** (50)
- **explore** (25)
- **view** (15)
- **book** (5)
- **discover** (5)
- **home** (3)
- **previous** (2)
- **next** (2)

### Button Copy Patterns

- "learn more" (48×)
- "explore offer" (16×)
- "book your stay" (5×)
- "explore more" (5×)
- "view all rooms" (5×)
- "discover more" (5×)
- "view all reviews" (5×)
- "view all blog" (5×)
- "home 01" (3×)
- "previous" (2×)

### Sample Headings

> A Sanctuary For Your Senses
> Enjoy summer in the lap of luxury
> Discover our rooms
> Rebalance yourself in a timeless space
> Rebalance yourself in a timeless space
> Award-winning resort in the paradise island

## Page Intent

**Type:** `unknown` (confidence 0)

## Section Roles

Reading order (top→bottom): nav → pricing-table → nav → nav → footer

| # | Role | Heading | Confidence |
|---|------|---------|------------|
| 0 | nav | — | 0.4 |
| 1 | nav | — | 0.9 |
| 2 | pricing-table | A Sanctuary For Your Senses | 0.9 |
| 3 | footer | Award-winning resort in the paradise island | 0.95 |
| 4 | nav | — | 0.9 |

## Material Language

**Label:** `flat` (confidence 0)

| Metric | Value |
|--------|-------|
| Avg saturation | 0.284 |
| Shadow profile | soft |
| Avg shadow blur | 0px |
| Max radius | 50px |
| backdrop-filter in use | no |
| Gradients | 0 |

## Imagery Style

**Label:** `photography` (confidence 0.433)
**Counts:** total 52, svg 4, icon 4, screenshot-like 0, photo-like 43
**Dominant aspect:** portrait
**Radius profile on images:** soft

## Component Library

**Detected:** `bootstrap` (confidence 0.6)

Evidence:
- bootstrap utility hits: 3

## Component Screenshots

16 retina crops written to `screenshots/`. Index: `*-screenshots.json`.

| Cluster | Variant | Size (px) | File |
|---------|---------|-----------|------|
| button--default | 0 | 400 × 29 | `screenshots/button-default-0.png` |
| button--default | 1 | 400 × 29 | `screenshots/button-default-1.png` |
| button--default | 2 | 40 × 29 | `screenshots/button-default-2.png` |
| button--outline | 0 | 209 × 50 | `screenshots/button-outline-0.png` |
| button--outline | 1 | 177 × 50 | `screenshots/button-outline-1.png` |
| button--outline | 2 | 1220 × 130 | `screenshots/button-outline-2.png` |
| button--default--sm | 0 | 189 × 50 | `screenshots/button-default-sm-0.png` |
| button--default--sm | 1 | 177 × 50 | `screenshots/button-default-sm-1.png` |
| button--default--sm | 2 | 191 × 50 | `screenshots/button-default-sm-2.png` |
| input--default | 0 | 150 × 42 | `screenshots/input-default-0.png` |
| input--default | 1 | 150 × 42 | `screenshots/input-default-1.png` |
| input--default | 2 | 531 × 59 | `screenshots/input-default-2.png` |
| button--secondary | 0 | 30 × 30 | `screenshots/button-secondary-0.png` |
| button--secondary | 1 | 30 × 30 | `screenshots/button-secondary-1.png` |
| button--secondary | 2 | 30 × 30 | `screenshots/button-secondary-2.png` |
| button--primary | 0 | 165 × 50 | `screenshots/button-primary-0.png` |

Full-page: `screenshots/full-page.png`

## Quick Start

To recreate this design in a new project:

1. **Install fonts:** Add `Jost` from Google Fonts or your font provider
2. **Import CSS variables:** Copy `variables.css` into your project
3. **Tailwind users:** Use the generated `tailwind.config.js` to extend your theme
4. **Design tokens:** Import `design-tokens.json` for tooling integration
