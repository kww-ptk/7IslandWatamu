<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/db.php';

$slug = trim($_GET['slug'] ?? 'standard');
$room = fetch_room_by_slug($slug);

if (!$room) {
    http_response_code(404);
    $pageTitle = '404 — Room Not Found';
    include __DIR__ . '/includes/header.php';
    echo '<section class="section"><div class="container" style="text-align:center;padding:4rem 0">
        <h1>Room not found</h1>
        <p>The room you are looking for does not exist or is no longer available.</p>
        <a class="btn btn--primary" href="rooms.php">View All Rooms</a>
    </div></section>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

$images     = fetch_room_images((int)$room['id']);
$hero_img   = '';
foreach ($images as $img) {
    if ($img['is_hero']) { $hero_img = $img['filename']; break; }
}
if (!$hero_img && !empty($images)) $hero_img = $images[0]['filename'];

$features   = json_decode($room['features_json'] ?? '[]', true) ?: [];
// Per-room override takes precedence over the global setting
$form_mode  = !empty($room['form_mode']) ? $room['form_mode'] : setting('form_mode', 'enquiry');

$pageTitle     = $room['seo_title']       ?: e($room['name']) . ' — Seven Islands Resort, Watamu';
$metaDesc      = $room['seo_description'] ?: ($room['short_desc'] ?? '');
$activeNav     = 'rooms';
$canonicalUrl  = site_url('room.php?slug=' . urlencode($room['slug']));
$ogImage       = $hero_img ? site_url('assets/img/' . $hero_img) : site_url('assets/img/7islands_resort_watamu1.jpg');
$extraScripts  = ['room.js'];
$jsonLd        = json_encode([
    '@context'    => 'https://schema.org',
    '@type'       => 'HotelRoom',
    'name'        => $room['name'],
    'description' => $metaDesc,
    'url'         => $canonicalUrl,
    'image'       => $ogImage,
    'occupancy'   => $room['capacity'] ? ['@type' => 'QuantitativeValue', 'maxValue' => (int)$room['capacity']] : null,
    'floorSize'   => $room['size_sqm']  ? ['@type' => 'QuantitativeValue', 'value' => (int)$room['size_sqm'], 'unitCode' => 'MTK'] : null,
    'containedInPlace' => [
        '@type' => 'LodgingBusiness',
        'name'  => 'Seven Islands Resort',
        'url'   => site_url(),
    ],
]);

include __DIR__ . '/includes/header.php';
?>

  <section class="page-hero" style="background:linear-gradient(rgba(11,98,115,.5),rgba(11,98,115,.62)),url('<?= e(storage_url($hero_img)) ?>') center/cover no-repeat;">
    <div class="page-hero__inner">
      <p class="page-hero__eyebrow">Rooms &amp; Suites</p>
      <h1 class="page-hero__title"><?= e($room['name']) ?></h1>
      <ul class="page-hero__meta">
        <?php if ($room['size_sqm']): ?>
        <li>
          <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M3 8V3h5M21 8V3h-5M3 16v5h5M21 16v5h-5"/>
          </svg>
          <?= e($room['size_sqm']) ?>M&sup2;
        </li>
        <?php endif; ?>
        <?php if ($room['capacity']): ?>
        <li>
          <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-8 8-8s8 3.6 8 8"/>
          </svg>
          1–<?= e($room['capacity']) ?> person
        </li>
        <?php endif; ?>
        <?php if ($room['bed_count']): ?>
        <li>
          <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M3 18V8h13a5 5 0 0 1 5 5v5M3 14h18M3 18v2M21 18v2"/>
            <path d="M6 12h4a2 2 0 0 1 2 2"/>
          </svg>
          <?= e($room['bed_count']) ?> bed<?= $room['bed_count'] > 1 ? 's' : '' ?>
        </li>
        <?php endif; ?>
      </ul>
    </div>
  </section>

  <?php if (!empty($images)): ?>
  <div class="room-gallery" data-gal-viewport>
    <div class="room-gallery__track" data-gal-track>
      <?php foreach ($images as $img): ?>
      <div class="room-gallery__slide">
        <img src="<?= e(storage_url($img['filename'])) ?>" alt="<?= e($img['alt_text'] ?: $room['name']) ?>">
      </div>
      <?php endforeach; ?>
    </div>
    <button class="room-gallery__arrow room-gallery__arrow--prev" data-gal-prev aria-label="Previous image">&#8592;</button>
    <button class="room-gallery__arrow room-gallery__arrow--next" data-gal-next aria-label="Next image">&#8594;</button>
    <a class="room-gallery__view" href="#">View Gallery
      <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
        <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="M21 15l-5-5L5 21"/>
      </svg>
    </a>
  </div>
  <?php endif; ?>

  <div class="container room-main">
    <div class="room-content">
      <h2 class="room-h2">Room Description</h2>
      <?php foreach (explode("\n\n", trim($room['long_desc'] ?? '')) as $para): ?>
        <?php if (trim($para)): ?>
        <p class="room-p"><?= nl2br(e(trim($para))) ?></p>
        <?php endif; ?>
      <?php endforeach; ?>

      <?php if (!empty($features)): ?>
      <h2 class="room-h2">Services &amp; Amenities</h2>
      <ul class="amen-grid">
        <?php foreach ($features as $feature): ?>
        <li>
          <span><?= e($feature) ?></span>
          <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M20 6L9 17l-5-5"/>
          </svg>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>

      <h2 class="room-h2">Room Rules</h2>
      <div class="rules-grid">
        <div class="rule-col">
          <h4 class="rule-h">Check-in</h4>
          <ul class="rule-list">
            <li>Check-in from 9:00 AM — anytime</li>
            <li>Early check-in subject to availability</li>
            <li>Minimum check-in age — 18</li>
          </ul>
        </div>
        <div class="rule-col">
          <h4 class="rule-h">Check-Out</h4>
          <ul class="rule-list">
            <li>Check-out before noon</li>
            <li>Express check-out</li>
          </ul>
        </div>
      </div>
      <div class="rule-block">
        <h4 class="rule-h">Special check-in instructions</h4>
        <p>Guests will receive an email 5 days before arrival with check-in instructions; front desk staff will greet guests on arrival.</p>
      </div>
      <div class="rule-block">
        <h4 class="rule-h">Children and extra beds</h4>
        <p>Children are welcome. Kids stay free when using existing bedding. Rollaway/extra beds are available for USD 40 per day.</p>
      </div>

    </div>

    <aside class="room-booking" id="book">
      <div class="booking-card">
        <p class="booking-card__price">
          <span>from</span>
          <strong><?= e($room['price_currency']) ?> <?= e(number_format((float)$room['price_amount'], 0)) ?></strong>
          <?= e($room['price_unit']) ?>
        </p>
        <h3 class="booking-card__title">Book This Room</h3>
        <?php if ($form_mode === 'availability'): ?>
          <?php include __DIR__ . '/includes/form-availability.php'; ?>
        <?php else: ?>
          <?php include __DIR__ . '/includes/form-enquiry.php'; ?>
        <?php endif; ?>
      </div>
    </aside>
  </div>

  <?php
  // Other rooms — show up to 3 published rooms excluding current
  $others = db_query(
      'SELECT r.*, (SELECT filename FROM room_images WHERE room_id = r.id AND is_hero = TRUE LIMIT 1) AS hero_img
       FROM rooms r
       WHERE r.slug != :slug AND r.is_published = TRUE
       ORDER BY r.sort_order ASC LIMIT 3',
      [':slug' => $slug]
  )->fetchAll();
  ?>
  <?php if (!empty($others)): ?>
  <section class="section other-rooms">
    <div class="container">
      <h2 class="room-h2">Other Rooms</h2>
      <div class="other-rooms__grid">
        <?php foreach ($others as $other): ?>
        <article class="other-room">
          <a class="other-room__img" href="room.php?slug=<?= e($other['slug']) ?>">
            <span class="other-room__price"><label>from</label><strong><?= e($other['price_currency']) ?> <?= e(number_format((float)$other['price_amount'], 0)) ?></strong> <?= e($other['price_unit']) ?></span>
            <?php if ($other['hero_img']): ?>
            <img src="<?= e(storage_url($other['hero_img'])) ?>" alt="<?= e($other['name']) ?>">
            <?php endif; ?>
          </a>
          <h3 class="other-room__name"><a href="room.php?slug=<?= e($other['slug']) ?>"><?= e($other['name']) ?></a></h3>
          <p class="other-room__meta">
            <?= e($other['size_sqm']) ?>M&sup2; &middot;
            1–<?= e($other['capacity']) ?> person &middot;
            <?= e($other['bed_count']) ?> bed<?= $other['bed_count'] > 1 ? 's' : '' ?>
          </p>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
