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
  // Skip if availability form is active — that form has its own scoped stepper.
  (function initBooking() {
    const card = document.querySelector(".booking-card");
    if (!card) return;
    if (card.querySelector("#availCalendar")) return; // availability mode handles its own
    const counts = { adult: 1, child: 0 };
    card.addEventListener("click", (e) => {
      const btn = e.target.closest("[data-bk]");
      if (!btn) return;
      const key = btn.dataset.bk;
      const min = key === "adult" ? 1 : 0;
      counts[key] = Math.max(min, Math.min(20, counts[key] + parseInt(btn.dataset.dir, 10)));
      const el = card.querySelector(`[data-bk-count="${key}"]`);
      if (el) el.textContent = counts[key];
      // Sync hidden input so the form submits the actual count
      const hidden = card.querySelector(`[name="${key === "adult" ? "adults" : "children"}"]`);
      if (hidden) hidden.value = counts[key];
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

  // ---- Availability form (booking card, form_mode=availability) ----
  // Single-step form: guest fills dates + people + info, then submits.
  // Server checks availability and either creates a 24h hold or returns "no availability".
  (function initAvailForm() {
    const wrap = document.getElementById("availCalendar");
    if (!wrap) return;
    const form = document.getElementById("availForm");
    if (!form) return;

    const slug     = wrap.dataset.slug;
    const defPrice = parseFloat(wrap.dataset.price) || 0;
    const currency = wrap.dataset.currency || "USD";

    const checkinEl  = document.getElementById("availCheckin");
    const checkoutEl = document.getElementById("availCheckout");
    const summary    = document.getElementById("availSummaryText");
    const feedback   = document.getElementById("availFeedback");
    const submitBtn  = form.querySelector("[type=submit]");
    const submitLbl  = submitBtn.innerHTML;

    // Keep check-out at least one day after check-in
    function bumpCheckout() {
      const ci = new Date(checkinEl.value + "T00:00");
      const co = new Date(checkoutEl.value + "T00:00");
      const minCo = new Date(ci); minCo.setDate(minCo.getDate() + 1);
      const minIso = minCo.toISOString().slice(0, 10);
      checkoutEl.min = minIso;
      if (!checkoutEl.value || co <= ci) checkoutEl.value = minIso;
    }

    // Live nights × price summary
    function updateSummary() {
      const ci = new Date(checkinEl.value + "T00:00");
      const co = new Date(checkoutEl.value + "T00:00");
      if (isNaN(ci) || isNaN(co) || co <= ci) {
        summary.textContent = "Select your dates";
        return;
      }
      const nights = Math.round((co - ci) / 86400000);
      const total  = defPrice * nights;
      const fmt = d => d.toLocaleDateString("en-GB", { day:"numeric", month:"short", year:"numeric" });
      const totalFmt = total > 0
        ? " · " + total.toLocaleString("en-US", { style:"currency", currency })
        : "";
      summary.textContent = `${fmt(ci)} → ${fmt(co)} · ${nights} night${nights>1?"s":""}${totalFmt}`;
    }

    checkinEl.addEventListener("change",  () => { bumpCheckout(); updateSummary(); });
    checkoutEl.addEventListener("change", updateSummary);
    bumpCheckout();
    updateSummary();

    // Adults / children stepper
    form.querySelectorAll("[data-bk]").forEach(btn => {
      btn.addEventListener("click", () => {
        const key      = btn.dataset.bk;
        const min      = key === "adult" ? 1 : 0;
        const countEl  = form.querySelector(`[data-bk-count="${key}"]`);
        const hiddenEl = form.querySelector(`[name="${key === "adult" ? "adults" : "children"}"]`);
        let val = parseInt(countEl.textContent, 10) + parseInt(btn.dataset.dir, 10);
        val = Math.max(min, Math.min(20, val));
        countEl.textContent = val;
        if (hiddenEl) hiddenEl.value = val;
      });
    });

    function showError(msg) {
      feedback.hidden = false;
      feedback.className = "form-feedback form-feedback--error";
      feedback.textContent = msg;
    }
    function clearError() {
      feedback.hidden = true;
      feedback.textContent = "";
    }

    form.addEventListener("submit", async e => {
      e.preventDefault();
      clearError();

      // Client-side date sanity
      const ci = checkinEl.value;
      const co = checkoutEl.value;
      if (!ci || !co || new Date(co) <= new Date(ci)) {
        showError("Please choose a check-out date after your check-in date.");
        return;
      }

      submitBtn.disabled = true;
      submitBtn.textContent = "Checking availability…";

      const data = {
        room_slug:            slug,
        checkin:              ci,
        checkout:             co,
        name:                 form.querySelector("[name=name]").value.trim(),
        email:                form.querySelector("[name=email]").value.trim(),
        phone:                form.querySelector("[name=phone]")?.value.trim() || "",
        adults:               parseInt(form.querySelector("[name=adults]").value, 10),
        children:             parseInt(form.querySelector("[name=children]").value, 10),
        message:              form.querySelector("[name=message]")?.value.trim() || "",
        "h-captcha-response": form.querySelector("[name='h-captcha-response']")?.value || "",
      };

      try {
        const res  = await fetch("/api/submit-enquiry.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(data),
        });
        const json = await res.json();

        if (json.ok) {
          wrap.innerHTML = `<p class="form-success">Great news — your dates are available. We've held them for 24 hours and will confirm your booking shortly. Please check your email.</p>`;
          return;
        }

        // Specific errors
        if (json.errors) {
          showError(Object.values(json.errors).filter(Boolean).join(" "));
        } else if (res.status === 409) {
          showError(json.error || "Sorry — those dates are no longer available. Please try different dates.");
        } else {
          showError(json.error || "Something went wrong. Please try again.");
        }
      } catch {
        showError("Network error. Please check your connection and try again.");
      } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = submitLbl;
      }
    });
  })();
});
