<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/db.php';
$pageTitle    = 'Dining — Seven Islands Resort, Watamu';
$metaDesc     = 'Savour fresh seafood, Swahili flavours and international cuisine at Seven Islands Resort — beachfront dining on the coast of Watamu, Kenya.';
$activeNav    = 'dining';
$canonicalUrl = site_url('dining.php');
$jsonLd       = json_encode([
    '@context'    => 'https://schema.org',
    '@type'       => 'Restaurant',
    'name'        => 'Seven Islands Resort — Dining',
    'description' => $metaDesc,
    'url'         => $canonicalUrl,
    'image'       => site_url('assets/img/7islands_resort_watamu8.webp'),
    'servesCuisine' => ['Seafood', 'Swahili', 'International'],
    'containedInPlace' => [
        '@type' => 'LodgingBusiness',
        'name'  => 'Seven Islands Resort',
        'url'   => site_url(),
    ],
]);
include __DIR__ . '/includes/header.php';
?>

  <section class="page-hero" style="background:linear-gradient(rgba(11,98,115,.5),rgba(11,98,115,.62)),url('assets/img/7islands_resort_watamu8.webp') center/cover no-repeat;">
    <div class="page-hero__inner">
      <p class="page-hero__eyebrow">Fine Dining</p>
      <h1 class="page-hero__title">Dining</h1>
      <p class="page-hero__text">Two seaside restaurants and three bars — a well-stocked buffet or an à la carte menu, fresh fish every day for lunch and dinner, tropical fruit at will, and good espresso at the pool bar.</p>
    </div>
  </section>

  <section class="section tour-section">
    <div class="container">
      <p class="eyebrow">Restaurants</p>
      <h2 class="tour-h2">Where to eat</h2>
      <div class="tour-grid tour-grid--feature">
        <article class="tour-card">
          <span class="tour-card__media"><img src="assets/img/7islands_resort_watamu8.webp" alt="Buffet Restaurant"></span>
          <div class="tour-card__body">
            <span class="tour-card__tag">Seaside Restaurant</span>
            <h3 class="tour-card__name">The Buffet Restaurant</h3>
            <p class="tour-card__detail">A well-stocked buffet to satisfy every taste, served with the courtesy and warmth of our staff — fresh fish daily and tropical fruit at will.</p>
            <span class="tour-card__detail tour-card__hours">Breakfast &middot; Lunch &middot; Dinner</span>
          </div>
        </article>
        <article class="tour-card">
          <span class="tour-card__media"><img src="assets/img/7islands_resort_watamu11.avif" alt="A la carte Restaurant"></span>
          <div class="tour-card__body">
            <span class="tour-card__tag">Seaside Restaurant</span>
            <h3 class="tour-card__name">À la Carte Restaurant</h3>
            <p class="tour-card__detail">An intimate seafront setting for a slower evening — local and international dishes prepared to order, with the ocean as your view.</p>
            <span class="tour-card__detail tour-card__hours">Dinner &middot; Reservation recommended</span>
          </div>
        </article>
      </div>
    </div>
  </section>

  <section class="section tour-section--alt">
    <div class="container">
      <p class="eyebrow">Bars</p>
      <h2 class="tour-h2">Three bars by the water</h2>
      <div class="tour-grid tour-grid--compact">
        <article class="exc-card">
          <span class="exc-card__media"><img src="assets/img/7islands_resort_watamu3.webp" alt="Pool Bar"></span>
          <h3 class="exc-card__name">Pool Bar</h3>
          <p class="exc-card__meta">Cocktails and good espresso by the main pool</p>
        </article>
        <article class="exc-card">
          <span class="exc-card__media"><img src="assets/img/7islands_resort_watamu12.webp" alt="Beach Bar"></span>
          <h3 class="exc-card__name">Beach Bar</h3>
          <p class="exc-card__meta">Drinks and light bites steps from the sand</p>
        </article>
        <article class="exc-card">
          <span class="exc-card__media"><img src="assets/img/7islands_resort_watamu2.webp" alt="Lounge Bar"></span>
          <h3 class="exc-card__name">Lounge Bar</h3>
          <p class="exc-card__meta">Sunset aperitifs, music and theme evenings</p>
        </article>
      </div>
    </div>
  </section>

  <section class="tour-cta">
    <div class="container">
      <h2 class="tour-cta__title">Reserve your table</h2>
      <p class="tour-cta__text">Planning a special dinner or have a dietary request? Let our team know and we will take care of it.</p>
      <a class="btn btn--primary" href="contact.php">Contact Us</a>
    </div>
  </section>

<?php include __DIR__ . '/includes/footer.php'; ?>
