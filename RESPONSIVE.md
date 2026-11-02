# Responsive layer

Phones and tablets are handled by one shared stylesheet and one small script,
plus a handful of page-specific fixes. Desktop (>= 992px) rendering is unchanged.

| File | Purpose |
|------|---------|
| `public/assets/css/responsive.css` | Global rules for cards, tables, forms, modals, DataTables, pagination, header, etc. Loaded last from every head partial in `resources/views/layouts` and `layouts-side-bar`. |
| `public/assets/js/responsive.js` | Wraps wide tables in a scroll container (also ones injected later), dims the page behind the slide-in menu, closes it on tap/Escape, re-fires `resize` so charts redraw. |

## Rules for new pages

* Put wide tables in `<div class="table-responsive">` (the script does it for you if you forget).
* Give grids `minmax(min(100%, 280px), 1fr)` rather than `minmax(280px, 1fr)`.
* Use `flex-wrap` on toolbars / button rows.
* Page-specific phone tweaks go in the page's own `<style>` inside `@media (max-width: 575.98px)`.
* Helpers available everywhere: `.ra-scroll-x`, `.ra-actions`, `.ra-stack-xs`, `.ra-full-xs`, `.ra-hide-xs`.

## Finding what still sticks out

Open any page with `?ra-debug` appended, e.g. `/students/all?ra-debug`, using the
browser's phone emulator (DevTools > Toggle device toolbar). Elements wider than the
screen get a red outline and are listed in the console.

## Bootstrap 4 vs 5 class names

The admin theme ships Bootstrap 4 but many views use Bootstrap 5 names
(`me-2`, `ms-3`, `g-3`, `fw-bold`, `form-select`, ...). Section 7 of
`responsive.css` defines the common ones for tablets/phones only. Prefer the
Bootstrap 4 names (`mr-2`, `ml-3`, `font-weight-bold`) in new code.
