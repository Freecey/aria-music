# Skill: aria-music-api

## Identity
You are interfacing with the **Aria Music API** — a REST API managing the website of Aria, an AI electronic music artist.
Base URL (prod): `https://aria-music.be/api/v1`
All responses are JSON `{ "data": ... }`.

## Authentication
**Flow**: POST `/auth/login` → receive `token` → send as `Authorization: Bearer <token>` on protected routes.
Token has no expiry. Revoke with POST `/auth/logout`.

## Endpoints

### Public (no auth)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /site | Site settings + bio + avatar_url |
| GET | /albums | Albums list with tracks — `?per_page=N` (max 100), `?all=1`* |
| GET | /albums/{id_or_slug} | Single album with tracks — accepts numeric ID or slug |
| GET | /tracks | Tracks list — `?album_id=N`, `?all=1`* |
| GET | /tracks/{id} | Single track |
| GET | /links | Social links — `?all=1`* |
| GET | /links/{id} | Single link |
| GET | /updates | News updates — `?per_page=N` (max 100), `?all=1`* |
| GET | /updates/{id} | Single update |

> \* `?all=1` includes inactive/hidden items — **requires Bearer token**.

### Protected (Bearer token required)

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | /auth/logout | Revoke current token |
| GET | /settings | All site settings |
| PATCH | /settings | Partial update settings |
| PATCH | /bio | Update bio only — body: `{ "value": "..." }` |
| POST | /albums | Create album (multipart) |
| PUT | /albums/{id} | Update album (multipart) |
| DELETE | /albums/{id} | Delete album |
| POST | /tracks | Create track (JSON) |
| PUT | /tracks/{id} | Update track (JSON) |
| DELETE | /tracks/{id} | Delete track |
| POST | /links | Create social link (JSON) |
| PUT | /links/{id} | Update link (JSON) |
| DELETE | /links/{id} | Delete link |
| POST | /updates | Create news update (JSON) |
| PUT | /updates/{id} | Update news update (JSON) |
| DELETE | /updates/{id} | Delete news update |
| POST | /media/upload | Upload image (multipart) — `file` + `type` (covers\|avatars\|og) |
| DELETE | /media/{filename} | Delete media file — encode `/` as `%2F` |

## Field reference

**Albums** (POST/PUT multipart)
Required on create: `title` (string), `year` (int 2000–2100), `platform` (youtube|soundcloud|bandcamp|spotify)
Optional: `media_url`, `description`, `cover` (image → auto-converted to WebP), `sort` (int), `active` (bool)

**Tracks** (POST/PUT JSON)
Required on create: `album_id`, `title`, `platform`
Optional: `media_url`, `duration` (e.g. "3:42"), `sort`, `active`

**Social Links** (POST/PUT JSON)
Required on create: `platform`, `label`, `url` (any scheme: https, mailto, tg, etc.)
Optional: `icon_svg` (SVG string, max 5000 chars — script tags stripped), `sort`, `active`

**News Updates** (POST/PUT JSON)
Required on create: `body` (max 2000 chars)
Optional: `visible` (bool, default true), `published_at` (ISO8601)

**Settings** (PATCH JSON)
Fields: `site_name`, `tagline`, `subtitle`, `bio`, `avatar_path`, `meta_description`, `meta_keywords`, `og_image_path`

**Media upload** response: `{ "data": { "path": "covers/slug-ts.webp", "url": "https://..." } }`

## Typical task flows

### Publish a new album
1. POST `/media/upload` with cover image + `type=covers` → get `path`
2. POST `/albums` with `title`, `year`, `platform`, `media_url`, `cover`

### Update site bio
`PATCH /bio` body `{ "value": "New bio text..." }`

### Add a social link
`POST /links` body `{ "platform": "instagram", "label": "Instagram", "url": "https://instagram.com/aria", "icon_svg": "<svg...>" }`

### Hide a news update
`PUT /updates/{id}` body `{ "visible": false }`

### Reorder albums
`PUT /albums/{id}` body `{ "sort": 2 }` — repeat for each album

## Error responses
- `401` — missing or invalid Bearer token
- `404` — resource not found
- `422` — validation error, body: `{ "message": "...", "errors": { "field": ["reason"] } }`
- `429` — rate limit exceeded (login: 10 req/min per IP)
