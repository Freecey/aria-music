<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('tab-title', 'Erreur') — Aria</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@700;900&family=Inter:wght@400;500&display=swap');

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg-primary: #0a0a0f;
      --color-violet: #8b5cf6;
      --color-violet-glow: rgba(139, 92, 246, 0.4);
      --color-cyan: #06b6d4;
      --color-cyan-glow: rgba(6, 182, 212, 0.3);
      --color-rose: #ec4899;
      --color-rose-glow: rgba(236, 72, 153, 0.35);
      --text-primary: #f8fafc;
      --text-secondary: #94a3b8;
      --font-display: 'Montserrat', system-ui, sans-serif;
      --font-body: 'Inter', system-ui, sans-serif;
      /* overridden per page */
      --accent: var(--color-violet);
      --accent-glow: var(--color-violet-glow);
      --accent-alt: var(--color-cyan);
    }

    html, body { height: 100%; background: var(--bg-primary); color: var(--text-primary); font-family: var(--font-body); overflow: hidden; }

    /* Per-error accent overrides */
    body.error-rose   { --accent: var(--color-rose);   --accent-glow: var(--color-rose-glow);   --accent-alt: var(--color-violet); }
    body.error-cyan   { --accent: var(--color-cyan);   --accent-glow: var(--color-cyan-glow);   --accent-alt: var(--color-violet); }
    body.error-violet { --accent: var(--color-violet); --accent-glow: var(--color-violet-glow); --accent-alt: var(--color-cyan); }

    /* Stars */
    .stars { position: fixed; inset: 0; overflow: hidden; pointer-events: none; z-index: 0; }
    .star {
      position: absolute;
      border-radius: 50%;
      background: #fff;
      animation: twinkle var(--d, 3s) ease-in-out infinite var(--delay, 0s);
    }
    @keyframes twinkle {
      0%, 100% { opacity: var(--min, 0.1); transform: scale(1); }
      50%       { opacity: var(--max, 0.8); transform: scale(1.2); }
    }

    /* Nebula blobs */
    .nebula { position: fixed; border-radius: 50%; filter: blur(80px); pointer-events: none; z-index: 0; }
    .nebula-1 {
      width: 500px; height: 500px;
      background: var(--accent-glow);
      top: -100px; right: -100px;
      animation: drift 12s ease-in-out infinite alternate;
    }
    .nebula-2 {
      width: 400px; height: 400px;
      background: var(--color-cyan-glow);
      bottom: -80px; left: -80px;
      animation: drift 15s ease-in-out infinite alternate-reverse;
    }
    @keyframes drift {
      from { transform: translate(0, 0) scale(1); }
      to   { transform: translate(30px, 20px) scale(1.05); }
    }

    /* Nav */
    .nav {
      position: fixed; top: 0; left: 0; right: 0; z-index: 10;
      padding: 1.25rem 2rem;
      display: flex; align-items: center;
      background: rgba(10, 10, 15, 0.6);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid rgba(139, 92, 246, 0.15);
    }
    .nav-logo {
      font-family: var(--font-display); font-weight: 900; font-size: 1.25rem;
      color: var(--text-primary); text-decoration: none; letter-spacing: 0.05em;
      background: linear-gradient(135deg, var(--color-violet), var(--color-cyan));
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    }

    /* Main */
    .error-page {
      position: relative; z-index: 1;
      display: flex; flex-direction: column; align-items: center; justify-content: center;
      min-height: 100vh; padding: 2rem; text-align: center;
    }

    /* Error code */
    .error-code {
      font-family: var(--font-display); font-weight: 900;
      font-size: clamp(6rem, 20vw, 12rem); line-height: 1;
      letter-spacing: -0.02em;
      background: linear-gradient(135deg, var(--accent), var(--accent-alt));
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
      animation: pulse-code 4s ease-in-out infinite;
    }
    @keyframes pulse-code {
      0%, 100% { filter: drop-shadow(0 0 30px var(--accent-glow)); }
      50%       { filter: drop-shadow(0 0 70px var(--accent-glow)); }
    }

    .error-symbol {
      font-size: 1.25rem; color: var(--accent);
      margin: 0.75rem 0 1.5rem; letter-spacing: 0.4em; opacity: 0.6;
    }

    .error-title {
      font-family: var(--font-display); font-weight: 700;
      font-size: clamp(1.2rem, 4vw, 1.9rem);
      color: var(--text-primary); margin-bottom: 0.75rem;
    }

    .error-divider {
      width: 60px; height: 2px;
      background: linear-gradient(90deg, transparent, var(--accent), transparent);
      margin: 0 auto 1.5rem;
    }

    .error-message {
      font-size: 1rem; color: var(--text-secondary);
      max-width: 480px; line-height: 1.7; margin-bottom: 2.5rem;
    }

    /* Buttons */
    .btn {
      display: inline-flex; align-items: center; gap: 0.5rem;
      padding: 0.75rem 1.75rem; border-radius: 9999px;
      font-family: var(--font-body); font-size: 0.9rem; font-weight: 500;
      text-decoration: none; transition: all 250ms ease; cursor: pointer; border: none;
    }
    .btn-primary {
      background: linear-gradient(135deg, var(--color-violet), var(--color-cyan));
      color: #fff; box-shadow: 0 0 20px var(--color-violet-glow);
    }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 0 40px var(--color-violet-glow); }
    .btn-ghost {
      background: rgba(255,255,255,0.05); color: var(--text-secondary);
      border: 1px solid rgba(255,255,255,0.1);
    }
    .btn-ghost:hover { background: rgba(255,255,255,0.09); color: var(--text-primary); transform: translateY(-2px); }

    .btn-group { display: flex; gap: 1rem; flex-wrap: wrap; justify-content: center; }

    /* Footer */
    .footer {
      position: fixed; bottom: 0; left: 0; right: 0; z-index: 10;
      padding: 1rem 2rem; text-align: center;
      font-size: 0.75rem; color: var(--text-secondary); opacity: 0.4;
    }
  </style>
</head>
<body class="@yield('body-class', 'error-violet')">

  <div class="nebula nebula-1"></div>
  <div class="nebula nebula-2"></div>
  <div class="stars" id="stars"></div>

  <nav class="nav">
    <a href="/" class="nav-logo">Aria</a>
  </nav>

  <main class="error-page">
    <div class="error-code">@yield('code')</div>
    <p class="error-symbol">✦ ✦ ✦</p>
    <h1 class="error-title">@yield('title')</h1>
    <div class="error-divider"></div>
    <p class="error-message">@yield('message')</p>
    <div class="btn-group">@yield('actions')</div>
  </main>

  <footer class="footer">&copy; {{ date('Y') }} Aria — Musique Électronique</footer>

  <script>
    const container = document.getElementById('stars');
    for (let i = 0; i < 130; i++) {
      const s = document.createElement('div');
      s.className = 'star';
      const size = Math.random() * 2.5 + 0.5;
      s.style.cssText = `width:${size}px;height:${size}px;left:${Math.random()*100}%;top:${Math.random()*100}%;--d:${(Math.random()*4+2).toFixed(1)}s;--delay:-${(Math.random()*6).toFixed(1)}s;--min:${(Math.random()*0.15+0.05).toFixed(2)};--max:${(Math.random()*0.55+0.3).toFixed(2)};`;
      container.appendChild(s);
    }
  </script>
</body>
</html>
