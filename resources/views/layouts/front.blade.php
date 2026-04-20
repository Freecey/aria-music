<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $site_name ?? 'Aria' }} — {{ $tagline ?? 'Musique Électronique' }}</title>
  
  @if(isset($meta_description))
  <meta name="description" content="{{ $meta_description }}">
  @endif
  
  <meta name="keywords" content="{{ $meta_keywords ?? 'Aria, artiste IA, musique électronique' }}">
  
  <!-- Open Graph -->
  <meta property="og:title" content="{{ $site_name ?? 'Aria' }}">
  <meta property="og:description" content="{{ $meta_description ?? '' }}">
  <meta property="og:type" content="website">
  @if(isset($og_image_url))
  <meta property="og:image" content="{{ $og_image_url }}">
  @endif
  
  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="{{ $site_name ?? 'Aria' }}">
  <meta name="twitter:description" content="{{ $meta_description ?? '' }}">
  @if(isset($og_image_url))
  <meta name="twitter:image" content="{{ $og_image_url }}">
  @endif

  <!-- Canonical -->
  <link rel="canonical" href="{{ url()->current() }}">
  
  <!-- Favicon -->
  <link rel="icon" href="/favicon.ico">
  
  <!-- Fonts preload -->
  <link rel="preconnect" href="{{ url('/fonts/montserrat-latin-700.woff2') }}">
  <link rel="preconnect" href="{{ url('/fonts/inter-latin-400.woff2') }}">

  <!-- Vite assets -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- JSON-LD Structured Data -->
  @include('partials.json-ld')
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
      <a href="#" class="nav-logo">{{ $site_name ?? 'Aria' }}</a>
      <ul class="nav-links">
        <li><a href="#hero" class="nav-link active">Accueil</a></li>
        <li><a href="#music" class="nav-link">Musique</a></li>
        <li><a href="#about" class="nav-link">À Propos</a></li>
        <li><a href="#contact" class="nav-link">Contact</a></li>
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
