#!/bin/bash
set -euo pipefail
cd "$(dirname "$0")"

# Colors
GREEN='\033[0;32m'
CYAN='\033[1;36m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

COMPOSE_FILE="docker-compose.yml"
ENV_FILE=".env.production"
APP_CONTAINER="efk-va-estriche-app"

# --- Preflight checks ---

if [[ ! -f "$ENV_FILE" ]]; then
  echo -e "${RED}Missing $ENV_FILE — copy the template and fill in real values.${NC}"
  exit 1
fi

# Check for placeholder passwords
if grep -q 'CHANGE_ME' "$ENV_FILE"; then
  echo -e "${RED}$ENV_FILE still contains CHANGE_ME placeholder passwords.${NC}"
  echo -e "${YELLOW}Update DB_PASSWORD, DB_ROOT_PASSWORD, PMA_USER, and PMA_PASSWORD before deploying.${NC}"
  exit 1
fi

# Check APP_KEY is set
source <(grep APP_KEY "$ENV_FILE")
if [[ -z "${APP_KEY:-}" ]]; then
  echo -e "${YELLOW}APP_KEY is empty. Will generate after containers start.${NC}"
  NEEDS_KEY=true
else
  NEEDS_KEY=false
fi

# --- Load env vars for compose interpolation ---
set -a
source "$ENV_FILE"
set +a

# --- Generate .htpasswd for phpMyAdmin basic auth ---
echo -e "${CYAN}Generating .htpasswd for phpMyAdmin...${NC}"
if command -v htpasswd &>/dev/null; then
  htpasswd -bc .htpasswd "${PMA_USER:-admin}" "${PMA_PASSWORD:-admin}" 2>/dev/null
else
  # Fallback: use openssl to generate Apache-compatible password
  HASHED=$(openssl passwd -apr1 "${PMA_PASSWORD:-admin}")
  echo "${PMA_USER:-admin}:${HASHED}" > .htpasswd
fi

echo -e "${GREEN}═══════════════════════════════════════════════${NC}"
echo -e "${GREEN}  eFakture VA Estriche — Production Deploy${NC}"
echo -e "${GREEN}═══════════════════════════════════════════════${NC}"
echo ""

# --- Build & Start ---
echo -e "${CYAN}Validating compose file...${NC}"
docker compose -f "$COMPOSE_FILE" --env-file "$ENV_FILE" config >/dev/null

echo -e "${CYAN}Building images...${NC}"
docker compose -f "$COMPOSE_FILE" --env-file "$ENV_FILE" build --no-cache

echo -e "${CYAN}Starting containers...${NC}"
docker compose -f "$COMPOSE_FILE" --env-file "$ENV_FILE" up -d --remove-orphans

# Wait for app to be ready
echo -e "${CYAN}Waiting for app to be ready...${NC}"
for i in $(seq 1 30); do
  if docker exec "$APP_CONTAINER" php artisan --version &>/dev/null; then
    break
  fi
  sleep 2
done

# --- Laravel initialization ---
if [[ "$NEEDS_KEY" == true ]]; then
  echo -e "${CYAN}Generating APP_KEY...${NC}"
  NEW_KEY=$(docker exec "$APP_CONTAINER" php artisan key:generate --show)
  sed -i "s|^APP_KEY=.*|APP_KEY=${NEW_KEY}|" "$ENV_FILE"
  echo -e "${GREEN}APP_KEY generated and saved to $ENV_FILE${NC}"

  # Restart app so it picks up the new key from env_file
  docker compose -f "$COMPOSE_FILE" --env-file "$ENV_FILE" restart app
  sleep 3
fi

echo -e "${CYAN}Running migrations...${NC}"
docker exec "$APP_CONTAINER" php artisan migrate --force

echo -e "${CYAN}Caching config, routes, views...${NC}"
docker exec "$APP_CONTAINER" php artisan config:cache
docker exec "$APP_CONTAINER" php artisan route:cache
docker exec "$APP_CONTAINER" php artisan view:cache

echo -e "${CYAN}Setting storage permissions...${NC}"
docker exec "$APP_CONTAINER" chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# --- Cleanup ---
docker image prune -f >/dev/null 2>&1 || true

# --- Status ---
echo ""
echo -e "${GREEN}═══════════════════════════════════════════════${NC}"
echo -e "${GREEN}  Production Deploy Complete${NC}"
echo -e "${GREEN}═══════════════════════════════════════════════${NC}"
echo -e "  App:           ${CYAN}https://va-estriche.fakture.at${NC}"
echo -e "  phpMyAdmin:    ${CYAN}https://va-estriche.fakture.at/phpmyadmin${NC}"
echo ""
echo -e "  MariaDB:       ${CYAN}127.0.0.1:3312${NC} (localhost only)"
echo -e "${GREEN}═══════════════════════════════════════════════${NC}"
echo ""

docker compose -f "$COMPOSE_FILE" --env-file "$ENV_FILE" ps
