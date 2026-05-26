<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/db.php';
$pageTitle    = 'SPA &amp; Wellness — Seven Islands Resort, Watamu';
$metaDesc     = 'Relax and restore at the Seven Islands Resort spa — beachside massages, wellness treatments and a tranquil pool overlooking the Indian Ocean.';
$activeNav    = 'spa';
$canonicalUrl = site_url('spa.php');
include __DIR__ . '/includes/header.php';
?>

  <section class="page-hero" style="background:linear-gradient(rgba(11,98,115,.5),rgba(11,98,115,.62)),url('assets/img/7islands_resort_watamu9.webp') center/cover no-repeat;">
    <div class="page-hero__inner">
      <p class="page-hero__eyebrow">Wellness</p>
      <h1 class="page-hero__title">SPA &amp; Wellness</h1>
      <p class="page-hero__text">Just a few steps from the front desk, our spa offers a calm retreat. A professional team, with an Italian presence on site, takes care of every guest's well-being and relaxation.</p>
    </div>
  </section>

  <section class="section">
    <div class="container about-grid">
      <div class="about-grid__media">
        <img src="assets/img/7islands_resort_watamu9.webp" alt="Seven Islands SPA">
      </div>
      <div class="about-grid__body">
        <p class="eyebrow">The SPA</p>
        <h2 class="tour-h2">Rebalance yourself in a timeless space</h2>
        <p class="room-p">Set within the resort, the spa is a quiet space designed for slowing down — soft light, gentle scents and the sound of the ocean nearby.</p>
        <p class="room-p">Our therapists tailor every treatment to the guest, from deep relaxation to active recovery after a day of safari, diving or sun.</p>
        <a class="btn btn--outline" href="contact.php">Book a treatment <span aria-hidden="true">&rsaquo;</span></a>
      </div>
    </div>
  </section>

  <section class="section tour-section--alt">
    <div class="container">
      <p class="eyebrow">Treatments</p>
      <h2 class="tour-h2">Spa menu</h2>
      <div class="tour-grid tour-grid--compact">
        <article class="exc-card">
          <span class="exc-card__media"><img src="assets/img/7islands_resort_watamu9.webp" alt="Massage"></span>
          <h3 class="exc-card__name">Signature Massage</h3>
          <p class="exc-card__meta">Full-body relaxation, 60 or 90 minutes</p>
        </article>
        <article class="exc-card">
          <span class="exc-card__media"><img src="assets/img/7islands_resort_watamu2.webp" alt="Facial"></span>
          <h3 class="exc-card__name">Facial Care</h3>
          <p class="exc-card__meta">Cleansing and hydrating facial rituals</p>
        </article>
        <article class="exc-card">
          <span class="exc-card__media"><img src="assets/img/7islands_resort_watamu5_Beach.webp" alt="Body scrub"></span>
          <h3 class="exc-card__name">Body Scrub &amp; Wrap</h3>
          <p class="exc-card__meta">Exfoliating treatments with coastal botanicals</p>
        </article>
        <article class="exc-card">
          <span class="exc-card__media"><img src="assets/img/7islands_resort_watamu10.webp" alt="Manicure and pedicure"></span>
          <h3 class="exc-card__name">Manicure &amp; Pedicure</h3>
          <p class="exc-card__meta">Hand and foot care in a calm setting</p>
        </article>
      </div>
    </div>
  </section>

  <section class="section" id="book-treatment">
    <div class="container">
      <div class="contact-grid">
        <div class="contact-form-wrap">
          <p class="eyebrow">Book a Treatment</p>
          <h2 class="tour-h2">Reserve your spa time</h2>
          <p class="room-p" style="margin-bottom:2rem">Let us know which treatment you have in mind and when you would like to come in. Our spa team will confirm your booking directly.</p>
          <form class="contact-form" id="spaContactForm" novalidate>
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
            <div class="contact-form__row">
              <label class="field">
                <span>Phone</span>
                <input type="tel" name="phone" placeholder="+254 700 000 000">
              </label>
              <label class="field">
                <span>Preferred date</span>
                <input type="date" name="subject" min="<?= date('Y-m-d') ?>">
              </label>
            </div>
            <label class="field">
              <span>Treatment &amp; any requests</span>
              <textarea name="message" rows="4" placeholder="Which treatment would you like? Any preferences or health notes?" required></textarea>
            </label>
            <div class="form-feedback" id="spaContactFeedback" hidden></div>
            <button type="submit" class="btn btn--primary">Request Booking <span aria-hidden="true">&rsaquo;</span></button>
          </form>
        </div>
        <div class="contact-map">
          <iframe
            title="Seven Islands Resort location, Watamu"
            src="https://www.google.com/maps?q=Watamu,+Kenya&output=embed"
            loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
      </div>
    </div>
  </section>

<?php include __DIR__ . '/includes/footer.php'; ?>
