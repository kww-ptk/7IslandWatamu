/**
 * custom-select.js — Replaces native <select> with accessible custom dropdowns.
 *
 * Auto-initializes on all <select> elements at DOMContentLoaded.
 * Skip a select with the data-cs-skip attribute.
 *
 * Auto-submit: if the original <select> had onchange="this.form.submit()"
 * or class="js-auto-submit", selecting an option also submits the form.
 *
 * The original <select> stays in the DOM (hidden) for form submission.
 */
(function () {
  "use strict";

  // Close all open menus, optionally skipping one
  function closeAll(except) {
    document.querySelectorAll(".cs-menu:not([hidden])").forEach(m => {
      if (m === except) return;
      m.hidden = true;
      const t = m.previousElementSibling;
      if (t && t.classList.contains("cs-trigger")) t.setAttribute("aria-expanded", "false");
    });
  }

  function initSelect(sel) {
    if (sel.dataset.csInit) return;
    sel.dataset.csInit = "1";

    const autoSubmit = sel.hasAttribute("onchange") || sel.classList.contains("js-auto-submit");
    const opts = [...sel.options];

    // ── Wrapper ──────────────────────────────────────────────────────
    const wrap = document.createElement("div");
    wrap.className = "cs-wrap";

    // ── Trigger button ────────────────────────────────────────────────
    const trigger = document.createElement("button");
    trigger.type  = "button";
    trigger.className = "cs-trigger";
    trigger.setAttribute("aria-haspopup", "listbox");
    trigger.setAttribute("aria-expanded", "false");

    const valSpan = document.createElement("span");
    valSpan.className = "cs-trigger__val";
    const selOpt = opts.find(o => o.selected) || opts[0];
    valSpan.textContent = selOpt?.text || "";

    trigger.appendChild(valSpan);
    trigger.insertAdjacentHTML("beforeend",
      `<span class="cs-caret" aria-hidden="true"><svg width="12" height="12" viewBox="0 0 12 12" fill="none">` +
      `<path d="M2 4l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>`);

    // ── Dropdown menu ─────────────────────────────────────────────────
    const menu = document.createElement("ul");
    menu.className = "cs-menu";
    menu.setAttribute("role", "listbox");
    menu.hidden = true;

    opts.forEach(opt => {
      const li = document.createElement("li");
      li.className  = "cs-item" + (opt.selected ? " cs-item--active" : "");
      li.setAttribute("role", "option");
      li.setAttribute("aria-selected", String(opt.selected));
      li.dataset.value = opt.value;
      li.textContent = opt.text;
      menu.appendChild(li);
    });

    // ── Hide native select, keep for form submission ──────────────────
    sel.setAttribute("aria-hidden", "true");
    sel.style.cssText = "position:absolute;opacity:0;pointer-events:none;height:0;width:0;min-width:0;overflow:hidden";

    // ── Inject into DOM ───────────────────────────────────────────────
    sel.parentNode.insertBefore(wrap, sel);
    wrap.appendChild(trigger);
    wrap.appendChild(menu);
    wrap.appendChild(sel);

    // ── Toggle open / close ───────────────────────────────────────────
    trigger.addEventListener("click", e => {
      e.stopPropagation();
      const wasOpen = !menu.hidden;
      closeAll(null);
      if (!wasOpen) {
        menu.hidden = false;
        trigger.setAttribute("aria-expanded", "true");
      }
    });

    // ── Select an option ──────────────────────────────────────────────
    menu.addEventListener("click", e => {
      const item = e.target.closest(".cs-item");
      if (!item) return;
      menu.querySelectorAll(".cs-item").forEach(i => {
        i.classList.toggle("cs-item--active", i === item);
        i.setAttribute("aria-selected", String(i === item));
      });
      valSpan.textContent = item.textContent;
      sel.value = item.dataset.value;
      sel.dispatchEvent(new Event("change", { bubbles: true }));
      menu.hidden = true;
      trigger.setAttribute("aria-expanded", "false");
      if (autoSubmit) sel.closest("form")?.submit();
    });

    // ── Keyboard navigation ────────────────────────────────────────────
    trigger.addEventListener("keydown", e => {
      if (e.key === "Enter" || e.key === " ") {
        e.preventDefault(); trigger.click();
      } else if (e.key === "ArrowDown" && menu.hidden) {
        e.preventDefault(); trigger.click();
      } else if (e.key === "Escape") {
        menu.hidden = true; trigger.setAttribute("aria-expanded", "false");
      }
    });

    menu.addEventListener("keydown", e => {
      const items = [...menu.querySelectorAll(".cs-item")];
      const idx   = items.findIndex(i => i.classList.contains("cs-item--active"));
      if (e.key === "ArrowDown") {
        e.preventDefault();
        items[Math.min(idx + 1, items.length - 1)]?.click();
      } else if (e.key === "ArrowUp") {
        e.preventDefault();
        items[Math.max(idx - 1, 0)]?.click();
      } else if (e.key === "Escape") {
        menu.hidden = true;
        trigger.setAttribute("aria-expanded", "false");
        trigger.focus();
      }
    });
  }

  // Close menus on outside click
  document.addEventListener("click", () => closeAll(null));

  function init() {
    document.querySelectorAll("select:not([data-cs-skip])").forEach(initSelect);
  }

  if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", init);
  else init();
})();
