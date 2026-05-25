document.addEventListener("DOMContentLoaded", () => {
  // ---- Gallery: centered peek carousel ----
  (function initGallery() {
    const viewport = document.querySelector("[data-gal-viewport]");
    const track = document.querySelector("[data-gal-track]");
    if (!viewport || !track) return;
    const slides = Array.from(track.children);
    const prevBtn = document.querySelector("[data-gal-prev]");
    const nextBtn = document.querySelector("[data-gal-next]");
    let index = 0;

    const apply = () => {
      const slideW = slides[0].offsetWidth;
      const gap = parseInt(getComputedStyle(track).columnGap) || 0;
      const center = viewport.clientWidth / 2 - slideW / 2;
      track.style.transform = `translateX(${center - index * (slideW + gap)}px)`;
      slides.forEach((s, i) => s.classList.toggle("is-active", i === index));
    };
    const go = (dir) => {
      index = (index + dir + slides.length) % slides.length;
      apply();
    };
    prevBtn.addEventListener("click", () => go(-1));
    nextBtn.addEventListener("click", () => go(1));
    window.addEventListener("resize", apply);
    apply();
  })();

  // ---- Booking sidebar steppers ----
  (function initBooking() {
    const card = document.querySelector(".booking-card");
    if (!card) return;
    const counts = { adult: 1, child: 0 };
    card.addEventListener("click", (e) => {
      const btn = e.target.closest("[data-bk]");
      if (!btn) return;
      const key = btn.dataset.bk;
      const min = key === "adult" ? 1 : 0;
      counts[key] = Math.max(min, Math.min(20, counts[key] + parseInt(btn.dataset.dir, 10)));
      const el = card.querySelector(`[data-bk-count="${key}"]`);
      if (el) el.textContent = counts[key];
    });
  })();

  // ---- Availability calendar (May 2026) ----
  (function initCalendar() {
    const grid = document.querySelector("[data-calendar]");
    if (!grid) return;
    const leadingApril = [27, 28, 29, 30]; // May 1, 2026 is a Friday (Mon-first grid)
    const daysInMay = 31;
    const availableFrom = 17; // nights available from the 17th onward
    const nightlyRate = "$450";
    let html = "";

    leadingApril.forEach((d) => {
      html += `<div class="cal-cell cal-cell--muted">${d}</div>`;
    });
    for (let d = 1; d <= daysInMay; d++) {
      if (d >= availableFrom) {
        html += `<div class="cal-cell">${d}<small>${nightlyRate}</small></div>`;
      } else {
        html += `<div class="cal-cell cal-cell--muted">${d}</div>`;
      }
    }
    grid.innerHTML = html;
  })();
});
