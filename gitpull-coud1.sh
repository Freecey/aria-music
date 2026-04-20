#!/usr/bin/env bash
# 🎵 Deployment script — coud1

set -euo pipefail

# ── Colors ───────────────────────────────────────────────────────────────────
RESET="\033[0m"
BOLD="\033[1m"
DIM="\033[2m"
GREEN="\033[0;32m"
CYAN="\033[0;36m"
YELLOW="\033[0;33m"
RED="\033[0;31m"
BLUE="\033[0;34m"
MAGENTA="\033[0;35m"

# ── Helpers ───────────────────────────────────────────────────────────────────
step()  { echo -e "\n${BOLD}${CYAN}  $*${RESET}"; }
ok()    { echo -e "  ${GREEN}✅ $*${RESET}"; }
warn()  { echo -e "  ${YELLOW}⚠️  $*${RESET}"; }
fail()  { echo -e "  ${RED}❌ $*${RESET}"; exit 1; }
dim()   { echo -e "  ${DIM}$*${RESET}"; }

hr() { echo -e "${DIM}$(printf '─%.0s' {1..62})${RESET}"; }

# ── Header ────────────────────────────────────────────────────────────────────
clear
echo -e "${BOLD}${BLUE}"
echo "  ╔════════════════════════════════════════════════╗"
echo "  ║  🎵  Aria Music  ·  Deploy → coud1  🚀        ║"
echo "  ╚════════════════════════════════════════════════╝"
echo -e "${RESET}"

DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
echo -e "  ${MAGENTA}📁 ${DIM}${DIR}${RESET}"
echo -e "  ${MAGENTA}🕐 ${DIM}$(date '+%Y-%m-%d %H:%M:%S')${RESET}"
hr

# ── Detect owner from current directory ──────────────────────────────────────
OWNER="$(stat -c '%U:%G' .)"
dim "🔍 Owner auto-détecté depuis . → ${BOLD}${OWNER}${RESET}"

# ── Steps ─────────────────────────────────────────────────────────────────────
step "🔄 Git pull"
git pull && ok "Repository à jour" || fail "git pull échoué"

step "🎼 Composer install"
php8.4 /usr/local/bin/composer install --no-interaction --prefer-dist --optimize-autoloader \
  && ok "Dépendances PHP installées" || fail "composer install échoué"

step "🗄️  Database migrations"
php8.4 artisan migrate --force \
  && ok "Migrations appliquées" || fail "artisan migrate échoué"

step "📦 NPM install"
npm install \
  && ok "Modules Node installés" || fail "npm install échoué"

step "⚡ NPM build"
npm run build \
  && ok "Assets compilés" || fail "npm run build échoué"

step "🧹 Clear caches"
php8.4 artisan cache:clear
php8.4 artisan config:clear
php8.4 artisan route:clear
php8.4 artisan view:clear
php8.4 artisan event:clear
ok "Caches vidés"

step "🔗 Storage link"
php8.4 artisan storage:link 2>/dev/null && ok "Storage lié" || warn "Lien déjà existant (ignoré)"

step "🗺️  Sitemap"
php8.4 artisan sitemap:generate 2>/dev/null \
  && ok "Sitemap généré" \
  || warn "sitemap:generate indisponible — ignoré"

step "🚀 Artisan optimise"
php8.4 artisan optimize \
  && ok "Cache & config optimisés" || fail "artisan optimize échoué"

step "🔐 Fix permissions  (owner: ${OWNER})"
dim "Référence depuis . et .. :"
ls -la --color=never | awk 'NR<=3' | while IFS= read -r line; do
  dim "    ${line}"
done
chown "${OWNER}" -R . \
  && ok "Permissions appliquées → chown ${OWNER} -R ." || fail "chown échoué"

# ── Done ──────────────────────────────────────────────────────────────────────
hr
echo -e "\n${BOLD}${GREEN}  🎉 Deploy terminé avec succès — $(date '+%H:%M:%S') ${RESET}\n"
