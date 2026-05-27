<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/db.php';

// ── Date search ──────────────────────────────────────────────
$check_in_raw  = trim($_GET['check_in']  ?? '');
$check_out_raw = trim($_GET['check_out'] ?? '');
$sb_adults     = max(1, (int)($_GET['adults']   ?? 2));
$sb_children   = max(0, (int)($_GET['children'] ?? 0));
$total_guests  = $sb_adults + $sb_children;

$search_mode = false;
$check_in    = '';
$check_out   = '';
if (
    preg_match('/^\d{4}-\d{2}-\d{2}$/', $check_in_raw) &&
    preg_match('/^\d{4}-\d{2}-\d{2}$/', $check_out_raw)
) {
    $ts_in  = strtotime($check_in_raw);
    $ts_out = strtotime($check_out_raw);
    if ($ts_in && $ts_out && $ts_out > $ts_in) {
        $search_mode = true;
        $check_in    = $check_in_raw;
        $check_out   = $check_out_raw;
    }
}

$pageTitle    = 'Rooms & Suites — Seven Islands Resort, Watamu';
$metaDesc     = 'Browse all rooms and suites at Seven Islands Resort in Watamu, Kenya — from classic rooms to luxury ocean suites, all fully inclusive.';
$activeNav    = 'rooms';
$canonicalUrl = site_url('rooms.php');
$extraScripts = ['assets/js/search-bar.js'];

$rooms = db_query(
    'SELECT r.*,
        (SELECT filename FROM room_images WHERE room_id = r.id AND is_hero = TRUE LIMIT 1) AS hero_img
     FROM rooms r
     WHERE r.is_published = TRUE
     ORDER BY r.sort_order ASC'
)->fetchAll();

// Run per-room availability check when dates are given
if ($search_mode) {
    foreach ($rooms as &$room) {
        if ($room['capacity'] > 0 && $total_guests > (int)$room['capacity']) {
            $room['avail'] = 'small';
        } else {
            $unit = find_available_unit((int)$room['id'], $check_in, $check_out);
            $room['avail'] = $unit ? 'available' : 'sold_out';
        }
    }
    unset($room);
}

$jsonLd = json_encode([
    '@context'    => 'https://schema.org',
    '@type'       => 'ItemList',
    'name'        => 'Rooms & Suites — Seven Islands Resort',
    'description' => $metaDesc,
    'url'         => $canonicalUrl,
    'itemListElement' => array_values(array_map(function ($r, $i) {
        return [
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'item'     => [
                '@type'       => 'HotelRoom',
                'name'        => $r['name'],
                'description' => $r['short_desc'] ?? '',
                'url'         => site_url('room.php?slug=' . urlencode($r['slug'])),
                'occupancy'   => $r['capacity'] ? ['@type' => 'QuantitativeValue', 'maxValue' => (int)$r['capacity']] : null,
                'floorSize'   => $r['size_sqm']  ? ['@type' => 'QuantitativeValue', 'value' => (int)$r['size_sqm'], 'unitCode' => 'MTK'] : null,
            ],
        ];
    }, $rooms, array_keys($rooms))),
]);

