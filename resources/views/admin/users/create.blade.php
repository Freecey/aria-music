@extends('admin.layouts.admin')

@section('title', 'Nouvel Utilisateur')

@section('content')
<div style="max-width:480px;">
  <div class="page-actions" style="margin-bottom:1.5rem;">
    <h2 class="page-title">Nouvel Utilisateur</h2>
    <a href="/admin/users" class="btn btn-secondary">← Retour</a>
  </div>
  <form method="POST" action="/admin/users" class="card">
    @csrf
    <p style="font-size:0.75rem; color:var(--text-muted); margin-bottom:1rem;">* Champs obligatoires</p>
    <div class="form-group">
      <label class="form-label" for="name">Nom *</label>
      <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
    </div>
    <div class="form-group">
      <label class="form-label" for="email">Email *</label>
      <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
    </div>
    <div class="form-group">
      <label class="form-label" for="password">Mot de passe *</label>
      <input type="password" id="password" name="password" class="form-control" required minlength="8">
    </div>
    <div class="form-group">
      <label class="form-label" for="password_confirmation">Confirmer le mot de passe *</label>
      <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
    </div>
    <div class="form-group">
      <label class="form-label" for="role">Rôle *</label>
      <select id="role" name="role" class="form-control" required>
        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin (accès total)</option>
        <option value="editor" {{ old('role') == 'editor' ? 'selected' : '' }}>Editor (contenu uniquement)</option>
      </select>
    </div>
    <div style="display:flex; gap:0.5rem;">
      <button type="submit" class="btn btn-primary">Créer</button>
      <a href="/admin/users" class="btn btn-secondary">Annuler</a>
    </div>
  </form>
</div>
@endsection
