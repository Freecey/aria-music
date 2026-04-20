@extends('admin.layouts.admin')

@section('title', 'Modifier Utilisateur')

@section('content')
<div style="max-width:480px;">
  <div class="page-actions" style="margin-bottom:1.5rem;">
    <h2 class="page-title">Modifier : {{ $user->name }}</h2>
    <a href="/admin/users" class="btn btn-secondary">← Retour</a>
  </div>
  <form method="POST" action="/admin/users/{{ $user->id }}" class="card">
    @csrf @method('PUT')
    @if($errors->any())
    <div style="margin-bottom:1rem; padding:0.75rem; background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.3); border-radius:6px; font-size:0.875rem; color:#f87171;">
      {{ $errors->first() }}
    </div>
    @endif
    <div class="form-group">
      <label class="form-label" for="name">Nom</label>
      <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
    </div>
    <div class="form-group">
      <label class="form-label" for="email">Email</label>
      <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
    </div>
    <div class="form-group">
      <label class="form-label" for="password">Nouveau mot de passe</label>
      <input type="password" id="password" name="password" class="form-control" minlength="8">
      <span class="form-hint">Laissez vide pour ne pas changer.</span>
    </div>
    <div class="form-group">
      <label class="form-label" for="password_confirmation">Confirmer le mot de passe</label>
      <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" minlength="8">
    </div>
    @if($user->id !== auth()->id())
    <div class="form-group">
      <label class="form-label" for="role">Rôle</label>
      <select id="role" name="role" class="form-control">
        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
        <option value="editor" {{ $user->role === 'editor' ? 'selected' : '' }}>Editor</option>
      </select>
    </div>
    @endif
    <div style="display:flex; gap:0.5rem;">
      <button type="submit" class="btn btn-primary">Sauvegarder</button>
      <a href="/admin/users" class="btn btn-secondary">Annuler</a>
    </div>
  </form>
</div>
@endsection
