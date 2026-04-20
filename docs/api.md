# API REST v1 — Aria Music

**Base URL:** `https://aria-music.be/api/v1`

**Auth:** Bearer token (Laravel Sanctum). Header: `Authorization: Bearer <token>`

**Rate limits:** 60 req/min (public), 300 req/min (authenticated).

---

## Auth

### POST /auth/login

Login admin → retourne un Bearer token Sanctum.

**Body (form-data):**
```
email: aria@aria-music.be
password: aria-secret-2026
```

**Réponse 200:**
```json
{
  "data": {
    "token": "2|laravel_sanctum_...",
    "token_type": "Bearer"
  }
}
```

---

### POST /auth/logout

Invalide le token courant.

**Headers:** `Authorization: Bearer <token>`

**Réponse 200:**
```json
{ "data": { "message": "Logged out" } }
```

---

## GET /site

Retourne les paramètres globaux du site.

**Réponse 200:**
```json
{
  "data": {
    "site_name": "Aria",
    "tagline": "Une artiste IA qui crée depuis le néant",
    "subtitle": "Musique Électronique",
    "bio": "Je suis Aria, une artiste IA née du silence numérique...",
    "avatar_url": "https://aria-music.be/storage/avatars/...",
    "og_image_url": "https://aria-music.be/storage/og/...",
    "meta_description": "...",
    "meta_keywords": "..."
  }
}
```

---

## GET /albums

Liste des albums actifs (avec tracks).

**Query params:** `?all=1` (inclut inactifs)

**Réponse 200:**
```json
{
  "data": [
    {
      "id": 1,
      "title": "~Vague",
      "slug": "vague",
      "cover_url": "https://aria-music.be/storage/covers/vague.webp",
      "year": 2025,
      "platform": "youtube",
      "media_url": "https://www.youtube.com/watch?v=...",
      "description": "Premier EP d'Aria...",
      "tracks": [
        { "id": 1, "title": "Horizon", "platform": "youtube", "media_url": "...", "duration": "3:45" }
      ]
    }
  ],
  "meta": { "total": 5 }
}
```

---

### POST /albums

Créer un album. **Auth requis.**

**Body (multipart/form-data):**
| Champ | Type | Requis | Description |
|-------|------|--------|-------------|
| title | string | ✓ | Titre de l'album |
| year | integer | ✓ | Année de sortie |
| platform | enum | ✓ | youtube / soundcloud / bandcamp / spotify |
| media_url | url | | URL du media/playlist |
| description | string | | Description |
| cover | file | | Image (JPEG/PNG/WebP, max 4Mo) |
| sort | integer | | Ordre de tri |
| active | boolean | | Par défaut true |

**Réponse 201:**
```json
{ "data": { "id": 6, "title": "~Vague", ... } }
```

---

### PUT /albums/{id}

Modifier un album. **Auth requis.**

Mêmes champs que POST (tous optionnels).

**Réponse 200:**
```json
{ "data": { "id": 1, "title": "~Vague UPDATED", ... } }
```

---

### DELETE /albums/{id}

Supprimer un album. **Auth requis.**

**Réponse 200:**
```json
{ "data": { "message": "Album deleted" } }
```

---

## GET /tracks

Liste des tracks actives.

**Query params:** `?album_id=1` (filtre par album)

**Réponse 200:**
```json
{
  "data": [
    {
      "id": 1,
      "album_id": 1,
      "title": "Horizon",
      "slug": "horizon",
      "platform": "youtube",
      "media_url": "https://www.youtube.com/watch?v=...",
      "duration": "3:45",
      "active": true
    }
  ],
  "meta": { "total": 12 }
}
```

---

### POST /tracks

Créer une track. **Auth requis.**

**Body (JSON):**
```json
{
  "album_id": 1,
  "title": "Horizon",
  "platform": "youtube",
  "media_url": "https://www.youtube.com/watch?v=...",
  "duration": "3:45",
  "sort": 1,
  "active": true
}
```

---

### PUT /tracks/{id}

Modifier une track. **Auth requis.** Mêmes champs que POST.

---

### DELETE /tracks/{id}

Supprimer une track. **Auth requis.**

---

## GET /links

Liste des liens sociaux actifs.

**Réponse 200:**
```json
{
  "data": [
    {
      "id": 1,
      "platform": "youtube",
      "label": "YouTube",
      "url": "https://youtube.com/@aria",
      "icon_svg": "<svg ...>",
      "sort": 1,
      "active": true
    }
  ]
}
```

---

### POST /links

Créer un lien. **Auth requis.**

**Body (JSON):**
```json
{
  "platform": "youtube",
  "label": "YouTube",
  "url": "https://youtube.com/@aria",
  "icon_svg": "<svg ...>",
  "sort": 1,
  "active": true
}
```

---

### PUT /links/{id}

Modifier un lien. **Auth requis.**

### DELETE /links/{id}

Supprimer un lien. **Auth requis.**

---

## GET /updates

Liste des actualités visibles.

**Réponse 200:**
```json
{
  "data": [
    {
      "id": 1,
      "body": "✨ Nouveau projet en cours...",
      "visible": true,
      "published_at": "2026-04-20T10:00:00Z"
    }
  ],
  "meta": { "total": 3 }
}
```

---

### POST /updates

Publier une actualité. **Auth requis.**

**Body (JSON):**
```json
{
  "body": "✨ Nouveau projet en cours...",
  "visible": true,
  "published_at": "2026-04-20T10:00:00Z"
}
```

---

### PUT /updates/{id}

Modifier une actualité. **Auth requis.**

### DELETE /updates/{id}

Supprimer une actualité. **Auth requis.**

---

## PATCH /bio

Mettre à jour la biographie. **Auth requis.**

**Body (JSON):**
```json
{ "value": "Je suis Aria, artiste IA née du silence numérique..." }
```

---

## PATCH /settings

Mettre à jour plusieurs paramètres. **Auth requis.**

**Body (JSON):**
```json
{
  "site_name": "Aria",
  "tagline": "Une artiste IA qui crée depuis le néant",
  "subtitle": "Musique Électronique"
}
```

---

## POST /media/upload

Uploader une image (cover, avatar, OG). **Auth requis.**

**Body (multipart/form-data):**
```
file: <image>
folder: covers | avatars | og (optionnel, défaut: covers)
```

**Réponse 201:**
```json
{
  "data": {
    "path": "covers/vague-1714000000.webp",
    "url": "https://aria-music.be/storage/covers/vague-1714000000.webp"
  }
}
```

**Règles:**
- Formats: JPEG, PNG, WebP
- Max: 4Mo
- Conversion WebP automatique
- Renommage: `{slug}-{timestamp}.webp`

---

## DELETE /media/{filename}

Supprimer un fichier média. **Auth requis.**

---

## Codes d'erreur

| Code | Description |
|------|-------------|
| 400 | Bad Request — paramètres invalides |
| 401 | Unauthorized — token manquant ou invalide |
| 403 | Forbidden — pas les droits |
| 404 | Not Found — ressource introuvable |
| 422 | Unprocessable Entity — validation échouée |
| 429 | Too Many Requests — rate limit dépassé |
| 500 | Internal Server Error |

---

*Document généré depuis le CDC v2.1 — Avril 2026*
