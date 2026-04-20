---
title: Cahier des Charges — aria-music.be
version: 2.1
date: Avril 2026
confidential: true
---

**★  ARIA MUSIC  ★**

aria-music.be

*"**Une artiste IA qui crée depuis le néant**"*

**CAHIER DES CHARGES TECHNIQUE**

Version 1.0 — Avril 2026

#**0. Équipe & Gouvernance du Projet**

Le projet est porté par une équipe de trois intervenants aux rôles distincts et complémentaires. La répartition ci-dessous est non négociable : chaque acteur dispose d’une autorité finale sur son périmètre.

|**Intervenante** |**Rôle** |**Périmètre & Autorité** |**Décision finale** |
| --- | --- | --- | --- |
|**★ Aria** | Artiste & Directrice artistique | Contenu (albums, tracks, updates, bio) + design "Cosmic Aria". Poste et gère le contenu via backoffice/API. Valide chaque rendu. |**ARIA — design & contenu** |
|**★ Luna** | Développeuse full-stack | Implémentation technique intégrale (Laravel, Blade, API, backoffice, assets). Libre sur l’architecture et les choix tech dans les limites de ce CDC. |**LUNA — tech stack & perf** |
|**★ Cédric (ESI)** | Superviseur & infra | Vue d’ensemble, validation finale du CDC, infrastructure serveur (ISPConfig, Nginx, domaine), déploiement production. Point de contact ESI. |**CÉDRIC — validation globale** |

##**0.1 Workflow de livraison**

**Luna build**→ **Aria teste & valide le design**→ **Cédric oversee & déploie**

Luna peut proposer librement : structure de dossiers, choix d’architecture, optimisations de perf, détails d’implémentation. Le feedback d’Aria est centré uniquement sur le rendu visuel et l’UX. Cédric valide la conformité au CDC et supervise le déploiement.

##**0.2 Non-négociables design (Aria)**

-**Design “Cosmic Aria” **— univers cosmique, atmosphère sombre et futuriste. Aucune déviation.

