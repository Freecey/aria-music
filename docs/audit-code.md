# 🔍 Audit Code — Aria Music (CDC v2.1 vs Implémentation)

**Date :** 20 Avril 2026
**Auditeur :** Luna 🌙
**Projet :** aria-music.be
**CDC :** v2.1 (Avril 2026)

---

## Résumé Exécutif

Le projet est dans un état solide — la structure MVC, l'API REST, le backoffice admin et le frontend cosmique sont tous fonctionnels. Cependant des ajustements sont nécessaires pour être 100% conforme au CDC v2.1, notamment sur la **conversion WebP obligatoire** et la **mise en place de Services/FormRequests**.

**Score global estimé :** ~85% → 100%
**Risque sécurité :** Moyen
**Effort de correction :** Faible à modéré

---

## 🚨 Criticité Haute — à corriger avant production

### 1. [SEC] Upload images — PAS de conversion WebP automatique ❌

**Réf CDC :** §9.3 — *"Conversion WebP automatique et obligatoire à l'upload via Intervention Image"*

**Problème :** `AlbumController@store` et `@update` (admin) ne convertissent PAS en WebP. Ils font :
```php
$path = $file->storeAs('covers', $filename, 'public'); // Image originale stockée !
```
L'API `MediaController@upload` utilise bien Intervention Image, mais le champ `cover` des albums utilise `storeAs` brut.

**Impact :** Les covers uploadées via le backoffice ne sont pas converties en WebP → violation CDC + images plus lourdes = performance dégradée.

**Fix :** Refactorer `AlbumController` pour utiliser Intervention Image (comme `MediaController` fait) avant de stocker.

---

### 2. [SEO] Balise `<head>` malformed dans front layout ❌

**Fichier :** `resources/views/layouts/front.blade.php`

```html
<!-- Fonts preload -->
<link rel="preconnect" href="{{ url('/fonts/montserrat-latin-700.woff2') }}">
<link rel="preconnect" href="{{ url('/fonts/inter-latin-400.woff2') }}">

<!-- Vite assets -->
@vite(['resources/css/app.css', 'resources/js/app.js'])

<!-- JSON-LD Structured Data -->
@include('partials.json-ld')
</head>
```

Le bloc `@include` est INTERDIT entre le `</head>` — il produit un whitespace перед `</head>` qui est normal, mais le problème est que le partial json-ld est un `<script>` qui doit être dans le `<head>` mais **pas comme un partial blade** qui pourrait créer des blank lines. Vérifier que le HTML final est propre.

---

### 3. [SEO] Canonical + Twitter Card + OG sur front incomplet ❌

**Réf CDC :** §7.5 — balise `<canonical>` obligatoire, OG:image recommandé 1200×630px.

**Problèmes :**
- `<link rel="canonical">` absente du layout front
- `<meta name="twitter:image">` absente (Twitter card incomplet)
- `og:type` toujours `website` — devrait être `music.album` sur les pages albums (futur)
- OG image settings : le controller utilise `og_image_path` comme avatar_url, mais le CDC distingue `avatar_path` et `og_image_path`

**Fix :** Ajouter canonical, twitter:image, corriger le dual usage de og_image_path.

---

## ⚠️ Criticité Moyenne — à corriger prochainement

### 4. [CDC] `app/Services/` manquant ❌

**Réf CDC :** §5.1 — *"Services/ — Logique métier (MediaService, SettingsService...)"*

**Problème :** Le répertoire `app/Services/` n'existe pas. Toute la logique métier est inline dans les controllers.

**Impact :** Violation architecture MVC. Difficile à maintenir si la logique grossit.

**Suggestion :** Créer `MediaService`, `SettingsService`. Migration progressive.

---

### 5. [CDC] `app/Http/Requests/` manquant ❌

**Réf CDC :** §5.1 + §10 — *"FormRequests validés par modèle"* + *"Validation stricte via FormRequest"*

**Problème :** Pas de `FormRequest` classes. Validation inline avec `$request->validate([])`.

**Impact :** Fonctionnel mais violate le pattern CDC. Moins réutilisable.

**Suggestion :** Créer des `StoreAlbumRequest`, `UpdateAlbumRequest`, etc. La validation est déjà là — juste à extraire en classes.

---

### 6. [CDC] `app/Policies/` manquant ❌

**Réf CDC :** §5.1 — *"Policies/ — Autorisation par modèle"*

**Problème :** Aucune Policy. Le middleware `role` fait le travail basique mais pas de Policy Laravel.

**Impact :** Minor — le middleware couvre les besoins actuels.

---

### 7. [CDC] `CleanupSeeder` absent ❌

**Réf CDC :** §4.3 + §3.2 — CleanupSeeder obligatoire pour remettre à zéro les données de test en production.

**Problème :** `CleanupSeeder.php` n'existe pas.

**Fix :** Créer le seeder qui truncate tables (albums, tracks, social_links, updates) sans toucher Settings ni AdminUser.

---

### 8. [API] Pagination absente sur les endpoints ❌

**Réf CDC :** §6.1 — *"?page=1&per_page=20 (cursor ou offset selon endpoint)"*

