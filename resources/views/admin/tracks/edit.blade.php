@extends('admin.layouts.admin')

@section('title', 'Modifier Track')

@section('content')
<div style="max-width:560px;">
  <div class="page-actions" style="margin-bottom:1.5rem;">
    <h2 class="page-title">Modifier : {{ $track->title }}</h2>
    <a href="/admin/tracks" class="btn btn-secondary">← Retour</a>
  </div>

  <form method="POST" action="/admin/tracks/{{ $track->id }}" class="card">
    @csrf @method('PUT')
    @if($errors->any())
    <div style="margin-bottom:1rem; padding:0.75rem; background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.3); border-radius:6px; font-size:0.875rem; color:#f87171;">
      {{ $errors->first() }}
    </div>
    @endif
    <p style="font-size:0.75rem; color:var(--text-muted); margin-bottom:1rem;">* Champs obligatoires</p>
    <div class="form-group">
      <label class="form-label" for="album_id">Album *</label>
      <select id="album_id" name="album_id" class="form-control" required>
        @foreach($albums as $album)
          <option value="{{ $album->id }}" {{ $track->album_id == $album->id ? 'selected' : '' }}>{{ $album->title }}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      <label class="form-label" for="title">Titre *</label>
      <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $track->title) }}" required>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label class="form-label" for="platform">Plateforme *</label>
        <select id="platform" name="platform" class="form-control" required>
          @foreach(['youtube','spotify','soundcloud','bandcamp'] as $p)
            <option value="{{ $p }}" {{ $track->platform == $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label class="form-label" for="duration">Durée</label>
        <input type="text" id="duration" name="duration" class="form-control" value="{{ old('duration', $track->duration) }}" placeholder="4:32">
      </div>
    </div>
    <div class="form-group">
      <label class="form-label" for="media_url">URL Media</label>
      <input type="url" id="media_url" name="media_url" class="form-control" value="{{ old('media_url', $track->media_url) }}">
    </div>
    <div class="form-group">
      <label class="form-label" for="sort">Ordre de tri</label>
      <input type="number" id="sort" name="sort" class="form-control" value="{{ old('sort', $track->sort) }}">
    </div>
    <div style="display:flex; gap:0.5rem; align-items:center; margin-bottom:1.5rem;">
      <label class="toggle"><input type="checkbox" name="active" {{ $track->active ? 'checked' : '' }}><span class="toggle-slider"></span></label>
      <span style="font-size:0.875rem; color:var(--text-secondary);">Actif</span>
    </div>
    <div style="display:flex; gap:0.5rem;">
      <button type="submit" class="btn btn-primary">Sauvegarder</button>
      <a href="/admin/tracks" class="btn btn-secondary">Annuler</a>
    </div>
  </form>
</div>
@endsection
