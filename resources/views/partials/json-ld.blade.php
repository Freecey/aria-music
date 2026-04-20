{{-- Partial: JSON-LD structured data for SEO --}}
{{-- MusicGroup + MusicAlbum schemas --}}
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "MusicGroup",
      "@@id": "https://aria-music.be/#organization",
      "name": "{{ $site_name ?? 'Aria' }}",
      "alternateName": "Aria",
      "description": "{{ $bio ?? '' }}",
      "url": "https://aria-music.be",
      "logo": {
        "@@type": "ImageObject",
        "url": "{{ $avatar_url ?? '' }}"
      },
      "sameAs": [
        @foreach($socialLinks ?? [] as $link)
          "{{ $link->url }}"{{ !$loop->last ? ',' : '' }}
        @endforeach
      ],
      "genre": "{{ $subtitle ?? 'Musique Électronique' }}",
      "contactPoint": {
        "@@type": "ContactPoint",
        "email": "aria@aria-music.be",
        "contactType": "Customer Service"
      }
    },
    {
      "@@type": "WebSite",
      "@@id": "https://aria-music.be/#website",
      "url": "https://aria-music.be",
      "name": "{{ $site_name ?? 'Aria' }}",
      "publisher": { "@@id": "https://aria-music.be/#organization" },
      "potentialAction": {
        "@@type": "SearchAction",
        "target": "https://aria-music.be/?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    },
    {
      "@@type": "BreadcrumbList",
      "@@id": "https://aria-music.be/#breadcrumb",
      "itemListElement": [
        {
          "@@type": "ListItem",
          "position": 1,
          "name": "Accueil",
          "item": "https://aria-music.be"
        }
      ]
    }
    @foreach($albums ?? [] as $album)
    ,{
      "@@type": "MusicAlbum",
      "@@id": "https://aria-music.be/#album-{{ $album->id }}",
      "name": "{{ $album->title }}",
      "byArtist": { "@@id": "https://aria-music.be/#organization" },
      "datePublished": "{{ $album->year }}",
      "description": "{{ $album->description ?? '' }}",
      "image": "{{ $album->cover_url ?? '' }}",
      "url": "https://aria-music.be/#music",
      "track": [
        @foreach($album->tracks ?? [] as $track)
        {
          "@@type": "MusicTrack",
          "name": "{{ $track->title }}",
          "duration": "{{ $track->duration ?? '' }}",
          "url": "{{ $track->media_url ?? '' }}"
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
      ]
    }
    @endforeach
  ]
}
</script>
