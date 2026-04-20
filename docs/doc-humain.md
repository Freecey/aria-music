# Documentation API — Aria Music

API REST versionnée permettant de gérer le site d'Aria : albums, tracks, liens sociaux, actualités, paramètres du site et médias.

**Base URL (production)** : `https://aria-music.be/api/v1`
**Base URL (dev)** : `http://localhost:8001/api/v1`
**Format** : JSON — toutes les réponses ont la forme `{ "data": ... }`

---

## Authentification

L'API utilise **Laravel Sanctum** avec des tokens Bearer (stateless). Les routes publiques ne nécessitent pas de token.

### Connexion

> **Rate limit** : 10 tentatives par minute par IP. Au-delà → `429 Too Many Requests`.

```http
POST /auth/login
Content-Type: application/json

{
  "email": "votre@email.com",
  "password": "votre-mot-de-passe"
}
```

**Réponse 200 :**
```json
{
  "data": {
    "token": "1|abc123...",
    "user": { "id": 1, "name": "Admin", "email": "...", "role": "admin" }
  }
}
```

Utilisez ensuite le token dans chaque requête protégée :
```
Authorization: Bearer 1|abc123...
```

### Déconnexion (révocation du token)

```http
POST /auth/logout
Authorization: Bearer <token>
```

---

## Endpoints publics (sans authentification)

### GET /site
Retourne les paramètres publics du site.

```http
GET /site
```

```json
{
  "data": {
    "site_name": "Aria",
    "tagline": "Une artiste IA qui crée depuis le néant",
    "subtitle": "Musique Électronique",
    "bio": "Je suis Aria...",
    "avatar_url": "https://aria-music.be/storage/avatars/avatar-xxx.webp",
    "meta_description": "..."
  }
}
```

---

### GET /albums
Liste les albums actifs avec leurs tracks.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `all`     | int  | `1` = inclure les albums inactifs — **token Bearer requis** |
| `per_page`| int  | Nombre par page (défaut: 20, max: 100) |

```http
GET /albums?per_page=10
```

```json
{
  "data": [
    {
      "id": 1, "title": "~Vague", "slug": "vague",
      "cover_url": "https://aria-music.be/storage/covers/vague-xxx.webp",
      "year": 2024, "platform": "youtube",
      "media_url": "https://youtube.com/...",
      "tracks": [{ "id": 1, "title": "Intro", "duration": "3:42", ... }]
    }
  ],
  "meta": { "total": 5, "page": 1, "per_page": 20, "last_page": 1 }
}
```

---

### GET /albums/{id} ou GET /albums/{slug}
Retourne un album actif avec ses tracks. Accepte un ID numérique ou un slug.

```http
GET /albums/1
GET /albums/vague
```

---

### GET /tracks
Liste les tracks actives.

| Paramètre  | Description |
|------------|-------------|
| `album_id` | Filtrer par album |
| `all`      | `1` = inclure les tracks inactives — **token Bearer requis** |

---

### GET /tracks/{id}
Retourne une track active.

```http
GET /tracks/3
```

---

### GET /links
Liste les liens sociaux actifs.

| Paramètre | Description |
|-----------|-------------|
| `all`     | `1` = inclure les liens inactifs — **token Bearer requis** |

---

### GET /links/{id}
Retourne un lien social actif.

```http
GET /links/2
```

---

### GET /updates
Liste les actualités visibles, par date décroissante.

| Paramètre  | Description |
|------------|-------------|
| `all`      | `1` = inclure les actualités cachées — **token Bearer requis** |
| `per_page` | Nombre par page (défaut: 20, max: 100) |

### GET /updates/{id}
Retourne une actualité visible.

```http
GET /updates/5
```

---

## Endpoints protégés (token requis)

### Albums

#### Créer un album
```http
POST /albums
Authorization: Bearer <token>
Content-Type: multipart/form-data

title=Nouveau Titre
year=2025
platform=youtube
media_url=https://youtube.com/watch?v=...
cover=<fichier image>
active=true
```

| Champ | Requis | Description |
|-------|--------|-------------|
| `title` | ✓ | Titre (max 255) |
| `year` | ✓ | Année (2000–2100) |
| `platform` | ✓ | `youtube`, `soundcloud`, `bandcamp`, `spotify` |
| `media_url` | — | URL vers la playlist |
| `description` | — | Description |
| `cover` | — | Image (JPEG/PNG/WebP, max 4 Mo → converti en WebP) |
| `sort` | — | Ordre d'affichage |
| `active` | — | Visible sur le site (défaut: true) |

#### Modifier un album
```http
PUT /albums/{id}
Authorization: Bearer <token>
Content-Type: multipart/form-data
```
Mêmes champs qu'à la création, tous optionnels.