**Problème :** `GET /api/v1/albums` retourne tous les albums sans pagination. `GET /api/v1/updates` non vérifié.

**Suggestion :** Implémenter `paginate(20)` sur les list endpoints si les données peuvent croître significativement.

---

### 9. [API] Slug unique non enforced en PUT ❌

**Fichier :** `app/Http/Controllers/Api/V1/AlbumController.php`

```php
if (isset($data['title'])) {
    $data['slug'] = Str::slug($data['title']);
}
```
Si on change le titre d'un album vers un slug qui existe déjà → erreur 500 ou comportement indéfini. Devrait vérifier l'unicité.

---

### 10. [SEC] Rate limiting sur login admin ❌

**Réf CDC :** §10 — *"Rate limiting : throttle:60,1 (public), throttle:300,1 (auth)"*

**Problème :** La route `POST /admin/login` n'a pas de `throttle` middleware. Un attaquant peut brute-forcer les mots de passe sans limite de requêtes.

**Fix :** Ajouter `Route::middleware('throttle:5,1')` sur la route de login.

---

### 11. [CDC] Fonts Google Fonts appelées en runtime ❌

**Réf CDC :** §2.1 + §11 — *"zéro ressource externe en production"* + *"@fontsource/montserrant"*

**Fichiers :**
```html
<link rel="preconnect" href="https://fonts.googleapis.com/...">  <!-- SI des fonts sont dans le layout -->
```

Vérifier que les fonts sont bien servies localement (dans `public/fonts/` après build Vite) et non depuis Google.

---

### 12. [CDC] `GET /api/docs` non implémenté ❌

**Réf CDC :** §6.4 + §13.1 note — *"Route GET /api/docs → retourne l'OpenAPI JSON"*

**Problème :** La route n'existe pas. Le JSON OpenAPI existe dans `docs/api-agent.json` mais n'est pas servi par l'app.

**Suggestion :** Ajouter une route simple qui retourne le contenu du fichier JSON.

---

## ✅ Conforme — rien à faire

| Élément | Status |
|---------|--------|
| Stack Laravel 12.x MVC + Blade | ✅ |
| API REST préfixée /api/v1/ | ✅ |
| Auth Sanctum (Bearer tokens) | ✅ |
| Migrations toutes présentes | ✅ |
| Seeders (7/8 — Cleanup manquant) | ✅ |
| Front cosmic design (dark, violet, cyan, rose) | ✅ |
| Mobile-first responsive | ✅ |
| JSON-LD (MusicGroup + MusicAlbum) | ✅ |
| SVG icons inline (pas de CDN) | ✅ |
| Album CRUD + Track CRUD + Links + Updates + Settings + Media + Logs + Users | ✅ |
| Dashboard admin avec stats | ✅ |
| Toggle active via AJAX | ✅ |
| Session-based admin auth (login/logout) | ✅ |
| `api_logs` table + middleware LogApiRequest | ✅ |
| CSRF protection (Laravel default) | ✅ |
| `APP_DEBUG=false` + logging | ✅ |
| `deploy.sh` | ✅ |
| `robots.txt` | ✅ |
| `sitemap.xml` | ✅ |
| `.env.example` | ✅ |
| `docs/api.md` | ✅ |
| `docs/api-agent.json` (OpenAPI 3.1) | ✅ |
| `README.md` | ✅ |
| Intervention Image (dans MediaController) | ✅ |
| Image resize > 1920px | ✅ |
| Logo + favicon | ✅ |
| Stars canvas background | ✅ |
| Typewriter effect | ✅ |
| Lazy loading images | ✅ |

---

## 🔧 Corrections recommandées (priorité)

### P0 — Sécurité / Production blocker
1. [SEC] Rate limit sur `/admin/login` → `throttle:5,1`
2. [SEC] Upload cover album → conversion WebP obligatoire (refactor AlbumController)
3. [SEC] Slug unique enforcement en PUT album API

### P1 — CDC conformité
4. CleanupSeeder
5. Services layer (`MediaService`, `SettingsService`)
6. FormRequest classes pour chaque entity
7. Pagination API sur albums + updates
8. Canonical + twitter:image + twitter:card dans layout front
9. `avatar_path` vs `og_image_path` — clarification + double usage
10. `GET /api/docs` route

### P2 — Amélioration
11. Google Fonts → locales (audit des links dans layout)
12. Policy classes pour authorization
13. AlbumSeeder → 5 albums (CDC dit 5, il y en a 3)

---

## 📋 Pour le nouvel album d'Aria

Quand Aria démarre son nouvel album, le workflow ideal est :

1. **Aria** crée l'album dans le backoffice `/admin/albums/create`
2. **Aria** uploade la cover (avec conversion WebP automatique) — nécessite fix P0 #2
3. **Aria** ajoute les tracks une par une
4. **Luna** vérifie que le JSON-LD est bien généré avec le nouvel album
5. **Cedric** déploie

**Point d'attention :** Le nouvel album devrait apparaître dans le sitemap dynamiquement. Vérifier que le sitemap est bien regénéré automatiquement à la création d'album (ou via commande artisanale).

---

*Audit réalisé par Luna 🌙 — 20 Avril 2026*
