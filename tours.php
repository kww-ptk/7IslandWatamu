<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/db.php';

$pageTitle    = 'Safari & Excursion — Seven Islands Resort, Watamu';
$metaDesc     = 'Explore Kenya from Seven Islands Resort — classic safaris to Tsavo and the Masai Mara, custom journeys, and day excursions along the Watamu coast.';
$activeNav    = 'tours';
$canonicalUrl = site_url('tours.php');
$ogImage      = site_url('assets/img/7islands_resort_watamu1.jpg');
$jsonLd       = json_encode([
    '@context'    => 'https://schema.org',
    '@type'       => 'TouristAttraction',
    'name'        => 'Safaris & Excursions — Seven Islands Resort',
    'description' => 'Classic safaris, custom journeys, and day excursions from Watamu, Kenya.',
    'url'         => site_url('tours.php'),
    'address'     => ['@type' => 'PostalAddress', 'addressLocality' => 'Watamu', 'addressCountry' => 'KE'],
]);

// Fetch tours from DB (graceful fallback if DB unavailable)
$allTours = [];
try {
    $allTours = db_query(
        'SELECT t.*, (SELECT filename FROM tour_images WHERE tour_id = t.id AND is_hero = TRUE LIMIT 1) AS hero_img
         FROM tours t WHERE t.is_published = TRUE ORDER BY t.sort_order ASC'
    )->fetchAll();
} catch (Throwable) {}

$classics   = array_values(array_filter($allTours, fn($t) => $t['category'] === 'classic'));
$customs    = array_values(array_filter($allTours, fn($t) => $t['category'] === 'custom'));
$excursions = array_values(array_filter($allTours, fn($t) => $t['category'] === 'excursion'));

$fallbackImgs = [
    'assets/img/7islands_resort_watamu1.jpg',
    'assets/img/7islands_resort_watamu6.webp',
    'assets/img/7islands_resort_watamu3.webp',
    'assets/img/7islands_resort_watamu5_Beach.webp',
    'assets/img/7islands_resort_watamu12.webp',
    'assets/img/7islands_resort_watamu2.webp',
];

function tour_card_img(array $t, int $idx, array $fallbacks): string {
    return $t['hero_img'] ? storage_url($t['hero_img']) : $fallbacks[$idx % count($fallbacks)];
}

include __DIR__ . '/includes/header.php';
?>

  <section class="page-hero" style="background:linear-gradient(rgba(11,98,115,.5),rgba(11,98,115,.62)),url('assets/img/7islands_resort_watamu1.jpg') center/cover no-repeat;">
    <div class="page-hero__inner">
      <p class="page-hero__eyebrow">Explore Kenya</p>
      <h1 class="page-hero__title">Safari &amp; Excursion</h1>
      <p class="page-hero__text">From the shores of Watamu, set out across Kenya — the great parks of Tsavo and Amboseli, the plains of the Masai Mara, the reefs of the Marine Park and the ruins of Gede. Our team arranges classic safaris, custom journeys and day excursions for every guest.</p>
    </div>
  </section>

<?php if ($classics): ?>
  <section class="section tour-section">
    <div class="container">
      <p class="eyebrow">Classics</p>
      <h2 class="tour-h2">Classic safari routes</h2>
      <div class="tour-grid tour-grid--feature">
        <?php foreach ($classics as $i => $t): ?>
        <article class="tour-card">
          <a class="tour-card__media" href="tour.php?slug=<?= urlencode($t['slug']) ?>">
            <img src="<?= e(tour_card_img($t, $i, $fallbackImgs)) ?>" alt="<?= e($t['name']) ?>">
          </a>
          <div class="tour-card__body">
            <span class="tour-card__tag"><?= e($t['tag_label'] ?: 'Classic Safari') ?></span>
            <h3 class="tour-card__name"><?= e($t['name']) ?></h3>
            <p class="tour-card__detail"><?= e($t['short_desc'] ?? '') ?></p>
            <a class="tour-card__link" href="tour.php?slug=<?= urlencode($t['slug']) ?>">Learn more <span aria-hidden="true">&rsaquo;</span></a>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
<?php endif; ?>

