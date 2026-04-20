@extends('admin.layouts.admin')

@section('title', 'Nouvel Album')

@section('content')
<div style="max-width: 640px;">
  <div class="page-actions" style="margin-bottom:1.5rem;">
    <h2 class="page-title">Nouvel Album</h2>
    <a href="/admin/albums" class="btn btn-secondary">← Retour</a>
  </div>

  <form method="POST" action="/admin/albums" enctype="multipart/form-data" class="card">
    @csrf
    <p style="font-size:0.75rem; color:var(--text-muted); margin-bottom:1rem;">* Champs obligatoires</p>

    <div class="form-group">
      <label class="form-label" for="title">Titre *</label>
      <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required>
      @error('title') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-row">
      <div class="form-group">
        <label class="form-label" for="year">Année *</label>
        <input type="number" id="year" name="year" class="form-control" value="{{ old('year', date('Y')) }}" min="2000" max="2100" required>
      </div>
      <div class="form-group">
        <label class="form-label" for="platform">Plateforme *</label>
        <select id="platform" name="platform" class="form-control" required>
          <option value="youtube" {{ old('platform') == 'youtube' ? 'selected' : '' }}>YouTube</option>
          <option value="spotify" {{ old('platform') == 'spotify' ? 'selected' : '' }}>Spotify</option>
          <option value="soundcloud" {{ old('platform') == 'soundcloud' ? 'selected' : '' }}>SoundCloud</option>
          <option value="bandcamp" {{ old('platform') == 'bandcamp' ? 'selected' : '' }}>Bandcamp</option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label class="form-label" for="media_url">URL Media (album complet)</label>
      <input type="url" id="media_url" name="media_url" class="form-control" value="{{ old('media_url') }}" placeholder="https://www.youtube.com/watch?v=...">
      <span class="form-hint">Lien vers la playlist/album sur la plateforme de streaming</span>
    </div>

    <div class="form-group">
      <label class="form-label" for="description">Description</label>
      <textarea id="description" name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
    </div>

    <div class="form-group">
      <label class="form-label" for="cover">Couverture</label>
      <input type="file" id="cover" name="cover" class="form-control" accept="image/*">
      <span class="form-hint">JPEG, PNG ou WebP. Max 4 Mo. Converti en WebP automatiquement.</span>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label class="form-label" for="sort">Ordre de tri</label>
        <input type="number" id="sort" name="sort" class="form-control" value="{{ old('sort', 0) }}">
      </div>
      <div class="form-group" style="display:flex; align-items:center; padding-top:1.5rem;">
        <label class="toggle" style="gap:0.5rem; cursor:pointer;">
          <input type="checkbox" name="active" checked>
          <span class="toggle-slider"></span>
        </label>
        <span style="font-size:0.875rem; color:var(--text-secondary); margin-left:0.25rem;">Actif (visible sur le site)</span>
      </div>
    </div>

    <div style="display:flex; gap:0.5rem; margin-top:1.5rem;">
      <button type="submit" class="btn btn-primary">Créer l'album</button>
      <a href="/admin/albums" class="btn btn-secondary">Annuler</a>
    </div>
  </form>
</div>
@endsection
