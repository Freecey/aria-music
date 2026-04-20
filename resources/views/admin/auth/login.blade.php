<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion — Aria Admin</title>
  <link rel="icon" href="/favicon.ico">
  <link rel="preconnect" href="/fonts/montserrat-latin-700.woff2">
  <link rel="preconnect" href="/fonts/inter-latin-400.woff2">
  @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>
<body>
  <div class="login-page">
    <div class="login-card">
      <div class="login-logo">✦ <span>Aria</span></div>
      <p class="login-subtitle">Administration du site</p>

      @if($errors->any())
        <div class="alert alert--error" style="margin-bottom:1.5rem;">
          {{ $errors->first() }}
        </div>
      @endif

      <form method="POST" action="/admin/login">
        @csrf
        <div class="form-group">
          <label class="form-label" for="email">Email</label>
          <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="aria@aria-music.be" required autofocus>
        </div>
        <div class="form-group">
          <label class="form-label" for="password">Mot de passe</label>
          <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; margin-top:0.5rem;">
          Connexion
        </button>
      </form>
    </div>
  </div>
</body>
</html>
