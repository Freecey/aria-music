@php
  $seo_title       = $seo_title       ?? (($site_name ?? 'Aria') . ' — ' . ($tagline ?? 'Musique Électronique'));
  $seo_description = $seo_description ?? ($meta_description ?? ($tagline ?? 'Artiste IA de musique électronique'));
  $seo_canonical   = $seo_canonical   ?? url('/');
  $seo_og_type     = $seo_og_type     ?? 'website';
  $seo_og_url      = $seo_og_url      ?? url('/');
  $seo_og_title    = $seo_og_title    ?? $seo_title;
  $seo_og_image    = $seo_og_image    ?? ($og_image_url ?? null);
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#0d0a1a">
  <title>{{ $seo_title }}</title>

  <meta name="description" content="{{ $seo_description }}">
  @if(!empty($meta_keywords))
  <meta name="keywords" content="{{ $meta_keywords }}">
  @endif

  <!-- Open Graph -->
  <meta property="og:type" content="{{ $seo_og_type }}">
  <meta property="og:url" content="{{ $seo_og_url }}">
  <meta property="og:site_name" content="{{ $site_name ?? 'Aria' }}">
  <meta property="og:locale" content="fr_FR">
  <meta property="og:title" content="{{ $seo_og_title }}">
  <meta property="og:description" content="{{ $seo_description }}">
  @if($seo_og_image)
  <meta property="og:image" content="{{ $seo_og_image }}">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  @endif

  <!-- Twitter Card -->
  <meta name="twitter:card" content="{{ $seo_og_image ? 'summary_large_image' : 'summary' }}">
  <meta name="twitter:title" content="{{ $seo_og_title }}">
  <meta name="twitter:description" content="{{ $seo_description }}">
  @if($seo_og_image)
  <meta name="twitter:image" content="{{ $seo_og_image }}">
  @endif

  <!-- Canonical -->
  <link rel="canonical" href="{{ $seo_canonical }}">

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="/favicon.png">

  <!-- Fonts preload -->
  <link rel="preload" as="font" type="font/woff2" href="/fonts/montserrat-latin-700.woff2" crossorigin>
  <link rel="preload" as="font" type="font/woff2" href="/fonts/inter-latin-400.woff2" crossorigin>

  <!-- Vite assets -->
  @vite(['resources/css/variables.css', 'resources/css/app.css', 'resources/js/app.js'])

  <!-- JSON-LD Structured Data -->
  @include('partials.json-ld')
  @yield('json_ld')
</head>
<body>
  <!-- Stars Background Canvas -->
  <canvas id="stars-canvas"></canvas>

  <!-- Cosmic Loader -->
  <div id="cosmic-loader" class="cosmic-loader">
    <div class="loader-orbit">
      <div class="center-star"></div>
      <div class="orbit-ring">
        <div class="star"></div>
      </div>
      <div class="orbit-ring">
        <div class="star"></div>
      </div>
      <div class="orbit-ring">
        <div class="star"></div>
      </div>
    </div>
    <p class="loader-text">Aria</p>
    <div class="loader-progress">
      <div class="loader-progress-bar"></div>
    </div>
  </div>

  <!-- Navigation -->
  <nav class="nav">
    <div class="nav-inner">
      <a href="/" class="nav-logo">{{ $site_name ?? 'Aria' }}</a>
      <ul class="nav-links">
        <li><a href="/#hero" class="nav-link">Accueil</a></li>
        <li><a href="/#music" class="nav-link">Musique</a></li>
        <li><a href="/#about" class="nav-link">À Propos</a></li>
        <li><a href="/#contact" class="nav-link">Contact</a></li>
      </ul>
    </div>
  </nav>

  <!-- Main Content -->
  <main>
    @yield('content')
  </main>

  <!-- Footer -->
  <footer class="footer">
    <p>&copy; {{ date('Y') }} {{ $site_name ?? 'Aria' }} — {{ $tagline ?? 'Une artiste IA qui crée depuis le néant' }}</p>
  </footer>
</body>
</html>
