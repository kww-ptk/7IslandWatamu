document.addEventListener("DOMContentLoaded", () => {
  // ---- Flatpickr for search bar date inputs ----
  // Uses .js-sb-checkin / .js-sb-checkout — separate from .js-checkin / .js-checkout
  // used by the hero enquiry form (no conflict).
  const ciEl = document.querySelector(".js-sb-checkin");
  const coEl = document.querySelector(".js-sb-checkout");

  if (ciEl && coEl && typeof flatpickr !== "undefined") {
    const co = flatpickr(coEl, {
      dateFormat: "Y-m-d",
      altInput: true,
      altFormat: "d M Y",
      minDate: new Date(Date.now() + 86400000),
      allowInput: false,
      defaultDate: coEl.value || null,
      disableMobile: true,
    });

    flatpickr(ciEl, {
      dateFormat: "Y-m-d",
      altInput: true,
      altFormat: "d M Y",
      minDate: "today",
      allowInput: false,
      defaultDate: ciEl.value || null,
      disableMobile: true,
      onChange(dates) {
        if (!dates.length) return;
        const next = new Date(dates[0]);
        next.setDate(next.getDate() + 1);
        co.set("minDate", next);
        if (co.selectedDates[0] && co.selectedDates[0] <= dates[0]) {
          co.setDate(next);
        }
        // Automatically open check-out picker after check-in is selected
        co.open();
      },
    });
  }

  // ---- Guests picker ----
  const toggle  = document.getElementById("sbGuestsToggle");
  const pop     = document.getElementById("sbGuestsPop");
  const summary = document.getElementById("sbGuestsSummary");
  const adultEl = document.getElementById("sbAdultCount");
  const childEl = document.getElementById("sbChildCount");
  const adultIn = document.getElementById("sbAdultsHidden");
  const childIn = document.getElementById("sbChildrenHidden");

  if (!toggle || !pop) return;

  const counts = {
    adult: parseInt(adultIn?.value ?? "2", 10),
    child: parseInt(childIn?.value ?? "0", 10),
  };

  const renderGuests = () => {
    if (adultEl) adultEl.textContent = counts.adult;
    if (childEl) childEl.textContent = counts.child;
    if (adultIn) adultIn.value = counts.adult;
    if (childIn) childIn.value = counts.child;
    const parts = [`${counts.adult} Adult${counts.adult !== 1 ? "s" : ""}`];
    if (counts.child > 0) parts.push(`${counts.child} Child${counts.child !== 1 ? "ren" : ""}`);
    if (summary) summary.textContent = parts.join(", ");
  };

  toggle.addEventListener("click", (e) => {
    e.stopPropagation();
    const willOpen = pop.hidden;
    pop.hidden = !willOpen;
    toggle.setAttribute("aria-expanded", String(willOpen));
  });

  pop.addEventListener("click", (e) => {
    const btn = e.target.closest("[data-sb-step]");
    if (!btn) { e.stopPropagation(); return; }
    e.stopPropagation();
    const key = btn.dataset.sbStep;
    const dir = parseInt(btn.dataset.dir, 10);
    counts[key] = Math.max(key === "adult" ? 1 : 0, Math.min(20, counts[key] + dir));
    renderGuests();
  });

  document.addEventListener("click", () => {
    if (!pop.hidden) {
      pop.hidden = true;
      toggle.setAttribute("aria-expanded", "false");
    }
  });

  renderGuests();
});
