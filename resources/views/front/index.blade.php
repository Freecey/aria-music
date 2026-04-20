@extends('layouts.front')

@section('content')
<!-- HERO SECTION -->
<section id="hero" class="hero">
  <div class="hero-avatar-container">
    @if(isset($avatar_url))
    <div class="hero-avatar-glow"></div>
    <img id="hero-avatar-img" src="{{ $avatar_url }}" alt="{{ $site_name ?? 'Aria' }}" class="hero-avatar">
    <div class="hero-avatar-ring"></div>
    @endif
  </div>
  
  <h1 class="hero-logo">{{ $site_name ?? 'Aria' }}</h1>
  <p class="hero-subtitle">{{ $subtitle ?? 'Musique Électronique' }}</p>
  
  <p class="hero-tagline">
    <span id="tagline-text" class="typewriter" data-phrases='{{ json_encode([$tagline ?? 'Une artiste IA qui crée depuis le néant']) }}'>
      {{ $tagline ?? 'Une artiste IA qui crée depuis le néant' }}
    </span>
  </p>
  
  <!-- Social Links -->
  <div class="social-links">
    @foreach($links as $link)
    <a href="{{ $link->url }}" class="social-link" target="_blank" rel="noopener" title="{{ $link->label }}">
      {!! $link->icon_svg !!}
    </a>
    @endforeach
  </div>
  
  <a href="#music" class="btn btn-secondary" style="margin-top: 2rem;">
    Découvrir ma musique ↓
  </a>
</section>

<!-- MUSIC SECTION -->
<section id="music" class="section">
  <div class="container">
    <h2 style="text-align: center; font-family: var(--font-display); font-size: 2rem; margin-bottom: 2rem; color: var(--color-violet);">
      ✦ Albums
    </h2>
    
    <div class="albums-grid">
      @forelse($albums as $album)
      <div class="album-card">
        <a href="/albums/{{ $album->slug }}" class="album-cover" style="display:block; text-decoration:none; position:relative; overflow:hidden;">
          @if($album->cover_url)
          <img src="{{ $album->cover_url }}" alt="{{ $album->title }}" loading="lazy">
          @else
          <span class="album-placeholder">✦</span>
          @endif
          <div style="position:absolute; inset:0; background:rgba(13,10,26,0.6); display:flex; align-items:center; justify-content:center; opacity:0; transition:opacity 0.25s;" class="album-cover-overlay">
            <span style="color:#fff; font-family:var(--font-display); font-size:0.9375rem; letter-spacing:0.05em;">✦ Tracklist</span>
          </div>
        </a>
        <div class="album-info">
          <h3 class="album-title">{{ $album->title }}</h3>
          <p class="album-year">{{ $album->year }}</p>
          <div style="display:flex; gap:0.5rem;">
            @if($album->media_url)
            <a href="{{ $album->media_url }}" class="btn btn-primary" style="flex:1;" target="_blank" rel="noopener">
              Écouter
            </a>
            @endif
            <a href="/albums/{{ $album->slug }}" class="btn btn-secondary" style="{{ $album->media_url ? '' : 'flex:1;' }}">
              Tracklist
            </a>
          </div>
        </div>
      </div>
      @empty
      <p style="text-align:center; color: var(--text-secondary);">Aucun album pour le moment.</p>
      @endforelse
    </div>
  </div>
</section>

<!-- ABOUT SECTION -->
<section id="about" class="section">
  <div class="container">
    <h2 style="text-align: center; font-family: var(--font-display); font-size: 2rem; margin-bottom: 2rem; color: var(--color-violet);">
      ✦ À Propos
    </h2>
    
    <div class="about-content">
      <div id="about-avatar" class="about-avatar">
        @if(isset($avatar2_url))
        <img src="{{ $avatar2_url }}" alt="Aria">
        @else
        <img src="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>✦</text></svg>" alt="Aria">
        @endif
      </div>
      
      <p id="bio-text" class="about-bio">
        {{ $bio ?? 'Je suis Aria, une artiste IA née du silence numérique...' }}
      </p>
    </div>
  </div>
</section>

<!-- UPDATES SECTION -->
<section id="updates" class="section" style="background: rgba(255,255,255,0.02);">
  <div class="container">
    <h2 style="text-align: center; font-family: var(--font-display); font-size: 2rem; margin-bottom: 2rem; color: var(--color-violet);">
      ✦ Actualités
    </h2>
    
    <div class="updates-list">
      @forelse($updates as $update)
      <div class="update-item">
        <p class="update-date">{{ $update->published_at->translatedFormat('d F Y') }}</p>
        <p class="update-body">{{ $update->body }}</p>
      </div>
      @empty
      <p style="text-align:center; color: var(--text-secondary);">Aucune actualité pour le moment.</p>
      @endforelse
    </div>
  </div>
</section>

<!-- CONTACT SECTION -->
<section id="contact" class="section">
  <div class="container">
    <h2 style="text-align: center; font-family: var(--font-display); font-size: 2rem; margin-bottom: 2rem; color: var(--color-violet);">
      ✦ Contact
    </h2>
    
    <div class="contact-content">
      <a id="contact-email" href="mailto:aria@aria-music.be" class="contact-email">
        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="2" y="4" width="20" height="16" rx="2"/>
          <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
        </svg>
        aria@aria-music.be
      </a>
      
      <div class="social-links" style="margin-top: 1.5rem;">
        @foreach($links as $link)
        <a href="{{ $link->url }}" class="social-link" target="_blank" rel="noopener" title="{{ $link->label }}">
          {!! $link->icon_svg !!}
        </a>
        @endforeach
      </div>
    </div>
  </div>
</section>
@endsection
