@php
  $baseUrl = rtrim(config('app.url'), '/');
  $emailLink = isset($links) ? $links->firstWhere('platform', 'email') : null;
@endphp
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "MusicGroup",
      "@@id": "{{ $baseUrl }}/#artist",
      "name": "{{ $site_name ?? 'Aria' }}",
      "description": "{{ $meta_description ?? ($bio ?? '') }}",
      "url": "{{ $baseUrl }}",
      "genre": "{{ $subtitle ?? 'Musique Électronique' }}"
      @if(isset($avatar_url) && $avatar_url)
      ,"logo": {
        "@@type": "ImageObject",
        "url": "{{ $avatar_url }}"
      }
      @endif
      @if(isset($links) && $links->isNotEmpty())
      ,"sameAs": [
        @foreach($links->whereNotIn('platform', ['email']) as $link)
        "{{ $link->url }}"{{ !$loop->last ? ',' : '' }}
        @endforeach
      ]
      @endif
      @if($emailLink)
      ,"contactPoint": {
        "@@type": "ContactPoint",
        "email": "{{ str_replace('mailto:', '', $emailLink->url) }}",
        "contactType": "Customer Service"
      }
      @endif
    },
    {
      "@@type": "WebSite",
      "@@id": "{{ $baseUrl }}/#website",
      "url": "{{ $baseUrl }}",
      "name": "{{ $site_name ?? 'Aria' }}",
      "inLanguage": "fr-FR",
      "publisher": { "@@id": "{{ $baseUrl }}/#artist" }
    }
    @foreach($albums ?? [] as $album)
    ,{
      "@@type": "MusicAlbum",
      "@@id": "{{ $baseUrl }}/#album-{{ $album->id }}",
      "name": "{{ $album->title }}",
      "byArtist": { "@@id": "{{ $baseUrl }}/#artist" },
      "datePublished": "{{ $album->year }}"
      @if($album->description)
      ,"description": "{{ $album->description }}"
      @endif
      @if($album->cover_url)
      ,"image": "{{ $album->cover_url }}"
      @endif
      @if($album->media_url)
      ,"url": "{{ $album->media_url }}"
      @endif
      @if($album->tracks->isNotEmpty())
      ,"track": [
        @foreach($album->tracks as $track)
        {
          "@@type": "MusicRecording",
          "name": "{{ $track->title }}"
          @if($track->duration)
          ,"duration": "{{ $track->duration }}"
          @endif
          @if($track->media_url)
          ,"url": "{{ $track->media_url }}"
          @endif
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
      ]
      @endif
    }
    @endforeach
  ]
}
</script>
