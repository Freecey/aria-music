@extends('admin.layouts.admin')

@section('title', 'Modifier Actualité')

@section('content')
<div style="max-width:560px;">
  <div class="page-actions" style="margin-bottom:1.5rem;">
    <h2 class="page-title">Modifier Actualité</h2>
    <a href="/admin/updates" class="btn btn-secondary">← Retour</a>
  </div>
  <form method="POST" action="/admin/updates/{{ $update->id }}" class="card">
    @csrf @method('PUT')
    <div class="form-group">
      <label class="form-label" for="body">Contenu *</label>
      <textarea id="body" name="body" class="form-control" rows="4" required>{{ old('body', $update->body) }}</textarea>
    </div>
    <div class="form-group">
      <label class="form-label" for="published_at">Date de publication</label>
      <input type="datetime-local" id="published_at" name="published_at" class="form-control" value="{{ old('published_at', $update->published_at?->format('Y-m-d\TH:i')) }}">
    </div>
    <div style="display:flex; gap:0.5rem; align-items:center; margin-bottom:1.5rem;">
      <label class="toggle"><input type="checkbox" name="visible" {{ $update->visible ? 'checked' : '' }}><span class="toggle-slider"></span></label>
      <span style="font-size:0.875rem; color:var(--text-secondary);">Visible</span>
    </div>
    <div style="display:flex; gap:0.5rem;">
      <button type="submit" class="btn btn-primary">Sauvegarder</button>
      <a href="/admin/updates" class="btn btn-secondary">Annuler</a>
    </div>
  </form>
</div>
@endsection
