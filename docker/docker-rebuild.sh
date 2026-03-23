#!/bin/bash
set -euo pipefail
cd "$(dirname "$0")"

ENV="dev"
NO_CACHE=""
PULL=""
SERVICES=()

# Parse args
for arg in "$@"; do
  case "$arg" in
    dev|development) ENV="dev" ;;
    prod|production) ENV="prod" ;;
    --no-cache) NO_CACHE="--no-cache" ;;
    --pull) PULL="--pull" ;;
    *) SERVICES+=("$arg") ;;
  esac
done

# Load env vars
if [[ "$ENV" == "prod" ]]; then
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

echo -e "${GREEN}Rebuilding eFakture VA Estriche (${YELLOW}${ENV}${GREEN})${NC}"
echo -e "${CYAN}Compose file:${NC} ${COMPOSE_FILE}"
[[ -n "$NO_CACHE" ]] && echo -e "${YELLOW}Flag:${NC} --no-cache"
[[ -n "$PULL" ]] && echo -e "${YELLOW}Flag:${NC} --pull"

if [[ ${#SERVICES[@]} -gt 0 ]]; then
  echo -e "${CYAN}Selected services:${NC} ${SERVICES[*]}"
else
  echo -e "${CYAN}Rebuilding all services${NC}"
fi

COMPOSE_ARGS="-f $COMPOSE_FILE"
[[ "$ENV" == "prod" && -f .env.production ]] && COMPOSE_ARGS="$COMPOSE_ARGS --env-file .env.production"

echo -e "${CYAN}Validating compose file...${NC}"
docker compose $COMPOSE_ARGS config >/dev/null

echo -e "${CYAN}Building images...${NC}"
if [[ ${#SERVICES[@]} -gt 0 ]]; then
  docker compose $COMPOSE_ARGS build $NO_CACHE $PULL "${SERVICES[@]}"
else
  docker compose $COMPOSE_ARGS build $NO_CACHE $PULL
fi

echo -e "${CYAN}Restarting containers...${NC}"
docker compose $COMPOSE_ARGS up -d --remove-orphans

# Optional prune of dangling images
docker image prune -f >/dev/null 2>&1 || true

echo ""
echo -e "${GREEN}═══════════════════════════════════════════════${NC}"
echo -e "${GREEN}  eFakture VA Estriche Running (${YELLOW}${ENV}${GREEN})${NC}"
echo -e "${GREEN}═══════════════════════════════════════════════${NC}"
if [[ "$ENV" == "prod" ]]; then
  echo -e "  App:           ${CYAN}https://va-estriche.fakture.at${NC}"
  echo -e "  phpMyAdmin:    ${CYAN}https://va-estriche.fakture.at/phpmyadmin${NC}"
  echo -e "  MariaDB:       ${CYAN}127.0.0.1:3312${NC}"
else
  echo -e "  App:           ${CYAN}http://localhost:8094${NC}"
  echo -e "  phpMyAdmin:    ${CYAN}http://localhost:8095${NC}"
  echo -e "  MariaDB:       ${CYAN}localhost:3312${NC}"
fi
echo -e "${GREEN}═══════════════════════════════════════════════${NC}"
echo ""

docker compose $COMPOSE_ARGS ps
