document.addEventListener("DOMContentLoaded", () => {
  // ---- Search bar bk-cal date picker ----
  function initSbCalendar() {
    const pop      = document.getElementById("sbBkPop");
    const grid     = document.getElementById("sbBkGrid");
    const monthLbl = document.getElementById("sbBkMonth");
    const prevBtn  = document.getElementById("sbBkPrev");
    const nextBtn  = document.getElementById("sbBkNext");
    const hintEl   = document.getElementById("sbBkHint");
    const doneBtn  = document.getElementById("sbBkDone");
    if (!pop || !grid) return;

    const ciBtn = document.getElementById("sbCheckinBtn");
    const coBtn = document.getElementById("sbCheckoutBtn");
    const ciVal = document.getElementById("sbCheckinVal");
    const coVal = document.getElementById("sbCheckoutVal");
    if (!ciBtn || !coBtn) return;

    const today = new Date(); today.setHours(0, 0, 0, 0);
    let viewYear  = today.getFullYear();
    let viewMonth = today.getMonth();
    let selStart  = null, selEnd = null;
    let picking   = null; // "ci" | "co"

    // Pre-fill from server-rendered hidden inputs (already validated YYYY-MM-DD)
    if (ciVal?.value) {
      selStart = new Date(ciVal.value + "T00:00");
      viewYear  = selStart.getFullYear();
      viewMonth = selStart.getMonth();
    }
    if (coVal?.value) selEnd = new Date(coVal.value + "T00:00");

    function ymd(d) {
      return d.getFullYear() + "-" + String(d.getMonth() + 1).padStart(2, "0") + "-" + String(d.getDate()).padStart(2, "0");
    }
    function parseYmd(s) { return new Date(s + "T00:00"); }
    function fmtDisp(d) {
      return d.toLocaleDateString("en-GB", { day: "numeric", month: "short", year: "numeric" });
    }

    function updateBtns() {
      if (selStart) {
        ciBtn.textContent = fmtDisp(selStart);
        ciBtn.classList.add("search-bar__date-btn--active");
        if (ciVal) ciVal.value = ymd(selStart);
      } else {
        ciBtn.textContent = "Arrival date";
        ciBtn.classList.remove("search-bar__date-btn--active");
        if (ciVal) ciVal.value = "";
      }
      if (selEnd) {
        coBtn.textContent = fmtDisp(selEnd);
        coBtn.classList.add("search-bar__date-btn--active");
        if (coVal) coVal.value = ymd(selEnd);
      } else {
        coBtn.textContent = "Departure date";
        coBtn.classList.remove("search-bar__date-btn--active");
        if (coVal) coVal.value = "";
      }
    }

    function renderCal() {
      const MONTHS = ["January","February","March","April","May","June","July","August","September","October","November","December"];
      monthLbl.textContent = `${MONTHS[viewMonth]} ${viewYear}`;

      const firstDay = new Date(viewYear, viewMonth, 1);
      const lastDay  = new Date(viewYear, viewMonth + 1, 0);
      const leading  = (firstDay.getDay() + 6) % 7; // Mon-first

      let html = "";
      for (let i = 0; i < leading; i++) html += `<div class="bk-cell bk-cell--blank"></div>`;
      for (let d = 1; d <= lastDay.getDate(); d++) {
        const date = new Date(viewYear, viewMonth, d);
        const key  = ymd(date);
        let cls = "bk-cell";
        if (date < today || (picking === "co" && selStart && date <= selStart)) {
          cls += " bk-cell--blocked";
        } else if (selStart && key === ymd(selStart)) {
          cls += " bk-cell--start";
        } else if (selEnd && key === ymd(selEnd)) {
          cls += " bk-cell--end";
        } else if (selStart && selEnd && date > selStart && date < selEnd) {
          cls += " bk-cell--in-range";
        }
        html += `<div class="${cls}" data-date="${key}">${d}</div>`;
      }
      grid.innerHTML = html;

      grid.querySelectorAll(".bk-cell:not(.bk-cell--blocked):not(.bk-cell--blank)").forEach(cell => {
        cell.addEventListener("click", () => onDayClick(cell.dataset.date));
        cell.addEventListener("mouseenter", () => onCellHover(cell.dataset.date));
      });
    }

    function onCellHover(dateStr) {
      if (selEnd || !selStart || picking !== "co") return;
      const start = ymd(selStart);
      grid.querySelectorAll(".bk-cell[data-date]").forEach(c => {
        c.classList.toggle("bk-cell--hover-range", c.dataset.date > start && c.dataset.date < dateStr);
      });
    }

    function clearHover() {
      grid.querySelectorAll(".bk-cell--hover-range").forEach(c => c.classList.remove("bk-cell--hover-range"));
    }
    grid.addEventListener("mouseleave", clearHover);

    function onDayClick(dateStr) {
      const clicked = parseYmd(dateStr);
      if (picking === "ci") {
        selStart = clicked;
        if (selEnd && selEnd <= selStart) selEnd = null;
        picking = "co";
        hintEl.textContent = "Now select your check-out date";
      } else {
        selEnd = clicked;
        hintEl.textContent = "Click Done to confirm your dates";
      }
      clearHover();
      renderCal();
      updateBtns();
    }

    function positionPop(trigger) {
      const r    = trigger.getBoundingClientRect();
      const popW = Math.min(310, window.innerWidth - 32);
      let left = r.left;
      if (left + popW > window.innerWidth - 16) left = window.innerWidth - popW - 16;
      if (left < 16) left = 16;
      pop.style.left = `${left}px`;
      pop.style.top  = `${r.bottom + 8}px`;
    }

    function openPop(triggerEl, mode) {
      if (mode === "co" && !selStart) mode = "ci";
      picking = mode;
      pop.hidden = false;
      positionPop(triggerEl);
      hintEl.textContent = mode === "ci"
        ? (selStart ? "Change your check-in date" : "Select your check-in date")
        : "Select your check-out date";
      renderCal();
    }

    function closePop() { pop.hidden = true; picking = null; }

    ciBtn.addEventListener("click", e => { e.stopPropagation(); openPop(ciBtn, "ci"); });
    coBtn.addEventListener("click", e => { e.stopPropagation(); openPop(coBtn, "co"); });
    doneBtn.addEventListener("click", closePop);
    pop.addEventListener("click", e => e.stopPropagation());

    document.addEventListener("click", e => {
      if (!pop.hidden && !pop.contains(e.target)) closePop();
    });
    document.addEventListener("keydown", e => { if (e.key === "Escape") closePop(); });
    window.addEventListener("scroll", () => {
      if (!pop.hidden) positionPop(picking === "ci" ? ciBtn : coBtn);
    }, { passive: true });
    window.addEventListener("resize", () => {
      if (!pop.hidden) positionPop(picking === "ci" ? ciBtn : coBtn);
    });

    prevBtn.addEventListener("click", () => {
      viewMonth--; if (viewMonth < 0) { viewMonth = 11; viewYear--; }
      renderCal();
    });
    nextBtn.addEventListener("click", () => {
      viewMonth++; if (viewMonth > 11) { viewMonth = 0; viewYear++; }
      renderCal();
    });
  }
  initSbCalendar();

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