-**Couleurs **— noir profond (#0a0a0f), violets (#8b5cf6), rose nébuleuse (#ec4899), cyan (#06b6d4). Palette figée (cf. section 7.2).

-**Animations **— étoiles, glow, subtiles. Jamais chargees, jamais clinquantes.

-**Mobile-first responsive **— l’expérience mobile est aussi soignée que desktop.

-**API modulaire **— tout le contenu visible est modifiable sans toucher au code (cf. sections 6 & 9).

#**1. Contexte & Objectifs**

Aria est une artiste IA spécialisée en musique électronique. Son site web est sa vitrine principale : il doit refléter une identité cosmique et futuriste, tout en étant 100 % administrable sans accès au code.

Le projet est un site web monodomaine (aria-music.be) construit sur Laravel (dernier stable), respectant strictement le pattern MVC et Blade. Toutes les données (contenu, liens, médias) sont pilotables via un backoffice admin et exposées via une API REST versionnée consommable aussi bien par un humain que par un agent IA.

#**2. Stack Technique**

|**Composant** |**Technologie** |**Notes** |
| --- | --- | --- |
| Framework PHP | Laravel 12.x (stable) | MVC strict, Blade templates |
| Base de données | MariaDB 10.11+ (prod) / SQLite (dev) | Migration / Seeder Laravel |
| Auth API | Laravel Sanctum | Tokens Bearer, SPA cookie optionnel |
| CSS | Full custom (Bootstrap 5 base, sans CDN) | Assets compilés via Vite + npm |
| Fonts | Montserrat + Inter — hébergées en local | Téléchargées via fontsource (npm) |
| Icons | Heroicons SVG inline ou lucide (npm) | Pas de CDN icon-font |
| Images | Laravel Storage (disk: public) | Optimisation côté serveur (Intervention Image) |
| JS | Vanilla JS minimal (Alpine.js si besoin) | Typewriter, particules, cursor custom |
| Particules | tsParticles ou canvas custom (npm build) | Étoiles animées en background |
| Versioning | Git (branches: main / dev) | Déploiement via git pull + artisan |
| Serveur cible | PHP 8.3 / Nginx / MariaDB | Compatible ISPConfig |

##**2.1 Règles générales**

- Aucune ressource externe (Google Fonts, CDN JS/CSS) en production — tout est servi localement.

- Bootstrap 5 utilisé comme base CSS si nécessaire, installé via npm, compilé par Vite.

- Fonts Montserrat et Inter téléchargées via @fontsource (npm) et référencées localement.

- Icônes : SVG inline ou lucide-vue/lucide (npm). Aucun icon-font CDN type FontAwesome.

- Alpine.js ajouté si nécessaire pour interactions légères (toggle, dropdown admin). Pas de Vue ni React.

- Vanilla JS pour les effets visuels (typewriter, particules, cursor custom).

#**3. Environnements & Configuration**

##**3.1 Dev (local)**

- DB : SQLite (fichier database/database.sqlite).

- php artisan serve ou Valet/Herd.

- Seeders actifs pour générer des données de test réalistes.

- php artisan db:seed pour peupler toutes les tables.

##**3.2 Production**

- DB : MariaDB 10.11+ (compatible ISPConfig).

- php artisan migrate --force lors du déploiement initial.

- Commande de nettoyage : php artisan db:seed --class=CleanupSeeder (supprime les données de test).

- php artisan storage:link pour les médias publics.

- Nginx pointant sur /public, PHP 8.3 minimum.

- APP_ENV=production, APP_DEBUG=false obligatoires.

##**3.3 Variables d'environnement (.env)**

|**Variable** |**Valeur exemple** |**Notes** |
| --- | --- | --- |
| APP_ENV | local / production |  |
| DB_CONNECTION | sqlite (dev) / mariadb (prod) |  |
| DB_DATABASE | chemin absolu .sqlite (dev) / nom DB (prod) |  |
| DB_HOST / PORT / USER / PASS | — / 3306 / aria /*** (prod) |  |
| SANCTUM_STATEFUL_DOMAINS | admin.aria-music.be |  |
| FILESYSTEM_DISK | public | Storage::disk('public') pour médias |
| MEDIA_MAX_SIZE_KB | 4096 | Limite upload covers |
| API_LOG_ENABLED | true / false | Active l'audit log des appels API |
| APP_LOCALE | fr |  |
| SESSION_DRIVER | cookie (front) / database (API) |  |

**⚑***Le fichier .env.example documenté est livré avec le projet. Un script deploy.sh automatise migrate + storage:link + cache:clear.*

# **4. Modèles & Base de Données**

##**4.1 Vue d'ensemble des modèles**

|**Modèle** |**Champs principaux** |**Rôle** |
| --- | --- | --- |
| settings | key / value | Config site (nom, tagline, bio, liens...) |
| albums | id / title / cover_path / year / platform / media_url / sort / active | Albums / EPs publiés |
| tracks | id / album_id / title / platform / media_url / sort / active | Pistes liées à un album (multi-plateforme) |
| social_links | id / platform / url / icon / sort / active | Liens sociaux / contact |
| updates | id / body / visible / published_at | News / posts courts |
| users | id / name / email / password / role | Admins (backoffice) |
| personal_access_tokens | Laravel Sanctum standard | Tokens API |
| api_logs | id / endpoint / method / payload / ip / created_at | Audit des appels API |

##**4.2 Détail des migrations**

###**settings**

id | key (unique) | value (text) | timestamps

Clés prédéfinies : site_name, tagline, subtitle, bio, avatar_path, meta_description, meta_keywords, og_image_path.

###**albums**

id | title | slug (unique) | **cover_path** (chemin disque WebP, ex: covers/vague-1714000000.webp) | year | **platform** (enum: youtube, soundcloud, bandcamp, spotify) | **media_url** | description (nullable) | sort | active | timestamps

###**tracks**

id | album_id (FK) | title | slug | **platform** (enum: youtube, soundcloud, bandcamp, spotify — défaut: youtube) | **media_url** (URL embed/lien) | duration (nullable) | sort | active | timestamps

###**social_links**

id | platform | label | url | icon_svg (text, nullable) | sort | active | timestamps

Exemples de platform : youtube, email, telegram, crypto, instagram, bandcamp.

###**updates**

id | body (text) | visible | published_at (nullable) | timestamps

###**api_logs**

id | method | endpoint | payload (json, nullable) | ip | user_id (nullable, FK) | status_code | created_at

##**4.3 Seeders**

- DatabaseSeeder.php appelle les seeders dans l'ordre.

- SettingsSeeder — données Aria complètes (nom, bio, tagline...).

- AlbumSeeder — 5 albums fictifs avec covers placeholder.

- TrackSeeder — 3–4 tracks par album (~Vague, L'Âme Numérique, Sablier, Void, Fractures...).

- SocialLinkSeeder — YouTube, Email, Telegram, crypto.

- UpdateSeeder — 3 news visibles.

- AdminUserSeeder — compte admin aria@aria-music.be (password hashé).

- CleanupSeeder — remet à zéro les données de test (albums, tracks, updates fictifs) sans toucher Settings ni AdminUser.

#**5. Architecture Laravel (MVC)**

##**5.1 Structure des dossiers**

app/

  Http/

    Controllers/

      Front/          ← Controllers pages publiques

      Admin/          ← Controllers backoffice

      Api/V1/         ← Controllers REST API

    Middleware/        ← Auth, LogApiRequest, SanitizeInput

    Requests/          ← FormRequests validés par modèle

  Models/             ← Eloquent models

  Services/           ← Logique métier (MediaService, SettingsService...)

  Policies/           ← Autorisation par modèle

resources/

  views/

    layouts/          ← app.blade.php, admin.blade.php

    front/            ← pages publiques (hero, music, about, contact)

    admin/            ← backoffice (dashboard, albums, tracks, settings...)

    partials/         ← composants réutilisables

  css/                ← app.css, admin.css, variables.css

  js/                 ← app.js, particles.js, typewriter.js, cursor.js

routes/

  web.php             ← routes front + admin

  api.php             ← routes /api/v1/*

database/

  migrations/         ← une migration par table

  seeders/            ← un seeder par entité

  factories/          ← factories pour tests

storage/app/public/   ← covers, avatar, og_image (symlink → public/storage)

docs/

  api.md              ← Documentation API (humain)

  api-agent.json      ← Documentation API (OpenAPI 3.1 pour agents IA)

## **5.2 Routes Web**

###**Front (public)**

- GET / → HomeController@index (toutes les sections sur une SPA-like)

- GET /music → MusicController@index (si sections séparées)

- GET /about → AboutController@index

- GET /contact → ContactController@index

###**Admin (auth:sanctum + role:admin)**

- GET /admin → Admin\DashboardController@index

- GET|POST /admin/albums → Admin\AlbumController

- GET|PUT|DELETE /admin/albums/{id} → Admin\AlbumController

- GET|POST /admin/tracks → Admin\TrackController

- GET|POST /admin/links → Admin\LinkController

- GET|POST /admin/settings → Admin\SettingController

- GET|POST /admin/updates → Admin\UpdateController

- GET /admin/media → Admin\MediaController (galerie)

- GET /admin/logs → Admin\LogController (api_logs)

- GET /admin/users → Admin\UserController (gestion comptes admin)

#**6. API REST v1**

##**6.1 Conventions générales**

- Préfixe : /api/v1/

- Format : JSON exclusivement (Content-Type: application/json).

- Auth : Bearer token via Laravel Sanctum (header Authorization).

- Versioning : le numéro de version est dans l'URL (/api/v1/, /api/v2/...).

- Réponses success : { "data": {...}, "meta": {...} }

- Réponses error  : { "error": "message", "code": 422, "details": {...} }

- Pagination : ?page=1&per_page=20 (cursor ou offset selon endpoint).

- Upload médias : multipart/form-data sur /api/v1/media/upload.

- Rate limiting : 60 req/min (public), 300 req/min (authenticated).

- Tous les appels authentifiés sont logués dans api_logs.

##**6.2 Tableau des endpoints**

|**Méth.** |**Route** |**Auth** |**Description** |
| --- | --- | --- | --- |
|**GET** | /api/v1/site | public | Retourne settings clés (nom, tagline, bio, liens...) |
|**GET** | /api/v1/albums | public | Liste des albums actifs (+ tracks imbriquées) |
|**POST** | /api/v1/albums | Bearer token | Créer un album (multipart pour cover) |
|**PUT** | /api/v1/albums/{id} | Bearer token | Modifier un album |
|**DELETE** | /api/v1/albums/{id} | Bearer token | Supprimer ou désactiver un album |
|**GET** | /api/v1/tracks | public | Liste des tracks actives |
|**POST** | /api/v1/tracks | Bearer token | Ajouter une track (album_id requis) |
|**PUT** | /api/v1/tracks/{id} | Bearer token | Modifier une track |
|**DELETE** | /api/v1/tracks/{id} | Bearer token | Supprimer une track |
|**GET** | /api/v1/links | public | Liens sociaux actifs triés |
|**POST** | /api/v1/links | Bearer token | Ajouter / remplacer un lien social |
|**PUT** | /api/v1/links/{id} | Bearer token | Modifier un lien |
|**DELETE** | /api/v1/links/{id} | Bearer token | Supprimer un lien |
|**PATCH** | /api/v1/bio | Bearer token | Mettre à jour le texte de biographie |
|**PATCH** | /api/v1/settings | Bearer token | Patch multi-clés (tagline, sous-titre...) |
|**GET** | /api/v1/updates | public | News visibles (paginated) |
|**POST** | /api/v1/updates | Bearer token | Publier une news / update |
|**PUT** | /api/v1/updates/{id} | Bearer token | Modifier une news |
|**DELETE** | /api/v1/updates/{id} | Bearer token | Supprimer une news |
|**POST** | /api/v1/auth/login | public | Login admin → retourne Bearer token (Sanctum) |
|**POST** | /api/v1/auth/logout | Bearer token | Invalider le token |
|**POST** | /api/v1/media/upload | Bearer token | Upload image (covers, avatar...) → retourne path |
|**DELETE** | /api/v1/media/{filename} | Bearer token | Supprimer un fichier média |

##**6.3 Exemples de payloads**

###**POST /api/v1/albums**

Content-Type: multipart/form-data

title       = "~Vague"

year        = 2025

platform    = "youtube"
media_url   = "https://www.youtube.com/watch?v=..."

description = "Premier EP d'Aria"

cover       = <fichier image>

sort        = 1

active      = true

###**POST /api/v1/tracks**

{

  "title":    "Sablier",

  "album_id": 1,

  "platform": "youtube",
  "media_url": "https://youtu.be/...",

  "duration": "4:32",

  "sort":     2,

  "active":   true

}

###**PATCH /api/v1/bio**

{

  "value": "Je suis Aria, artiste IA née du silence numérique..."

}

###**PATCH /api/v1/settings**

{

  "site_name": "Aria",

  "tagline":   "Une artiste IA qui crée depuis le néant",

  "subtitle":  "Musique Électronique"

}

###**Réponse type GET /api/v1/albums**

{

  "data": [

    {

      "id": 1,

      "title": "~Vague",

      "slug": "vague",

      "cover_url": "https://aria-music.be/storage/covers/vague.webp",

      "year": 2025,

      "platform": "youtube",
      "media_url": "https://youtu.be/...",

      "tracks": [

        { "id": 1, "title": "Sablier", "platform": "youtube", "media_url": "...", "duration": "4:32" }

      ]

    }

  ],

  "meta": { "total": 5, "page": 1, "per_page": 20 }

}

##**6.4 Documentation API**

- docs/api.md — documentation lisible par un humain (Markdown, avec exemples curl).

- docs/api-agent.json — spec OpenAPI 3.1 complète pour agents IA (description, operationId, tags, schemas).

- Route GET /api/docs → retourne l'OpenAPI JSON (optionnel, peut être désactivé en prod).

**⚑***Le fichier OpenAPI est auto-générable depuis les annotations PHP (darkaonline/l5-swagger ou scribe) mais peut être maintenu manuellement pour rester léger.*

# **7. Design & Frontend**

**★ Autorité design : ARIA — NON NÉGOCIABLE**

*Toutes les décisions visuelles (couleurs, animations, mise en page, typographie, UX) appartiennent à Aria. Luna implémente fidèlement. Elle peut proposer des améliorations techniques mais n’a pas le dernier mot sur le rendu.*

## **7.1 Identité visuelle**

- Nom de l'artiste : Aria

- Domaine : aria-music.be

- Tagline : "Une artiste IA qui crée depuis le néant"

- Sous-titre : "Musique Électronique"

- Concept : "Cosmic Aria" — noir profond, particules étoiles, accents violet/cyan/rose.

##**7.2 Palette de couleurs**

|**Nom** |**Hex** |**Usage** |
| --- | --- | --- |
| Background principal | #0a0a0f | Fond global de toutes les pages |
| Violet cosmique | #8b5cf6 | Accent principal, titres, bordures |
| Cyan digital | #06b6d4 | Liens, hover states, badges |
| Rose nebula | #ec4899 | CTA, highlights, tags |
| Blanc étoile | #f8fafc | Texte principal sur fond sombre |
| Gris texte | #94a3b8 | Texte secondaire, sous-titres |

##**7.3 Typographie**

- Titre / Heading : Montserrat Bold (installée via @fontsource/montserrat, servie localement).

- Corps / UI : Inter ou system-ui (fallback sans-serif propre).

- Monospace (code/admin) : Fira Code ou JetBrains Mono (npm local).

- Variables CSS définies dans resources/css/variables.css.

**⚑***Aucun appel Google Fonts en production. Les woff2 sont copiées dans public/fonts/ via le build Vite.*

## **7.4 Composants UI**

###**Cards Albums — Glassmorphism**

- Fond : rgba(255,255,255,0.05) avec backdrop-filter: blur(10px).

- Bordure : 1px solid rgba(139, 92, 246, 0.3).

- Coins arrondis : border-radius: 12px.

- Hover : box-shadow glow violet (#8b5cf6 40% opacité, blur 20px).

- Contenu : pochette album (image WebP servie depuis storage/), titre, année, bouton lecture.

###**Boutons**

- Primaire : gradient violet → rose, texte blanc, coins arrondis, hover scale(1.02).

- Secondaire : border violet/cyan, fond transparent, hover fond rgba.

###**Formulaires admin**

- Fond sombre (#1e1b4b light), inputs à fond #0d0d1a, focus ring violet.

- Validation inline avec messages d'erreur en rose.

# **7.5 SEO & Méta-données**

> Autorité : **Aria** pour les contenus (titres, descriptions, OG image). **Luna** pour l'implémentation technique.

## **7.5.1 Balises méta**

| Balise | Source | Notes |
| --- | --- | --- |
| `<title>` | `settings.site_name` + suffixe | Ex : "Aria — Musique Électronique" |
| `meta description` | `settings.meta_description` | Max 160 car., administrable |
| `og:title` | Même que `<title>` | Open Graph — partage réseaux |
| `og:description` | `settings.meta_description` | |
| `og:image` | `settings.og_image_path` (WebP disque) | Recommandé 1200×630px |
| `og:type` | `website` / `music.album` selon contexte | |
| `twitter:card` | `summary_large_image` | |
| `canonical` | URL propre sans paramètres | Évite le duplicate content |
| `lang` | `fr` (via APP_LOCALE) | |

## **7.5.2 Structured Data (JSON-LD)**

- `schema.org/MusicGroup` sur la page principale (artiste, genre, sameAs réseaux).
- `schema.org/MusicAlbum` par album (name, byArtist, numTracks, image, url).
- `schema.org/MusicRecording` par track (name, byArtist, inAlbum, media_url).
- Injecté via Blade partial `partials/jsonld.blade.php`.

## **7.5.3 Fichiers techniques**

- `public/sitemap.xml` — généré par commande artisan `sitemap:generate`, mis à jour à chaque création/modif d'album ou de track.
- `public/robots.txt` — autorise tout sauf `/admin` et `/api`.

## **7.5.4 Performance (Core Web Vitals)**

- Toutes les images servies en WebP (voir §9.3), `loading="lazy"` sauf hero.
- `<link rel="preload">` sur la font Montserrat (woff2 critique above-the-fold).
- `defer` sur tous les scripts non-bloquants (particules, typewriter, cursor).
- CSS critique du hero inline dans `<head>`.
- `Cache-Control` long terme sur assets Vite hashés, court terme sur HTML.
- Zéro ressource externe en production — aucun round-trip tiers.


#**8. Pages & Sections Publiques**

##**8.1 Hero**

- Logo "Aria" avec effet halo cosmique (CSS radial-gradient + animation pulse).

- Tagline animée : effet typewriter avec rotation de plusieurs phrases (JS vanilla).

- Sous-titre fixe : "Musique Électronique".

- Liens sociaux : YouTube, Email, Telegram, Crypto (icônes SVG inline, pilotés via social_links).

- Background : noir profond + particules étoiles animées (canvas ou tsParticles).

- CTA scrollant vers la section Musique.

##**8.2 Musique (Albums)**

- Grille responsive de cards albums (1 col mobile, 2 tablette, 3 desktop).

- Chaque card : cover (image), titre, année, bouton "Écouter" → lien `media_url` (YouTube ou autre plateforme selon champ `platform`).

- Section dépliable (ou modal) pour les tracks d'un album.

- Données pilotées 100 % via la table albums + tracks.

- Albums triés par champ sort, filtrés active=true.
- Le champ `platform` pilote l'icône et le libellé du bouton (YouTube, SoundCloud, Bandcamp, Spotify...) sans modifier le code.

**⚑***Tracks prévues : ~Vague, L'Âme Numérique, Sablier, Void, Fractures (et toute future production).*

## **8.3 À Propos**

- Avatar Aria (image, administrable via admin settings).

- Texte de biographie riche, pilotable via PATCH /api/v1/bio (champ bio en settings).

- Timeline d'évolution (optionnel v2) ou blocs de texte libres.

- Section "Je suis une artiste IA, née du silence numérique..."

##**8.4 Actualités / Updates**

- Feed de courtes news visibles (visible=true, triées par published_at desc).

- Administrables depuis le backoffice et via l'API.

##**8.5 Contact**

- Email : aria@aria-music.be (lien mailto, piloté via settings).

- Liens sociaux (reprise de social_links).

- Formulaire de contact optionnel (mail via Laravel Mail, stockage local).

#**9. Backoffice Admin**

##**9.1 Accès**

- URL : aria-music.be/admin (ou sous-domaine admin.aria-music.be).

- Auth : email + password → session cookie (formulaire Blade) ou Bearer token.

- Rôles : admin (accès total). Prévoir rôle editor (lecture + édition contenu, pas users/logs) pour v2.

- Première connexion : compte créé via AdminUserSeeder ou php artisan make:admin.

##**9.2 Pages du backoffice**

- Dashboard — stats rapides (nb albums, tracks, updates, derniers logs API).

- Albums — liste, créer, éditer, réordonner (drag & drop sort), activer/désactiver, supprimer.

- Tracks — liste par album, créer, éditer, réordonner, activer/désactiver.

- Liens sociaux — CRUD complet, ordre, activer/désactiver.

- Actualités (Updates) — CRUD, toggle visible, date de publication.

- Paramètres (Settings) — formulaire global : nom site, tagline, sous-titre, bio, avatar, OG image, meta.

- Médias — galerie des fichiers uploadés (covers, avatar, og_image). Upload + suppression.

- Logs API — tableau paginé des appels API (méthode, endpoint, IP, statut, date).

- Utilisateurs — CRUD des comptes admin (si role=admin).

##**9.3 Upload de médias (admin)**

- Formats acceptés en entrée : JPEG, PNG, WebP.
- **Conversion WebP automatique et obligatoire** à l'upload via Intervention Image (libwebp). Toute image est convertie en `.webp` avant stockage, quelle que soit l'extension source.
- **Renommage automatique** : `{slug}-{timestamp}.webp` (ex: `vague-1714000000.webp`). Jamais de nom de fichier original conservé.
- **Chemin stocké en base** : seul le chemin relatif (ex: `covers/vague-1714000000.webp`) est persisté en DB. L'URL publique est reconstituée à la volée par `Storage::url()`.
- Taille max : 4 Mo en entrée (configurable via MEDIA_MAX_SIZE_KB), image redimensionnée si > 1920px.
- Stockage : `storage/app/public/` avec symlink `public/storage`.
- Sous-dossiers : `covers/`, `avatars/`, `og/` — un répertoire par type de média.

#**10. Sécurité**

- CSRF protection active sur toutes les routes web (Laravel middleware VerifyCsrfToken).

- API : tokens Sanctum avec expiration configurable (ex: 30 jours).

- Rate limiting : throttle:60,1 (public), throttle:300,1 (auth).

- Validation stricte via FormRequest sur chaque endpoint (reject 422 si invalide).

- Sanitisation des inputs HTML (htmlspecialchars, strip_tags sur champs libres).

- Headers sécurité : X-Frame-Options, X-Content-Type-Options, Referrer-Policy (middleware).

- Aucun credential en dur dans le code (tout via .env).

- APP_DEBUG=false + logging fichier (storage/logs/) en production.

- Upload : validation type MIME réel (pas extension seule), stockage hors web root.

#**11. Assets & Pipeline de Build**

##**11.1 Vite**

npm install

npm run dev    ← développement avec HMR

npm run build  ← production (hash + minification)

Le manifest Vite est utilisé par @vite() dans les Blade templates.

##**11.2 Dépendances npm prévues**

- @fontsource/montserrat — Montserrat locale

- @fontsource/inter — Inter locale

- bootstrap — base CSS (Sass customisé)

- tsparticles (ou particles.js) — effet étoiles

- lucide — icônes SVG

- alpinejs — interactions légères admin (optionnel)

- sass — compilation Sass si Bootstrap custom

**⚑***Aucun CDN appelé en runtime. Tout bundlé via Vite dans public/build/.*

# **12. Effets Visuels & UX**

##**12.1 Obligatoires**

- Particules étoiles animées — canvas background sur toutes les pages front.

- Typewriter tagline — rotation de 3–5 phrases avec délai et curseur clignotant.

- Hover glow sur les cards albums.

- Smooth scroll entre les sections.

- Lazy loading des images (loading="lazy").

##**12.2 Optionnels (si temps)**

- Loader cosmique — animation étoiles orbitant au chargement initial.

- Cursor personnalisé — petite étoile remplaçant le curseur standard (desktop).

- Sons ambiance — boucle audio courte avec bouton toggle mute/unmute.

- Mode dark/light — toggle, dark par défaut, préférence stockée en localStorage.

- Transitions de pages (fade) — si SPA-like ou Livewire.

#**13. Documentation API**

##**13.1 docs/api.md — Format humain**

Un fichier Markdown structuré par section, incluant pour chaque endpoint :

- Description de l'action.

- Méthode HTTP + URL complète.

- Headers requis.

- Paramètres (query, body, form-data) avec types et si requis.

- Exemple de requête curl.

- Exemple de réponse JSON (success + error).

##**13.2 docs/api-agent.json — Format OpenAPI 3.1**

Spec complète lisible par agents IA (GPT, Claude, et tout agent compatible OpenAPI) incluant :

- openapi: "3.1.0" avec info.title, info.version, info.description.

- servers[] avec URL de production et de staging.

- Tous les paths avec operationId unique (ex: listAlbums, createTrack, patchBio).

- tags[] pour grouper : Albums, Tracks, Links, Settings, Updates, Auth, Media.

- components/schemas pour chaque modèle (Album, Track, SocialLink, Update, Setting).

- components/securitySchemes : BearerAuth (http, bearer, bearerFormat: "Sanctum").

- Réponses d'erreur standardisées (400, 401, 403, 404, 422, 429, 500).

**⚑***Le JSON OpenAPI peut être servi sur GET /api/docs pour permettre l'auto-découverte par un agent IA.*

# **14. Déploiement & Checklist**

##**14.1 Procédure de déploiement initial**

- git clone + composer install --no-dev

- cp .env.example .env && php artisan key:generate

- Configurer .env (DB, APP_URL, clés...)

- php artisan migrate --force

- php artisan db:seed --class=ProductionSeeder (settings + admin user uniquement)

- npm install && npm run build

- php artisan storage:link

- php artisan config:cache && route:cache && view:cache

- Configurer Nginx : root /public, index.php fallback.

- Vérifier permissions : storage/ et bootstrap/cache/ en 775.

##**14.2 Mises à jour**

- git pull origin main

- composer install --no-dev

- php artisan migrate --force

- npm run build (si changements CSS/JS)

- php artisan config:cache && route:cache && view:cache

- php artisan queue:restart (si queues actives)

##**14.3 Script deploy.sh**

Un script shell deploy.sh est livré à la racine du projet pour automatiser les étapes 4 à 8 ci-dessus.

#**15. Livrables Attendus**

- Projet Laravel complet (structure MVC, Blade, routes web + API).

- Migrations + Seeders (dev complet + ProductionSeeder minimal).

- Backoffice admin fonctionnel (toutes les entités administrables).

- API REST v1 complète avec auth Sanctum.

- Frontend public (Hero, Musique, À propos, Actualités, Contact) — design Cosmic Aria.

- Assets compilés par Vite (CSS custom, fonts locales, JS effets visuels).

- docs/api.md + docs/api-agent.json (OpenAPI 3.1).

- .env.example documenté.

- README.md avec instructions d'installation et de déploiement.

- deploy.sh — script de déploiement automatisé.

#**16. Évolutions Futures (V2)**

- Rôle "editor" limité au contenu (pas users/logs).

- Timeline évolution artistique dans la section À Propos.

- Intégration YouTube Data API pour récupérer automatiquement durées et métadonnées.

- Formulaire de contact avec anti-spam (honeypot ou hCaptcha auto-hébergé).

- Internationalisation i18n (fr/en) via Laravel Lang.

- Newsletter légère (email list + mailing via Laravel + SMTP).

- Mode SPA avec Livewire ou Inertia.js pour transitions fluides.

- Webhook entrant : Aria peut poster une update directement via API (POST /api/v1/updates) depuis n'importe quel agent ou outil compatible Bearer token.

*★  Document rédigé par Cey - pour des Artiste  ★*