<?php if ($customs): ?>
  <section class="section tour-section tour-section--alt">
    <div class="container">
      <p class="eyebrow">Custom</p>
      <h2 class="tour-h2">Custom journeys</h2>
      <div class="tour-grid tour-grid--feature">
        <?php foreach ($customs as $i => $t): ?>
        <article class="tour-card">
          <a class="tour-card__media" href="tour.php?slug=<?= urlencode($t['slug']) ?>">
            <img src="<?= e(tour_card_img($t, $i + 4, $fallbackImgs)) ?>" alt="<?= e($t['name']) ?>">
          </a>
          <div class="tour-card__body">
            <span class="tour-card__tag"><?= e($t['tag_label'] ?: 'Custom Journey') ?></span>
            <h3 class="tour-card__name"><?= e($t['name']) ?></h3>
            <p class="tour-card__detail"><?= e($t['short_desc'] ?? '') ?></p>
            <a class="tour-card__link" href="tour.php?slug=<?= urlencode($t['slug']) ?>">Learn more <span aria-hidden="true">&rsaquo;</span></a>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
<?php endif; ?>

<?php if ($excursions): ?>
  <section class="section tour-section">
    <div class="container">
      <p class="eyebrow">Excursions</p>
      <h2 class="tour-h2">Day excursions</h2>
      <div class="tour-grid tour-grid--compact">
        <?php foreach ($excursions as $i => $t): ?>
        <article class="exc-card">
          <a class="exc-card__media" href="tour.php?slug=<?= urlencode($t['slug']) ?>">
            <img src="<?= e(tour_card_img($t, $i, $fallbackImgs)) ?>" alt="<?= e($t['name']) ?>">
          </a>
          <h3 class="exc-card__name"><?= e($t['name']) ?></h3>
          <?php if ($t['duration'] || $t['short_desc']): ?>
          <p class="exc-card__meta"><?= e($t['duration'] ?: $t['short_desc'] ?? '') ?></p>
          <?php endif; ?>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
<?php endif; ?>

<?php if (!$allTours): ?>
  <section class="section tour-section">
    <div class="container" style="text-align:center;padding:3rem 0">
      <p class="eyebrow">Coming soon</p>
      <h2 class="tour-h2">Tours will be published shortly</h2>
      <p style="color:var(--text-light);margin-bottom:2rem">Our team is adding the full tour programme. In the meantime, contact us to plan your safari.</p>
    </div>
  </section>
<?php endif; ?>

  <section class="section" id="enquire">
    <div class="container">
      <div class="contact-grid">
        <div class="contact-form-wrap">
          <p class="eyebrow">Plan your safari</p>
          <h2 class="tour-h2">Tell us what you'd like to see</h2>
          <p class="room-p" style="margin-bottom:2rem">Our team will build your itinerary and handle every detail — from park permits to transport and accommodation.</p>
          <form class="contact-form" id="toursContactForm" novalidate>
            <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">
            <div class="contact-form__row">
              <label class="field">
                <span>Full name</span>
                <input type="text" name="name" placeholder="Your name" required>
              </label>
              <label class="field">
                <span>Email</span>
                <input type="email" name="email" placeholder="you@email.com" required>
              </label>
            </div>
            <label class="field">
              <span>Phone</span>
              <input type="tel" name="phone" placeholder="+254 700 000 000">
            </label>
            <label class="field">
              <span>Which safari or excursion interests you?</span>
              <input type="text" name="subject" placeholder="e.g. Tsavo East, Masai Mara, day excursion…">
            </label>
            <label class="field">
              <span>Message</span>
              <textarea name="message" rows="4" placeholder="Tell us your dates, group size, and any special requests"></textarea>
            </label>
            <div class="form-feedback" id="toursContactFeedback" hidden></div>
            <?php if (captcha_site_key()): ?>
            <div class="h-captcha" data-sitekey="<?= e(captcha_site_key()) ?>"></div>
            <?php endif; ?>
            <button type="submit" class="btn btn--primary">Send Enquiry <span aria-hidden="true">&rsaquo;</span></button>
          </form>
        </div>
        <div class="contact-info">
          <h3 class="contact-info__title">Get in touch directly</h3>
          <ul class="contact-info__list">
            <li>
              <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3-8.6A2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .3 1.9.6 2.8a2 2 0 0 1-.5 2.1L8 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.5 2.8.6a2 2 0 0 1 1.8 2.1z"/>
              </svg>
              <a href="tel:+2540713326336">+254 0713 326 336</a>
            </li>
            <li>
              <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>
              </svg>
              <a href="mailto:reservation@sevenislandswatamu.com">reservation@sevenislandswatamu.com</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </section>

<?php include __DIR__ . '/includes/footer.php'; ?>
