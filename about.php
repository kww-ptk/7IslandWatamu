<?php $pageTitle = 'About — Seven Islands Resort, Watamu'; $activeNav = 'about'; include __DIR__ . '/includes/header.php'; ?>

  <section class="about-hero">
    <div class="about-hero__slides" data-about-slides>
      <div class="about-hero__slide is-active" style="background-image:url('assets/img/7islands_resort_watamu_hero.webp')"></div>
      <div class="about-hero__slide" style="background-image:url('assets/img/7islands_resort_watamu5_Beach.webp')"></div>
      <div class="about-hero__slide" style="background-image:url('assets/img/7islands_resort_watamu6.webp')"></div>
      <div class="about-hero__slide" style="background-image:url('assets/img/7islands_resort_watamu1.jpg')"></div>
    </div>
    <div class="about-hero__content">
      <p class="about-hero__eyebrow">The Resort</p>
      <h1 class="about-hero__title">A seafront sanctuary in Watamu</h1>
    </div>
    <div class="about-hero__dots" data-about-dots>
      <button class="about-hero__dot is-active" type="button" aria-label="Show slide 1"></button>
      <button class="about-hero__dot" type="button" aria-label="Show slide 2"></button>
      <button class="about-hero__dot" type="button" aria-label="Show slide 3"></button>
      <button class="about-hero__dot" type="button" aria-label="Show slide 4"></button>
    </div>
  </section>

  <section class="facts">
    <div class="container">
      <ul class="facts__grid">
        <li class="fact">
          <span class="fact__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
          </span>
          <span class="fact__body">
            <strong class="fact__num" data-to="84">0</strong>
            <span class="fact__label">Rooms</span>
          </span>
        </li>
        <li class="fact">
          <span class="fact__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 2v7c0 1.1.9 2 2 2a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></svg>
          </span>
          <span class="fact__body">
            <strong class="fact__num" data-to="2">0</strong>
            <span class="fact__label">Restaurants</span>
          </span>
        </li>
        <li class="fact">
          <span class="fact__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 22h8"/><path d="M7 10h10"/><path d="M12 15v7"/><path d="M12 15a5 5 0 0 0 5-5c0-2-.5-4-2-8H9c-1.5 4-2 6-2 8a5 5 0 0 0 5 5Z"/></svg>
          </span>
          <span class="fact__body">
            <strong class="fact__num" data-to="3">0</strong>
            <span class="fact__label">Bars</span>
          </span>
        </li>
        <li class="fact">
          <span class="fact__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 6c.6.5 1.2 1 2.5 1C7 7 7 5 9.5 5c2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M2 12c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M2 18c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/></svg>
          </span>
          <span class="fact__body">
            <strong class="fact__num" data-to="3">0</strong>
            <span class="fact__label">Pools</span>
          </span>
        </li>
        <li class="fact">
          <span class="fact__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
          </span>
          <span class="fact__body">
            <strong class="fact__num" data-to="1">0</strong>
            <span class="fact__label">Spa</span>
          </span>
        </li>
      </ul>
      <div class="facts__live" data-resort-activity aria-live="polite">
        <span class="facts__live-dot" aria-hidden="true"></span>
        <span class="facts__live-label">Live</span>
        <span class="facts__live-feed">
          <span class="facts__live-icon" data-activity-icon aria-hidden="true"></span>
          <span class="facts__live-text" data-activity-text></span>
        </span>
      </div>
    </div>
  </section>

  <section class="section" id="story">
    <div class="container about-grid">
      <div class="about-grid__media">
        <img src="assets/img/7islands_resort_watamu6.webp" alt="Seven Islands Resort seafront">
      </div>
      <div class="about-grid__body">
        <p class="eyebrow">Our Story</p>
        <h2 class="tour-h2">An international resort on Kenya's coast</h2>
        <p class="room-p">A short walk along the low-tide beach from the village, Seven Islands Resort sits directly on the seafront — a place of ever-changing tides, shifting colours and the seven islands offshore.</p>
        <p class="room-p">Our team welcomes guests from around the world with the warm hospitality of the Kenyan coast, local and international cuisine, and a calm, unhurried sense of place. To be international is our power.</p>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="section-head">
        <p class="eyebrow">What we offer</p>
        <h2>Everything for a complete stay</h2>
        <p>From sea-view suites to safaris inland, every part of your holiday is taken care of in one place.</p>
      </div>
      <div class="rfeature-grid">
        <a class="rfeature" href="rooms.php">
          <div class="rfeature__media"><img src="assets/img/7islands_resort_watamu14.webp" alt="Rooms and suites"></div>
          <div class="rfeature__body">
            <span class="rfeature__tag">Stay</span>
            <h3 class="rfeature__name">Rooms &amp; Suites</h3>
            <p class="rfeature__meta">84 sea-view rooms with private balconies and Swahili interiors.</p>
            <span class="rfeature__cta">Explore <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg></span>
          </div>
        </a>
        <a class="rfeature" href="dining.php">
          <div class="rfeature__media"><img src="assets/img/7islands_resort_watamu8.webp" alt="Dining"></div>
          <div class="rfeature__body">
            <span class="rfeature__tag">Dining</span>
            <h3 class="rfeature__name">Restaurants &amp; Bars</h3>
            <p class="rfeature__meta">Two seaside restaurants and three bars serving fresh coastal cuisine.</p>
            <span class="rfeature__cta">Explore <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg></span>
          </div>
        </a>
        <a class="rfeature" href="spa.php">
          <div class="rfeature__media"><img src="assets/img/7islands_resort_watamu9.webp" alt="Spa and wellness"></div>
          <div class="rfeature__body">
            <span class="rfeature__tag">Wellness</span>
            <h3 class="rfeature__name">SPA &amp; Wellness</h3>
            <p class="rfeature__meta">A beachfront spa with a professional team and signature treatments.</p>
            <span class="rfeature__cta">Explore <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg></span>
          </div>
        </a>
        <a class="rfeature" href="tours.php">
          <div class="rfeature__media"><img src="assets/img/7islands_resort_watamu1.jpg" alt="Safari and excursions"></div>
          <div class="rfeature__body">
            <span class="rfeature__tag">Adventure</span>
            <h3 class="rfeature__name">Safari &amp; Excursion</h3>
            <p class="rfeature__meta">Classic safaris inland and day excursions along the coast.</p>
            <span class="rfeature__cta">Explore <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg></span>
          </div>
        </a>
        <a class="rfeature" href="contact.php">
          <div class="rfeature__media"><img src="assets/img/7islands_resort_watamu11.avif" alt="Conferences"></div>
          <div class="rfeature__body">
            <span class="rfeature__tag">Business</span>
            <h3 class="rfeature__name">Conferences</h3>
            <p class="rfeature__meta">A congress centre seating up to 300 guests for events and meetings.</p>
            <span class="rfeature__cta">Explore <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg></span>
          </div>
        </a>
        <a class="rfeature" href="contact.php">
          <div class="rfeature__media"><img src="assets/img/7islands_resort_watamu13.webp" alt="Weddings and events"></div>
          <div class="rfeature__body">
            <span class="rfeature__tag">Celebrate</span>
            <h3 class="rfeature__name">Weddings &amp; Events</h3>
            <p class="rfeature__meta">Beachfront ceremonies and celebrations against the Indian Ocean.</p>
            <span class="rfeature__cta">Explore <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg></span>
          </div>
        </a>
        <a class="rfeature" href="contact.php">
          <div class="rfeature__media"><img src="assets/img/7islands_resort_watamu2.webp" alt="Fitness gym"></div>
          <div class="rfeature__body">
            <span class="rfeature__tag">Fitness</span>
            <h3 class="rfeature__name">Gym</h3>
            <p class="rfeature__meta">A fully-equipped fitness room, open from early morning until late.</p>
            <span class="rfeature__cta">Explore <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg></span>
          </div>
        </a>
        <a class="rfeature" href="contact.php">
          <div class="rfeature__media"><img src="assets/img/7islands_resort_watamu5_Beach.webp" alt="Children's pool"></div>
          <div class="rfeature__body">
            <span class="rfeature__tag">Family</span>
            <h3 class="rfeature__name">Kids Pool</h3>
            <p class="rfeature__meta">A dedicated shallow pool where children splash and play safely in the sun.</p>
            <span class="rfeature__cta">Explore <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg></span>
          </div>
        </a>
        <a class="rfeature" href="contact.php">
          <div class="rfeature__media"><img src="assets/img/7islands_resort_watamu6.webp" alt="Kids club"></div>
          <div class="rfeature__body">
            <span class="rfeature__tag">Family</span>
            <h3 class="rfeature__name">Kids Club</h3>
            <p class="rfeature__meta">Supervised games, crafts and activities that keep children happy all day.</p>
            <span class="rfeature__cta">Explore <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg></span>
          </div>
        </a>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="section-head">
        <p class="eyebrow">Gallery</p>
        <h2>Moments around the resort</h2>
        <p>Turquoise water, white sand and warm Swahili interiors &mdash; a glimpse of life at Seven Islands.</p>
      </div>
      <div class="resort-gallery__grid">
        <figure class="gallery-tile gallery-tile--wide"><img src="assets/img/7islands_resort_watamu6.webp" alt="Palm-lined private beach"></figure>
        <figure class="gallery-tile"><img src="assets/img/7islands_resort_watamu3.webp" alt="Seafront swimming pool"></figure>
        <figure class="gallery-tile"><img src="assets/img/7islands_resort_watamu10.webp" alt="Ocean-view balcony"></figure>
        <figure class="gallery-tile"><img src="assets/img/7islands_resort_watamu12.webp" alt="Beach bar lounge"></figure>
        <figure class="gallery-tile"><img src="assets/img/7islands_resort_watamu14.webp" alt="Swahili-style room"></figure>
        <figure class="gallery-tile gallery-tile--wide"><img src="assets/img/7islands_resort_watamu1.jpg" alt="The seven islands offshore"></figure>
      </div>
    </div>
  </section>

  <section class="section reviews">
    <div class="container">
      <div class="section-head section-head--center">
        <p class="eyebrow">Guest Reviews</p>
        <h2>Loved by travellers worldwide</h2>
        <p>Rated excellent by 1,859 guests on Tripadvisor.</p>
      </div>
      <div class="reviews__grid">
        <article class="quote-card">
          <div class="review-stars" aria-label="5 out of 5 stars">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M12 2l3 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.9 21l1.2-6.8-5-4.9 6.9-1z"/></svg>
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M12 2l3 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.9 21l1.2-6.8-5-4.9 6.9-1z"/></svg>
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M12 2l3 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.9 21l1.2-6.8-5-4.9 6.9-1z"/></svg>
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M12 2l3 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.9 21l1.2-6.8-5-4.9 6.9-1z"/></svg>
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M12 2l3 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.9 21l1.2-6.8-5-4.9 6.9-1z"/></svg>
          </div>
          <p class="quote-card__text">"Very comfortable seafront location and functional structure, a 15-minute walk along the low-tide beach from the village. Great food: fresh fish every day and fruit at will."</p>
          <div class="quote-card__author">
            <span class="review-avatar">GO</span>
            <span><strong>giannaoll</strong><br>Novara, Italy</span>
          </div>
        </article>
        <article class="quote-card">
          <div class="review-stars" aria-label="5 out of 5 stars">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M12 2l3 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.9 21l1.2-6.8-5-4.9 6.9-1z"/></svg>
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M12 2l3 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.9 21l1.2-6.8-5-4.9 6.9-1z"/></svg>
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M12 2l3 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.9 21l1.2-6.8-5-4.9 6.9-1z"/></svg>
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M12 2l3 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.9 21l1.2-6.8-5-4.9 6.9-1z"/></svg>
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M12 2l3 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.9 21l1.2-6.8-5-4.9 6.9-1z"/></svg>
          </div>
          <p class="quote-card__text">"A wonderful place that takes your breath away. The islands, the tide, the ever-changing colours — an emotional mix that did not disappoint my expectations."</p>
          <div class="quote-card__author">
            <span class="review-avatar">PY</span>
            <span><strong>Patrizia Ylenia</strong><br>Traveller</span>
          </div>
        </article>
        <article class="quote-card">
          <div class="review-stars" aria-label="5 out of 5 stars">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M12 2l3 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.9 21l1.2-6.8-5-4.9 6.9-1z"/></svg>
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M12 2l3 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.9 21l1.2-6.8-5-4.9 6.9-1z"/></svg>
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M12 2l3 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.9 21l1.2-6.8-5-4.9 6.9-1z"/></svg>
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M12 2l3 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.9 21l1.2-6.8-5-4.9 6.9-1z"/></svg>
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M12 2l3 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.9 21l1.2-6.8-5-4.9 6.9-1z"/></svg>
          </div>
          <p class="quote-card__text">"A beautiful property and village. They offered us an aperitif and made check-in straight away. A spacious, clean and romantic room, a large pool and always-smiling staff."</p>
          <div class="quote-card__author">
            <span class="review-avatar">GA</span>
            <span><strong>Giovanni A</strong><br>Traveller</span>
          </div>
        </article>
      </div>
    </div>
  </section>

  <section class="tour-cta">
    <div class="container">
      <h2 class="tour-cta__title">Plan your stay in Watamu</h2>
      <p class="tour-cta__text">Our team is always open and ready to help you arrange the perfect holiday on the Kenyan coast.</p>
      <a class="btn btn--primary" href="contact.php">Contact Us</a>
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
