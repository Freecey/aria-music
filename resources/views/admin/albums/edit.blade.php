@extends('admin.layouts.admin')

@section('title', 'Modifier Album')

@section('content')
<div style="max-width: 640px;">
  <div class="page-actions" style="margin-bottom:1.5rem;">
    <h2 class="page-title">Modifier : {{ $album->title }}</h2>
    <a href="/admin/albums" class="btn btn-secondary">← Retour</a>
  </div>

  <div style="display:grid; grid-template-columns: 1fr 280px; gap:1.5rem;">
    <form method="POST" action="/admin/albums/{{ $album->id }}" enctype="multipart/form-data" class="card">
      @csrf @method('PUT')

      <div class="form-group">
        <label class="form-label" for="title">Titre *</label>
        <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $album->title) }}" required>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label" for="year">Année *</label>
          <input type="number" id="year" name="year" class="form-control" value="{{ old('year', $album->year) }}" required>
        </div>
        <div class="form-group">
          <label class="form-label" for="platform">Plateforme *</label>
          <select id="platform" name="platform" class="form-control" required>
            @foreach(['youtube','spotify','soundcloud','bandcamp'] as $p)
              <option value="{{ $p }}" {{ $album->platform == $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label" for="media_url">URL Media</label>
        <input type="url" id="media_url" name="media_url" class="form-control" value="{{ old('media_url', $album->media_url) }}">
      </div>

      <div class="form-group">
        <label class="form-label" for="description">Description</label>
        <textarea id="description" name="description" class="form-control" rows="3">{{ old('description', $album->description) }}</textarea>
      </div>

      <div class="form-group">
        <label class="form-label" for="cover">Nouvelle couverture</label>
        <input type="file" id="cover" name="cover" class="form-control" accept="image/*">
        <span class="form-hint">Remplacera la couverture actuelle.</span>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label" for="sort">Ordre de tri</label>
          <input type="number" id="sort" name="sort" class="form-control" value="{{ old('sort', $album->sort) }}">
        </div>
        <div class="form-group" style="display:flex; align-items:center; padding-top:1.5rem;">
          <label class="toggle" style="gap:0.5rem; cursor:pointer;">
            <input type="checkbox" name="active" {{ $album->active ? 'checked' : '' }}>
            <span class="toggle-slider"></span>
          </label>
          <span style="font-size:0.875rem; color:var(--text-secondary); margin-left:0.25rem;">Actif</span>
        </div>
      </div>

      <div style="display:flex; gap:0.5rem; margin-top:1.5rem;">
        <button type="submit" class="btn btn-primary">Sauvegarder</button>
        <a href="/admin/albums" class="btn btn-secondary">Annuler</a>
      </div>
    </form>

    <!-- Sidebar: cover preview + tracks -->
    <div style="display:flex; flex-direction:column; gap:1rem;">
      @if($album->cover_url)
        <div class="card">
          <p class="form-label" style="margin-bottom:0.5rem;">Couverture actuelle</p>
          <img src="{{ $album->cover_url }}" alt="" style="width:100%; border-radius:8px;">
        </div>
      @endif

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Tracks</h3>
          <a href="/admin/tracks/create?album_id={{ $album->id }}" class="btn btn-sm btn-primary">+ Ajouter</a>
        </div>
        @if($album->tracks->isEmpty())
          <p style="font-size:0.875rem; color:var(--text-muted); padding:0.5rem 0;">Aucune track</p>
        @else
          <ul style="list-style:none; font-size:0.8125rem;">
            @foreach($album->tracks as $track)
              <li style="display:flex; justify-content:space-between; align-items:center; padding:0.375rem 0; border-bottom:1px solid var(--border-card);">
                <span>{{ $track->title }} <span style="color:var(--text-muted);">{{ $track->duration ? '('.$track->duration.')' : '' }}</span></span>
                <a href="/admin/tracks/{{ $track->id }}/edit" style="color:var(--color-violet);">✦</a>
              </li>
            @endforeach
          </ul>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
