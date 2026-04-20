@extends('admin.layouts.admin')

@section('title', 'Paramètres')

@section('content')
<div style="max-width:720px;">
  <div class="page-actions" style="margin-bottom:1.5rem;">
    <h2 class="page-title">Paramètres du Site</h2>
  </div>

  <form method="POST" action="/admin/settings" enctype="multipart/form-data" class="card">
    @csrf

    <h3 style="font-family:var(--font-display); font-size:1rem; font-weight:600; margin-bottom:1.25rem; color:var(--color-violet);">Informations générales</h3>

    <div class="form-group">
      <label class="form-label" for="site_name">Nom du site</label>
      <input type="text" id="site_name" name="site_name" class="form-control" value="{{ old('site_name', $settings['site_name']->value ?? '') }}">
    </div>

    <div class="form-row">
      <div class="form-group">
        <label class="form-label" for="tagline">Tagline</label>
        <input type="text" id="tagline" name="tagline" class="form-control" value="{{ old('tagline', $settings['tagline']->value ?? '') }}">
      </div>
      <div class="form-group">
        <label class="form-label" for="subtitle">Sous-titre</label>
        <input type="text" id="subtitle" name="subtitle" class="form-control" value="{{ old('subtitle', $settings['subtitle']->value ?? '') }}">
      </div>
    </div>

    <div class="form-group">
      <label class="form-label" for="bio">Biographie</label>
      <textarea id="bio" name="bio" class="form-control" rows="6">{{ old('bio', $settings['bio']->value ?? '') }}</textarea>
      <span class="form-hint">Texte présenté dans la section "À Propos".</span>
    </div>

    <hr style="border:none; border-top:1px solid var(--border-card); margin:1.5rem 0;">

    <h3 style="font-family:var(--font-display); font-size:1rem; font-weight:600; margin-bottom:1.25rem; color:var(--color-violet);">Images</h3>

    <div class="form-row">
      <div class="form-group">
        <label class="form-label" for="avatar">Avatar</label>
        <input type="file" id="avatar" name="avatar" class="form-control" accept="image/*">
        @if(!empty($settings['avatar_path']->value))
          <div style="margin-top:0.5rem;">
            <img src="{{ asset('storage/' . $settings['avatar_path']->value) }}" style="width:80px; height:80px; object-fit:cover; border-radius:50%; border:2px solid var(--color-violet);">
          </div>
        @endif
      </div>
      <div class="form-group">
        <label class="form-label" for="og_image">Image OG (1200×630)</label>
        <input type="file" id="og_image" name="og_image" class="form-control" accept="image/*">
        @if(!empty($settings['og_image_path']->value))
          <div style="margin-top:0.5rem;">
            <img src="{{ asset('storage/' . $settings['og_image_path']->value) }}" style="height:60px; border-radius:6px;">
          </div>
        @endif
      </div>
    </div>

    <hr style="border:none; border-top:1px solid var(--border-card); margin:1.5rem 0;">

    <h3 style="font-family:var(--font-display); font-size:1rem; font-weight:600; margin-bottom:1.25rem; color:var(--color-violet);">SEO</h3>

    <div class="form-group">
      <label class="form-label" for="meta_description">Méta description</label>
      <textarea id="meta_description" name="meta_description" class="form-control" rows="2" maxlength="160">{{ old('meta_description', $settings['meta_description']->value ?? '') }}</textarea>
      <span class="form-hint">Max 160 caractères. Visible dans les résultats de recherche.</span>
    </div>

    <div class="form-group">
      <label class="form-label" for="meta_keywords">Méta keywords</label>
      <input type="text" id="meta_keywords" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $settings['meta_keywords']->value ?? '') }}" placeholder="Aria, artiste IA, musique électronique...">
    </div>

    <div style="margin-top:1.5rem;">
      <button type="submit" class="btn btn-primary">Sauvegarder les paramètres</button>
    </div>
  </form>
</div>
@endsection
