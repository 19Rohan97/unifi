(function () {
  "use strict";

  /* ============ Cable type selector ============ */
  var tabs = Array.prototype.slice.call(document.querySelectorAll(".cable-spine__btn"));
  var panels = Array.prototype.slice.call(document.querySelectorAll(".cable-panel"));
  var stage = document.querySelector(".cable-stage");
  var stageName = document.querySelector(".cable-stage__name");

  if (tabs.length && panels.length && stage) {
    var NAMES = {
      cat5e: "Cat5e",
      cat6: "Cat6",
      cat6a: "Cat6A",
      cat7: "Cat7",
      rated: "CMP / CMR"
    };

    var select = function (tab) {
      var type = tab.getAttribute("data-cable");

      tabs.forEach(function (t) {
        var active = t === tab;
        t.classList.toggle("is-active", active);
        t.setAttribute("aria-selected", active ? "true" : "false");
      });

      panels.forEach(function (p) {
        var show = p.id === "panel-" + type;
        if (show) {
          p.removeAttribute("hidden");
          // restart entry animation + meter growth
          p.style.animation = "none";
          void p.offsetWidth; // reflow
          p.style.animation = "";
        } else {
          p.setAttribute("hidden", "");
        }
      });

      stage.setAttribute("data-cable", type);
      if (stageName) stageName.textContent = NAMES[type] || type;
    };

    tabs.forEach(function (tab) {
      tab.addEventListener("click", function () { select(tab); });
    });

    // Arrow-key support on the tablist
    var tablist = document.querySelector(".cable-spine");
    if (tablist) {
      tablist.addEventListener("keydown", function (e) {
        var idx = tabs.indexOf(document.activeElement);
        if (idx === -1) return;
        var next = -1;
        if (e.key === "ArrowDown" || e.key === "ArrowRight") next = (idx + 1) % tabs.length;
        if (e.key === "ArrowUp" || e.key === "ArrowLeft") next = (idx - 1 + tabs.length) % tabs.length;
        if (next >= 0) {
          e.preventDefault();
          tabs[next].focus();
          select(tabs[next]);
        }
      });
    }
  }

  /* ============ 3D stage: pointer parallax tilt ============ */
  var tilt = document.querySelector(".cable-tilt");
  var lab = document.querySelector(".cable-lab");
  var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  if (tilt && lab && !reduceMotion && window.matchMedia("(pointer: fine)").matches) {
    var raf = 0;
    var target = { x: 0, y: 0 };

    lab.addEventListener("mousemove", function (e) {
      var rect = stage.getBoundingClientRect();
      var cx = rect.left + rect.width / 2;
      var cy = rect.top + rect.height / 2;
      // normalized -1..1, clamped
      var nx = Math.max(-1, Math.min(1, (e.clientX - cx) / (rect.width)));
      var ny = Math.max(-1, Math.min(1, (e.clientY - cy) / (rect.height)));
      target.x = nx * 10; // rotateY degrees
      target.y = ny * -8; // rotateX degrees
      if (!raf) {
        raf = requestAnimationFrame(function () {
          tilt.style.transform = "rotateY(" + target.x.toFixed(2) + "deg) rotateX(" + target.y.toFixed(2) + "deg)";
          raf = 0;
        });
      }
    });

    lab.addEventListener("mouseleave", function () {
      tilt.style.transform = "";
    });
  }
})();
