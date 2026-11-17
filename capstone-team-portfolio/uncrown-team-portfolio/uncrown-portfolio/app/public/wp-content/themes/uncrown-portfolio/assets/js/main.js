/* Uncrown Portfolio interactions. Everything degrades to a fully visible static page. */
(function () {
  'use strict';

  var root = document.documentElement;
  var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var finePointer = window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches;
  var hasIO = 'IntersectionObserver' in window;
  if (!reduceMotion) root.classList.add('has-motion');
  if (!reduceMotion && hasIO) root.classList.add('has-reveal');

  function clamp(v, a, b) { return Math.min(b, Math.max(a, v)); }

  /* ---------- Theme ---------- */
  var themeButton = document.querySelector('[data-theme-toggle]');
  function applyTheme(theme) {
    var t = theme === 'light' ? 'light' : 'dark';
    root.setAttribute('data-theme', t);
    if (themeButton) themeButton.setAttribute('aria-label', t === 'dark' ? 'Switch to light mode' : 'Switch to dark mode');
    document.dispatchEvent(new CustomEvent('og:theme'));
  }
  applyTheme(root.getAttribute('data-theme'));
  if (themeButton) {
    themeButton.addEventListener('click', function () {
      var next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
      applyTheme(next);
      try { window.localStorage.setItem('ctp-theme', next); } catch (e) { /* storage unavailable */ }
    });
  }

  /* ---------- Menu ---------- */
  var menuButton = document.querySelector('.menu-toggle');
  var nav = document.querySelector('.primary-nav');
  function setMenu(open) {
    if (!nav || !menuButton) return;
    nav.classList.toggle('is-open', open);
    document.body.classList.toggle('nav-open', open);
    menuButton.setAttribute('aria-expanded', open ? 'true' : 'false');
  }
  if (menuButton && nav) {
    menuButton.addEventListener('click', function () { setMenu(!nav.classList.contains('is-open')); });
    nav.addEventListener('click', function (e) { if (e.target.closest('a')) setMenu(false); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') setMenu(false); });
  }

  /* ---------- Header, progress bar, process, pinned work (one scroll loop) ---------- */
  var header = document.querySelector('[data-header]');
  var progress = document.querySelector('.scroll-progress');
  var process = document.querySelector('[data-process]');
  var processWrap = process ? process.querySelector('.process-wrap') : null;
  var steps = process ? process.querySelectorAll('.process-step') : [];
  var pin = document.querySelector('[data-work-pin]');
  var track = pin ? pin.querySelector('[data-work-track]') : null;
  var pinDistance = 0;
  var lastY = window.scrollY;
  var ticking = false;

  function layoutPin() {
    if (!pin || !track) return;
    pin.classList.remove('is-pinned');
    pin.style.height = '';
    track.style.setProperty('--x', 0);
    var desktop = window.innerWidth >= 1024;
    if (reduceMotion || !desktop) return;
    var distance = track.scrollWidth - window.innerWidth;
    if (distance < 80) return;
    pinDistance = distance;
    pin.classList.add('is-pinned');
    pin.style.height = (window.innerHeight + distance) + 'px';
  }

  function onScroll() {
    ticking = false;
    var y = window.scrollY;
    var max = document.documentElement.scrollHeight - window.innerHeight;
    if (progress) progress.style.setProperty('--progress', max > 0 ? (y / max).toFixed(4) : 0);
    if (header) {
      header.classList.toggle('is-scrolled', y > 20);
      var menuOpen = nav && nav.classList.contains('is-open');
      if (!menuOpen && y > 300 && y > lastY + 4) header.classList.add('is-hidden');
      else if (y < lastY - 4 || y < 300) header.classList.remove('is-hidden');
    }
    lastY = y;

    if (processWrap && !reduceMotion) {
      var r = processWrap.getBoundingClientRect();
      var vh = window.innerHeight;
      var p = clamp((vh * 0.8 - r.top) / (r.height + vh * 0.25), 0, 1);
      processWrap.style.setProperty('--p', p.toFixed(4));
      for (var i = 0; i < steps.length; i++) {
        steps[i].classList.toggle('is-lit', p >= (i / steps.length) + 0.02);
      }
    }

    if (pin && pin.classList.contains('is-pinned')) {
      var pr = pin.getBoundingClientRect();
      var x = clamp(-pr.top, 0, pinDistance);
      track.style.setProperty('--x', x.toFixed(1));
    }
  }
  function requestScroll() { if (!ticking) { ticking = true; window.requestAnimationFrame(onScroll); } }
  window.addEventListener('scroll', requestScroll, { passive: true });
  window.addEventListener('resize', function () { layoutPin(); requestScroll(); });
  window.addEventListener('load', function () { layoutPin(); requestScroll(); });
  layoutPin();
  onScroll();

  /* ---------- Reveal on scroll ---------- */
  var revealables = document.querySelectorAll('[data-reveal]');
  if (root.classList.contains('has-reveal')) {
    var revealObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) { entry.target.classList.add('is-in'); revealObserver.unobserve(entry.target); }
      });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.12 });
    revealables.forEach(function (el) { revealObserver.observe(el); });
  } else {
    revealables.forEach(function (el) { el.classList.add('is-in'); });
  }

  /* ---------- Counters ---------- */
  var counters = document.querySelectorAll('[data-counter]');
  function countUp(el) {
    var target = parseInt(el.getAttribute('data-counter'), 10) || 0;
    if (reduceMotion || target === 0) { el.textContent = String(target); return; }
    var start = null, dur = 1400;
    function tick(t) {
      if (!start) start = t;
      var k = Math.min((t - start) / dur, 1);
      el.textContent = String(Math.round(target * (1 - Math.pow(1 - k, 4))));
      if (k < 1) window.requestAnimationFrame(tick);
    }
    window.requestAnimationFrame(tick);
  }
  if (hasIO && !reduceMotion) {
    var countObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) { countUp(entry.target); countObserver.unobserve(entry.target); }
      });
    }, { threshold: 0.5 });
    counters.forEach(function (c) { c.textContent = '0'; countObserver.observe(c); });
  }

  /* ---------- 3D tilt + spotlight ---------- */
  if (finePointer && !reduceMotion) {
    document.querySelectorAll('[data-tilt]').forEach(function (el) {
      var max = el.classList.contains('hero-mark') ? 14 : 7;
      el.addEventListener('pointermove', function (e) {
        var r = el.getBoundingClientRect();
        var px = (e.clientX - r.left) / r.width;
        var py = (e.clientY - r.top) / r.height;
        el.style.setProperty('--ry', ((px - 0.5) * max).toFixed(2) + 'deg');
        el.style.setProperty('--rx', ((0.5 - py) * max).toFixed(2) + 'deg');
        el.style.setProperty('--mx', (px * 100).toFixed(1) + '%');
        el.style.setProperty('--my', (py * 100).toFixed(1) + '%');
      });
      el.addEventListener('pointerleave', function () {
        el.style.setProperty('--rx', '0deg');
        el.style.setProperty('--ry', '0deg');
      });
    });

    /* Magnetic buttons */
    document.querySelectorAll('[data-magnetic]').forEach(function (el) {
      var strength = el.classList.contains('hello-button') ? 0.35 : 0.22;
      el.addEventListener('pointermove', function (e) {
        var r = el.getBoundingClientRect();
        var dx = e.clientX - (r.left + r.width / 2);
        var dy = e.clientY - (r.top + r.height / 2);
        el.style.transform = 'translate(' + (dx * strength).toFixed(1) + 'px,' + (dy * strength).toFixed(1) + 'px)';
      });
      el.addEventListener('pointerleave', function () { el.style.transform = ''; });
    });
  }

  /* ---------- Active nav link ---------- */
  var navLinks = nav ? nav.querySelectorAll('a[href*="#"]') : [];
  if (hasIO && navLinks.length) {
    var map = {};
    navLinks.forEach(function (a) {
      var id = a.getAttribute('href').split('#')[1];
      var section = id && document.getElementById(id);
      if (section) map[id] = a;
    });
    var sectionObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        var link = map[entry.target.id];
        if (link) link.classList.toggle('is-current', entry.isIntersecting);
      });
    }, { rootMargin: '-45% 0px -50% 0px' });
    Object.keys(map).forEach(function (id) { sectionObserver.observe(document.getElementById(id)); });
  }

  /* ---------- Project filters ---------- */
  var filterButtons = document.querySelectorAll('.project-filter');
  var projectCards = document.querySelectorAll('.project-card');
  filterButtons.forEach(function (button) {
    button.addEventListener('click', function () {
      var filter = button.getAttribute('data-filter') || 'all';
      filterButtons.forEach(function (b) {
        var on = b === button;
        b.classList.toggle('is-active', on);
        b.setAttribute('aria-pressed', on ? 'true' : 'false');
      });
      projectCards.forEach(function (card) {
        var cats = (card.getAttribute('data-categories') || '').split(/\s+/);
        card.hidden = !(filter === 'all' || cats.indexOf(filter) !== -1);
      });
      if (track) track.scrollLeft = 0;
      layoutPin();
      requestScroll();
    });
  });

  /* ---------- Contact form recipient field ---------- */
  var contactType = document.getElementById('ctp_contact_type');
  var memberField = document.querySelector('.contact-member-field');
  var memberSelect = document.getElementById('ctp_member_id');
  function updateRecipient() {
    if (!contactType || !memberField || !memberSelect) return;
    var individual = contactType.value === 'individual';
    memberField.hidden = !individual;
    memberSelect.required = individual;
    if (!individual) memberSelect.value = '';
  }
  if (contactType) { contactType.addEventListener('change', updateRecipient); updateRecipient(); }

  /* ---------- Cinematic scroll intro ----------
     Scroll position drives a camera fly-through of the scenes, a particle warp between them,
     and letter-by-letter headlines. Smoothed with a lerp so it feels filmed rather than scrolled. */
  var story = document.querySelector('[data-story]');
  if (story && !reduceMotion) {
    story.classList.add('is-live');
    var stageEl = story.querySelector('[data-story-stage]');
    var stage = stageEl && window.UncrownScenes ? window.UncrownScenes.create(stageEl) : null;
    var sceneNames = stageEl ? (stageEl.getAttribute('data-scenes') || '').split(',') : [];
    var clock = 0;
    var texts = story.querySelectorAll('[data-story-text]');
    var counter = story.querySelector('[data-story-count]');
    var rail = story.querySelector('[data-story-rail]');
    var cue = story.querySelector('[data-story-cue]');
    var skip = story.querySelector('.story-skip');
    var pCanvas = story.querySelector('[data-story-particles]');
    var steps = texts.length;
    var last = steps - 1;
    var sceneOf = [];
    for (var si = 0; si < steps; si++) sceneOf.push(si < sceneNames.length ? sceneNames[si] : 'orbits');
    var cur = 0, target = 0, lastTarget = 0, velocity = 0, warp = 0, storyVisible = true, storyRaf = 0;
    var px = 0, py = 0, tpx = 0, tpy = 0;
    function smooth(x) { x = clamp(x, 0, 1); return x * x * (3 - 2 * x); }
    function readTarget() {
      var r = story.getBoundingClientRect();
      var total = story.offsetHeight - window.innerHeight;
      target = total > 0 ? clamp(-r.top / total, 0, 1) : 0;
    }

    /* Particle warp field (2D canvas, projected 3D points). */
    var pctx = pCanvas && pCanvas.getContext('2d');
    var parts = [], pw = 0, ph = 0, pdpr = 1;
    function sizeParticles() {
      if (!pctx) return;
      pdpr = Math.min(window.devicePixelRatio || 1, 1.5);
      pw = pCanvas.clientWidth; ph = pCanvas.clientHeight;
      pCanvas.width = Math.round(pw * pdpr); pCanvas.height = Math.round(ph * pdpr);
      pctx.setTransform(pdpr, 0, 0, pdpr, 0, 0);
      var n = pw < 700 ? 220 : 420;
      parts = [];
      for (var i = 0; i < n; i++) parts.push({ x: (Math.random() * 2 - 1) * 1.6, y: (Math.random() * 2 - 1) * 1.0, z: Math.random() });
    }
    function drawParticles(dt) {
      if (!pctx) return;
      pctx.clearRect(0, 0, pw, ph);
      var cx = pw / 2 + px * 30, cy = ph * 0.44 + py * 20, f = Math.min(pw, ph) * 0.55;
      var speed = 0.05 + warp * 1.9;
      var streak = 0.004 + warp * 0.22;
      pctx.globalCompositeOperation = 'lighter';
      pctx.lineCap = 'round';
      for (var i = 0; i < parts.length; i++) {
        var q = parts[i];
        q.z -= dt * speed;
        if (q.z <= 0.03) { q.z = 1; q.x = (Math.random() * 2 - 1) * 1.6; q.y = (Math.random() * 2 - 1) * 1.0; }
        var x1 = cx + (q.x / q.z) * f, y1 = cy + (q.y / q.z) * f;
        var z0 = Math.min(1, q.z + streak);
        var x0 = cx + (q.x / z0) * f, y0 = cy + (q.y / z0) * f;
        var a = (1 - q.z) * (0.35 + warp * 0.9);
        if (a <= 0.01 || x1 < -50 || x1 > pw + 50 || y1 < -50 || y1 > ph + 50) continue;
        pctx.strokeStyle = 'rgba(255,' + (170 + Math.round(70 * (1 - q.z))) + ',' + (40 + Math.round(120 * warp * (1 - q.z))) + ',' + Math.min(1, a).toFixed(3) + ')';
        pctx.lineWidth = (1 - q.z) * 2.2 + 0.3;
        pctx.beginPath(); pctx.moveTo(x0, y0); pctx.lineTo(x1, y1); pctx.stroke();
      }
      pctx.globalCompositeOperation = 'source-over';
    }

    function renderStory(dt) {
      cur += (target - cur) * Math.min(1, dt * 7);
      velocity += (Math.abs(target - lastTarget) / Math.max(dt, 0.001) - velocity) * 0.1;
      lastTarget = target;
      px += (tpx - px) * 0.06; py += (tpy - py) * 0.06;

      var s = Math.min(cur * (steps - 0.35), steps - 0.35);
      var k = Math.min(Math.floor(s), last);
      var t = s - k;
      var u = k < last ? smooth((t - 0.68) / 0.32) : 0;

      clock += dt;
      if (stage) {
        stage.clear();
        stage.draw(sceneOf[k], clock, t, 1.02 + 0.14 * t + u * 0.7, 1 - u * 0.999, px, py);
        if (k < last && u > 0) stage.draw(sceneOf[k + 1], clock, 0, 0.82 + 0.2 * u, u, px, py);
        stage.flash(u * (1 - u) * 4);
        if (!story.classList.contains('is-drawn')) story.classList.add('is-drawn');
      }

      for (var j = 0; j < texts.length; j++) {
        var tin = 0, tout = 0;
        if (j < k) { tin = 1; tout = 1; }
        else if (j === k) {
          tin = smooth((t - 0.04) / 0.36);
          tout = j === last ? 0 : smooth((t - 0.6) / 0.26);
        }
        var te = texts[j];
        te.style.setProperty('--in', tin.toFixed(3));
        te.style.setProperty('--out', tout.toFixed(3));
        te.classList.toggle('is-active', tin > 0.6 && tout < 0.4);
        te.setAttribute('aria-hidden', tin > 0.6 && tout < 0.4 ? 'false' : 'true');
      }

      var flight = u * (1 - u) * 4;
      warp += (Math.max(flight, Math.min(velocity * 0.9, 0.6)) - warp) * 0.12;
      if (counter) counter.textContent = (k + 1 < 10 ? '0' : '') + (k + 1);
      story.classList.toggle('at-final', k === last);
      if (rail) rail.style.transform = 'scaleY(' + cur.toFixed(4) + ')';
      var startFade = clamp(1 - cur * 25, 0, 1).toFixed(3);
      if (cue) cue.style.opacity = startFade;
      if (skip) skip.style.opacity = cur > 0.92 ? '0' : '1';
      drawParticles(dt);
    }

    var lastT = 0;
    function storyLoop(now) {
      var dt = lastT ? Math.min((now - lastT) / 1000, 0.05) : 0.016;
      lastT = now;
      readTarget();
      renderStory(dt);
      storyRaf = storyVisible ? window.requestAnimationFrame(storyLoop) : 0;
    }
    function startStory() { if (!storyRaf && storyVisible) { lastT = 0; storyRaf = window.requestAnimationFrame(storyLoop); } }

    sizeParticles();
    if (stage) stage.resize();
    readTarget(); cur = target; renderStory(0.016);
    window.addEventListener('resize', function () { sizeParticles(); if (stage) stage.resize(); });
    if (finePointer) {
      story.addEventListener('pointermove', function (e) {
        tpx = e.clientX / window.innerWidth - 0.5; tpy = e.clientY / window.innerHeight - 0.5;
      });
    }
    if (hasIO) {
      new IntersectionObserver(function (entries) {
        storyVisible = entries[0].isIntersecting && !document.hidden;
        startStory();
      }).observe(story);
    }
    document.addEventListener('visibilitychange', function () { storyVisible = !document.hidden; startStory(); });
    startStory();
  }

  /* ---------- Project demo videos on cards: hover to play (mouse), play while in view (touch) ---------- */
  var cardVideos = document.querySelectorAll('[data-card-video]');
  function loadVideo(v) { if (!v.src) { v.src = v.getAttribute('data-src'); v.muted = true; } }
  function playVideo(v) {
    loadVideo(v);
    var pr = v.play();
    if (pr && pr.catch) pr.catch(function () {});
    v.classList.add('is-playing');
  }
  function stopVideo(v) { v.pause(); v.classList.remove('is-playing'); }
  if (cardVideos.length && !reduceMotion) {
    cardVideos.forEach(function (v) {
      var card = v.closest('.project-card');
      if (finePointer && card) {
        card.addEventListener('pointerenter', function () { playVideo(v); });
        card.addEventListener('pointerleave', function () { stopVideo(v); });
        card.addEventListener('focusin', function () { playVideo(v); });
        card.addEventListener('focusout', function () { stopVideo(v); });
      }
    });
    if (!finePointer && hasIO) {
      var cardObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) { if (entry.intersectionRatio >= 0.6) playVideo(entry.target); else stopVideo(entry.target); });
      }, { threshold: [0, 0.6] });
      cardVideos.forEach(function (v) { cardObserver.observe(v); });
    }
  }

  /* ---------- Project page: devices fly together, marquee slides, pinned app switcher ---------- */
  var stageEl = document.querySelector('[data-stage]');
  var marquee = document.querySelector('[data-marquee] .marquee-track');
  if ((stageEl || marquee) && !reduceMotion) {
    var landingTick = false;
    var updateLanding = function () {
      landingTick = false;
      var vh = window.innerHeight;
      if (stageEl) {
        var r = stageEl.getBoundingClientRect();
        var p = clamp((vh - r.top) / (vh * 0.75), 0, 1);
        p = 1 - Math.pow(1 - p, 3);
        stageEl.style.setProperty('--p', p.toFixed(4));
        var main = stageEl.querySelector('.stage-main');
        if (main) main.style.setProperty('--p', p.toFixed(4));
      }
      if (marquee) marquee.style.setProperty('--mx', (window.scrollY * 0.45).toFixed(1));
    };
    updateLanding();
    window.addEventListener('scroll', function () { if (!landingTick) { landingTick = true; window.requestAnimationFrame(updateLanding); } }, { passive: true });
    window.addEventListener('resize', updateLanding);
  }

  var pfSection = document.querySelector('[data-platforms]');
  if (pfSection && hasIO) {
    var pfPanels = pfSection.querySelectorAll('[data-pf-panel]');
    var pfDevices = pfSection.querySelectorAll('[data-pf-device]');
    var setPlatform = function (n) {
      pfPanels.forEach(function (el) { el.classList.toggle('is-active', el.getAttribute('data-pf-panel') === n); });
      pfDevices.forEach(function (el) {
        var on = el.getAttribute('data-pf-device') === n;
        el.classList.toggle('is-active', on);
        var v = el.querySelector('video');
        if (v && !on) v.pause();
        if (v && on && !reduceMotion) { var pr = v.play(); if (pr && pr.catch) pr.catch(function () {}); }
      });
    };
    var pfObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) { if (e.isIntersecting) setPlatform(e.target.getAttribute('data-pf-panel')); });
    }, { rootMargin: '-45% 0px -45% 0px' });
    pfPanels.forEach(function (el) { pfObserver.observe(el); });
  }

  /* Any app recording in a device frame plays only while it is on screen (and never for reduced motion). */
  var autoVideos = document.querySelectorAll('[data-auto-video]');
  if (autoVideos.length && hasIO && !reduceMotion) {
    var avObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        var v = e.target;
        var pf = v.closest('[data-pf-device]');
        if (pf && !pf.classList.contains('is-active')) { v.pause(); return; }
        if (e.isIntersecting) { v.muted = true; var pr = v.play(); if (pr && pr.catch) pr.catch(function () {}); } else v.pause();
      });
    }, { threshold: 0.2 });
    autoVideos.forEach(function (v) { avObserver.observe(v); });
  }

  /* Skip intro: jump straight to the last scene (the logo, headline and buttons) without playing the film. */
  var skipLink = document.querySelector('[data-story-skip]');
  if (skipLink) {
    skipLink.addEventListener('click', function (e) {
      var storyEl = document.querySelector('[data-story]');
      if (!storyEl || !storyEl.classList.contains('is-live')) return;
      e.preventDefault();
      var top = storyEl.getBoundingClientRect().top + window.scrollY;
      var end = top + storyEl.offsetHeight - window.innerHeight - 2;
      var html = document.documentElement, prev = html.style.scrollBehavior;
      html.style.scrollBehavior = 'auto';
      window.scrollTo(0, end);
      html.style.scrollBehavior = prev;
    });
  }

  /* ---------- Cinematic hero video (loaded only when motion is welcome) ---------- */
  var heroVideo = document.querySelector('[data-hero-video]');
  if (heroVideo) {
    var conn = navigator.connection || {};
    var saveData = conn.saveData || /(^|-)2g$/.test(conn.effectiveType || '');
    if (!reduceMotion && !saveData) {
      heroVideo.src = heroVideo.getAttribute('data-src');
      heroVideo.muted = true;
      heroVideo.playbackRate = 0.8;
      heroVideo.addEventListener('playing', function () { heroVideo.classList.add('is-playing'); });
      heroVideo.addEventListener('loadeddata', function () { heroVideo.playbackRate = 0.8; });
      var tryPlay = function () { var p = heroVideo.play(); if (p && p.catch) p.catch(function () {}); };
      tryPlay();
      if (hasIO) {
        new IntersectionObserver(function (entries) {
          if (entries[0].isIntersecting) tryPlay(); else heroVideo.pause();
        }, { threshold: 0.05 }).observe(heroVideo);
      }
      document.addEventListener('visibilitychange', function () { if (document.hidden) heroVideo.pause(); else tryPlay(); });
    }
  }

  /* ---------- Hero signal field (canvas) ---------- */
  document.querySelectorAll('canvas[data-signal]').forEach(function (canvas) {
    var ctx = canvas.getContext('2d');
    if (!ctx) return;
    var w = 0, h = 0, dpr = 1, t = 0, raf = 0, visible = true;
    var mouse = { x: -9999, y: -9999, tx: -9999, ty: -9999 };
    var colors = {};
    var LINES = 22;

    function readColors() {
      var cs = getComputedStyle(root);
      colors.a = cs.getPropertyValue('--signal-a').trim() || '#33d6ff';
      colors.b = cs.getPropertyValue('--signal-b').trim() || '#2f5bff';
      colors.alpha = root.getAttribute('data-theme') === 'light' ? 0.32 : 0.5;
    }
    function resize() {
      var r = canvas.getBoundingClientRect();
      dpr = Math.min(window.devicePixelRatio || 1, 2);
      w = r.width; h = r.height;
      canvas.width = Math.round(w * dpr); canvas.height = Math.round(h * dpr);
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    }
    function draw() {
      ctx.clearRect(0, 0, w, h);
      var grad = ctx.createLinearGradient(0, 0, w, 0);
      grad.addColorStop(0, colors.a);
      grad.addColorStop(1, colors.b);
      ctx.strokeStyle = grad;
      mouse.x += (mouse.tx - mouse.x) * 0.08;
      mouse.y += (mouse.ty - mouse.y) * 0.08;
      var step = Math.max(6, w / 160);
      for (var i = 0; i < LINES; i++) {
        var k = i / (LINES - 1);
        var base = h * (0.42 + k * 0.5);
        var amp = 18 + k * 46;
        var freq = 0.0016 + k * 0.0009;
        var speed = 0.5 + k * 0.6;
        ctx.globalAlpha = colors.alpha * (0.25 + 0.75 * Math.sin(Math.PI * k));
        ctx.lineWidth = 1;
        ctx.beginPath();
        for (var x = -step; x <= w + step; x += step) {
          var y = base
            + Math.sin(x * freq + t * speed + i * 0.35) * amp
            + Math.sin(x * freq * 2.3 - t * speed * 0.7 + i) * amp * 0.25;
          var dx = x - mouse.x, dy = y - mouse.y;
          var d2 = dx * dx + dy * dy;
          y -= Math.exp(-d2 / 26000) * 70 * (dy < 0 ? -1 : 1) * 0.6;
          if (x === -step) ctx.moveTo(x, y); else ctx.lineTo(x, y);
        }
        ctx.stroke();
      }
      ctx.globalAlpha = 1;
    }
    function loop() {
      t += 0.012;
      draw();
      raf = visible ? window.requestAnimationFrame(loop) : 0;
    }
    function start() { if (!raf && visible && !reduceMotion) raf = window.requestAnimationFrame(loop); }

    readColors(); resize(); draw();
    window.addEventListener('resize', function () { resize(); draw(); });
    document.addEventListener('og:theme', function () { readColors(); draw(); });
    if (reduceMotion) return;
    var host = canvas.parentElement;
    host.addEventListener('pointermove', function (e) {
      var r = canvas.getBoundingClientRect();
      mouse.tx = e.clientX - r.left; mouse.ty = e.clientY - r.top;
      if (mouse.x < -5000) { mouse.x = mouse.tx; mouse.y = mouse.ty; }
    });
    host.addEventListener('pointerleave', function () { mouse.tx = -9999; mouse.ty = -9999; });
    if (hasIO) {
      new IntersectionObserver(function (entries) {
        visible = entries[0].isIntersecting && !document.hidden;
        start();
      }).observe(canvas);
    }
    document.addEventListener('visibilitychange', function () { visible = !document.hidden; start(); });
    start();
  });
}());
