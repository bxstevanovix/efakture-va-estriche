#!/bin/bash
set -euo pipefail
cd "$(dirname "$0")"

# Colors
GREEN='\033[0;32m'
CYAN='\033[1;36m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

ENV_FILE=".env.production"
COMPOSE_FILE="docker-compose.yml"

if [[ ! -f "$ENV_FILE" ]]; then
  echo -e "${RED}Missing $ENV_FILE${NC}"
  exit 1
fi

echo -e "${GREEN}Reloading environment for eFakture VA Estriche...${NC}"

# Load env vars for compose interpolation
set -a
source "$ENV_FILE"
set +a

# Regenerate .htpasswd in case PMA credentials changed
echo -e "${CYAN}Regenerating .htpasswd...${NC}"
if command -v htpasswd &>/dev/null; then
  htpasswd -bc .htpasswd "${PMA_USER:-admin}" "${PMA_PASSWORD:-admin}" 2>/dev/null
else
  HASHED=$(openssl passwd -apr1 "${PMA_PASSWORD:-admin}")
  echo "${PMA_USER:-admin}:${HASHED}" > .htpasswd
fi

# Recreate containers with new env
echo -e "${CYAN}Recreating containers with updated environment...${NC}"
docker compose -f "$COMPOSE_FILE" --env-file "$ENV_FILE" up -d

echo -e "${CYAN}Clearing config cache...${NC}"
docker exec efk-va-estriche-app php artisan config:cache

echo ""
echo -e "${GREEN}Environment updated successfully.${NC}"
docker compose -f "$COMPOSE_FILE" --env-file "$ENV_FILE" ps