include __DIR__ . '/includes/header.php';
?>

  <section class="page-hero" style="background:linear-gradient(rgba(11,98,115,.5),rgba(11,98,115,.62)),url('assets/img/7islands_resort_watamu14.webp') center/cover no-repeat;">
    <div class="page-hero__inner">
      <p class="page-hero__eyebrow">Rooms &amp; Suites</p>
      <h1 class="page-hero__title">Find your perfect room</h1>
      <p class="page-hero__text">Eighty-four sea-view and garden rooms, each with a private balcony and warm Swahili interiors &mdash; choose the space that suits your stay.</p>
    </div>
  </section>

  <!-- Search bar -->
  <div class="search-strip">
    <div class="container search-strip__inner">
      <p class="search-strip__heading">Check availability for your dates</p>
      <?php include __DIR__ . '/includes/search-bar.php'; ?>
    </div>
  </div>

  <?php if ($search_mode):
    $nights      = (int)round((strtotime($check_out) - strtotime($check_in)) / 86400);
    $avail_count = count(array_filter($rooms, fn($r) => ($r['avail'] ?? '') === 'available'));
    $ci_fmt      = date('d M', strtotime($check_in));
    $co_fmt      = date('d M Y', strtotime($check_out));
    $guests_str  = $sb_adults . ' Adult' . ($sb_adults !== 1 ? 's' : '');
    if ($sb_children) $guests_str .= ', ' . $sb_children . ' Child' . ($sb_children !== 1 ? 'ren' : '');
  ?>
  <!-- Search results summary bar -->
  <div class="search-results-bar">
    <div class="container search-results-bar__inner">
      <p class="search-results-bar__text">
        <strong><?= $avail_count ?> room<?= $avail_count !== 1 ? 's' : '' ?> available</strong>
        &nbsp;&middot;&nbsp; <?= e($ci_fmt) ?> &ndash; <?= e($co_fmt) ?> (<?= $nights ?> night<?= $nights !== 1 ? 's' : '' ?>)
        &nbsp;&middot;&nbsp; <?= e($guests_str) ?>
      </p>
      <a class="search-results-bar__clear" href="rooms.php">&#10005; Clear search</a>
    </div>
  </div>
  <?php endif; ?>

  <section class="section">
    <div class="container">
      <?php if (!$search_mode): ?>
      <div class="section-head">
        <p class="eyebrow">Our Rooms</p>
        <h2>Six room types for every kind of stay</h2>
        <p>From a cosy double to a spacious family suite &mdash; every room opens onto the gardens or the Indian Ocean.</p>
      </div>
      <?php endif; ?>
      <div class="other-rooms__grid rooms-grid">
        <?php foreach ($rooms as $room):
          $avail      = $room['avail'] ?? null;
          $is_dimmed  = $avail === 'sold_out' || $avail === 'small';
          $room_url   = 'room.php?slug=' . urlencode($room['slug'])
                        . ($search_mode ? '&check_in=' . urlencode($check_in) . '&check_out=' . urlencode($check_out) : '');
        ?>
        <article class="other-room<?= $is_dimmed ? ' other-room--dimmed' : '' ?>">
          <a class="other-room__img" href="<?= e($room_url) ?>">
            <span class="other-room__price">
              <label>from</label>
              <strong><?= e($room['price_currency']) ?> <?= e(number_format((float)$room['price_amount'], 0)) ?></strong>
              <?= e($room['price_unit']) ?>
            </span>
            <?php if ($room['hero_img']): ?>
            <img src="<?= e(storage_url($room['hero_img'])) ?>" alt="<?= e($room['name']) ?>">
            <?php endif; ?>
          </a>
          <h3 class="other-room__name">
            <a href="<?= e($room_url) ?>"><?= e($room['name']) ?></a>
          </h3>
          <p class="other-room__meta">
            <?= e($room['size_sqm']) ?>M&sup2; &middot;
            1–<?= e($room['capacity']) ?> person &middot;
            <?= e($room['bed_count']) ?> bed<?= $room['bed_count'] > 1 ? 's' : '' ?>
          </p>
          <?php if ($avail === 'available'): ?>
          <p style="margin:6px 0 0">
            <span class="room-avail-badge room-avail-badge--available">&#10003; Available</span>
            <a href="<?= e($room_url) ?>" style="font-size:12px;color:#0b6273;margin-left:8px;font-weight:600">Select &rsaquo;</a>
          </p>
          <?php elseif ($avail === 'sold_out'): ?>
          <p style="margin:6px 0 0">
            <span class="room-avail-badge room-avail-badge--sold_out">Sold out for these dates</span>
          </p>
          <?php elseif ($avail === 'small'): ?>
          <p style="margin:6px 0 0">
            <span class="room-avail-badge room-avail-badge--small">Too small &mdash; max <?= e($room['capacity']) ?> guests</span>
          </p>
          <?php endif; ?>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="tour-cta">
    <div class="container">
      <h2 class="tour-cta__title">Ready to book your stay?</h2>
      <p class="tour-cta__text">Our reservations team is always open and happy to help you find the right room for your dates.</p>
      <a class="btn btn--primary" href="contact.php">Contact Us</a>
    </div>
  </section>

<?php include __DIR__ . '/includes/footer.php'; ?>
