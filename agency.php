<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/db.php';
$pageTitle    = 'Travel Agency — Seven Islands Resort, Watamu';
$metaDesc     = 'Travel agents and tour operators — register as a partner with Seven Islands Resort, Watamu. Competitive rates and dedicated support.';
$activeNav    = 'agency';
$canonicalUrl = site_url('agency.php');
$jsonLd       = json_encode([
    '@context'    => 'https://schema.org',
    '@type'       => 'WebPage',
    'name'        => 'Travel Agency Partners — Seven Islands Resort',
    'description' => $metaDesc,
    'url'         => $canonicalUrl,
    'mainEntity'  => [
        '@type'       => 'LodgingBusiness',
        'name'        => 'Seven Islands Resort',
        'url'         => site_url(),
        'description' => 'Partner with Seven Islands Resort, Watamu Kenya — competitive rates for travel agents and tour operators.',
        'address'     => ['@type' => 'PostalAddress', 'addressLocality' => 'Watamu', 'addressCountry' => 'KE'],
    ],
]);
include __DIR__ . '/includes/header.php';
?>

  <section class="page-hero" style="background:linear-gradient(rgba(11,98,115,.5),rgba(11,98,115,.62)),url('assets/img/7islands_resort_watamu5_Beach.webp') center/cover no-repeat;">
    <div class="page-hero__inner">
      <p class="page-hero__eyebrow">For the Trade</p>
      <h1 class="page-hero__title">Travel Agency Partners</h1>
      <p class="page-hero__text">Sell Seven Islands Resort with confidence. We work hand in hand with travel agencies and tour operators to bring guests to the Watamu coast — to be international is our power.</p>
    </div>
  </section>

  <section class="section">
    <div class="container about-grid">
      <div class="about-grid__media">
        <img src="assets/img/7islands_resort_watamu_hero.webp" alt="Seven Islands Resort">
      </div>
      <div class="about-grid__body">
        <p class="eyebrow">Partner With Us</p>
        <h2 class="tour-h2">A reliable partner on the Kenyan coast</h2>
        <p class="room-p">From independent agents to international operators, our trade partners trust Seven Islands Resort for honest availability, fast confirmations and the warm hospitality their clients remember.</p>
        <p class="room-p">We already work alongside partners such as Oltre l'Equatore, Crystal Bay Resort and Papa Remo Beach — and we would be glad to welcome your agency too.</p>
        <a class="btn btn--outline" href="#partner">Request agency rates <span aria-hidden="true">&rsaquo;</span></a>
      </div>
    </div>
  </section>

  <section class="section tour-section--alt">
    <div class="container">
      <p class="eyebrow">Why partner</p>
      <h2 class="tour-h2">What we offer agencies</h2>
      <div class="agency-grid">
        <article class="agency-card">
          <span class="agency-card__icon">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
          </span>
          <h3 class="agency-card__title">Competitive commission</h3>
          <p class="agency-card__text">Attractive, transparent commission on every confirmed booking, paid reliably.</p>
        </article>
        <article class="agency-card">
          <span class="agency-card__icon">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 18a4 4 0 0 0-8 0"/><circle cx="12" cy="8" r="3.5"/><path d="M3 20a6 6 0 0 1 5-5M21 20a6 6 0 0 0-5-5"/></svg>
          </span>
          <h3 class="agency-card__title">Dedicated trade support</h3>
          <p class="agency-card__text">A direct contact at the resort for quotes, bookings and on-the-ground assistance.</p>
        </article>
        <article class="agency-card">
          <span class="agency-card__icon">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 7h18M3 12h18M3 17h12"/></svg>
          </span>
          <h3 class="agency-card__title">Group &amp; FIT rates</h3>
          <p class="agency-card__text">Net rates for groups, series and individual travellers, plus tailored safari packages.</p>
        </article>
        <article class="agency-card">
          <span class="agency-card__icon">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 7l9-4 9 4-9 4-9-4zM3 7v7c0 2 4 4 9 4s9-2 9-4V7"/></svg>
          </span>
          <h3 class="agency-card__title">Familiarisation trips</h3>
          <p class="agency-card__text">Fam-trip invitations so your team can experience the resort and sell it first-hand.</p>
        </article>
        <article class="agency-card">
          <span class="agency-card__icon">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="14" rx="2"/><path d="M3 9h18M8 18v3M16 18v3"/></svg>
          </span>
          <h3 class="agency-card__title">Marketing materials</h3>
          <p class="agency-card__text">Up-to-date photos, fact sheets and brochures ready to use in your own channels.</p>
        </article>
        <article class="agency-card">
          <span class="agency-card__icon">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s-7-4.5-9.5-9A5 5 0 0 1 12 6a5 5 0 0 1 9.5 7C19 17.5 12 22 12 22z"/></svg>
          </span>
          <h3 class="agency-card__title">Happy clients</h3>
          <p class="agency-card__text">99% of our guests say they would return — your clients come back to you, too.</p>
        </article>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <p class="eyebrow">How it works</p>
      <h2 class="tour-h2">Four steps to start selling</h2>
      <div class="steps">
        <div class="step">
          <h3>Register</h3>
          <p>Send us your agency details and IATA or registration number.</p>
        </div>
        <div class="step">
          <h3>Get your rates</h3>
          <p>Receive net and commissionable rates plus our latest media kit.</p>
        </div>
        <div class="step">
          <h3>Book &amp; confirm</h3>
          <p>Request availability and get fast confirmations from our team.</p>
        </div>
        <div class="step">
          <h3>Earn commission</h3>
          <p>Your guests enjoy Watamu, and your commission is settled reliably.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="partners">
    <div class="container">
      <h2 class="partners__title">Trusted by guests &amp; travel partners</h2>
      <p class="partners__intro">Years of warm hospitality on the Watamu coast have earned us the trust of guests and travel professionals worldwide.</p>
      <div class="trust-row">
        <div class="trust-badge">
          <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true"><path d="M12 2l3 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.9 21l1.2-6.8-5-4.9 6.9-1z"/></svg>
          Tripadvisor Travelers' Choice
        </div>
        <div class="trust-badge">
          <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="9" r="6"/><path d="M9 14l-2 7 5-3 5 3-2-7"/></svg>
          4-Star Resort
        </div>
        <div class="trust-badge">
          <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s-7-4.5-9.5-9A5 5 0 0 1 12 6a5 5 0 0 1 9.5 7C19 17.5 12 22 12 22z"/></svg>
          2,800+ Happy Guests
        </div>
        <div class="trust-badge">
          <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12a9 9 0 1 1-3-6.7M21 4v5h-5"/></svg>
          99% Would Return
        </div>
      </div>
      <p class="partners__label">Our Partners</p>
      <ul class="partners__logos">
        <li>Crystal Bay Resort</li>
        <li>Papa Remo Beach</li>
        <li>Oltre l'Equatore</li>
        <li>Watamu Marine Park</li>
      </ul>
    </div>
  </section>

  <section class="section" id="partner">
    <div class="container">
      <div class="contact-grid">
        <div class="contact-form-wrap">
          <p class="eyebrow">Partner Registration</p>
          <h2 class="tour-h2">Become a partner</h2>
          <form class="contact-form" id="agencyForm" novalidate>
            <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">
            <div class="contact-form__row">
              <label class="field">
                <span>Your name</span>
                <input type="text" name="name" placeholder="Full name" required>
              </label>
              <label class="field">
                <span>Email</span>
                <input type="email" name="email" placeholder="Work email" required>
              </label>
            </div>
            <div class="contact-form__row">
              <label class="field">
                <span>Agency name</span>
                <input type="text" name="agency" placeholder="Your agency" required>
              </label>
              <label class="field">
                <span>Phone</span>
                <input type="tel" name="phone" placeholder="Your phone">
              </label>
            </div>
            <div class="contact-form__row">
              <label class="field">
                <span>IATA / Registration number</span>
                <input type="text" name="iata" placeholder="Optional">
              </label>
              <label class="field">
                <span>Country</span>
                <input type="text" name="country" placeholder="Your country">
              </label>
            </div>
            <label class="field">
              <span>Message</span>
              <textarea name="message" rows="4" placeholder="Tell us about your agency and clients"></textarea>
            </label>
            <div class="form-feedback" id="agencyFeedback" hidden></div>
            <?php if (captcha_site_key()): ?>
            <div class="h-captcha" data-sitekey="<?= e(captcha_site_key()) ?>"></div>
            <?php endif; ?>
            <button type="submit" class="btn btn--primary">Send Request <span aria-hidden="true">&rsaquo;</span></button>
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
