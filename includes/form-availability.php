<?php
/** Availability form partial — single-step request, server checks availability on submit */
$room_slug  = $room['slug']          ?? '';
$room_name  = $room['name']          ?? '';
$room_price = (float)($room['price_amount']   ?? 0);
$room_curr  = $room['price_currency'] ?? 'USD';

// Today + tomorrow as min defaults for the date inputs
$today_iso    = date('Y-m-d');
$tomorrow_iso = date('Y-m-d', strtotime('+1 day'));
?>
<div class="avail-wrap"
     id="availCalendar"
     data-slug="<?= e($room_slug) ?>"
     data-price="<?= e($room_price) ?>"
     data-currency="<?= e($room_curr) ?>">

  <form id="availForm" class="room-enquiry-form" novalidate
        data-room-slug="<?= e($room_slug) ?>">
    <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">

    <div class="booking-field booking-field--row" style="gap:8px">
      <label style="flex:1">
        <span>Check-in</span>
        <input type="date" name="checkin" id="availCheckin" required
               min="<?= e($today_iso) ?>" value="<?= e($today_iso) ?>">
      </label>
      <label style="flex:1">
        <span>Check-out</span>
        <input type="date" name="checkout" id="availCheckout" required
               min="<?= e($tomorrow_iso) ?>" value="<?= e($tomorrow_iso) ?>">
      </label>
    </div>

    <div class="avail-summary" id="availSummary" style="display:block">
      <span id="availSummaryText">Select your dates</span>
    </div>

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
      <span>Message</span>
      <textarea name="message" rows="2" placeholder="Special requests?"></textarea>
    </label>

    <div class="form-feedback" id="availFeedback" hidden></div>

    <?php if (captcha_site_key()): ?>
    <div class="h-captcha" data-sitekey="<?= e(captcha_site_key()) ?>"></div>
    <?php endif; ?>

    <button type="submit" class="btn btn--primary booking-card__submit">
      Check availability &amp; request hold <span aria-hidden="true">&rsaquo;</span>
    </button>
    <p class="avail-hold-note">We'll confirm availability and hold your dates for 24 hours</p>
  </form>

</div>
