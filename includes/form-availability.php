<?php
/** Availability form partial — calendar date picker + hold request form */
$room_slug  = $room['slug']          ?? '';
$room_name  = $room['name']          ?? '';
$room_price = (float)($room['price_amount']   ?? 0);
$room_curr  = $room['price_currency'] ?? 'USD';
?>
<div class="avail-wrap"
     id="availCalendar"
     data-slug="<?= e($room_slug) ?>"
     data-price="<?= e($room_price) ?>"
     data-currency="<?= e($room_curr) ?>">

  <!-- Step 1: date picker -->
  <div id="availStep1">
    <div class="avail-nav">
      <button type="button" class="avail-nav__btn" id="availPrev">&#8249;</button>
      <span class="avail-nav__label" id="availMonthLabel"></span>
      <button type="button" class="avail-nav__btn" id="availNext">&#8250;</button>
    </div>
    <div class="avail-day-names">
      <span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span><span>Su</span>
    </div>
    <div class="avail-grid" id="availGrid"></div>
    <div class="avail-hint" id="availHint">Select check-in date</div>
  </div>

  <!-- Step 2: guest details (shown after valid dates chosen) -->
  <form id="availForm" class="room-enquiry-form" novalidate
        data-room-slug="<?= e($room_slug) ?>"
        style="display:none">
    <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">
    <input type="hidden" name="checkin"  id="availCheckinHidden">
    <input type="hidden" name="checkout" id="availCheckoutHidden">

    <div class="avail-summary" id="availSummary">
      <span id="availSummaryText"></span>
      <button type="button" class="avail-change-btn" id="availChangeDates">Change dates</button>
    </div>
    <div class="avail-rate-notice" id="availRateNotice" hidden>
      ★ Prices vary — some nights in your stay are at a special rate
    </div>

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

    <div class="booking-field booking-field--row">
      <span>Adults <small>(18+)</small></span>
      <div class="booking-step">
        <button type="button" data-bk="adult" data-dir="-1" aria-label="Decrease adults">&minus;</button>
        <span data-bk-count="adult">2</span>
        <button type="button" data-bk="adult" data-dir="1"  aria-label="Increase adults">+</button>
      </div>
      <input type="hidden" name="adults" value="2">
    </div>
    <div class="booking-field booking-field--row">
      <span>Children</span>
      <div class="booking-step">
        <button type="button" data-bk="child" data-dir="-1" aria-label="Decrease children">&minus;</button>
        <span data-bk-count="child">0</span>
        <button type="button" data-bk="child" data-dir="1"  aria-label="Increase children">+</button>
      </div>
      <input type="hidden" name="children" value="0">
    </div>

    <label class="booking-field">
      <span>Message</span>
      <textarea name="message" rows="2" placeholder="Special requests?"></textarea>
    </label>

    <div class="form-feedback" id="availFeedback" hidden></div>

    <?php if (captcha_site_key()): ?>
    <div class="h-captcha" data-sitekey="<?= e(captcha_site_key()) ?>"></div>
    <?php endif; ?>

    <button type="submit" class="btn btn--primary booking-card__submit">
      Request Hold <span aria-hidden="true">&rsaquo;</span>
    </button>
    <p class="avail-hold-note">Dates held 24 hours pending confirmation</p>
  </form>

</div>
