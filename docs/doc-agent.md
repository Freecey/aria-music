# Skill: aria-music-api

## Identity
You are interfacing with the **Aria Music API** — a REST API managing the website of Aria, an AI electronic music artist.
Base URL (prod): `https://aria-music.be/api/v1`
All responses are JSON `{ "data": ... }`.

## Authentication
**Flow**: POST `/auth/login` → receive `token` → send as `Authorization: Bearer <token>` on protected routes.
Token has no expiry. Revoke with POST `/auth/logout`.

## Capabilities

### Read (no auth required)
| What | Endpoint |
|------|----------|
| Site settings + bio + avatar_url | GET /site |
| Albums list (with tracks) | GET /albums |
| Tracks list | GET /tracks |
| Social links | GET /links |
| News updates | GET /updates |

Query params: `?all=1` returns hidden/inactive items too — **requires Bearer token**. `?per_page=N` paginates albums/updates (max 100). `?album_id=N` filters tracks.

> GET routes are public for active/visible content. `?all=1` requires a valid Bearer token.

### Write (Bearer token required)

**Albums** — `POST /albums` (multipart), `PUT /albums/{id}` (multipart), `DELETE /albums/{id}`
Required on create: `title` (string), `year` (int 2000-2100), `platform` (youtube|soundcloud|bandcamp|spotify)
Optional: `media_url`, `description`, `cover` (image file → auto-converted to WebP), `sort` (int), `active` (bool)

**Tracks** — `POST /tracks` (JSON), `PUT /tracks/{id}` (JSON), `DELETE /tracks/{id}`
Required on create: `album_id`, `title`, `platform`
Optional: `media_url`, `duration` (e.g. "3:42"), `sort`, `active`

**Social Links** — `POST /links` (JSON), `PUT /links/{id}` (JSON), `DELETE /links/{id}`
Required on create: `platform`, `label`, `url` (any scheme: https, mailto, tg, etc.)
Optional: `icon_svg` (SVG string, max 5000 chars — script tags stripped), `sort`, `active`

**News Updates** — `POST /updates` (JSON), `PUT /updates/{id}` (JSON), `DELETE /updates/{id}`
Required on create: `body` (max 2000 chars)
Optional: `visible` (bool, default true), `published_at` (ISO8601)

**Site Settings** — `PATCH /settings` (JSON) — partial update any combination of:
`site_name`, `tagline`, `subtitle`, `bio`, `avatar_path`, `meta_description`, `meta_keywords`, `og_image_path`

**Bio only shortcut** — `PATCH /bio` (JSON) — body: `{ "value": "..." }`

**Media** — `POST /media/upload` (multipart): fields `file` (image, max 4MB) + `type` (covers|avatars|og)
→ returns `{ "data": { "path": "covers/slug-ts.webp", "url": "https://..." } }`
`DELETE /media/{filename}` — filename = relative path (e.g. `covers/my-album-123.webp`)

## Typical task flows

### Publish a new album
1. POST `/media/upload` with cover image + `type=covers` → get `path`
2. POST `/albums` with `title`, `year`, `platform`, `media_url`, `cover` (or use path from step 1)

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
