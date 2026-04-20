@extends('layouts.front')

@section('json_ld')
@php $baseUrl = rtrim(config('app.url'), '/'); @endphp
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "WebPage",
      "@@id": "{{ $baseUrl }}/albums/{{ $album->slug }}",
      "url": "{{ $baseUrl }}/albums/{{ $album->slug }}",
      "name": "{{ $album->title }} — {{ $site_name ?? 'Aria' }}",
      "description": "{{ $album->description ?? '' }}",
      "inLanguage": "fr-FR",
      "isPartOf": { "@@id": "{{ $baseUrl }}/#website" }
      @if($album->cover_url)
      ,"primaryImageOfPage": { "@@type": "ImageObject", "url": "{{ $album->cover_url }}" }
      @endif
    },
    {
      "@@type": "MusicAlbum",
      "@@id": "{{ $baseUrl }}/albums/{{ $album->slug }}#album",
      "name": "{{ $album->title }}",
      "url": "{{ $baseUrl }}/albums/{{ $album->slug }}",
      "byArtist": { "@@id": "{{ $baseUrl }}/#artist" },
      "datePublished": "{{ $album->year }}"
      @if($album->description)
      ,"description": "{{ $album->description }}"
      @endif
      @if($album->cover_url)
      ,"image": "{{ $album->cover_url }}"
      @endif
      @if($album->media_url)
      ,"potentialAction": {
        "@@type": "ListenAction",
        "target": "{{ $album->media_url }}"
      }
      @endif
      @if($album->tracks->isNotEmpty())
      ,"numTracks": {{ $album->tracks->count() }}
      ,"track": [
        @foreach($album->tracks as $track)
        {
          "@@type": "MusicRecording",
          "@@id": "{{ $baseUrl }}/albums/{{ $album->slug }}#track-{{ $track->id }}",
          "name": "{{ $track->title }}",
          "position": {{ $loop->iteration }}
          @if($track->duration),"duration": "{{ $track->duration }}"@endif
          @if($track->media_url),"url": "{{ $track->media_url }}"@endif
          ,"byArtist": { "@@id": "{{ $baseUrl }}/#artist" }
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
      ]
      @endif
    }
  ]
}
</script>
@endsection

@section('content')
<section class="section" style="min-height:100vh; padding-top: 6rem;">
  <div class="container" style="max-width: 800px;">

    <!-- Back -->
    <a href="/#music" class="btn btn-secondary" style="margin-bottom:2rem; display:inline-flex; align-items:center; gap:0.5rem;">
      ← Albums
    </a>

    <!-- Album header -->
    <div style="display:flex; gap:2rem; align-items:flex-start; margin-bottom:3rem; flex-wrap:wrap;">
      @if($album->cover_url)
        <img src="{{ $album->cover_url }}" alt="{{ $album->title }}"
             style="width:200px; height:200px; object-fit:cover; border-radius:12px; box-shadow: 0 0 40px rgba(139,92,246,0.4); flex-shrink:0;">
      @else
        <div style="width:200px; height:200px; background:var(--bg-card); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:3rem; opacity:0.4; flex-shrink:0;">✦</div>
      @endif

      <div style="flex:1; min-width:220px;">
        <p style="color:var(--color-violet); font-size:0.8125rem; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:0.5rem;">
          {{ $album->year }}
        </p>
        <h1 style="font-family:var(--font-display); font-size:2rem; font-weight:700; line-height:1.2; margin-bottom:1rem;">
          {{ $album->title }}
        </h1>

        <!-- Artist credit -->
        <a href="/" style="display:inline-flex; align-items:center; gap:0.625rem; text-decoration:none; margin-bottom:1.25rem;">
          @if($avatar_url)
            <img src="{{ $avatar_url }}" alt="{{ $site_name ?? 'Aria' }}"
                 style="width:32px; height:32px; border-radius:50%; object-fit:cover; border:1px solid rgba(139,92,246,0.4);">
          @endif
          <span style="font-size:0.875rem; color:var(--text-secondary);">{{ $site_name ?? 'Aria' }}</span>
        </a>

        @if($album->description)
          <p style="color:var(--text-secondary); font-size:0.9375rem; line-height:1.7; margin-bottom:1.5rem;">
            {{ $album->description }}
          </p>
        @endif
        @if($album->media_url)
          <a href="{{ $album->media_url }}" class="btn btn-primary" target="_blank" rel="noopener">
            Écouter l'album
          </a>
        @endif
      </div>
    </div>

    <!-- Tracklist -->
    @if($album->tracks->isNotEmpty())
    <div>
      <h2 style="font-family:var(--font-display); font-size:1.25rem; color:var(--color-violet); margin-bottom:1.25rem;">
        ✦ Tracklist
      </h2>
      <div style="display:flex; flex-direction:column; gap:0;">
        @foreach($album->tracks as $i => $track)
        <div style="display:flex; align-items:center; gap:1rem; padding:0.875rem 0; border-bottom:1px solid rgba(139,92,246,0.12); transition:background 0.2s;">
          <span style="color:var(--color-violet); font-size:0.8125rem; font-family:var(--font-display); width:24px; text-align:right; flex-shrink:0; opacity:0.7;">
            {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
          </span>
          <span style="flex:1; font-size:0.9375rem; font-weight:500;">{{ $track->title }}</span>
          @if($track->duration)
            <span style="color:var(--text-secondary); font-size:0.8125rem; flex-shrink:0;">{{ $track->duration }}</span>
          @endif
          @if($track->media_url)
            <a href="{{ $track->media_url }}" target="_blank" rel="noopener"
               style="color:var(--color-violet); font-size:0.8125rem; flex-shrink:0; text-decoration:none; opacity:0.8; transition:opacity 0.2s;"
               title="Écouter {{ $track->title }}">
              ▶
            </a>
          @endif
        </div>
        @endforeach
      </div>
    </div>
    @endif

    <!-- Other albums -->
    @if($other_albums->isNotEmpty())
    <div style="margin-top:4rem; padding-top:2.5rem; border-top:1px solid rgba(139,92,246,0.15);">
      <h2 style="font-family:var(--font-display); font-size:1.25rem; color:var(--color-violet); margin-bottom:1.5rem;">
        ✦ Autres albums
      </h2>
      <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(160px, 1fr)); gap:1rem;">
        @foreach($other_albums as $other)
        <a href="/albums/{{ $other->slug }}" style="text-decoration:none; display:block;">
          <div class="album-card" style="cursor:pointer;">
            <div class="album-cover">
              @if($other->cover_url)
                <img src="{{ $other->cover_url }}" alt="{{ $other->title }}" loading="lazy">
              @else
                <span class="album-placeholder">✦</span>
              @endif
            </div>
            <div class="album-info" style="padding:0.75rem;">
              <h3 class="album-title" style="font-size:0.9375rem;">{{ $other->title }}</h3>
              <p class="album-year">{{ $other->year }}</p>
            </div>
          </div>
        </a>
        @endforeach
      </div>
    </div>
    @endif

  </div>
</section>
@endsection
