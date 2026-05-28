<?php
require_once __DIR__ . '/tracking.php';

if (!function_exists('e')) {
    function e(mixed $val): string {
        return htmlspecialchars((string)$val, ENT_QUOTES, 'UTF-8');
    }
}

$pageTitle   = $pageTitle   ?? 'Seven Islands Resort — Watamu, Kenya';
$metaDesc    = $metaDesc    ?? '';
$activeNav   = $activeNav   ?? '';
$headerSolid = $headerSolid ?? true;
$jsonLd      = $jsonLd      ?? '';

// Derive absolute base URL for OG/canonical tags
$_sch  = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
          || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')) ? 'https' : 'http';
$_host = $_SERVER['HTTP_HOST'] ?? 'sevenislandswatamu.com';
$_base = $_sch . '://' . $_host;

$ogTitle      = $ogTitle      ?? $pageTitle;
$ogDesc       = $ogDesc       ?? $metaDesc;
$ogType       = $ogType       ?? 'website';
$ogImage      = $ogImage      ?? $_base . '/assets/img/7islands_resort_watamu1.jpg';
$canonicalUrl = $canonicalUrl ?? '';

$navItems = [
  'home'    => ['index.php#top', 'Home'],
  'about'   => ['about.php',     'Resort'],
  'rooms'   => ['rooms.php',     'Rooms'],
  'dining'  => ['dining.php',    'Dining'],
  'spa'     => ['spa.php',       'SPA'],
  'tours'   => ['tours.php',     'Safari &amp; Excursion'],
  'agency'  => ['agency.php',    'Travel Agency'],
  'contact' => ['contact.php',   'Contact'],
];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle) ?></title>
  <?php if ($metaDesc): ?>
  <meta name="description" content="<?= e($metaDesc) ?>">
  <?php endif; ?>
  <meta property="og:type"        content="<?= e($ogType) ?>">
  <meta property="og:title"       content="<?= e($ogTitle) ?>">
  <meta property="og:description" content="<?= e($ogDesc) ?>">
  <meta property="og:image"       content="<?= e($ogImage) ?>">
  <meta property="og:site_name"   content="Seven Islands Resort">
  <meta property="og:locale"      content="en_US">
  <?php if ($canonicalUrl): ?>
  <meta property="og:url"         content="<?= e($canonicalUrl) ?>">
  <?php endif; ?>
  <meta name="twitter:card"        content="summary_large_image">
  <meta name="twitter:title"       content="<?= e($ogTitle) ?>">
  <meta name="twitter:description" content="<?= e($ogDesc) ?>">
  <meta name="twitter:image"       content="<?= e($ogImage) ?>">
  <?php if ($canonicalUrl): ?>
  <link rel="canonical" href="<?= e($canonicalUrl) ?>">
  <?php endif; ?>
  <?php if ($jsonLd): ?>
  <script type="application/ld+json"><?= $jsonLd ?></script>
  <?php endif; ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header class="site-header<?= $headerSolid ? ' site-header--solid' : '' ?>" id="siteHeader">
    <div class="container site-header__top">
      <button class="site-header__burger" id="navBurger" aria-label="Toggle menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
      <a class="site-header__logo" href="index.php#top">
        <img class="site-header__logo-img" src="assets/img/logo-white.png" alt="Seven Islands Watamu">
      </a>
      <div class="site-header__actions">
        <a class="site-header__phone" href="tel:+2540713326336">
          <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3-8.6A2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .3 1.9.6 2.8a2 2 0 0 1-.5 2.1L8 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.5 2.8.6a2 2 0 0 1 1.8 2.1z"/>
          </svg>
          +254 0713 326 336
        </a>
        <a class="btn btn--ghost site-header__cta" href="contact.php">Book Your Stay <span aria-hidden="true">&rsaquo;</span></a>
      </div>
    </div>
    <nav class="site-nav" id="siteNav">
      <ul>
<?php foreach ($navItems as $key => [$href, $label]): ?>
        <li><a<?= $activeNav === $key ? ' class="is-active"' : '' ?> href="<?= $href ?>"><?= $label ?></a></li>
<?php endforeach; ?>
      </ul>
    </nav>
  </header>
