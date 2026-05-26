<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/db.php';

$slug = trim($_GET['slug'] ?? '');
$tour = $slug ? fetch_tour_by_slug($slug) : false;

if (!$tour) {
    http_response_code(404);
    $pageTitle = '404 — Tour Not Found';
    include __DIR__ . '/includes/header.php';
    echo '<section class="section"><div class="container" style="text-align:center;padding:4rem 0">
        <h1>Tour not found</h1>
        <p>The tour you are looking for does not exist or is no longer available.</p>
        <a class="btn btn--primary" href="tours.php">View All Tours</a>
    </div></section>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

$images   = fetch_tour_images((int)$tour['id']);
$hero_img = '';
foreach ($images as $img) {
    if ($img['is_hero']) { $hero_img = $img['filename']; break; }
}
if (!$hero_img && !empty($images)) $hero_img = $images[0]['filename'];
$hero_src = $hero_img ? storage_url($hero_img) : 'assets/img/7islands_resort_watamu1.jpg';

$highlights = json_decode($tour['highlights_json'] ?? '[]', true) ?: [];

$pageTitle    = $tour['seo_title']       ?: e($tour['name']) . ' — Seven Islands Resort, Watamu';
$metaDesc     = $tour['seo_description'] ?: '';
$activeNav    = 'tours';
$canonicalUrl = site_url('tour.php?slug=' . urlencode($tour['slug']));
$ogImage      = $hero_img ? site_url('assets/img/' . $hero_img) : site_url('assets/img/7islands_resort_watamu1.jpg');
$jsonLd       = json_encode([
    '@context'    => 'https://schema.org',
    '@type'       => 'TouristTrip',
    'name'        => $tour['name'],
    'description' => $tour['short_desc'] ?? '',
    'url'         => $canonicalUrl,
    'image'       => $ogImage,
    'touristType' => $tour['tag_label'] ?? 'Safari',
    'provider'    => [
        '@type' => 'LodgingBusiness',
        'name'  => 'Seven Islands Resort',
        'url'   => site_url(),
    ],
]);

include __DIR__ . '/includes/header.php';
?>

  <section class="page-hero" style="background:linear-gradient(rgba(11,98,115,.5),rgba(11,98,115,.62)),url('<?= e($hero_src) ?>') center/cover no-repeat;">
    <div class="page-hero__inner">
      <?php if ($tour['tag_label']): ?>
      <p class="page-hero__eyebrow"><?= e($tour['tag_label']) ?></p>
      <?php endif; ?>
      <h1 class="page-hero__title"><?= e($tour['name']) ?></h1>
      <?php if ($tour['duration']): ?>
      <ul class="page-hero__meta">
        <li>
          <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/>
          </svg>
          <?= e($tour['duration']) ?>
        </li>
      </ul>
      <?php endif; ?>
    </div>
  </section>

  <?php if (count($images) > 1): ?>
  <div class="room-gallery" data-gal-viewport>
    <div class="room-gallery__track" data-gal-track>
      <?php foreach ($images as $img): ?>
      <div class="room-gallery__slide">
        <img src="<?= e(storage_url($img['filename'])) ?>" alt="<?= e($img['alt_text'] ?: $tour['name']) ?>" loading="lazy">
      </div>
      <?php endforeach; ?>
    </div>
    <button class="room-gallery__arrow room-gallery__arrow--prev" data-gal-prev aria-label="Previous image">&#8592;</button>
    <button class="room-gallery__arrow room-gallery__arrow--next" data-gal-next aria-label="Next image">&#8594;</button>
  </div>
  <?php endif; ?>

  <div class="container room-main">
    <div class="room-content">
      <p class="room-p room-p--lead"><?= e($tour['short_desc'] ?? '') ?></p>
      <?php if ($tour['long_desc']): ?>
      <div class="room-p"><?= nl2br(e($tour['long_desc'])) ?></div>
      <?php endif; ?>

      <?php if ($highlights): ?>
      <h2 class="room-h2">Highlights</h2>
      <ul class="amen-grid">
        <?php foreach ($highlights as $h): ?>
        <li>
          <span><?= e($h) ?></span>
          <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M20 6L9 17l-5-5"/>
          </svg>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>
    </div>

    <aside class="room-booking" id="enquire">
      <div class="booking-card">
        <h3 class="booking-card__title">Enquire about this tour</h3>
        <form class="room-enquiry-form" id="tourEnquiryForm" novalidate
              data-tour-slug="<?= e($tour['slug']) ?>"
              data-tour-name="<?= e($tour['name']) ?>">

          <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">

          <label class="booking-field">
            <span>Your name</span>
            <input type="text" name="name" placeholder="Full name" required>
          </label>
          <label class="booking-field">
            <span>Email</span>
            <input type="email" name="email" placeholder="Your email" required>
          </label>
          <label class="booking-field">
            <span>Phone</span>
            <input type="tel" name="phone" placeholder="Your phone">
          </label>
          <label class="booking-field">
            <span>Preferred dates</span>
            <input type="text" name="checkin" placeholder="e.g. July 10–14, 2026">
          </label>
          <label class="booking-field">
            <span>Message</span>
            <textarea name="message" rows="3" placeholder="Any questions or special requests?"></textarea>
          </label>

          <div class="form-feedback" id="tourEnquiryFeedback" hidden></div>

          <button type="submit" class="btn btn--primary booking-card__submit">
            Send Enquiry <span aria-hidden="true">&rsaquo;</span>
          </button>
        </form>
      </div>
    </aside>
  </div>

  <section class="tour-cta">
    <div class="container">
      <h2 class="tour-cta__title">Plan your perfect safari</h2>
      <p class="tour-cta__text">Our team is available every day to help you plan your itinerary, arrange transfers, and make your trip unforgettable.</p>
      <a class="btn btn--primary" href="tel:+2540713326336">Call +254 0713 326 336</a>
    </div>
  </section>

<script>
document.addEventListener("DOMContentLoaded", function() {
  var form = document.getElementById("tourEnquiryForm");
  if (!form) return;
  var feedback = document.getElementById("tourEnquiryFeedback");

  form.addEventListener("submit", async function(e) {
    e.preventDefault();
    var btn = form.querySelector("[type=submit]");
    btn.disabled = true;
    btn.textContent = "Sending…";
    if (feedback) feedback.hidden = true;

    var data = {};
    new FormData(form).forEach(function(v, k) { data[k] = v; });
    data.tour_slug = form.dataset.tourSlug || "";
    data.tour_name = form.dataset.tourName || "";

    try {
      var res  = await fetch("/api/submit-enquiry.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify(data)
      });
      var json = await res.json();
      if (json.ok) {
        form.innerHTML = '<p class="form-success">Thank you! We will be in touch shortly.</p>';
      } else {
        btn.disabled = false;
        btn.textContent = "Send Enquiry ›";
        if (feedback) {
          feedback.hidden = false;
          feedback.className = "form-feedback form-feedback--err";
          feedback.textContent = json.error || "Something went wrong. Please try again.";
        }
      }
    } catch {
      btn.disabled = false;
      btn.textContent = "Send Enquiry ›";
      if (feedback) {
        feedback.hidden = false;
        feedback.className = "form-feedback form-feedback--err";
        feedback.textContent = "Network error. Please check your connection.";
      }
    }
  });
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
