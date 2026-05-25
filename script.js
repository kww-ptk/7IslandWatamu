document.addEventListener("DOMContentLoaded", () => {
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

    const render = () => {
      Object.keys(counts).forEach((k) => {
        const el = popover.querySelector(`[data-count="${k}"]`);
        if (el) el.textContent = counts[k];
      });
      const parts = [`${counts.adult} Adult${counts.adult !== 1 ? "s" : ""}`];
      if (counts.child > 0) parts.push(`${counts.child} Child${counts.child !== 1 ? "ren" : ""}`);
      summary.textContent = parts.join(", ");
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
    const val = (id) => (form.querySelector("#" + id)?.value || "").trim();

    form.querySelector("[data-enq-next]").addEventListener("click", () => show(2));
    const back = form.querySelector("[data-enq-back]");
    if (back) back.addEventListener("click", () => show(1));

    form.addEventListener("submit", (e) => {
      e.preventDefault();
      const name = val("enqName");
      const email = val("enqEmail");
      const guests = (document.querySelector("[data-guests-summary]")?.textContent || "").trim();
      const body = [
        "Name: " + name,
        "Email: " + email,
        "Phone: " + (val("enqPhone") || "—"),
        "",
        "Check in: " + (val("enqCheckin") || "—"),
        "Check out: " + (val("enqCheckout") || "—"),
        "Guests: " + (guests || "—"),
        "",
        "Message:",
        val("enqMsg") || "—",
      ].join("\n");
      window.location.href =
        "mailto:reservation@sevenislandswatamu.com" +
        "?subject=" + encodeURIComponent("Stay enquiry — " + name) +
        "&body=" + encodeURIComponent(body);
      show(3);
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
});
