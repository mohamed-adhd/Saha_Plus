# Saha+ UI Kit

## What is included

The kit provides a shared warm-green visual foundation, responsive application shell, configurable navigation, reusable page components, and small vanilla JavaScript helpers. It is designed for plain PHP pages and does not require a framework.

## Folder tree

```text
shared/
├── README.md
├── demo.php
├── helpers.php
├── config/
│   └── menu.php
├── assets/
│   ├── css/
│   │   ├── tokens.css
│   │   ├── base.css
│   │   ├── background.css
│   │   ├── shell.css
│   │   ├── sidebar.css
│   │   ├── components.css
│   │   └── animations.css
│   ├── js/
│   │   ├── sidebar.js
│   │   └── ui.js
│   └── img/
│       ├── leaf.svg
│       └── saha-mark.svg
└── app/Views/
    ├── layout_kit.php
    └── partials/
        ├── head.php
        ├── sidebar.php
        ├── topbar.php
        └── scripts.php
```

## Add the kit to a module

1. From the project root, load the helpers once in `index.php`:

   ```php
   require_once __DIR__ . '/shared/helpers.php';
   ```

2. Keep each page view limited to its own HTML. In the module controller, set the page key and title before capturing the view and loading the shared layout:

   ```php
   $page = 'health-map';
   $title = 'Health Map';
   $currentPage = $page;
   $GLOBALS['currentPage'] = $currentPage;
   $breadcrumb = ['Saha+', $title];

   ob_start();
   require __DIR__ . '/../../Views/pages/front/health_map.php';
   $content = ob_get_clean();
   require __DIR__ . '/../../../shared/app/Views/layout_kit.php';
   ```

   The layout supplies the document head, background, sidebar, top bar and script includes. For Back Office pages, continue using the workshop `Controller::render('view_name')` flow and the existing Back Office layout.

3. Use `base_url()` for page links and `asset()` for local assets. Both helpers account for the project being served from a subfolder:

   ```php
   <img src="<?= htmlspecialchars(asset('assets/img/map.svg'), ENT_QUOTES, 'UTF-8') ?>" alt="">
   <a href="<?= htmlspecialchars(base_url('index.php?page=health-map'), ENT_QUOTES, 'UTF-8') ?>">Open map</a>
   ```

4. The shell uses `navActive($key)` to match `$currentPage`. Keep the value and menu key identical; the helper reads the current value from `$GLOBALS['currentPage']`.

## Component classes

| Class | Example |
|---|---|
| `.sp-bg` | `<body class="sp-bg">` |
| `.sp-app-shell` / `.sp-content` | `<div class="sp-app-shell"><main class="sp-content">…</main></div>` |
| `.sp-page-header` | `<header class="sp-page-header"><div><h1>Local care</h1><p>Find useful information nearby.</p></div></header>` |
| `.sp-breadcrumb` | `<nav class="sp-breadcrumb" aria-label="Breadcrumb"><a href="index.php">Home</a><span>/</span><span>Local care</span></nav>` |
| `.sp-card` | `<article class="sp-card"><div class="sp-card__body">A clear overview.</div></article>` |
| `.sp-card__header` / `.sp-card__body` | `<section class="sp-card"><header class="sp-card__header">Nearby services</header><div class="sp-card__body">Details go here.</div></section>` |
| `.sp-stat-card` | `<article class="sp-stat-card"><p class="sp-stat-card__label">Visits this month</p><p class="sp-stat-card__value">12</p></article>` |
| `.sp-section-title` | `<header class="sp-section-title"><div><h2>Nearby services</h2><p>Useful places in your area.</p></div></header>` |
| `.sp-button--primary` | `<button class="sp-button sp-button--primary" type="button">Save changes</button>` |
| `.sp-button--outline` / `--soft` / `--danger` | `<button class="sp-button sp-button--outline" type="button">Learn more</button>` |
| `.sp-pill` | `<span class="sp-pill">Open today</span>` |
| `.sp-badge--info` / `--warn` / `--danger` | `<span class="sp-badge sp-badge--info">Available</span>` |
| `.sp-empty` | `<section class="sp-empty"><h3>No locations yet</h3><p>Try a nearby area.</p></section>` |
| `.sp-skeleton` | `<div class="sp-skeleton sp-skeleton--text" aria-hidden="true"></div>` |
| `.sp-toast` | `window.spToast('Your changes are saved.');` |
| `.sp-tooltip` | `<span class="sp-tooltip" data-sp-tooltip="More details" tabindex="0">Details</span>` |
| `.sp-field` / `.sp-input` | `<div class="sp-field"><label for="area">Area</label><input class="sp-input" id="area"></div>` |
| `.sp-select` / `.sp-textarea` | `<select class="sp-select" aria-label="Choose an area"><option>Central</option></select>` |
| `.sp-table` | `<div class="sp-table-wrap"><table class="sp-table"><thead><tr><th scope="col">Name</th></tr></thead><tbody><tr><td>Local center</td></tr></tbody></table></div>` |
| `.sp-alert--warn` / `--danger` | `<div class="sp-alert sp-alert--warn" role="status">Check the latest opening hours.</div>` |
| `.sp-modal` | `<button class="sp-button sp-button--primary" data-sp-modal-open="detailsModal">Open details</button>` |
| `.sp-enter` / `data-sp-reveal` | `<section class="sp-card" data-sp-reveal>Appears as it enters view.</section>` |

For a dialog, use `<dialog class="sp-modal" id="detailsModal">…<button data-sp-modal-close>Close</button></dialog>`. The shared script handles open and close controls.

The JavaScript helpers are `window.spToast(message, options)` and `window.spTooltip(elementOrSelector, text)`. Add `data-sp-tooltip` to an element for an automatic title hint. Reveal effects use `data-sp-reveal` or `.sp-enter`; reduced-motion preferences are respected.

## Add a navigation item

Add one row to `config/menu.php`; the item’s section controls its group label:

```php
['key' => 'health-map', 'label' => 'Health Map', 'icon' => 'map', 'url' => 'index.php?page=health-map', 'section' => 'Care'],
```

Available icon names are `home`, `atlas`, `map`, `drop`, `mind`, and `leaf`. Use a unique `key` and set `$currentPage` to that same key in the controller. Add new SVG paths in the sidebar partial only when a new icon is required.

## Run the demo

From the project root, run `php -S localhost:8000` and visit `http://localhost:8000/shared/demo.php`. Google Fonts and Bootstrap are loaded from their CDNs; local styles and scripts use the shared URL helpers.
