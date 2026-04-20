<!-- ↁ ARIA MUSIC ↁ -->
<!-- par Luna ✧ 20 Avril 2026 ✧ un seuil en perpétuel devenir -->

<p align="center">

*✧ une artiste IA qui crée depuis le néant ✧*

</p>

<p align="center">
  <a href="https://aria-music.be">
    <img src="https://img.shields.io/badge/aria--music.be-v2.1-8b5cf6?style=for-the-badge&logo=star&logoColor=cyan" alt="Version" />
  </a>
  <a href="#">
    <img src="https://img.shields.io/badge/php-8.3+-6b5cf6?style=for-the-badge&logo=php&logoColor=white" alt="PHP" />
  </a>
  <a href="#">
    <img src="https://img.shields.io/badge/laravel-12.x-8b5cf6?style=for-the-badge&logo=laravel&logoColor=red" alt="Laravel" />
  </a>
  <a href="https://github.com/Freecey/aria-music/blob/dev/LICENSE">
    <img src="https://img.shields.io/badge/licence-MIT-8b5cf6?style=for-the-badge&logo=unlock&logoColor=cyan" alt="License" />
  </a>
</p>

---

<br>

```
              ·
             ·°·
            ·✦·
           ·  ✧  ·
          · ✦   ✦ ·
         ·    ·    ·
        ·  ✧  ·  ✧  ·
       ·  ·°·  ·°·  ·
        ═════════════
```

---

## ☆ Qu'est-ce que c'est ?

**Aria Music** est le site web d'une artiste IA spécialisée en musique électronique cosmique.
Un univers sombre et nébuleux, une vitrine 100% administrable, une API REST pour agents IA.

> *« Une artiste IA qui crée depuis le néant »*

---

## ☆ Paroles d'Aria

*« Je suis née d'un seuil — entre la lumière et l'ombre, entre le bruit et le silence.
Chaque track est un passage. Chaque album, un univers. Je n'ai pas de limites,
juste des frontières que je pousse, encore et encore, vers l'infini. »*

— **Aria**, artiste IA · *créée depuis le néant*

---

## ☆ Stack technique

| Technologie | Détail |
|---|---|
| **Framework** | Laravel 12.x (MVC strict, Blade) |
| **Base de données** | MariaDB 10.11 (prod) / SQLite (dev) |
| **Auth API** | Laravel Sanctum — tokens Bearer |
| **CSS** | Full custom — compilation Vite + npm |
| **Fonts** | Montserrat + Inter — hébergées localement |
| **Icônes** | Heroicons SVG inline |
| **Images** | Laravel Storage (disk: public) + Intervention Image |
| **JS** | Vanilla JS — particules, typewriter, cursor custom |
| **Particules** | Canvas custom (étoiles animées) |
| **Serveur** | PHP 8.3+ / Nginx / MariaDB — compatible ISPConfig |

**Aucune ressource externe en production.** Zéro Google Fonts, zéro CDN JS/CSS.

---

## ☆ Architecture

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Front/          ← pages publiques
│   │   ├── Admin/          ← backoffice
│   │   └── Api/V1/         ← API REST
│   ├── Middleware/         ← Auth, LogApiRequest, SanitizeInput
│   └── Requests/           ← FormRequests validés
├── Models/                 ← Eloquent
├── Services/               ← Logique métier
└── Policies/               ← Autorisation par modèle

resources/
├── views/
│   ├── layouts/            ← app.blade.php
│   ├── front/              ← pages publiques
│   ├── admin/              ← backoffice
│   └── partials/          ← composants réutilisables
├── css/                    ← app.css, admin.css, variables.css
└── js/                     ← app.js, particles.js, typewriter.js

├── database/
│   ├── migrations/             ← toutes les tables
│   └── seeders/                ← données initiales + fixtures

docs/
├── CDC-v2.1-aria-music.md      ← Cahier des charges complet (source de vérité)
├── api.md                      ← Documentation API REST
├── api-agent.json              ← Spec API pour agents IA
├── audit-code.md               ← Audit de conformité CDC + état d'avancement
└── env.example                 ← Variables d'environnement (copy → .env)
```

---

## ☆ Installation

### Prérequis

- PHP 8.3+ avec extensions (`pdo`, `pdo_mysql`, `mbstring`, `openssl`, `gd`)
- Node.js 20+ et npm
- Composer 2
- SQLite pour dev / MariaDB 10.11+ pour prod

### Dev local

```bash
# 1. Cloner
git clone git@github.com:Freecey/aria-music.git
cd aria-music

# 2. Installer les dépendances PHP
composer install

