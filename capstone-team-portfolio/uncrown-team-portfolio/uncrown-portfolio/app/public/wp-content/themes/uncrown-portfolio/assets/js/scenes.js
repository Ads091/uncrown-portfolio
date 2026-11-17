/* Uncrown Portfolio — live-drawn scenes for the cinematic scroll intro.
   Every scene is painted on one 2D canvas in black, gold and silver, built from the logo's motifs:
   gold dust in a beam of light, orbit rings, ranges of crown peaks, a gold arena floor, rising embers.
   draw(name, time, t, zoom, alpha, px, py): t = progress through the scene (0–1), zoom = camera push. */
(function () {
  'use strict';

  var TAU = Math.PI * 2;
  function rand(a, b) { return a + Math.random() * (b - a); }

  function create(canvas) {
    var ctx = canvas.getContext('2d');
    if (!ctx) return null;
    var w = 0, h = 0, dpr = 1;
    var dust = [], sparks = [], shards = [], rings = [];

    function seed() {
      var n = w < 700 ? 140 : 260;
      dust = [];
      for (var i = 0; i < n; i++) dust.push({ x: rand(-1.4, 1.4), y: rand(-1, 1), z: rand(0.15, 1), s: rand(0.4, 1.6), p: rand(0, TAU) });
      sparks = [];
      for (var j = 0; j < (w < 700 ? 110 : 200); j++) sparks.push({ x: rand(0, 1), y: rand(0, 1.2), v: rand(0.03, 0.11), r: rand(0.6, 2.2), p: rand(0, TAU) });
      shards = [];
      for (var k = 0; k < 14; k++) shards.push({ x: rand(0.05, 0.95), y: rand(0.1, 0.9), r: rand(10, 46), a: rand(0, TAU), v: rand(-0.25, 0.25), d: rand(0.4, 1) });
      rings = [];
      for (var m = 0; m < 7; m++) rings.push({ r: 0.14 + m * 0.085, tilt: rand(-0.55, 0.15), sq: rand(0.18, 0.34), v: rand(0.18, 0.45) * (m % 2 ? -1 : 1), a: rand(0, TAU) });
    }

    function resize() {
      dpr = Math.min(window.devicePixelRatio || 1, 1.25);
      w = canvas.clientWidth; h = canvas.clientHeight;
      canvas.width = Math.max(1, Math.round(w * dpr)); canvas.height = Math.max(1, Math.round(h * dpr));
      seed();
    }

    function gold(a) { return 'rgba(255, 196, 64, ' + a + ')'; }
    function deep(a) { return 'rgba(227, 153, 12, ' + a + ')'; }
    function silver(a) { return 'rgba(232, 230, 225, ' + a + ')'; }

    function backdrop(alpha, cx, cy, glow) {
      ctx.globalAlpha = alpha;
      ctx.fillStyle = '#050403';
      ctx.fillRect(0, 0, w, h);
      var g = ctx.createRadialGradient(cx, cy, 0, cx, cy, Math.max(w, h) * 0.75);
      g.addColorStop(0, 'rgba(120, 78, 10, ' + (0.32 * glow) + ')');
      g.addColorStop(0.45, 'rgba(40, 26, 6, ' + (0.25 * glow) + ')');
      g.addColorStop(1, 'rgba(0, 0, 0, 0)');
      ctx.fillStyle = g;
      ctx.fillRect(0, 0, w, h);
    }

    function camera(zoom, px, py, cx, cy) {
      ctx.translate(cx + px * -18, cy + py * -10);
      ctx.scale(zoom, zoom);
      ctx.translate(-cx, -cy);
    }

    /* 1. A shaft of light with gold dust drifting through it. */
    function sceneDust(time, t) {
      var cx = w / 2, cy = h * 0.42;
      var sway = Math.sin(time * 0.3) * w * 0.02;
      ctx.globalCompositeOperation = 'lighter';
      var beam = ctx.createLinearGradient(0, 0, 0, h);
      beam.addColorStop(0, gold(0.34)); beam.addColorStop(0.55, deep(0.1)); beam.addColorStop(1, 'rgba(0,0,0,0)');
      ctx.fillStyle = beam;
      ctx.beginPath();
      ctx.moveTo(cx - w * 0.06 + sway, -10); ctx.lineTo(cx + w * 0.06 + sway, -10);
      ctx.lineTo(cx + w * 0.32, h); ctx.lineTo(cx - w * 0.32, h); ctx.closePath(); ctx.fill();
      var core = ctx.createLinearGradient(0, 0, 0, h * 0.9);
      core.addColorStop(0, 'rgba(255, 236, 180, 0.35)'); core.addColorStop(1, 'rgba(0,0,0,0)');
      ctx.fillStyle = core;
      ctx.beginPath();
      ctx.moveTo(cx - w * 0.015 + sway, -10); ctx.lineTo(cx + w * 0.015 + sway, -10);
      ctx.lineTo(cx + w * 0.1, h * 0.9); ctx.lineTo(cx - w * 0.1, h * 0.9); ctx.closePath(); ctx.fill();
      var pool = ctx.createRadialGradient(cx, h * 0.9, 0, cx, h * 0.9, w * 0.4);
      pool.addColorStop(0, gold(0.28)); pool.addColorStop(1, 'rgba(0,0,0,0)');
      ctx.fillStyle = pool;
      ctx.save(); ctx.translate(cx, h * 0.9); ctx.scale(1, 0.18); ctx.translate(-cx, -h * 0.9);
      ctx.beginPath(); ctx.arc(cx, h * 0.9, w * 0.4, 0, TAU); ctx.fill(); ctx.restore();
      var f = Math.min(w, h) * 0.5;
      for (var i = 0; i < dust.length; i++) {
        var d = dust[i];
        var z = d.z - t * 0.18;
        if (z < 0.08) z += 0.92;
        var y = d.y - ((time * 0.02 * d.s) % 2);
        if (y < -1) y += 2;
        var sx = cx + (d.x + Math.sin(time * 0.4 + d.p) * 0.03) / z * f * 0.6;
        var sy = cy + y / z * f * 0.6;
        if (sx < -10 || sx > w + 10 || sy < -10 || sy > h + 10) continue;
        var inBeam = 1 - Math.min(1, Math.abs(sx - cx) / (w * (0.08 + 0.25 * (sy / h))));
        var tw = 0.6 + 0.4 * Math.sin(time * 2 + d.p);
        var a = (0.18 + inBeam * 0.7) * tw * (1 - z * 0.6);
        var r = d.s * (1.4 - z);
        ctx.fillStyle = gold(a.toFixed(3));
        ctx.beginPath(); ctx.arc(sx, sy, Math.max(0.4, r), 0, TAU); ctx.fill();
      }
      ctx.globalCompositeOperation = 'source-over';
    }

    /* 2. The logo's gold ring, multiplied into a slow system of orbits. */
    function sceneOrbits(time, t) {
      var cx = w / 2, cy = h * 0.47, base = Math.min(w, h * 1.5);
      ctx.globalCompositeOperation = 'lighter';
      var core = ctx.createRadialGradient(cx, cy, 0, cx, cy, base * 0.16);
      core.addColorStop(0, 'rgba(255, 240, 200, 0.9)'); core.addColorStop(0.2, gold(0.5)); core.addColorStop(1, 'rgba(0,0,0,0)');
      ctx.fillStyle = core; ctx.beginPath(); ctx.arc(cx, cy, base * 0.16, 0, TAU); ctx.fill();
      for (var i = 0; i < rings.length; i++) {
        var R = rings[i];
        var rx = base * R.r, ry = rx * R.sq;
        var rot = R.tilt + Math.sin(time * 0.1 + i) * 0.05;
        ctx.lineWidth = 1;
        ctx.strokeStyle = silver(0.1);
        ctx.beginPath(); ctx.ellipse(cx, cy, rx, ry, rot, 0, TAU); ctx.stroke();
        var head = R.a + time * R.v + t * 1.2 * (R.v > 0 ? 1 : -1);
        var len = 1.3;
        for (var s = 0; s < 3; s++) {
          ctx.lineWidth = [7, 3.2, 1.4][s];
          ctx.strokeStyle = s === 2 ? 'rgba(255, 244, 214, 0.95)' : gold([0.08, 0.35, 0][s]);
          ctx.beginPath(); ctx.ellipse(cx, cy, rx, ry, rot, head - len, head); ctx.stroke();
        }
        var hx = cx + Math.cos(rot) * rx * Math.cos(head) - Math.sin(rot) * ry * Math.sin(head);
        var hy = cy + Math.sin(rot) * rx * Math.cos(head) + Math.cos(rot) * ry * Math.sin(head);
        var spark = ctx.createRadialGradient(hx, hy, 0, hx, hy, 18);
        spark.addColorStop(0, 'rgba(255, 246, 220, 0.95)'); spark.addColorStop(1, 'rgba(0,0,0,0)');
        ctx.fillStyle = spark; ctx.beginPath(); ctx.arc(hx, hy, 18, 0, TAU); ctx.fill();
      }
      ctx.globalCompositeOperation = 'source-over';
    }

    /* 3. Ranges of crown peaks, layer after layer, moving toward the camera. */
    function crownPath(y, amp, width, offset) {
      var period = width / 3.2;
      ctx.beginPath();
      ctx.moveTo(-20, h + 20);
      ctx.lineTo(-20, y);
      for (var x = -period * 2 + (offset % period); x < w + period * 2; x += period) {
        ctx.lineTo(x, y);
        ctx.lineTo(x + period * 0.22, y);
        ctx.lineTo(x + period * 0.36, y - amp * 0.55);
        ctx.lineTo(x + period * 0.5, y - amp * 0.2);
        ctx.lineTo(x + period * 0.64, y - amp);
        ctx.lineTo(x + period * 0.78, y - amp * 0.2);
        ctx.lineTo(x + period * 0.92, y - amp * 0.55);
        ctx.lineTo(x + period * 1.06, y);
      }
      ctx.lineTo(w + 20, y);
      ctx.lineTo(w + 20, h + 20);
      ctx.closePath();
    }
    function scenePeaks(time, t) {
      var horizon = h * 0.46;
      var glow = ctx.createLinearGradient(0, horizon - h * 0.25, 0, horizon + h * 0.05);
      glow.addColorStop(0, 'rgba(0,0,0,0)'); glow.addColorStop(0.8, deep(0.35)); glow.addColorStop(1, gold(0.5));
      ctx.fillStyle = glow; ctx.fillRect(0, horizon - h * 0.25, w, h * 0.3);
      var layers = 9;
      var travel = (time * 0.05 + t * 0.9) % 1;
      for (var i = layers; i >= 1; i--) {
        var z = (i - travel) / layers;
        if (z <= 0.04) continue;
        var y = horizon + (h * 0.07) / z;
        var amp = (h * 0.05) / z;
        var width = w * (0.6 + 0.6 / z);
        crownPath(y, amp, width, i * 97 + time * 6 / z);
        ctx.fillStyle = '#050403';
        ctx.fill();
        var a = Math.max(0, Math.min(1, 1.15 - z)) * (z > 0.9 ? (1 - z) * 10 : 1);
        ctx.lineWidth = Math.max(0.8, 2.4 * (1 - z));
        ctx.strokeStyle = gold((0.85 * a).toFixed(3));
        ctx.stroke();
        ctx.lineWidth = Math.max(4, 12 * (1 - z));
        ctx.strokeStyle = deep((0.12 * a).toFixed(3));
        ctx.stroke();
      }
    }

    /* 4. A gold arena floor with silver light pillars rushing past. */
    function sceneArena(time, t) {
      var hz = h * 0.48, cx = w / 2;
      var sky = ctx.createLinearGradient(0, 0, 0, hz);
      sky.addColorStop(0, 'rgba(0,0,0,0)'); sky.addColorStop(1, deep(0.28));
      ctx.fillStyle = sky; ctx.fillRect(0, 0, w, hz);
      ctx.globalCompositeOperation = 'lighter';
      var move = (time * 0.35 + t * 3) % 1;
      for (var i = 0; i < 26; i++) {
        var z = (i + 1 - move) / 26;
        var y = hz + (h * 0.03) / z;
        if (y > h + 2) continue;
        var a = Math.min(1, (1 - z) * 1.4);
        ctx.strokeStyle = gold((0.55 * a).toFixed(3));
        ctx.lineWidth = 1 + (1 - z) * 1.5;
        ctx.beginPath(); ctx.moveTo(0, y); ctx.lineTo(w, y); ctx.stroke();
      }
      for (var j = -14; j <= 14; j++) {
        var xb = cx + j * w * 0.09;
        ctx.strokeStyle = gold((0.35 - Math.abs(j) * 0.012).toFixed(3));
        ctx.lineWidth = 1;
        ctx.beginPath(); ctx.moveTo(cx + j * 6, hz); ctx.lineTo(cx + (xb - cx) * 4, h * 1.6); ctx.stroke();
      }
      var band = ctx.createLinearGradient(0, hz - 30, 0, hz + 30);
      band.addColorStop(0, 'rgba(0,0,0,0)'); band.addColorStop(0.5, 'rgba(255, 226, 150, 0.55)'); band.addColorStop(1, 'rgba(0,0,0,0)');
      ctx.fillStyle = band; ctx.fillRect(0, hz - 30, w, 60);
      var pmove = (time * 0.25 + t * 2) % 1;
      for (var k = 0; k < 7; k++) {
        var pz = (k + 1 - pmove) / 7;
        var spread = w * 0.14 / pz;
        var ph = (h * 0.5) / pz;
        var pwid = Math.max(2, 10 / pz);
        var pa = Math.min(1, (1 - pz) * 1.6) * 0.5;
        for (var side = -1; side <= 1; side += 2) {
          var x = cx + side * spread;
          var g = ctx.createLinearGradient(0, hz + h * 0.03 / pz - ph, 0, hz + h * 0.03 / pz);
          g.addColorStop(0, 'rgba(0,0,0,0)'); g.addColorStop(0.7, silver((pa * 0.6).toFixed(3))); g.addColorStop(1, gold(pa.toFixed(3)));
          ctx.fillStyle = g;
          ctx.fillRect(x - pwid / 2, hz + h * 0.03 / pz - ph, pwid, ph);
        }
      }
      ctx.globalCompositeOperation = 'source-over';
    }

    /* 5. A giant faint crown, rising embers and drifting gold shards. */
    function sceneEmbers(time, t) {
      var cx = w / 2, cy = h * 0.44, s = Math.min(w, h) * 0.42;
      ctx.globalCompositeOperation = 'lighter';
      var pulse = 0.55 + 0.25 * Math.sin(time * 0.8);
      ctx.save();
      ctx.translate(cx, cy); ctx.scale(1 + t * 0.08, 1 + t * 0.08);
      ctx.beginPath();
      ctx.moveTo(-s, s * 0.32); ctx.lineTo(-s * 0.98, -s * 0.18); ctx.lineTo(-s * 0.42, s * 0.12);
      ctx.lineTo(0, -s * 0.62); ctx.lineTo(s * 0.42, s * 0.12); ctx.lineTo(s * 0.98, -s * 0.18); ctx.lineTo(s, s * 0.32);
      ctx.lineJoin = 'miter';
      ctx.lineWidth = 16; ctx.strokeStyle = deep((0.1 * pulse).toFixed(3)); ctx.stroke();
      ctx.lineWidth = 2.2; ctx.strokeStyle = gold((0.6 * pulse).toFixed(3)); ctx.stroke();
      ctx.restore();
      for (var i = 0; i < sparks.length; i++) {
        var p = sparks[i];
        var y = ((p.y - time * p.v - t * 0.4) % 1.2 + 1.2) % 1.2;
        var x = p.x + Math.sin(time * 0.9 + p.p) * 0.02;
        var sx = x * w, sy = y * h;
        var a = Math.min(1, y * 1.2) * (0.5 + 0.5 * Math.sin(time * 3 + p.p));
        ctx.strokeStyle = gold((a * 0.8).toFixed(3));
        ctx.lineWidth = p.r;
        ctx.lineCap = 'round';
        ctx.beginPath(); ctx.moveTo(sx, sy); ctx.lineTo(sx - Math.sin(time + p.p) * 2, sy + p.r * 6); ctx.stroke();
      }
      ctx.globalCompositeOperation = 'source-over';
      for (var k = 0; k < shards.length; k++) {
        var q = shards[k];
        var ang = q.a + time * q.v;
        var qx = q.x * w + Math.sin(time * 0.2 + k) * 12, qy = q.y * h - t * 60 * q.d;
        ctx.save(); ctx.translate(qx, qy); ctx.rotate(ang);
        ctx.beginPath(); ctx.moveTo(0, -q.r); ctx.lineTo(q.r * 0.7, q.r * 0.6); ctx.lineTo(-q.r * 0.5, q.r * 0.4); ctx.closePath();
        var g = ctx.createLinearGradient(-q.r, -q.r, q.r, q.r);
        g.addColorStop(0, 'rgba(60, 40, 8, 0.6)'); g.addColorStop(1, 'rgba(10, 8, 4, 0.6)');
        ctx.fillStyle = g; ctx.fill();
        ctx.lineWidth = 1; ctx.strokeStyle = gold((0.5 * q.d).toFixed(3)); ctx.stroke();
        ctx.restore();
      }
    }

    var scenes = { dust: [sceneDust, 0.42, 1], orbits: [sceneOrbits, 0.47, 1.2], peaks: [scenePeaks, 0.46, 0.9], arena: [sceneArena, 0.48, 1], embers: [sceneEmbers, 0.44, 1.1] };

    return {
      resize: resize,
      clear: function () {
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        ctx.globalAlpha = 1;
        ctx.fillStyle = '#050403';
        ctx.fillRect(0, 0, w, h);
      },
      draw: function (name, time, t, zoom, alpha, px, py) {
        var sc = scenes[name] || scenes.dust;
        if (alpha <= 0.002) return;
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        var cx = w / 2, cy = h * sc[1];
        backdrop(alpha, cx, cy, sc[2]);
        ctx.save();
        camera(zoom, px || 0, py || 0, cx, cy);
        ctx.globalAlpha = alpha;
        sc[0](time, t);
        ctx.restore();
        ctx.globalAlpha = 1;
      },
      flash: function (amount) {
        if (amount <= 0.01) return;
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        var g = ctx.createRadialGradient(w / 2, h * 0.45, 0, w / 2, h * 0.45, Math.max(w, h) * 0.6);
        g.addColorStop(0, 'rgba(255, 226, 150, ' + (amount * 0.35).toFixed(3) + ')');
        g.addColorStop(1, 'rgba(0,0,0,0)');
        ctx.globalCompositeOperation = 'lighter';
        ctx.fillStyle = g; ctx.fillRect(0, 0, w, h);
        ctx.globalCompositeOperation = 'source-over';
      }
    };
  }

  window.UncrownScenes = { create: create };
}());
