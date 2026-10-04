/* =========================================================
   bulbula.et — coming soon
   GSAP (timelines, loops, parallax) + Motion (springy micro-interactions)
   ========================================================= */
(function () {
  "use strict";

  var hasGSAP   = typeof window.gsap !== "undefined";
  var hasMotion = typeof window.Motion !== "undefined";
  var animate   = hasMotion ? window.Motion.animate : null;
  var spring    = hasMotion && window.Motion.spring ? window.Motion.spring : null;
  var reduced   = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  /* ---------- small utilities ---------- */
  var $  = function (s, c) { return (c || document).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };

  document.documentElement.classList.remove("no-js");
  var yearEl = $("[data-year]");
  if (yearEl) yearEl.textContent = String(new Date().getFullYear());

  /* ---------- split the headline into animatable words ---------- */
  var title = $("[data-split]");
  var words = [];
  if (title) {
    var accent = ["Ethiopia,"];           // words painted with the brand gradient
    var parts  = title.textContent.trim().split(/\s+/);
    title.textContent = "";
    parts.forEach(function (w, i) {
      var wrap = document.createElement("span");
      wrap.style.display = "inline-block";
      wrap.style.overflow = "hidden";
      wrap.style.verticalAlign = "top";
      var inner = document.createElement("span");
      inner.className = "word" + (accent.indexOf(w) > -1 ? " word--accent" : "");
      inner.textContent = w;
      wrap.appendChild(inner);
      title.appendChild(wrap);
      if (i < parts.length - 1) title.appendChild(document.createTextNode(" "));
      words.push(inner);
    });
  }

  /* ---------- seamless marquee ---------- */
  var track = $("[data-marquee]");
  if (track) {
    track.innerHTML += track.innerHTML;   // duplicate for a loop with no gap
    if (hasGSAP && !reduced) {
      gsap.to(track, { xPercent: -50, duration: 38, ease: "none", repeat: -1 });
    }
  }

  /* ---------- intro timeline ---------- */
  if (hasGSAP && !reduced) {
    gsap.set("[data-anim], .marquee, [data-card]", { opacity: 0 });
    gsap.set(words, { yPercent: 115, opacity: 0 });

    var tl = gsap.timeline({ defaults: { ease: "power3.out" }, delay: 0.12 });

    tl.from(".brand", { y: -14, opacity: 0, duration: 0.7 })
      .from(".site-head__right > *", { y: -10, opacity: 0, duration: 0.6, stagger: 0.08 }, "<0.05")
      .to("[data-anim='badge']", { opacity: 1, y: 0, duration: 0.6 }, "-=0.35")
      .from("[data-anim='badge']", { y: 12, duration: 0.6 }, "<")
      .to(words, { yPercent: 0, opacity: 1, duration: 0.95, stagger: 0.045 }, "-=0.35")
      .to("[data-anim='sub']", { opacity: 1, duration: 0.8 }, "-=0.6")
      .from("[data-anim='sub']", { y: 16, duration: 0.8 }, "<")
      .to("[data-anim='form']", { opacity: 1, duration: 0.7 }, "-=0.55")
      .from("[data-anim='form']", { y: 20, scale: 0.985, duration: 0.7 }, "<")
      .to("[data-anim='stats']", { opacity: 1, duration: 0.7, onStart: runCounters }, "-=0.45")
      .from("[data-anim='stats'] li", { y: 18, duration: 0.7, stagger: 0.09 }, "<")
      .to(".marquee", { opacity: 1, duration: 0.7 }, "-=0.45")
      .to("[data-card]", { opacity: 1, duration: 0.7, stagger: 0.1 }, "-=0.4")
      .from("[data-card]", { y: 26, duration: 0.8, stagger: 0.1 }, "<");

    /* drifting orbs */
    $$("[data-orb]").forEach(function (orb, i) {
      gsap.to(orb, {
        xPercent: i % 2 ? -12 : 14,
        yPercent: i % 2 ? 10 : -8,
        duration: 16 + i * 5,
        ease: "sine.inOut",
        repeat: -1,
        yoyo: true
      });
    });

    /* pointer parallax */
    var qx = [], qy = [];
    var orbs = $$("[data-orb]");
    orbs.forEach(function (orb) {
      qx.push(gsap.quickTo(orb, "x", { duration: 1.1, ease: "power3" }));
      qy.push(gsap.quickTo(orb, "y", { duration: 1.1, ease: "power3" }));
    });
    window.addEventListener("pointermove", function (e) {
      var nx = e.clientX / window.innerWidth - 0.5;
      var ny = e.clientY / window.innerHeight - 0.5;
      orbs.forEach(function (orb, i) {
        var d = parseFloat(orb.dataset.depth || 14);
        qx[i](nx * d * 2);
        qy[i](ny * d * 2);
      });
    }, { passive: true });
  } else {
    gsapless();
    runCounters();
  }

  function gsapless() {
    $$("[data-anim], .marquee, [data-card]").forEach(function (el) { el.style.opacity = 1; });
    words.forEach(function (w) { w.style.opacity = 1; });
  }

  /* ---------- animated stat counters ---------- */
  var countersDone = false;
  function runCounters() {
    if (countersDone) return;
    countersDone = true;
    $$("[data-count]").forEach(function (el) {
      var target = parseInt(el.dataset.count, 10) || 0;
      if (reduced || !hasGSAP) { el.textContent = fmt(target); return; }
      var obj = { v: 0 };
      gsap.to(obj, {
        v: target,
        duration: 1.9,
        ease: "power2.out",
        onUpdate: function () { el.textContent = fmt(Math.round(obj.v)); }
      });
    });
  }
  function fmt(n) {
    return n >= 1000 ? (n / 1000).toFixed(n % 1000 === 0 ? 0 : 1) + "k+" : String(n) + "+";
  }

  /* ---------- Motion: springy hover / press on interactive bits ---------- */
  if (animate && !reduced) {
    $$("[data-magnet]").forEach(function (el) {
      var opts = spring ? { easing: spring({ stiffness: 320, damping: 18 }) } : { duration: 0.25 };
      el.addEventListener("pointerenter", function () { animate(el, { scale: 1.045 }, opts); });
      el.addEventListener("pointerleave", function () { animate(el, { scale: 1 }, opts); });
      el.addEventListener("pointerdown",  function () { animate(el, { scale: 0.96 }, { duration: 0.12 }); });
      el.addEventListener("pointerup",    function () { animate(el, { scale: 1.045 }, opts); });
    });
  }

  /* ---------- card cursor glow ---------- */
  $$("[data-card]").forEach(function (card) {
    card.addEventListener("pointermove", function (e) {
      var r = card.getBoundingClientRect();
      card.style.setProperty("--mx", (e.clientX - r.left) + "px");
      card.style.setProperty("--my", (e.clientY - r.top) + "px");
    }, { passive: true });
  });

  /* ---------- waitlist form ---------- */
  var form  = $("#waitlist");
  var input = $("#email", form);
  var note  = $("[data-note]", form);
  var ENDPOINT = ""; // e.g. "https://api.bulbula.et/waitlist" (Laravel) or a Formspree URL

  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      var email = (input.value || "").trim();

      if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email)) {
        setNote("Please enter a valid email address.", "is-error");
        shake(form.querySelector(".waitlist__field"));
        input.focus();
        return;
      }

      var btn = form.querySelector(".btn--primary");
      var label = form.querySelector(".btn__label");
      btn.disabled = true;
      label.textContent = "Saving…";

      submit(email)
        .then(function () {
          try {
            var list = JSON.parse(localStorage.getItem("bulbula:waitlist") || "[]");
            if (list.indexOf(email) === -1) list.push(email);
            localStorage.setItem("bulbula:waitlist", JSON.stringify(list));
          } catch (err) { /* storage blocked — ignore */ }

          form.classList.add("is-done");
          label.textContent = "You're on the list ✓";
          input.value = "";
          input.placeholder = "See you at launch";
          input.disabled = true;
          setNote("Thank you — we'll email " + email + " the moment Bulbula opens.", "is-ok");
          if (animate && !reduced) {
            animate(btn, { scale: [1, 1.07, 1] }, { duration: 0.5 });
            animate(note, { opacity: [0, 1], y: [8, 0] }, { duration: 0.45 });
          }
        })
        .catch(function () {
          btn.disabled = false;
          label.textContent = "Get early access";
          setNote("Something went wrong. Please try again or email hello@bulbula.et.", "is-error");
        });
    });
  }

  function submit(email) {
    if (!ENDPOINT) return Promise.resolve();            // no backend yet → local only
    return fetch(ENDPOINT, {
      method: "POST",
      headers: { "Content-Type": "application/json", Accept: "application/json" },
      body: JSON.stringify({ email: email })
    }).then(function (r) { if (!r.ok) throw new Error("bad response"); });
  }

  function setNote(msg, cls) {
    if (!note) return;
    note.textContent = msg;
    note.className = "waitlist__note " + (cls || "");
  }

  function shake(el) {
    if (!el) return;
    if (hasGSAP && !reduced) {
      gsap.fromTo(el, { x: -8 }, { x: 0, duration: 0.55, ease: "elastic.out(1,0.3)" });
    } else if (animate && !reduced) {
      animate(el, { x: [-8, 6, -4, 0] }, { duration: 0.4 });
    }
  }
})();
