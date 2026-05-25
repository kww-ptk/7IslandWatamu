<?php
/** Enquiry form partial — included inside room.php booking card */
$room_slug = $room['slug'] ?? '';
$room_name = $room['name'] ?? '';
?>
<form class="room-enquiry-form" id="roomEnquiryForm" novalidate
      data-room-slug="<?= e($room_slug) ?>"
      data-room-name="<?= e($room_name) ?>">

  <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">

  <label class="booking-field">
    <span>Check in</span>
    <input type="date" name="checkin" id="enqRoomCheckin" min="<?= date('Y-m-d') ?>" required>
  </label>
  <label class="booking-field">
    <span>Check out</span>
    <input type="date" name="checkout" id="enqRoomCheckout" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
  </label>

  <div class="booking-field booking-field--row">
    <span>Adult <small>(18+ years)</small></span>
    <div class="booking-step">
      <button type="button" data-bk="adult" data-dir="-1" aria-label="Decrease adults">&minus;</button>
      <span data-bk-count="adult">1</span>
      <button type="button" data-bk="adult" data-dir="1" aria-label="Increase adults">+</button>
    </div>
    <input type="hidden" name="adults" value="1">
  </div>
  <div class="booking-field booking-field--row">
    <span>Children <small>+$15</small></span>
    <div class="booking-step">
      <button type="button" data-bk="child" data-dir="-1" aria-label="Decrease children">&minus;</button>
      <span data-bk-count="child">0</span>
      <button type="button" data-bk="child" data-dir="1" aria-label="Increase children">+</button>
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
    <textarea name="message" rows="3" placeholder="Any special requests?"></textarea>
  </label>

  <div class="form-feedback" id="roomEnquiryFeedback" hidden></div>

  <button type="submit" class="btn btn--primary booking-card__submit">
    Book Now <span aria-hidden="true">&rsaquo;</span>
  </button>
</form>
<script>
(function(){
  var ci = document.getElementById('enqRoomCheckin');
  var co = document.getElementById('enqRoomCheckout');
  if (!ci || !co) return;
  ci.addEventListener('change', function(){
    if (!ci.value) return;
    var next = new Date(ci.value);
    next.setDate(next.getDate() + 1);
    var min = next.toISOString().slice(0, 10);
    co.min = min;
    if (co.value && co.value <= ci.value) co.value = min;
  });
})();
</script>
