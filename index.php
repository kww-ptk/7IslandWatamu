<?php $pageTitle = 'Seven Islands Resort — Watamu, Kenya'; $activeNav = 'home'; $headerSolid = false; include __DIR__ . '/includes/header.php'; ?>
  <section class="hero" id="top">
    <div class="container hero__inner">
      <p class="hero__text">Rated #1 all-inclusive resort in Watamu, Kenya</p>
      <h1 class="hero__title">7 Islands Resort, Watamu, Kenya</h1>
      <form class="hero-search" id="enquiryForm">
        <div class="hero-step" data-step="1">
          <div class="hero-search__field">
            <label for="enqCheckin">Check in</label>
            <input type="date" id="enqCheckin" name="checkin" min="<?= date('Y-m-d') ?>">
          </div>
          <div class="hero-search__field">
            <label for="enqCheckout">Check out</label>
            <input type="date" id="enqCheckout" name="checkout" min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
          </div>
          <div class="hero-search__field hero-search__field--guests">
            <label>Guests</label>
            <button type="button" class="hero-search__guests" data-guests-toggle aria-expanded="false">
              <span data-guests-summary>1 Adult</span>
              <i class="hero-search__caret">&#9662;</i>
            </button>
            <div class="guests-popover" data-guests-popover hidden>
              <div class="guests-row">
                <span class="guests-row__label">Adult</span>
                <div class="guests-stepper">
                  <button type="button" data-step="adult" data-dir="-1" aria-label="Decrease adults">&minus;</button>
                  <span data-count="adult">1</span>
                  <button type="button" data-step="adult" data-dir="1" aria-label="Increase adults">+</button>
                </div>
              </div>
              <div class="guests-row">
                <span class="guests-row__label">Children</span>
                <div class="guests-stepper">
                  <button type="button" data-step="child" data-dir="-1" aria-label="Decrease children">&minus;</button>
                  <span data-count="child">0</span>
                  <button type="button" data-step="child" data-dir="1" aria-label="Increase children">+</button>
                </div>
              </div>
            </div>
          </div>
          <button type="button" class="hero-search__submit" data-enq-next>Enquire Now <span aria-hidden="true">&rsaquo;</span></button>
        </div>

        <div class="hero-step hero-step--enquiry" data-step="2" hidden>
          <div class="hero-enq-grid">
            <div class="hero-search__field">
              <label for="enqName">Full name</label>
              <input type="text" id="enqName" name="name" placeholder="Your name" required>
            </div>
            <div class="hero-search__field">
              <label for="enqEmail">Email</label>
              <input type="email" id="enqEmail" name="email" placeholder="you@email.com" required>
            </div>
            <div class="hero-search__field">
              <label for="enqPhone">Phone</label>
              <input type="tel" id="enqPhone" name="phone" placeholder="+254 700 000 000">
            </div>
            <div class="hero-search__field">
              <label for="enqMsg">Message</label>
              <input type="text" id="enqMsg" name="message" placeholder="Tell us about your stay">
            </div>
          </div>
          <div class="hero-enq-actions">
            <button type="button" class="hero-enq-back" data-enq-back>&#8592; Back</button>
            <button type="submit" class="hero-search__submit" data-enq-send>Send Enquiry <span aria-hidden="true">&rsaquo;</span></button>
          </div>
        </div>

        <div class="hero-step hero-step--done" data-step="3" hidden>
          <strong>Thank you for your enquiry!</strong>
          <p>Your email is ready to send &mdash; our team will reply within 24 hours.</p>
        </div>
      </form>
    </div>
    <a class="hero__scroll" href="#resort" aria-label="Scroll down">&#8595;</a>
  </section>
  <section class="section resort" id="resort">
    <div class="container">
      <div class="resort__grid">
        <div class="resort__body">
          <div class="resort__eyebrow">
            <span class="resort__badge">
              <svg viewBox="0 0 64 64" width="62" height="62" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1">
                <circle cx="54" cy="32" r="5"/><circle cx="51.05" cy="43" r="5"/><circle cx="43" cy="51.05" r="5"/>
                <circle cx="32" cy="54" r="5"/><circle cx="21" cy="51.05" r="5"/><circle cx="12.95" cy="43" r="5"/>
                <circle cx="10" cy="32" r="5"/><circle cx="12.95" cy="21" r="5"/><circle cx="21" cy="12.95" r="5"/>
                <circle cx="32" cy="10" r="5"/><circle cx="43" cy="12.95" r="5"/><circle cx="51.05" cy="21" r="5"/>
              </svg>
              <em>01</em>
            </span>
            <span class="resort__kicker">The Resort</span>
          </div>
          <h2 class="resort__title">A seafront resort on the shores of Watamu</h2>
          <p class="resort__text">Seven Islands Resort sits on the seafront at Watamu, on Kenya's Indian Ocean coast — a 15-minute walk along the low-tide beach from the village. With its ever-changing tides, shifting colours and the seven islands offshore, it is an emotional mix that takes your breath away.</p>
          <div class="resort__cta">
            <a class="btn btn--outline" href="#">Explore More <span aria-hidden="true">&rsaquo;</span></a>
            <span class="resort__line"></span>
          </div>
        </div>
        <div class="resort__card">
          <div class="resort__photo">
            <img src="assets/img/7islands_resort_watamu1.jpg" alt="Award-winning resort">
            <span class="resort__award">
              <img src="assets/img/tripadvisor.jpg" alt="Tripadvisor 2026 Travellers' Choice">
            </span>
          </div>
          <p class="resort__caption">Award-winning resort in the<br>paradise island</p>
        </div>
      </div>
      <ul class="amenities">
        <li>
          <span class="amenities__icon">
            <svg viewBox="0 0 48 48" width="46" height="46" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="31" cy="13" r="4"/>
              <path d="M9 24l11-5 6 5 7-3"/>
              <path d="M4 33c3 0 3 2 6 2s3-2 6-2 3 2 6 2 3-2 6-2 3 2 6 2 3-2 6-2"/>
              <path d="M4 40c3 0 3 2 6 2s3-2 6-2 3 2 6 2 3-2 6-2 3 2 6 2 3-2 6-2"/>
            </svg>
          </span>
          <span class="amenities__label">Outdoor activity and children's pool</span>
        </li>
        <li>
          <span class="amenities__icon">
            <svg viewBox="0 0 48 48" width="46" height="46" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M24 7v6M11 12l3.5 3.5M37 12l-3.5 3.5M6 26h6M42 26h-6"/>
              <path d="M15 27a9 9 0 0 1 18 0"/>
              <path d="M5 33c3 0 3 2 6 2s3-2 6-2 3 2 6 2 3-2 6-2 3 2 6 2 3-2 6-2"/>
              <path d="M6 40h36"/>
            </svg>
          </span>
          <span class="amenities__label">Seaside location in the exclusive island resort</span>
        </li>
        <li>
          <span class="amenities__icon">
            <svg viewBox="0 0 48 48" width="46" height="46" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M7 15v26M7 40v-12h34v12M41 28v13"/>
              <path d="M7 28v-4a4 4 0 0 1 4-4h26a4 4 0 0 1 4 4v4"/>
              <rect x="12" y="22" width="12" height="6" rx="2"/>
              <rect x="24" y="22" width="12" height="6" rx="2"/>
            </svg>
          </span>
          <span class="amenities__label">Luxury family rooms and suites</span>
        </li>
        <li>
          <span class="amenities__icon">
            <svg viewBox="0 0 48 48" width="46" height="46" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M17 24h14"/>
              <rect x="10" y="16" width="7" height="16" rx="2.5"/>
              <rect x="31" y="16" width="7" height="16" rx="2.5"/>
              <path d="M6 20v8M42 20v8"/>
            </svg>
          </span>
          <span class="amenities__label">Stay Fit programme for all ages</span>
        </li>
        <li>
          <span class="amenities__icon">
            <svg viewBox="0 0 48 48" width="46" height="46" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M13 8v11M17 8v11M21 8v11"/>
              <path d="M11 19a6 6 0 0 0 12 0M17 19v21"/>
              <path d="M34 8c-4 4-4 15-1 19l1 1v12"/>
            </svg>
          </span>
          <span class="amenities__label">Wide range of top restaurants and bars</span>
        </li>
        <li>
          <span class="amenities__icon">
            <svg viewBox="0 0 48 48" width="46" height="46" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M24 13c-3.5 5-3.5 13 0 21 3.5-8 3.5-16 0-21Z"/>
              <path d="M24 34c-6-2-11-7-13-15 7 1 12 6 13 15Z"/>
              <path d="M24 34c6-2 11-7 13-15-7 1-12 6-13 15Z"/>
              <path d="M8 27c3 7 9 11 16 11s13-4 16-11"/>
            </svg>
          </span>
          <span class="amenities__label">Spa &amp; wellness for everyone</span>
        </li>
      </ul>
    </div>
  </section>
  <section class="section rooms" id="rooms">
    <div class="container">
      <div class="rooms__head">
        <div>
          <p class="eyebrow">02 &mdash; rooms &amp; suites</p>
          <h2 class="rooms__title">Discover our rooms</h2>
        </div>
        <div class="rooms__nav">
          <span class="rooms__fraction"><span data-rooms-current>01</span> / <span data-rooms-total>06</span></span>
          <div class="slider__nav">
            <button class="slider__btn" data-rooms-prev aria-label="Previous room">&#8592;</button>
            <button class="slider__btn" data-rooms-next aria-label="Next room">&#8594;</button>
          </div>
        </div>
      </div>
    </div>
    <div class="rooms-carousel" data-rooms-viewport>
      <div class="rooms-carousel__track" data-rooms-track>
        <article class="room-slide">
          <a class="room-slide__img" href="room.php">
            <span class="room-slide__price"><label>from</label><strong>$450</strong></span>
            <img src="assets/img/7islands_resort_watamu14.webp" alt="Standard room">
            <h3 class="room-slide__name">Standard room</h3>
          </a>
          <ul class="room-slide__meta">
            <li>55M&sup2;</li><li>1-6 person</li><li>2 beds</li>
          </ul>
        </article>
        <article class="room-slide">
          <a class="room-slide__img" href="room.php">
            <span class="room-slide__price"><label>from</label><strong>$300</strong></span>
            <img src="assets/img/7islands_resort_watamu9.webp" alt="Double Room">
            <h3 class="room-slide__name">Double Room</h3>
          </a>
          <ul class="room-slide__meta">
            <li>60M&sup2;</li><li>1-3 person</li><li>2 beds</li>
          </ul>
        </article>
        <article class="room-slide">
          <a class="room-slide__img" href="room.php">
            <span class="room-slide__price"><label>from</label><strong>$500</strong></span>
            <img src="assets/img/7islands_resort_watamu10.webp" alt="King size bed">
            <h3 class="room-slide__name">King size bed</h3>
          </a>
          <ul class="room-slide__meta">
            <li>80M&sup2;</li><li>1-7 person</li><li>3 beds</li>
          </ul>
        </article>
        <article class="room-slide">
          <a class="room-slide__img" href="room.php">
            <span class="room-slide__price"><label>from</label><strong>$399</strong></span>
            <img src="assets/img/7islands_resort_watamu14.webp" alt="Junior suite">
            <h3 class="room-slide__name">Junior suite</h3>
          </a>
          <ul class="room-slide__meta">
            <li>50M&sup2;</li><li>1-4 person</li><li>2 beds</li>
          </ul>
        </article>
        <article class="room-slide">
          <a class="room-slide__img" href="room.php">
            <span class="room-slide__price"><label>from</label><strong>$250</strong></span>
            <img src="assets/img/7islands_resort_watamu9.webp" alt="Classic single bed">
            <h3 class="room-slide__name">Classic single bed</h3>
          </a>
          <ul class="room-slide__meta">
            <li>45M&sup2;</li><li>1-2 person</li><li>2 beds</li>
          </ul>
        </article>
        <article class="room-slide">
          <a class="room-slide__img" href="room.php">
            <span class="room-slide__price"><label>from</label><strong>$450</strong></span>
            <img src="assets/img/7islands_resort_watamu10.webp" alt="Luxury suite">
            <h3 class="room-slide__name">Luxury suite</h3>
          </a>
          <ul class="room-slide__meta">
            <li>60M&sup2;</li><li>1-6 person</li><li>2 beds</li>
          </ul>
        </article>
      </div>
    </div>
    <div class="container rooms__foot">
      <a class="btn btn--outline" href="rooms.php">View All Rooms</a>
    </div>
  </section>
  <section class="section wellness" id="events">
    <div class="container wellness__grid">
      <input class="wellness__radio" type="radio" name="wellness" id="w-wedding" checked>
      <input class="wellness__radio" type="radio" name="wellness" id="w-dinner">
      <input class="wellness__radio" type="radio" name="wellness" id="w-animation">
      <input class="wellness__radio" type="radio" name="wellness" id="w-kids">
      <input class="wellness__radio" type="radio" name="wellness" id="w-aqua">
      <div class="wellness__body">
        <p class="eyebrow">03 &mdash; events &amp; animation</p>
        <h2 class="wellness__title">Always something to do</h2>
        <p class="wellness__lead">At Seven Islands there is always something happening &mdash; for you and for your kids, from sunrise yoga to the last dance of the night.</p>
        <div class="wellness__tabs">
          <label class="wellness__tab" for="w-wedding">
            <span class="wellness__tab-num">01</span>
            <span class="wellness__tab-text">
              <span class="wellness__tab-title">Weddings</span>
              <span class="wellness__tab-desc">Say &ldquo;I do&rdquo; on the beach, with the Indian Ocean and the seven islands as your backdrop.</span>
            </span>
            <span class="wellness__tab-mark" aria-hidden="true">+</span>
          </label>
          <label class="wellness__tab" for="w-dinner">
            <span class="wellness__tab-num">02</span>
            <span class="wellness__tab-text">
              <span class="wellness__tab-title">Special Dinners</span>
              <span class="wellness__tab-desc">Themed, candle-lit dinners by the sea, prepared by our chefs just for the occasion.</span>
            </span>
            <span class="wellness__tab-mark" aria-hidden="true">+</span>
          </label>
          <label class="wellness__tab" for="w-animation">
            <span class="wellness__tab-num">03</span>
            <span class="wellness__tab-text">
              <span class="wellness__tab-title">Animation</span>
              <span class="wellness__tab-desc">Live music, shows and evening entertainment that bring the whole resort to life.</span>
            </span>
            <span class="wellness__tab-mark" aria-hidden="true">+</span>
          </label>
          <label class="wellness__tab" for="w-kids">
            <span class="wellness__tab-num">04</span>
            <span class="wellness__tab-text">
              <span class="wellness__tab-title">Kids Club</span>
              <span class="wellness__tab-desc">A safe, playful space where younger guests make friends and stay busy all day long.</span>
            </span>
            <span class="wellness__tab-mark" aria-hidden="true">+</span>
          </label>
          <label class="wellness__tab" for="w-aqua">
            <span class="wellness__tab-num">05</span>
            <span class="wellness__tab-text">
              <span class="wellness__tab-title">Aqua Gym</span>
              <span class="wellness__tab-desc">Energising water workouts in the pool, guided every day by our animation team.</span>
            </span>
            <span class="wellness__tab-mark" aria-hidden="true">+</span>
          </label>
        </div>
        <a class="btn btn--outline" href="contact.php">Plan Your Event</a>
      </div>
      <div class="wellness__media">
        <figure class="wellness__slide" data-for="wedding">
          <img src="assets/img/7islands_resort_watamu13.webp" alt="Beachfront wedding setup">
          <figcaption><span>01</span> Your wedding day</figcaption>
        </figure>
        <figure class="wellness__slide" data-for="dinner">
          <img src="assets/img/7islands_resort_watamu7.webp" alt="Special themed dinner">
          <figcaption><span>02</span> By candlelight</figcaption>
        </figure>
        <figure class="wellness__slide" data-for="animation">
          <img src="assets/img/7islands_resort_watamu12.webp" alt="Evening animation and entertainment">
          <figcaption><span>03</span> Evening shows</figcaption>
        </figure>
        <figure class="wellness__slide" data-for="kids">
          <img src="assets/img/7islands_resort_watamu6.webp" alt="Kids club and family activities">
          <figcaption><span>04</span> Made for kids</figcaption>
        </figure>
        <figure class="wellness__slide" data-for="aqua">
          <img src="assets/img/7islands_resort_watamu3.webp" alt="Aqua gym in the pool">
          <figcaption><span>05</span> Aqua fun</figcaption>
        </figure>
      </div>
    </div>
  </section>
  <section class="section dining" id="dining">
    <div class="container">
      <div class="dining__head">
        <div class="dining__intro">
          <div class="resort__eyebrow">
            <span class="resort__badge">
              <svg viewBox="0 0 64 64" width="62" height="62" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1">
                <circle cx="54" cy="32" r="5"/><circle cx="51.05" cy="43" r="5"/><circle cx="43" cy="51.05" r="5"/>
                <circle cx="32" cy="54" r="5"/><circle cx="21" cy="51.05" r="5"/><circle cx="12.95" cy="43" r="5"/>
                <circle cx="10" cy="32" r="5"/><circle cx="12.95" cy="21" r="5"/><circle cx="21" cy="12.95" r="5"/>
                <circle cx="32" cy="10" r="5"/><circle cx="43" cy="12.95" r="5"/><circle cx="51.05" cy="21" r="5"/>
              </svg>
              <em>04</em>
            </span>
            <span class="resort__kicker">Resort Facilities</span>
          </div>
          <h2 class="dining__title">Everything you need for the perfect stay</h2>
        </div>
        <div class="dining__aside">
          <p class="dining__text">From the seafront pool to the spa and beach bar &mdash; every facility is just a short walk from your room.</p>
          <a class="dining__more" href="about.php">Discover More</a>
          <div class="slider__nav dining__nav">
            <button class="slider__btn" data-dining-prev aria-label="Previous">&#8592;</button>
            <button class="slider__btn" data-dining-next aria-label="Next">&#8594;</button>
          </div>
        </div>
      </div>
    </div>
    <div class="dining-carousel" data-dining-viewport>
      <div class="dining-carousel__track" data-dining-track>
        <article class="dish-card">
          <img src="assets/img/7islands_resort_watamu3.webp" alt="Seafront swimming pool">
          <div class="dish-card__body">
            <span class="dish-card__cat">Leisure</span>
            <h3 class="dish-card__name">Swimming Pool</h3>
            <div class="dish-card__reveal">
              <p>A large freshwater pool framed by palms and sun loungers, with a shallow area for children and a swim-up view of the ocean.</p>
              <a class="dish-card__link" href="about.php">Learn More</a>
            </div>
          </div>
        </article>
        <article class="dish-card">
          <img src="assets/img/7islands_resort_watamu6.webp" alt="Private beach">
          <div class="dish-card__body">
            <span class="dish-card__cat">Outdoors</span>
            <h3 class="dish-card__name">Private Beach</h3>
            <div class="dish-card__reveal">
              <p>Soft white sand, shaded cabanas and sun beds steps from the resort, on one of Watamu's calmest stretches of coast.</p>
              <a class="dish-card__link" href="about.php">Learn More</a>
            </div>
          </div>
        </article>
        <article class="dish-card">
          <img src="assets/img/7islands_resort_watamu9.webp" alt="Wellness and spa">
          <div class="dish-card__body">
            <span class="dish-card__cat">Wellness</span>
            <h3 class="dish-card__name">Wellness &amp; Spa</h3>
            <div class="dish-card__reveal">
              <p>A beachfront sanctuary for signature massages, facials and body treatments that leave you completely restored.</p>
              <a class="dish-card__link" href="spa.php">Learn More</a>
            </div>
          </div>
        </article>
        <article class="dish-card">
          <img src="assets/img/7islands_resort_watamu8.webp" alt="Restaurants and buffet">
          <div class="dish-card__body">
            <span class="dish-card__cat">Dining</span>
            <h3 class="dish-card__name">Restaurants &amp; Buffet</h3>
            <div class="dish-card__reveal">
              <p>Fresh fish daily, a generous international buffet and an à la carte restaurant overlooking the gardens.</p>
              <a class="dish-card__link" href="dining.php">Learn More</a>
            </div>
          </div>
        </article>
        <article class="dish-card">
          <img src="assets/img/7islands_resort_watamu12.webp" alt="Beach bar">
          <div class="dish-card__body">
            <span class="dish-card__cat">Bars</span>
            <h3 class="dish-card__name">Beach &amp; Pool Bar</h3>
            <div class="dish-card__reveal">
              <p>Tropical cocktails, fresh juices and good espresso served all day, with your feet practically in the sand.</p>
              <a class="dish-card__link" href="dining.php">Learn More</a>
            </div>
          </div>
        </article>
        <article class="dish-card">
          <img src="assets/img/7islands_resort_watamu2.webp" alt="Fitness gym">
          <div class="dish-card__body">
            <span class="dish-card__cat">Fitness</span>
            <h3 class="dish-card__name">Gym</h3>
            <div class="dish-card__reveal">
              <p>A fully-equipped fitness room for your daily workout, open from early morning until late.</p>
              <a class="dish-card__link" href="about.php">Learn More</a>
            </div>
          </div>
        </article>
        <article class="dish-card">
          <img src="assets/img/7islands_resort_watamu5_Beach.webp" alt="Children's pool">
          <div class="dish-card__body">
            <span class="dish-card__cat">Family</span>
            <h3 class="dish-card__name">Kids Pool</h3>
            <div class="dish-card__reveal">
              <p>A dedicated shallow pool where younger guests can splash and play safely in the sun.</p>
              <a class="dish-card__link" href="about.php">Learn More</a>
            </div>
          </div>
        </article>
        <article class="dish-card">
          <img src="assets/img/7islands_resort_watamu13.webp" alt="Kids club">
          <div class="dish-card__body">
            <span class="dish-card__cat">Family</span>
            <h3 class="dish-card__name">Kids Club</h3>
            <div class="dish-card__reveal">
              <p>Supervised games, crafts and activities that keep children happy while you relax.</p>
              <a class="dish-card__link" href="about.php">Learn More</a>
            </div>
          </div>
        </article>
      </div>
    </div>
  </section>
  <section class="stats">
    <div class="container stats__grid">
      <div class="stat"><span class="stat__value"><span class="stat__num" data-to="35">0</span><span class="stat__suffix">k</span></span><p class="stat__label">Time of activity</p></div>
      <div class="stat"><span class="stat__value"><span class="stat__num" data-to="99.9">0</span><span class="stat__suffix">%</span></span><p class="stat__label">Customer satisfaction</p></div>
      <div class="stat"><span class="stat__value"><span class="stat__num" data-to="2800">0</span></span><p class="stat__label">Happy guests</p></div>
      <div class="stat"><span class="stat__value"><span class="stat__num" data-to="99">0</span><span class="stat__suffix">%</span></span><p class="stat__label">Will come back again</p></div>
    </div>
  </section>
  <section class="section testimonials">
    <div class="container">
      <div class="testimonials__head">
        <div>
          <p class="eyebrow">05 &mdash; customers reviews</p>
          <h2 class="testimonials__title">Hear what our past guests have to say</h2>
          <p class="testimonials__meta">
            <img src="https://demo2.wpopal.com/amoja/wp-content/uploads/2024/11/tripadvisor-logo.svg" alt="Tripadvisor" height="22">
            1,859 reviews
          </p>
        </div>
        <div class="slider__nav">
          <button class="slider__btn" data-tm-prev aria-label="Previous">&#8592;</button>
          <button class="slider__btn" data-tm-next aria-label="Next">&#8594;</button>
        </div>
      </div>
      <div class="slider" data-tm-viewport>
        <div class="slider__track" data-tm-track>
          <article class="quote-card">
            <p class="quote-card__text">"Very comfortable seafront location and functional structure, a 15-minute walk along the low-tide beach from the village. Great food: fresh fish every day and fruit at will."</p>
            <div class="quote-card__author">
              <img src="https://demo2.wpopal.com/amoja/wp-content/uploads/2024/11/avatar1.png" alt="">
              <span><strong>giannaoll</strong><br>Novara, Italy</span>
            </div>
          </article>
          <article class="quote-card">
            <p class="quote-card__text">"A wonderful place that takes your breath away. The islands, the tide, the ever-changing colours — an emotional mix that did not disappoint my expectations."</p>
            <div class="quote-card__author">
              <img src="https://demo2.wpopal.com/amoja/wp-content/uploads/2024/11/avatar2.png" alt="">
              <span><strong>Patrizia Ylenia</strong><br>Traveller</span>
            </div>
          </article>
          <article class="quote-card">
            <p class="quote-card__text">"A beautiful property and village. They offered us an aperitif and made check-in straight away. A spacious, clean and romantic room, a large pool and always-smiling staff."</p>
            <div class="quote-card__author">
              <img src="https://demo2.wpopal.com/amoja/wp-content/uploads/2024/11/avatar1.png" alt="">
              <span><strong>Giovanni A</strong><br>Traveller</span>
            </div>
          </article>
        </div>
      </div>
    </div>
  </section>
  <section class="section dining" id="activities">
    <div class="container">
      <div class="dining__head">
        <div class="dining__intro">
          <div class="resort__eyebrow">
            <span class="resort__badge">
              <svg viewBox="0 0 64 64" width="62" height="62" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1">
                <circle cx="54" cy="32" r="5"/><circle cx="51.05" cy="43" r="5"/><circle cx="43" cy="51.05" r="5"/>
                <circle cx="32" cy="54" r="5"/><circle cx="21" cy="51.05" r="5"/><circle cx="12.95" cy="43" r="5"/>
                <circle cx="10" cy="32" r="5"/><circle cx="12.95" cy="21" r="5"/><circle cx="21" cy="12.95" r="5"/>
                <circle cx="32" cy="10" r="5"/><circle cx="43" cy="12.95" r="5"/><circle cx="51.05" cy="21" r="5"/>
              </svg>
              <em>06</em>
            </span>
            <span class="resort__kicker">Activities &amp; Experiences</span>
          </div>
          <h2 class="dining__title">Unforgettable things to do</h2>
        </div>
        <div class="dining__aside">
          <p class="dining__text">From snorkelling the marine park to dhow cruises at sunset &mdash; adventure begins right at your doorstep.</p>
          <a class="dining__more" href="tours.php">View Excursions</a>
          <div class="slider__nav dining__nav">
            <button class="slider__btn" data-activity-prev aria-label="Previous">&#8592;</button>
            <button class="slider__btn" data-activity-next aria-label="Next">&#8594;</button>
          </div>
        </div>
      </div>
    </div>
    <div class="dining-carousel" data-activity-viewport>
      <div class="dining-carousel__track" data-activity-track>
        <article class="dish-card">
          <img src="assets/img/7islands_resort_watamu1.jpg" alt="Snorkelling and diving">
          <div class="dish-card__body">
            <span class="dish-card__cat">Ocean</span>
            <h3 class="dish-card__name">Snorkelling &amp; Diving</h3>
            <div class="dish-card__reveal">
              <p>Explore coral gardens and turtle grounds in the protected waters of Watamu Marine Park.</p>
              <a class="dish-card__link" href="tours.php">Learn More</a>
            </div>
          </div>
        </article>
        <article class="dish-card">
          <img src="assets/img/7islands_resort_watamu5_Beach.webp" alt="Dhow sunset cruise">
          <div class="dish-card__body">
            <span class="dish-card__cat">On the Water</span>
            <h3 class="dish-card__name">Dhow Sunset Cruise</h3>
            <div class="dish-card__reveal">
              <p>Sail Mida Creek aboard a traditional dhow as the sky turns gold over the Indian Ocean.</p>
              <a class="dish-card__link" href="tours.php">Learn More</a>
            </div>
          </div>
        </article>
        <article class="dish-card">
          <img src="assets/img/7islands_resort_watamu6.webp" alt="Watamu Marine National Park">
          <div class="dish-card__body">
            <span class="dish-card__cat">Nature</span>
            <h3 class="dish-card__name">Marine Park Safari</h3>
            <div class="dish-card__reveal">
              <p>A guided boat trip through one of Kenya's oldest marine reserves, alive with fish and turtles.</p>
              <a class="dish-card__link" href="tours.php">Learn More</a>
            </div>
          </div>
        </article>
        <article class="dish-card">
          <img src="assets/img/7islands_resort_watamu12.webp" alt="Kayaking and paddleboarding">
          <div class="dish-card__body">
            <span class="dish-card__cat">Water Sports</span>
            <h3 class="dish-card__name">Kayak &amp; Paddleboard</h3>
            <div class="dish-card__reveal">
              <p>Glide across the calm low-tide lagoon at your own pace, with gear ready at the beach.</p>
              <a class="dish-card__link" href="tours.php">Learn More</a>
            </div>
          </div>
        </article>
        <article class="dish-card">
          <img src="assets/img/7islands_resort_watamu7.webp" alt="Swahili cooking class">
          <div class="dish-card__body">
            <span class="dish-card__cat">Culture</span>
            <h3 class="dish-card__name">Swahili Cooking Class</h3>
            <div class="dish-card__reveal">
              <p>Learn the spices and recipes of the Kenyan coast hands-on with our resort chefs.</p>
              <a class="dish-card__link" href="tours.php">Learn More</a>
            </div>
          </div>
        </article>
        <article class="dish-card">
          <img src="assets/img/7islands_resort_watamu10.webp" alt="Sunrise beach yoga">
          <div class="dish-card__body">
            <span class="dish-card__cat">Wellness</span>
            <h3 class="dish-card__name">Sunrise Beach Yoga</h3>
            <div class="dish-card__reveal">
              <p>Start the day grounded with guided yoga on the sand as the sun rises over the islands.</p>
              <a class="dish-card__link" href="spa.php">Learn More</a>
            </div>
          </div>
        </article>
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
