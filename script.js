(function () {
  "use strict";

  /* -------- Year in footer -------- */
  var yearEl = document.getElementById("year");
  if (yearEl) yearEl.textContent = String(new Date().getFullYear());

  /* -------- Mobile nav toggle -------- */
  var navToggle = document.querySelector(".nav-toggle");
  var primaryNav = document.querySelector(".primary-nav");
  if (navToggle && primaryNav) {
    navToggle.addEventListener("click", function () {
      var open = primaryNav.classList.toggle("is-open");
      navToggle.setAttribute("aria-expanded", open ? "true" : "false");
      navToggle.setAttribute("aria-label", open ? "Close menu" : "Open menu");
    });
    primaryNav.addEventListener("click", function (e) {
      if (e.target.tagName === "A" && primaryNav.classList.contains("is-open")) {
        primaryNav.classList.remove("is-open");
        navToggle.setAttribute("aria-expanded", "false");
      }
    });
  }

  /* -------- Nav dropdowns -------- */
  // Mobile: first tap on a dropdown parent expands it; second tap follows the link.
  document.querySelectorAll(".nav-item--dropdown > a").forEach(function (link) {
    link.addEventListener("click", function (e) {
      if (!window.matchMedia("(max-width: 720px)").matches) return;
      var li = link.parentElement;
      if (!li.classList.contains("is-open")) {
        e.preventDefault();
        e.stopPropagation(); // keep the mobile menu open while expanding
        document.querySelectorAll(".nav-item--dropdown.is-open").forEach(function (other) {
          if (other !== li) {
            other.classList.remove("is-open");
            other.querySelector("a").setAttribute("aria-expanded", "false");
          }
        });
        li.classList.add("is-open");
        link.setAttribute("aria-expanded", "true");
      }
    });
  });
  // Desktop: keep aria-expanded in sync with the CSS hover/focus state.
  document.querySelectorAll(".nav-item--dropdown").forEach(function (li) {
    var link = li.querySelector(":scope > a");
    li.addEventListener("mouseenter", function () {
      if (window.matchMedia("(min-width: 721px)").matches) link.setAttribute("aria-expanded", "true");
    });
    li.addEventListener("mouseleave", function () {
      if (window.matchMedia("(min-width: 721px)").matches) link.setAttribute("aria-expanded", "false");
    });
  });

  /* -------- FAQ: close siblings when one opens (single-open accordion) -------- */
  var faqItems = document.querySelectorAll(".faq-item");
  faqItems.forEach(function (item) {
    item.addEventListener("toggle", function () {
      if (item.open) {
        faqItems.forEach(function (other) {
          if (other !== item && other.open) other.open = false;
        });
      }
    });
  });

  /* -------- Quote form (front-end demo handler) -------- */
  var form = document.getElementById("quote-form");
  var success = document.getElementById("form-success");
  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();

      // Minimal validation — required fields handled by browser via required attr
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }

      // Collect data for demo logging
      var data = Object.fromEntries(new FormData(form).entries());
      var interests = Array.from(form.querySelectorAll('input[name="interest"]:checked, input[name="service"]:checked'))
        .map(function (el) { return el.value; });
      data.interests = interests;

      // In production this would POST to the backend / CRM endpoint.
      console.log("Quote form submitted:", data);

      form.reset();
      if (success) {
        success.hidden = false;
        success.scrollIntoView({ behavior: "smooth", block: "center" });
        setTimeout(function () { success.hidden = true; }, 6000);
      }
    });
  }

  /* -------- Smooth-scroll for in-page anchors (offset for sticky header) -------- */
  document.querySelectorAll('a[href^="#"]').forEach(function (a) {
    a.addEventListener("click", function (e) {
      var href = a.getAttribute("href");
      if (!href || href === "#") return;
      var target = document.querySelector(href);
      if (!target) return;
      e.preventDefault();
      var header = document.querySelector(".site-header");
      var offset = header ? header.offsetHeight + 8 : 0;
      var top = target.getBoundingClientRect().top + window.pageYOffset - offset;
      window.scrollTo({ top: top, behavior: "smooth" });
    });
  });

  /* -------- Reveal-on-scroll for cards / sections -------- */
  if ("IntersectionObserver" in window) {
    var revealEls = document.querySelectorAll(
      ".replaces-card, .category-card, .ts-card, .industry-card, .region-card, .post-card, .trust-bar-list li, .case-study-card"
    );
    revealEls.forEach(function (el) {
      el.style.opacity = "0";
      el.style.transform = "translateY(14px)";
      el.style.transition = "opacity .5s ease, transform .5s ease";
    });
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry, i) {
        if (entry.isIntersecting) {
          var el = entry.target;
          setTimeout(function () {
            el.style.opacity = "1";
            el.style.transform = "translateY(0)";
          }, i * 40);
          io.unobserve(el);
        }
      });
    }, { rootMargin: "0px 0px -40px 0px", threshold: 0.08 });
    revealEls.forEach(function (el) { io.observe(el); });
  }

  /* -------- Process slider (mobile): dot position + tap-to-jump -------- */
  var slider = document.querySelector(".process-timeline");
  var dots = document.querySelectorAll(".process-dot");
  var steps = document.querySelectorAll(".ts-step");
  if (slider && dots.length && steps.length) {
    var setActive = function (idx) {
      dots.forEach(function (d, i) {
        d.classList.toggle("is-active", i === idx);
      });
    };

    dots.forEach(function (dot, i) {
      dot.addEventListener("click", function () {
        var target = steps[i];
        if (!target) return;
        slider.scrollTo({
          left: target.offsetLeft - slider.offsetLeft,
          behavior: "smooth"
        });
      });
    });

    var updateActiveFromScroll = function () {
      var sliderRect = slider.getBoundingClientRect();
      var probe = sliderRect.left + 40; // detect the card sitting closest to the start
      var bestIdx = 0;
      var bestDist = Infinity;
      for (var i = 0; i < steps.length; i++) {
        var r = steps[i].getBoundingClientRect();
        var dist = Math.abs(r.left - probe);
        if (dist < bestDist) { bestDist = dist; bestIdx = i; }
      }
      setActive(bestIdx);
    };
    var scrollRaf;
    slider.addEventListener("scroll", function () {
      if (scrollRaf) return;
      scrollRaf = requestAnimationFrame(function () {
        updateActiveFromScroll();
        scrollRaf = 0;
      });
    }, { passive: true });
    updateActiveFromScroll();
  }

  /* -------- Header shadow on scroll -------- */
  var header = document.querySelector(".site-header");
  if (header) {
    var onScroll = function () {
      if (window.scrollY > 8) header.style.boxShadow = "0 6px 22px rgba(0,0,0,.25)";
      else header.style.boxShadow = "";
    };
    window.addEventListener("scroll", onScroll, { passive: true });
    onScroll();
  }
})();
