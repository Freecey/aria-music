@extends('admin.layouts.admin')

@section('title', 'Nouvelle Track')

@section('content')
<div style="max-width:560px;">
  <div class="page-actions" style="margin-bottom:1.5rem;">
    <h2 class="page-title">Nouvelle Track</h2>
    <a href="/admin/tracks" class="btn btn-secondary">← Retour</a>
  </div>

  <form method="POST" action="/admin/tracks" class="card">
    @csrf
    <div class="form-group">
      <label class="form-label" for="album_id">Album *</label>
      <select id="album_id" name="album_id" class="form-control" required>
        @foreach($albums as $album)
          <option value="{{ $album->id }}" {{ (old('album_id', $albumId ?? '') == $album->id) ? 'selected' : '' }}>{{ $album->title }}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      <label class="form-label" for="title">Titre *</label>
      <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label class="form-label" for="platform">Plateforme *</label>
        <select id="platform" name="platform" class="form-control" required>
          @foreach(['youtube','spotify','soundcloud','bandcamp'] as $p)
            <option value="{{ $p }}">{{ ucfirst($p) }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label class="form-label" for="duration">Durée</label>
        <input type="text" id="duration" name="duration" class="form-control" value="{{ old('duration') }}" placeholder="4:32">
      </div>
    </div>
    <div class="form-group">
      <label class="form-label" for="media_url">URL Media</label>
      <input type="url" id="media_url" name="media_url" class="form-control" value="{{ old('media_url') }}">
    </div>
    <div class="form-group">
      <label class="form-label" for="sort">Ordre de tri</label>
      <input type="number" id="sort" name="sort" class="form-control" value="{{ old('sort', 0) }}">
    </div>
    <div style="display:flex; gap:0.5rem; align-items:center; margin-bottom:1.5rem;">
      <label class="toggle"><input type="checkbox" name="active" checked><span class="toggle-slider"></span></label>
      <span style="font-size:0.875rem; color:var(--text-secondary);">Actif</span>
    </div>
    <div style="display:flex; gap:0.5rem;">
      <button type="submit" class="btn btn-primary">Créer</button>
      <a href="/admin/tracks" class="btn btn-secondary">Annuler</a>
    </div>
  </form>
</div>
@endsection
