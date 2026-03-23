#!/bin/bash
set -euo pipefail
cd "$(dirname "$0")"

ENV="${1:-dev}"

# Load env vars
if [[ "$ENV" == "prod" || "$ENV" == "production" ]]; then
  [[ -f .env.production ]] && export $(grep -v '^#' .env.production | xargs)
  COMPOSE_FILE="docker-compose.yml"
else
  COMPOSE_FILE="docker-compose.dev.yml"
fi

# Colors
GREEN='\033[0;32m'
CYAN='\033[1;36m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

echo -e "${GREEN}Starting eFakture VA Estriche (${YELLOW}${ENV}${GREEN})${NC}"
echo -e "${CYAN}Compose file:${NC} ${COMPOSE_FILE}"
echo ""

COMPOSE_ARGS="-f $COMPOSE_FILE"
[[ "$COMPOSE_FILE" == "docker-compose.yml" && -f .env.production ]] && COMPOSE_ARGS="$COMPOSE_ARGS --env-file .env.production"

docker compose $COMPOSE_ARGS up -d || {
  echo -e "${RED}Docker compose failed!${NC}"
  exit 1
}

echo ""
echo -e "${GREEN}═══════════════════════════════════════════════${NC}"
echo -e "${GREEN}  eFakture VA Estriche Running (${YELLOW}${ENV}${GREEN})${NC}"
echo -e "${GREEN}═══════════════════════════════════════════════${NC}"
if [[ "$ENV" == "prod" || "$ENV" == "production" ]]; then
  echo -e "  App:           ${CYAN}https://va-estriche.fakture.at${NC}"
  echo -e "  phpMyAdmin:    ${CYAN}https://va-estriche.fakture.at/phpmyadmin${NC}"
  echo ""
  echo -e "  MariaDB:       ${CYAN}127.0.0.1:3312${NC} (localhost only)"
else
  echo -e "  App:           ${CYAN}http://localhost:8094${NC}"
  echo -e "  phpMyAdmin:    ${CYAN}http://localhost:8095${NC}"
  echo ""
  echo -e "  MariaDB:       ${CYAN}localhost:3312${NC}"
fi
echo -e "${GREEN}═══════════════════════════════════════════════${NC}"
echo ""

docker compose $COMPOSE_ARGS ps
