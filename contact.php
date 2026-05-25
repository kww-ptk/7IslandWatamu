<?php $pageTitle = 'Contact — Seven Islands Resort, Watamu'; $activeNav = 'contact'; include __DIR__ . '/includes/header.php'; ?>

  <section class="page-hero" style="background:linear-gradient(rgba(11,98,115,.5),rgba(11,98,115,.62)),url('assets/img/7islands_resort_watamu5_Beach.webp') center/cover no-repeat;">
    <div class="page-hero__inner">
      <p class="page-hero__eyebrow">Contact</p>
      <h1 class="page-hero__title">Get in touch</h1>
      <p class="page-hero__text">Our reception is always open. Reach out to plan your stay, ask a question, or arrange a safari — we are happy to help.</p>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="contact-cards">
        <div class="contact-card">
          <span class="contact-card__icon">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>
            </svg>
          </span>
          <h3 class="contact-card__title">Address</h3>
          <p>P.O. Box 424, Jacaranda Road<br>80202 Watamu, Kenya</p>
        </div>
        <div class="contact-card">
          <span class="contact-card__icon">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3-8.6A2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .3 1.9.6 2.8a2 2 0 0 1-.5 2.1L8 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.5 2.8.6a2 2 0 0 1 1.8 2.1z"/>
            </svg>
          </span>
          <h3 class="contact-card__title">Phone</h3>
          <p><a href="tel:+2540713326336">+254 0713 326 336</a></p>
        </div>
        <div class="contact-card">
          <span class="contact-card__icon">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>
            </svg>
          </span>
          <h3 class="contact-card__title">Email</h3>
          <p><a href="mailto:reservation@sevenislandswatamu.com">reservation@sevenislandswatamu.com</a></p>
        </div>
        <div class="contact-card">
          <span class="contact-card__icon">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/>
            </svg>
          </span>
          <h3 class="contact-card__title">Reception</h3>
          <p>Always open<br>24-hour reception service</p>
        </div>
      </div>
    </div>
  </section>

  <section class="section tour-section--alt" id="team">
    <div class="container about-grid">
      <div class="about-grid__media">
        <img src="assets/img/Team_7island_resort.jpg" alt="The Seven Islands Resort front office team">
      </div>
      <div class="about-grid__body">
        <p class="eyebrow">Our Team</p>
        <h2 class="tour-h2">A warm welcome, around the clock</h2>
        <p class="room-p">From the moment you arrive, our front-office and reservations team are here to make your stay effortless &mdash; booking excursions, arranging airport transfers, or simply pointing you to the best spot on the beach.</p>
        <p class="room-p">Reception is open 24 hours a day, every day. Whatever you need during your stay, a friendly face is always close by.</p>
      </div>
    </div>
  </section>

  <section class="section" id="enquiry">
    <div class="container">
      <div class="contact-grid">
        <div class="contact-form-wrap">
          <p class="eyebrow">Enquiry</p>
          <h2 class="tour-h2">Send us a message</h2>
          <form class="contact-form" onsubmit="return false">
            <div class="contact-form__row">
              <label class="field">
                <span>Full name</span>
                <input type="text" name="name" placeholder="Your name" required>
              </label>
              <label class="field">
                <span>Email</span>
                <input type="email" name="email" placeholder="Your email" required>
              </label>
            </div>
            <div class="contact-form__row">
              <label class="field">
                <span>Phone</span>
                <input type="tel" name="phone" placeholder="Your phone">
              </label>
              <label class="field">
                <span>Subject</span>
                <input type="text" name="subject" placeholder="How can we help?">
              </label>
            </div>
            <label class="field">
              <span>Message</span>
              <textarea name="message" rows="5" placeholder="Tell us about your stay" required></textarea>
            </label>
            <button type="submit" class="btn btn--primary">Send Message <span aria-hidden="true">&rsaquo;</span></button>
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
        <li class="partner-logo"><img src="assets/img/7islands_resort_watamu_Partner_crystalbay.jpg" alt="Crystal Bay Resort"></li>
        <li class="partner-logo"><img src="assets/img/7islands_resort_watamu_Partner_paparemobeach.jpg" alt="Papa Remo Beach"></li>
        <li class="partner-logo"><img src="assets/img/7islands_resort_watamu_Partner_watamu_golf.jpg" alt="Watamu Golf Club"></li>
        <li class="partner-logo"><img src="assets/img/7islands_resort_watamu_Partner-paparemovillage.jpg" alt="Papa Remo Village"></li>
      </ul>
    </div>
  </section>

<?php include __DIR__ . '/includes/footer.php'; ?>