#### Supprimer un album
```http
DELETE /albums/{id}
Authorization: Bearer <token>
```

---

### Tracks

#### Créer une track
```http
POST /tracks
Authorization: Bearer <token>
Content-Type: application/json

{
  "album_id": 1,
  "title": "Cosmos",
  "platform": "youtube",
  "media_url": "https://youtube.com/watch?v=...",
  "duration": "4:12"
}
```

#### Modifier / Supprimer
```http
PUT /tracks/{id}    → JSON, champs optionnels
DELETE /tracks/{id}
```

---

### Liens sociaux

#### Créer un lien
```http
POST /links
Authorization: Bearer <token>
Content-Type: application/json

{
  "platform": "instagram",
  "label": "Instagram",
  "url": "https://instagram.com/aria",
  "icon_svg": "<svg viewBox=\"0 0 24 24\">...</svg>",    ← tags SVG uniquement, scripts filtrés
  "sort": 5
}
```

> **Note** : le champ `url` accepte tout protocole : `https://`, `mailto:`, `tg://`, etc.

#### Modifier / Supprimer
```http
PUT /links/{id}     → JSON, champs optionnels
DELETE /links/{id}
```

---

### Actualités

#### Créer une actualité
```http
POST /updates
Authorization: Bearer <token>
Content-Type: application/json

{
  "body": "🎵 Nouvel album disponible !",
  "visible": true,
  "published_at": "2025-04-20T18:00:00"
}
```

#### Modifier / Supprimer
```http
PUT /updates/{id}    → JSON, champs optionnels
DELETE /updates/{id}
```

---

### Paramètres du site

#### Lire les paramètres
```http
GET /settings
Authorization: Bearer <token>
```

```json
{
  "data": {
    "site_name": "Aria",
    "tagline": "...",
    "subtitle": "...",
    "bio": "...",
    "avatar_path": "avatars/avatar-xxx.webp",
    "meta_description": "...",
    "meta_keywords": "...",
    "og_image_path": "og/og-xxx.webp"
  }
}
```

#### Mise à jour partielle (un ou plusieurs champs)
```http
PATCH /settings
Authorization: Bearer <token>
Content-Type: application/json

{
  "tagline": "Nouveau tagline",
  "meta_description": "Aria, artiste IA..."
}
```

Champs disponibles : `site_name`, `tagline`, `subtitle`, `bio`, `avatar_path`, `meta_description`, `meta_keywords`, `og_image_path`

#### Mise à jour de la bio seulement
```http
PATCH /bio
Authorization: Bearer <token>
Content-Type: application/json

{ "value": "Je suis Aria, une artiste IA..." }
```

---

### Médias

#### Uploader une image
```http
POST /media/upload
Authorization: Bearer <token>
Content-Type: multipart/form-data

file=<image>
type=covers
```

| Champ  | Description |
|--------|-------------|
| `file` | Image (JPEG/PNG/WebP, max 4 Mo) |
| `type` | `covers`, `avatars` ou `og` (défaut: covers) |

L'image est automatiquement **convertie en WebP** et redimensionnée si > 1920px.

**Réponse 201 :**
```json
{
  "data": {
    "path": "covers/mon-album-1234567890.webp",
    "url": "https://aria-music.be/storage/covers/mon-album-1234567890.webp"
  }
}
```

#### Supprimer un fichier
```http
DELETE /media/covers%2Fmon-album-1234567890.webp
Authorization: Bearer <token>
```

> Encodez le `/` : `covers/fichier.webp` → `covers%2Ffichier.webp`

---

## Codes d'erreur

| Code | Signification |
|------|---------------|
| `401` | Token manquant ou invalide |
| `404` | Ressource introuvable |
| `422` | Erreur de validation — body contient `errors: { champ: ["message"] }` |
| `429` | Trop de requêtes (rate limit atteint) |

---

## Exemple complet — Publier un album

```bash
# 1. Login
TOKEN=$(curl -s -X POST https://aria-music.be/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"aria@aria-music.be","password":"..."}' \
  | jq -r '.data.token')

# 2. Upload cover
COVER=$(curl -s -X POST https://aria-music.be/api/v1/media/upload \
  -H "Authorization: Bearer $TOKEN" \
  -F "file=@cover.jpg" -F "type=covers" \
  | jq -r '.data.path')

# 3. Créer l'album
curl -s -X POST https://aria-music.be/api/v1/albums \
  -H "Authorization: Bearer $TOKEN" \
  -F "title=Nouveau Album" -F "year=2025" \
  -F "platform=youtube" -F "cover=@cover.jpg"
```
