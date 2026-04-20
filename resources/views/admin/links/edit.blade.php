@extends('admin.layouts.admin')

@section('title', 'Modifier Lien')

@section('content')
<div style="max-width:560px;">
  <div class="page-actions" style="margin-bottom:1.5rem;">
    <h2 class="page-title">Modifier : {{ $link->label }}</h2>
    <a href="/admin/links" class="btn btn-secondary">← Retour</a>
  </div>
  <form method="POST" action="/admin/links/{{ $link->id }}" class="card">
    @csrf @method('PUT')
    @if($errors->any())
    <div class="alert alert-error" style="margin-bottom:1rem; padding:0.75rem; background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.3); border-radius:6px; font-size:0.875rem; color:#f87171;">
      {{ $errors->first() }}
    </div>
    @endif
    <div class="form-group">
      <label class="form-label" for="platform">Plateforme</label>
      <input type="text" id="platform" name="platform" class="form-control" value="{{ old('platform', $link->platform) }}">
    </div>
    <div class="form-group">
      <label class="form-label" for="label">Label</label>
      <input type="text" id="label" name="label" class="form-control" value="{{ old('label', $link->label) }}">
    </div>
    <div class="form-group">
      <label class="form-label" for="url">URL</label>
      <input type="text" id="url" name="url" class="form-control" value="{{ old('url', $link->url) }}" placeholder="https://... ou mailto:...">
      @error('url') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    <div class="form-group">
      <label class="form-label" for="icon_svg">Icône SVG</label>
      <textarea id="icon_svg" name="icon_svg" class="form-control" rows="2">{!! old('icon_svg', $link->icon_svg) !!}</textarea>
    </div>
    <div class="form-group">
      <label class="form-label" for="sort">Ordre de tri</label>
      <input type="number" id="sort" name="sort" class="form-control" value="{{ old('sort', $link->sort) }}">
    </div>
    <div style="display:flex; gap:0.5rem; align-items:center; margin-bottom:1.5rem;">
      <label class="toggle"><input type="checkbox" name="active" {{ $link->active ? 'checked' : '' }}><span class="toggle-slider"></span></label>
      <span style="font-size:0.875rem; color:var(--text-secondary);">Actif</span>
    </div>
    <div style="display:flex; gap:0.5rem;">
      <button type="submit" class="btn btn-primary">Sauvegarder</button>
      <a href="/admin/links" class="btn btn-secondary">Annuler</a>
    </div>
  </form>
</div>
@endsection
