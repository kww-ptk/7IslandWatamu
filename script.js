document.addEventListener("DOMContentLoaded", () => {
  // Flatpickr — replace native date pickers site-wide
  if (typeof flatpickr !== "undefined") {
    const today = new Date(); today.setHours(0, 0, 0, 0);

    // Standalone single-date pickers
    flatpickr(".js-datepicker", {
      dateFormat: "Y-m-d",
      altInput: true,
      altFormat: "d M Y",
      minDate: "today",
      allowInput: true,
    });

    // Linked check-in / check-out pairs (scoped per form)
    document.querySelectorAll(".js-checkin").forEach((ciEl) => {
      const form = ciEl.closest("form");
      const coEl = form && form.querySelector(".js-checkout");
      if (!coEl) return;

      const co = flatpickr(coEl, {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d M Y",
        minDate: new Date(today.getTime() + 86400000),
        allowInput: true,
      });

      flatpickr(ciEl, {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d M Y",
        minDate: "today",
        allowInput: true,
        onChange: (dates) => {
          if (!dates.length) return;
          const next = new Date(dates[0]); next.setDate(next.getDate() + 1);
          co.set("minDate", next);
          if (co.selectedDates[0] && co.selectedDates[0] <= dates[0]) co.setDate(next);
        },
      });
    });
  }

  // Room/Tour gallery carousel
  document.querySelectorAll("[data-gal-viewport]").forEach((viewport) => {
    const track  = viewport.querySelector("[data-gal-track]");
    const slides = [...viewport.querySelectorAll(".room-gallery__slide")];
    const prev   = viewport.querySelector("[data-gal-prev]");
    const next   = viewport.querySelector("[data-gal-next]");
    if (!track || slides.length === 0) return;

    let index = 0;

    const update = () => {
      slides.forEach((s, i) => s.classList.toggle("is-active", i === index));
      const active = slides[index];
      const slideW = active.getBoundingClientRect().width;
      const gap    = parseFloat(getComputedStyle(track).gap) || 0;
      // Center the active slide in the viewport
      const offset = (viewport.clientWidth - slideW) / 2 - index * (slideW + gap);
      track.style.transform = `translateX(${offset}px)`;
    };

    const go = (dir) => {
      index = (index + dir + slides.length) % slides.length;
      update();
    };

    prev && prev.addEventListener("click", () => go(-1));
    next && next.addEventListener("click", () => go(1));
    slides.forEach((s, i) => s.addEventListener("click", () => { index = i; update(); }));

    window.addEventListener("resize", update);
    update();

    // Hide arrows when only one slide
    if (slides.length < 2) {
      prev && (prev.style.display = "none");
      next && (next.style.display = "none");
    }
  });

  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  const header = document.getElementById("siteHeader");
  const nav = document.getElementById("siteNav");
  const burger = document.getElementById("navBurger");

  window.addEventListener("scroll", () => {
    header.classList.toggle("is-stuck", window.scrollY > 80);
  });

  burger.addEventListener("click", () => {
    const open = nav.classList.toggle("is-open");
    burger.setAttribute("aria-expanded", String(open));
  });

  function addSwipe(el, onLeft, onRight) {
    if (!el) return;
    let x0 = null, y0 = null;
    el.addEventListener("touchstart", (e) => {
      x0 = e.touches[0].clientX;
      y0 = e.touches[0].clientY;
    }, { passive: true });
    el.addEventListener("touchend", (e) => {
      if (x0 === null) return;
      const dx = e.changedTouches[0].clientX - x0;
      const dy = e.changedTouches[0].clientY - y0;
      x0 = null;
      if (Math.abs(dx) < 45 || Math.abs(dx) < Math.abs(dy)) return;
      (dx < 0 ? onLeft : onRight)();
    }, { passive: true });
  }

  function initSlider(viewport, track, prevBtn, nextBtn) {
    if (!track) return;
    let index = 0;
    const step = () => {
      const card = track.children[0];
      if (!card) return 0;
      const gap = parseInt(getComputedStyle(track).columnGap) || 0;
      return card.getBoundingClientRect().width + gap;
    };
    const maxIndex = () => {
      const visible = Math.round(viewport.clientWidth / step());
      return Math.max(0, track.children.length - visible);
    };
    const apply = () => { track.style.transform = `translateX(-${index * step()}px)`; };
    const go = (d) => { index = Math.max(0, Math.min(maxIndex(), index + d)); apply(); };
    if (prevBtn) prevBtn.addEventListener("click", () => go(-1));
    if (nextBtn) nextBtn.addEventListener("click", () => go(1));
    window.addEventListener("resize", () => { index = Math.min(index, maxIndex()); apply(); });
    addSwipe(viewport, () => go(1), () => go(-1));
  }

  function initRoomsCarousel() {
    const viewport = document.querySelector("[data-rooms-viewport]");
    const track = document.querySelector("[data-rooms-track]");
    if (!viewport || !track) return;
    const slides = Array.from(track.children);
    const prevBtn = document.querySelector("[data-rooms-prev]");
    const nextBtn = document.querySelector("[data-rooms-next]");
    const current = document.querySelector("[data-rooms-current]");
    const total = document.querySelector("[data-rooms-total]");
    let index = 0;

    if (total) total.textContent = String(slides.length).padStart(2, "0");

    const apply = () => {
      const slideW = slides[0].offsetWidth;
      const gap = parseInt(getComputedStyle(track).columnGap) || 0;
      const center = viewport.clientWidth / 2 - slideW / 2;
      const offset = center - index * (slideW + gap);
      track.style.transform = `translateX(${offset}px)`;
      slides.forEach((s, i) => s.classList.toggle("is-active", i === index));
      if (current) current.textContent = String(index + 1).padStart(2, "0");
    };
    const go = (dir) => {
      index = (index + dir + slides.length) % slides.length;
      apply();
    };

    prevBtn.addEventListener("click", () => { go(-1); resetAuto(); });
    nextBtn.addEventListener("click", () => { go(1); resetAuto(); });
    window.addEventListener("resize", apply);

    let timer = null;
    const resetAuto = () => {
      if (reduceMotion) return;
      clearInterval(timer);
      timer = setInterval(() => go(1), 5000);
    };
    resetAuto();
    viewport.addEventListener("mouseenter", () => clearInterval(timer));
    viewport.addEventListener("mouseleave", resetAuto);
    addSwipe(viewport, () => { go(1); resetAuto(); }, () => { go(-1); resetAuto(); });

    apply();
  }
  initRoomsCarousel();

  function initGuestsPicker() {
    const toggle = document.querySelector("[data-guests-toggle]");
    const popover = document.querySelector("[data-guests-popover]");
    const summary = document.querySelector("[data-guests-summary]");
    if (!toggle || !popover) return;
    const counts = { adult: 1, child: 0 };
    const heroForm = toggle.closest("form");

    const render = () => {
      Object.keys(counts).forEach((k) => {
        const el = popover.querySelector(`[data-count="${k}"]`);
        if (el) el.textContent = counts[k];
      });
      const parts = [`${counts.adult} Adult${counts.adult !== 1 ? "s" : ""}`];
      if (counts.child > 0) parts.push(`${counts.child} Child${counts.child !== 1 ? "ren" : ""}`);
      summary.textContent = parts.join(", ");
      const aInput = heroForm?.querySelector('[name="adults"]');
      const cInput = heroForm?.querySelector('[name="children"]');
      if (aInput) aInput.value = counts.adult;
      if (cInput) cInput.value = counts.child;
    };

    toggle.addEventListener("click", (e) => {
      e.stopPropagation();
      const open = popover.hasAttribute("hidden");
      popover.toggleAttribute("hidden", !open);
      toggle.setAttribute("aria-expanded", String(open));
    });
    popover.addEventListener("click", (e) => {
      const btn = e.target.closest("[data-step]");
      if (!btn) { e.stopPropagation(); return; }
      e.stopPropagation();
      const key = btn.dataset.step;
      const dir = parseInt(btn.dataset.dir, 10);
      const min = key === "adult" ? 1 : 0;
      counts[key] = Math.max(min, Math.min(20, counts[key] + dir));
      render();
    });
    document.addEventListener("click", () => {
      popover.setAttribute("hidden", "");
      toggle.setAttribute("aria-expanded", "false");
    });

    render();
  }
  initGuestsPicker();

  initSlider(
    document.querySelector("[data-tm-viewport]"),
    document.querySelector("[data-tm-track]"),
    document.querySelector("[data-tm-prev]"),
    document.querySelector("[data-tm-next]")
  );

  initSlider(
    document.querySelector("[data-dining-viewport]"),
    document.querySelector("[data-dining-track]"),
    document.querySelector("[data-dining-prev]"),
    document.querySelector("[data-dining-next]")
  );

  initSlider(
    document.querySelector("[data-activity-viewport]"),
    document.querySelector("[data-activity-track]"),
    document.querySelector("[data-activity-prev]"),
    document.querySelector("[data-activity-next]")
  );

  const statNums = document.querySelectorAll(".stat__num, .fact__num");
  const countObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      const el = entry.target;
      const target = parseFloat(el.dataset.to);
      const isFloat = !Number.isInteger(target);
      if (reduceMotion) {
        el.textContent = isFloat ? target.toFixed(1) : target.toLocaleString();
        countObserver.unobserve(el);
        return;
      }
      const duration = 2000;
      const start = performance.now();
      const tick = (now) => {
        const p = Math.min((now - start) / duration, 1);
        el.textContent = isFloat
          ? (p * target).toFixed(1)
          : Math.floor(p * target).toLocaleString();
        if (p < 1) requestAnimationFrame(tick);
      };
      requestAnimationFrame(tick);
      countObserver.unobserve(el);
    });
  }, { threshold: 0.5 });
  statNums.forEach((el) => countObserver.observe(el));

  function initActivityToast() {
    const widget = document.getElementById("activityToast");
    if (!widget) return;
    const iconEl = widget.querySelector("[data-toast-icon]");
    const titleEl = widget.querySelector("[data-toast-title]");
    const metaEl = widget.querySelector("[data-toast-meta]");

    const ICON = {
      booking: '<svg viewBox="0 0 24 24" width="19" height="19" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M16 2v4M8 2v4M3 10h18M9 16l2 2 4-4"/></svg>',
      review: '<svg viewBox="0 0 24 24" width="19" height="19" fill="currentColor"><path d="M12 2l3 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.9 21l1.2-6.8-5-4.9 6.9-1z"/></svg>',
      viewing: '<svg viewBox="0 0 24 24" width="19" height="19" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>',
    };
    const items = [
      { icon: "booking", title: "Booked 10 times", meta: "in the last 24 hours" },
      { icon: "review", title: "Anna just left a review", meta: "“Breathtaking sea views” · 2 min ago" },
      { icon: "booking", title: "Marco booked a Beach Villa", meta: "35 minutes ago" },
      { icon: "viewing", title: "7 people are viewing the resort", meta: "right now" },
      { icon: "review", title: "Giulia rated her stay 5 stars", meta: "1 hour ago" },
    ];

    let i = 0;
    const apply = () => {
      const it = items[i % items.length];
      i += 1;
      iconEl.innerHTML = ICON[it.icon];
      titleEl.textContent = it.title;
      metaEl.textContent = it.meta;
    };

    apply();
    setInterval(() => {
      if (reduceMotion) { apply(); return; }
      widget.classList.add("is-fading");
      setTimeout(() => { apply(); widget.classList.remove("is-fading"); }, 320);
    }, 6000);
  }
  initActivityToast();

  function initResortActivity() {
    const widget = document.querySelector("[data-resort-activity]");
    if (!widget) return;
    const iconEl = widget.querySelector("[data-activity-icon]");
    const textEl = widget.querySelector("[data-activity-text]");

    const ICON = {
      booking: '<svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M16 2v4M8 2v4M3 10h18M9 16l2 2 4-4"/></svg>',
      viewing: '<svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>',
      review: '<svg viewBox="0 0 24 24" width="17" height="17" fill="currentColor"><path d="M12 2l3 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.9 21l1.2-6.8-5-4.9 6.9-1z"/></svg>',
      spa: '<svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>',
      sun: '<svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>',
    };
    const items = [
      { icon: "booking", text: "Maria just booked an Ocean Suite" },
      { icon: "viewing", text: "9 guests are exploring the resort right now" },
      { icon: "review", text: "“A slice of paradise” — new 5-star review" },
      { icon: "sun", text: "Sunset dhow cruise — 4 seats left today" },
      { icon: "spa", text: "Spa is fully booked this weekend" },
      { icon: "booking", text: "James checked in this morning" },
    ];

    let i = 0;
    const apply = () => {
      const it = items[i % items.length];
      i += 1;
      iconEl.innerHTML = ICON[it.icon];
      textEl.textContent = it.text;
    };

    apply();
    setInterval(() => {
      if (reduceMotion) { apply(); return; }
      widget.classList.add("is-fading");
      setTimeout(() => { apply(); widget.classList.remove("is-fading"); }, 320);
    }, 5000);
  }
  initResortActivity();

  function initEnquiryForm() {
    const form = document.getElementById("enquiryForm");
    if (!form) return;
    const steps = form.querySelectorAll(".hero-step");
    const show = (n) => steps.forEach((s) => { s.hidden = s.dataset.step !== String(n); });

    const ci = form.querySelector("#enqCheckin");
    const co = form.querySelector("#enqCheckout");
    if (ci && co) {
      ci.addEventListener("change", () => {
        if (!ci.value) return;
        const next = new Date(ci.value);
        next.setDate(next.getDate() + 1);
        const min = next.toISOString().slice(0, 10);
        co.min = min;
        if (co.value && co.value <= ci.value) co.value = min;
      });
    }

    form.querySelector("[data-enq-next]").addEventListener("click", () => show(2));
    const back = form.querySelector("[data-enq-back]");
    if (back) back.addEventListener("click", () => show(1));

    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      const btn = form.querySelector("[data-enq-send]");
      const errEl = form.querySelector("[data-enq-error]");
      btn.disabled = true;
      btn.textContent = "Sending…";
      if (errEl) errEl.hidden = true;

      try {
        const res = await fetch("/api/submit-enquiry.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(collectFields(form)),
        });
        const json = await res.json();
        if (json.ok) {
          show(3);
        } else {
          btn.disabled = false;
          btn.textContent = "Send Enquiry ›";
          if (errEl) {
            errEl.textContent = json.error || "Something went wrong. Please try again.";
            errEl.hidden = false;
          }
        }
      } catch {
        btn.disabled = false;
        btn.textContent = "Send Enquiry ›";
        if (errEl) {
          errEl.textContent = "Network error. Please check your connection.";
          errEl.hidden = false;
        }
      }
    });
  }
  initEnquiryForm();

  function initAboutHeroSlider() {
    const slidesWrap = document.querySelector("[data-about-slides]");
    if (!slidesWrap) return;
    const slides = Array.from(slidesWrap.children);
    const dots = Array.from(document.querySelectorAll("[data-about-dots] .about-hero__dot"));
    if (slides.length < 2) return;
    let i = 0;
    let timer = null;
    const go = (n) => {
      slides[i].classList.remove("is-active");
      if (dots[i]) dots[i].classList.remove("is-active");
      i = (n + slides.length) % slides.length;
      slides[i].classList.add("is-active");
      if (dots[i]) dots[i].classList.add("is-active");
    };
    const start = () => {
      if (reduceMotion) return;
      clearInterval(timer);
      timer = setInterval(() => go(i + 1), 5000);
    };
    dots.forEach((dot, idx) => dot.addEventListener("click", () => { go(idx); start(); }));
    start();
  }
  initAboutHeroSlider();

  // --- Form submission handlers ---

  function showFeedback(el, ok, msg) {
    if (!el) return;
    el.hidden = false;
    el.className = "form-feedback " + (ok ? "form-feedback--ok" : "form-feedback--err");
    el.textContent = msg;
  }

  function collectFields(form) {
    const data = {};
    new FormData(form).forEach((v, k) => { data[k] = v; });
    return data;
  }

  function highlightErrors(form, errors) {
    form.querySelectorAll(".field-error").forEach((el) => el.remove());
    Object.entries(errors).forEach(([name, msg]) => {
      const input = form.querySelector(`[name="${name}"]`);
      if (!input) return;
      input.classList.add("is-invalid");
      const err = document.createElement("span");
      err.className = "field-error";
      err.textContent = msg;
      input.closest(".field, .booking-field")?.appendChild(err);
    });
  }

  function clearErrors(form) {
    form.querySelectorAll(".is-invalid").forEach((el) => el.classList.remove("is-invalid"));
    form.querySelectorAll(".field-error").forEach((el) => el.remove());
  }

  async function submitForm(endpoint, data, form, feedbackEl, successMsg) {
    const btn = form.querySelector("[type=submit]");
    btn.disabled = true;
    btn.textContent = "Sending…";
    clearErrors(form);

    try {
      const res = await fetch(endpoint, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      });
      const json = await res.json();

      if (json.ok) {
        const msg = json.mode === 'hold'
          ? "Your request has been received and your dates are held for 24 hours. We'll confirm your booking shortly."
          : successMsg;
        form.innerHTML = `<p class="form-success">${msg}</p>`;
      } else if (json.errors) {
        highlightErrors(form, json.errors);
        showFeedback(feedbackEl, false, "Please fix the errors above.");
        btn.disabled = false;
        btn.textContent = "Send";
      } else {
        showFeedback(feedbackEl, false, json.error || "Something went wrong. Please try again.");
        btn.disabled = false;
        btn.textContent = "Send";
      }
    } catch {
      showFeedback(feedbackEl, false, "Network error. Please check your connection and try again.");
      btn.disabled = false;
      btn.textContent = "Send";
    }
  }

  // Room enquiry form
  const roomForm = document.getElementById("roomEnquiryForm");
  if (roomForm) {
    const feedback = document.getElementById("roomEnquiryFeedback");
    roomForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const data = collectFields(roomForm);
      data.room_slug = roomForm.dataset.roomSlug || "";
      data.room_name = roomForm.dataset.roomName || "";
      submitForm("/api/submit-enquiry.php", data, roomForm, feedback,
        "Thank you! We have received your enquiry and will be in touch shortly.");
    });
  }

  // Contact form
  const contactForm = document.getElementById("contactForm");
  if (contactForm) {
    const feedback = document.getElementById("contactFeedback");
    contactForm.addEventListener("submit", (e) => {
      e.preventDefault();
      submitForm("/api/submit-contact.php", collectFields(contactForm), contactForm, feedback,
        "Thank you for your message! We will get back to you as soon as possible.");
    });
  }

  // Agency form
  const agencyForm = document.getElementById("agencyForm");
  if (agencyForm) {
    const feedback = document.getElementById("agencyFeedback");
    agencyForm.addEventListener("submit", (e) => {
      e.preventDefault();
      submitForm("/api/submit-agency.php", collectFields(agencyForm), agencyForm, feedback,
        "Thank you! Your agency registration request has been received. We will be in touch shortly.");
    });
  }

  // Spa booking form
  const spaContactForm = document.getElementById("spaContactForm");
  if (spaContactForm) {
    const feedback = document.getElementById("spaContactFeedback");
    spaContactForm.addEventListener("submit", (e) => {
      e.preventDefault();
      submitForm("/api/submit-contact.php", collectFields(spaContactForm), spaContactForm, feedback,
        "Thank you! We have received your request and our spa team will confirm your booking shortly.");
    });
  }

  // Tours page general enquiry form
  const toursContactForm = document.getElementById("toursContactForm");
  if (toursContactForm) {
    const feedback = document.getElementById("toursContactFeedback");
    toursContactForm.addEventListener("submit", (e) => {
      e.preventDefault();
      submitForm("/api/submit-contact.php", collectFields(toursContactForm), toursContactForm, feedback,
        "Thank you! We have received your enquiry and will reply within 24 hours.");
    });
  }
});
