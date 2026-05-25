<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/db.php';

$pageTitle = 'Rooms &amp; Suites — Seven Islands Resort, Watamu';
$activeNav = 'rooms';
include __DIR__ . '/includes/header.php';

$rooms = db_query(
    'SELECT r.*,
        (SELECT filename FROM room_images WHERE room_id = r.id AND is_hero = TRUE LIMIT 1) AS hero_img
     FROM rooms r
     WHERE r.is_published = TRUE
     ORDER BY r.sort_order ASC'
)->fetchAll();
?>

  <section class="page-hero" style="background:linear-gradient(rgba(11,98,115,.5),rgba(11,98,115,.62)),url('assets/img/7islands_resort_watamu14.webp') center/cover no-repeat;">
    <div class="page-hero__inner">
      <p class="page-hero__eyebrow">Rooms &amp; Suites</p>
      <h1 class="page-hero__title">Find your perfect room</h1>
      <p class="page-hero__text">Eighty-four sea-view and garden rooms, each with a private balcony and warm Swahili interiors &mdash; choose the space that suits your stay.</p>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="section-head">
        <p class="eyebrow">Our Rooms</p>
        <h2>Six room types for every kind of stay</h2>
        <p>From a cosy double to a spacious family suite &mdash; every room opens onto the gardens or the Indian Ocean.</p>
      </div>
      <div class="other-rooms__grid rooms-grid">
        <?php foreach ($rooms as $room): ?>
        <article class="other-room">
          <a class="other-room__img" href="room.php?slug=<?= e($room['slug']) ?>">
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
            <a href="room.php?slug=<?= e($room['slug']) ?>"><?= e($room['name']) ?></a>
          </h3>
          <p class="other-room__meta">
            <?= e($room['size_sqm']) ?>M&sup2; &middot;
            1–<?= e($room['capacity']) ?> person &middot;
            <?= e($room['bed_count']) ?> bed<?= $room['bed_count'] > 1 ? 's' : '' ?>
          </p>
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
