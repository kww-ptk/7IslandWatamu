<?php
/**
 * Date + guests search bar partial.
 * Submits GET to rooms.php. Pre-fills from current query string when already on results page.
 */
$_sb_checkin  = isset($_GET['check_in'])  ? htmlspecialchars($_GET['check_in'],  ENT_QUOTES, 'UTF-8') : '';
$_sb_checkout = isset($_GET['check_out']) ? htmlspecialchars($_GET['check_out'], ENT_QUOTES, 'UTF-8') : '';
$_sb_adults   = max(1, (int)($_GET['adults']   ?? 2));
$_sb_children = max(0, (int)($_GET['children'] ?? 0));
$_sb_summary  = $_sb_adults . ' Adult' . ($_sb_adults !== 1 ? 's' : '');
if ($_sb_children > 0) $_sb_summary .= ', ' . $_sb_children . ' Child' . ($_sb_children !== 1 ? 'ren' : '');
?>
<form class="search-bar" id="searchBar" method="GET" action="rooms.php" novalidate>
  <div class="search-bar__group">

    <div class="search-bar__field">
      <label class="search-bar__label" for="sbCheckin">Check in</label>
      <input class="search-bar__input js-sb-checkin" type="text" id="sbCheckin"
             name="check_in" placeholder="Arrival date" autocomplete="off"
             value="<?= $_sb_checkin ?>" readonly>
    </div>

    <div class="search-bar__field">
      <label class="search-bar__label" for="sbCheckout">Check out</label>
      <input class="search-bar__input js-sb-checkout" type="text" id="sbCheckout"
             name="check_out" placeholder="Departure date" autocomplete="off"
             value="<?= $_sb_checkout ?>" readonly>
    </div>

    <div class="search-bar__field search-bar__field--guests">
      <label class="search-bar__label" for="sbGuestsToggle">Guests</label>
      <button type="button" class="search-bar__guests-btn" id="sbGuestsToggle" aria-expanded="false" aria-haspopup="true">
        <span id="sbGuestsSummary"><?= htmlspecialchars($_sb_summary, ENT_QUOTES, 'UTF-8') ?></span>
        <svg class="search-bar__caret" viewBox="0 0 10 6" width="10" height="6" aria-hidden="true"><path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round"/></svg>
      </button>
      <div class="search-bar__guests-pop" id="sbGuestsPop" hidden>
        <div class="guests-row">
          <div>
            <p class="guests-row__label" style="margin:0 0 2px">Adults</p>
            <p class="guests-row__sub"  style="margin:0;font-size:11px;color:#9ca3af">Age 18+</p>
          </div>
          <div class="guests-stepper">
            <button type="button" class="guests-stepper__btn" data-sb-step="adult" data-dir="-1" aria-label="Fewer adults">&minus;</button>
            <span   class="guests-stepper__count" id="sbAdultCount"><?= $_sb_adults ?></span>
            <button type="button" class="guests-stepper__btn" data-sb-step="adult" data-dir="1"  aria-label="More adults">+</button>
          </div>
        </div>
        <div class="guests-row" style="margin-top:10px">
          <div>
            <p class="guests-row__label" style="margin:0 0 2px">Children</p>
            <p class="guests-row__sub"  style="margin:0;font-size:11px;color:#9ca3af">Under 18</p>
          </div>
          <div class="guests-stepper">
            <button type="button" class="guests-stepper__btn" data-sb-step="child" data-dir="-1" aria-label="Fewer children">&minus;</button>
            <span   class="guests-stepper__count" id="sbChildCount"><?= $_sb_children ?></span>
            <button type="button" class="guests-stepper__btn" data-sb-step="child" data-dir="1"  aria-label="More children">+</button>
          </div>
        </div>
      </div>
      <input type="hidden" name="adults"   id="sbAdultsHidden"   value="<?= $_sb_adults ?>">
      <input type="hidden" name="children" id="sbChildrenHidden" value="<?= $_sb_children ?>">
    </div>

  </div>

  <button type="submit" class="search-bar__submit btn btn--primary">
    Search Rooms <span aria-hidden="true">&rsaquo;</span>
  </button>
</form>
