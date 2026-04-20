@extends('admin.layouts.admin')

@section('title', 'Nouveau Lien')

@section('content')
<div style="max-width:560px;">
  <div class="page-actions" style="margin-bottom:1.5rem;">
    <h2 class="page-title">Nouveau Lien</h2>
    <a href="/admin/links" class="btn btn-secondary">← Retour</a>
  </div>
  <form method="POST" action="/admin/links" class="card">
    @csrf
    <div class="form-group">
      <label class="form-label" for="platform">Plateforme *</label>
      <input type="text" id="platform" name="platform" class="form-control" value="{{ old('platform') }}" placeholder="youtube, telegram, instagram..." required>
      <span class="form-hint">Identifiant de la plateforme (sera utilisé pour l'icône)</span>
    </div>
    <div class="form-group">
      <label class="form-label" for="label">Label *</label>
      <input type="text" id="label" name="label" class="form-control" value="{{ old('label') }}" placeholder="YouTube" required>
    </div>
    <div class="form-group">
      <label class="form-label" for="url">URL *</label>
      <input type="url" id="url" name="url" class="form-control" value="{{ old('url') }}" placeholder="https://..." required>
    </div>
    <div class="form-group">
      <label class="form-label" for="icon_svg">Icône SVG (optionnel)</label>
      <textarea id="icon_svg" name="icon_svg" class="form-control" rows="2" placeholder="<svg viewBox=&quot;0 0 24 24&quot; ...>">{!! old('icon_svg') !!}</textarea>
      <span class="form-hint">Code SVG inline. Laissez vide pour un défaut.</span>
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
      <button type="submit" class="btn btn-primary">Ajouter</button>
      <a href="/admin/links" class="btn btn-secondary">Annuler</a>
    </div>
  </form>
</div>
@endsection
