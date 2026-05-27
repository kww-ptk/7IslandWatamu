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

  // ---- Static room-page calendar (decorative month grid) ----
  (function initCalendar() {
    const grid = document.querySelector("[data-calendar]");
    if (!grid) return;
    const today = new Date();
    const year  = today.getFullYear();
    const month = today.getMonth(); // 0-indexed
    const firstDow = new Date(year, month, 1).getDay(); // 0=Sun
    const leadingBlanks = (firstDow + 6) % 7; // Mon-first
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    let html = "";
    for (let i = 0; i < leadingBlanks; i++) html += `<div class="cal-cell cal-cell--muted"></div>`;
    for (let d = 1; d <= daysInMonth; d++) {
      const isPast = d < today.getDate();
      html += `<div class="cal-cell${isPast ? " cal-cell--muted" : ""}">${d}</div>`;
    }
    grid.innerHTML = html;
  })();

  // ---- Availability calendar (booking card, form_mode=availability) ----
  (function initAvailCalendar() {
    const wrap = document.getElementById("availCalendar");
    if (!wrap) return;

    const slug     = wrap.dataset.slug;
    const defPrice = parseFloat(wrap.dataset.price) || 0;
    const currency = wrap.dataset.currency || "USD";

    const step1    = document.getElementById("availStep1");
    const step2    = document.getElementById("availForm");
    const grid     = document.getElementById("availGrid");
    const hint     = document.getElementById("availHint");
    const monthLbl = document.getElementById("availMonthLabel");
    const prevBtn  = document.getElementById("availPrev");
    const nextBtn  = document.getElementById("availNext");

    let fullyBlocked = []; // array of "YYYY-MM-DD" strings
    let viewYear, viewMonth; // currently displayed month (0-indexed)
    let selStart = null, selEnd = null; // selected dates as Date objects

    // Init view to current month
    const now = new Date();
    viewYear  = now.getFullYear();
    viewMonth = now.getMonth();

    // Fetch blocked dates from API
    fetch(`/api/check-availability.php?room=${encodeURIComponent(slug)}`)
      .then(r => r.json())
      .then(data => {
        fullyBlocked = data.fully_blocked || [];
        renderCalendar();
      })
      .catch(() => renderCalendar());

    function ymd(d) {
      return d.getFullYear() + "-" +
        String(d.getMonth() + 1).padStart(2, "0") + "-" +
        String(d.getDate()).padStart(2, "0");
    }
    function isBlocked(d)  { return fullyBlocked.includes(ymd(d)); }
    function isPast(d)     { const t = new Date(); t.setHours(0,0,0,0); return d < t; }
    function inRange(d)    {
      if (!selStart || !selEnd) return false;
      const lo = selStart <= selEnd ? selStart : selEnd;
      const hi = selStart <= selEnd ? selEnd   : selStart;
      return d > lo && d < hi;
    }
    function rangeHasBlock() {
      if (!selStart || !selEnd) return false;
      const lo = selStart <= selEnd ? selStart : selEnd;
      const hi = selStart <= selEnd ? selEnd   : selStart;
      let d = new Date(lo); d.setDate(d.getDate() + 1);
      while (d < hi) { if (isBlocked(d)) return true; d.setDate(d.getDate() + 1); }
      return false;
    }

    function renderCalendar() {
      const firstDay = new Date(viewYear, viewMonth, 1);
      const lastDay  = new Date(viewYear, viewMonth + 1, 0);
      const monthNames = ["January","February","March","April","May","June",
                          "July","August","September","October","November","December"];
      monthLbl.textContent = `${monthNames[viewMonth]} ${viewYear}`;

      const leadingBlanks = (firstDay.getDay() + 6) % 7; // Mon-first
      let html = "";
      for (let i = 0; i < leadingBlanks; i++) html += `<div class="avail-cell avail-cell--blank"></div>`;

      for (let d = 1; d <= lastDay.getDate(); d++) {
        const date = new Date(viewYear, viewMonth, d);
        const key  = ymd(date);
        let cls    = "avail-cell";

        if (isPast(date) || isBlocked(date)) {
          cls += " avail-cell--blocked";
        } else {
          if (selStart && ymd(date) === ymd(selStart)) cls += " avail-cell--start";
          else if (selEnd && ymd(date) === ymd(selEnd)) cls += " avail-cell--end";
          else if (inRange(date)) cls += " avail-cell--range";
        }

        html += `<div class="${cls}" data-date="${key}">${d}</div>`;
      }
      grid.innerHTML = html;

      grid.querySelectorAll(".avail-cell:not(.avail-cell--blocked):not(.avail-cell--blank)").forEach(cell => {
        cell.addEventListener("click", () => onDayClick(cell.dataset.date));
      });
    }

    function onDayClick(dateStr) {
      const clicked = new Date(dateStr);
      if (!selStart) {
        selStart = clicked; selEnd = null;
        hint.textContent = "Now select check-out date";
      } else if (!selEnd) {
        if (clicked <= selStart) {
          selStart = clicked; selEnd = null;
          hint.textContent = "Now select check-out date";
        } else {
          selEnd = clicked;
          if (rangeHasBlock()) {
            hint.textContent = "Those dates include unavailable nights — please choose a different range.";
            selEnd = null;
          } else {
            showDateSummary();
          }
        }
      } else {
        // Reset selection
        selStart = clicked; selEnd = null;
        hint.textContent = "Now select check-out date";
      }
      renderCalendar();
    }

    function showDateSummary() {
      const nights = Math.round((selEnd - selStart) / 86400000);
      const fmt = d => d.toLocaleDateString("en-GB", { day:"numeric", month:"short", year:"numeric" });
      const total = (defPrice * nights).toLocaleString("en-US", { style:"currency", currency });

      document.getElementById("availCheckinHidden").value  = ymd(selStart);
      document.getElementById("availCheckoutHidden").value = ymd(selEnd);
      document.getElementById("availSummaryText").textContent =
        `${fmt(selStart)} → ${fmt(selEnd)} · ${nights} night${nights>1?"s":""} · ${total}`;

      step1.style.display = "none";
      step2.style.display = "block";
    }

    // "Change dates" resets to step 1
    document.getElementById("availChangeDates")?.addEventListener("click", () => {
      selEnd = null;
      step2.style.display = "none";
      step1.style.display = "block";
      hint.textContent = "Select check-in date";
      renderCalendar();
    });

    prevBtn.addEventListener("click", () => { viewMonth--; if (viewMonth < 0) { viewMonth = 11; viewYear--; } renderCalendar(); });
    nextBtn.addEventListener("click", () => { viewMonth++; if (viewMonth > 11) { viewMonth = 0; viewYear++; } renderCalendar(); });

    // Guest counter stepper sync for availability form
    step2.querySelectorAll("[data-bk]").forEach(btn => {
      btn.addEventListener("click", () => {
        const key = btn.dataset.bk;
        const min = key === "adult" ? 1 : 0;
        const countEl = step2.querySelector(`[data-bk-count="${key}"]`);
        const hiddenEl = step2.querySelector(`[name="${key === "adult" ? "adults" : "children"}"]`);
        let val = parseInt(countEl.textContent, 10) + parseInt(btn.dataset.dir, 10);
        val = Math.max(min, Math.min(20, val));
        countEl.textContent = val;
        if (hiddenEl) hiddenEl.value = val;
      });
    });

    // Form submit — reuse the same submitForm helper from script.js
    step2.addEventListener("submit", async e => {
      e.preventDefault();
      const btn = step2.querySelector("[type=submit]");
      btn.disabled = true; btn.textContent = "Sending…";
      const feedback = document.getElementById("availFeedback");

      const data = {
        room_slug: slug,
        checkin:   document.getElementById("availCheckinHidden").value,
        checkout:  document.getElementById("availCheckoutHidden").value,
        name:      step2.querySelector("[name=name]").value.trim(),
        email:     step2.querySelector("[name=email]").value.trim(),
        phone:     step2.querySelector("[name=phone]")?.value.trim() || "",
        adults:    parseInt(step2.querySelector("[name=adults]").value, 10),
        children:  parseInt(step2.querySelector("[name=children]").value, 10),
        message:   step2.querySelector("[name=message]")?.value.trim() || "",
      };

      try {
        const res  = await fetch("/api/submit-enquiry.php", { method:"POST", headers:{"Content-Type":"application/json"}, body: JSON.stringify(data) });
        const json = await res.json();
        if (json.ok) {
          wrap.innerHTML = `<p class="form-success">Your dates are held for 24 hours. We'll confirm your booking shortly — check your email.</p>`;
        } else if (json.errors) {
          feedback.hidden = false;
          feedback.className = "form-feedback form-feedback--error";
          feedback.textContent = Object.values(json.errors).filter(Boolean).join(" ");
          btn.disabled = false; btn.textContent = "Request Hold";
        } else {
          feedback.hidden = false;
          feedback.className = "form-feedback form-feedback--error";
          feedback.textContent = json.error || "Something went wrong. Please try again.";
          btn.disabled = false; btn.textContent = "Request Hold";
        }
      } catch {
        feedback.hidden = false;
        feedback.className = "form-feedback form-feedback--error";
        feedback.textContent = "Network error. Please check your connection and try again.";
        btn.disabled = false; btn.textContent = "Request Hold";
      }
    });
  })();
});