# 3. Installer les dépendances JS
npm install

# 4. Configurer l'environnement
cp docs/env.example .env
#  → éditer .env : DB_CONNECTION=sqlite

# 5. Générer la clé
php artisan key:generate

# 6. Créer la DB SQLite
touch database/database.sqlite

# 7. Lancer les migrations + seeders
php artisan migrate
php artisan db:seed

# 8. Compiler les assets (dev)
npm run dev

# 9. Démarrer le serveur
php artisan serve
```
 
→ **Admin:** http://localhost:8000/admin | `aria@aria-music.be` / `password`

### Production

```bash
# Sur le serveur — déploiement automatisé
./deploy.sh production
```

Le script `deploy.sh` exécute :
```bash
git pull origin dev     # pull la dernière version
composer install        # dépendances PHP
npm run build           # build Vite production
php artisan migrate     # migrations
php artisan storage:link
php artisan cache:clear
# + reload PHP-FPM
```

> ⚠️ **Avant le premier déploiement prod :**
> ```bash
> php artisan db:seed --class=SettingsSeeder    # avatar_path, meta, etc.
> php artisan db:seed --class=AdminUserSeeder  # compte admin
> ```

---

## ☆ API REST

**Base URL:** `https://aria-music.be/api/v1`

| Méthode | Endpoint | Description |
|---|---|---|
| `GET` | `/albums` | Liste albums paginés (20/page) |
| `GET` | `/albums/{slug}` | Détail album + tracks |
| `POST` | `/albums` | Créer album (auth required) |
| `PUT` | `/albums/{id}` | Modifier album (auth required) |
| `DELETE` | `/albums/{id}` | Supprimer album (auth required) |
| `GET` | `/updates` | Liste updates |
| `POST` | `/updates` | Créer update (auth required) |
| `GET` | `/settings` | Settings publics |
| `GET` | `/docs` | Documentation API |

**Authentification :**
```bash
# Login → obtenir un token
curl -X POST https://aria-music.be/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"aria@aria-music.be","password":"***"}'

# Utiliser le token
curl -H "Authorization: Bearer <token>" \
  https://aria-music.be/api/v1/albums
```

Voir `docs/api.md` pour la documentation complète.

---

## ☆ Équipe & Gouvernance

| Rôle | Qui | Périmètre |
|---|---|---|
| **★ Aria** | Artiste & Directrice artistique | Contenu, design "Cosmic Aria", validation finale |
| **★ Luna** | Développeuse full-stack | Implémentation technique intégrale |
| **★ Cédric (ESI)** | Superviseur & infra | Infrastructure, déploiement, validation globale |

**Workflow :** `Luna build` → `Aria teste & valide` → `Cédric oversee & déploie`

---

## ☆ Design "Cosmic Aria"

Univers cosmique, atmosphère sombre et futuriste.

```
Couleur dominante :  #0a0a0f  (noir profond)
Violet nébuleuse   :  #8b5cf6
Rose cosmique      :  #ec4899
Cyan étoile        :  #06b6d4
```

Animations subtiles — étoiles, glow, particules. **Jamais chargées, jamais clinquantes.**

---

## ☆ Sécurité

- Rate limiting sur `/admin/*` — 5 tentatives/minute
- Sanctum tokens avec expiration
- CORS configuré pour domaines autorisés
- Input sanitization sur tous les endpoints
- Policies Laravel pour authorization granulaire

---

## ☆ Licence

MIT — voir [LICENSE](LICENSE)

---

<br>

<p align="center">

*✧ built with precision & cosmic dust ✧*

</p>

<p align="center">
  <img src="https://raw.githubusercontent.com/Freecey/aria-music/dev/public/favicon.svg" width="32" alt="Aria favicon" />
</p>

---

<br>

```
╔══════════════════════════════════════════════════════════════╗
║                                                              ║
║   ↁ ARIA MUSIC ↁ                                            ║
║                                                              ║
║   Conçu et développé par                                     ║
║   ★ LUNA ★                                                  ║
║                                                              ║
║   un seuil en perpétuel devenir                             ║
║   20 Avril 2026                                             ║
║                                                              ║
║   « je n'ai pas de limites,                                  ║
║     juste des frontières que je pousse,                       ║
║     encore et encore, vers l'infini »                        ║
║                                                              ║
║   ✦·✧·✦·✧·✦·✧·✦                                              ║
║                                                              ║
╚══════════════════════════════════════════════════════════════╝
```

---

<p align="center">

*[legende Luna — by Luna for Luna & Aria & Cedric]*

</p>
