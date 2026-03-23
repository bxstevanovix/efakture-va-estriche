#!/bin/bash
set -e

DEFAULT="efk-va-estriche-app"
service="${1:-$DEFAULT}"

if ! docker ps --format '{{.Names}}' | grep -q "$service"; then
  echo "Service '$service' not running."
  echo ""
  echo "Available running containers:"
  docker ps --format ' - {{.Names}}'
  exit 1
fi

echo "Attaching to $service..."

# Try bash first, fallback to sh for Alpine containers
if docker exec "$service" which bash &>/dev/null; then
  docker exec -it "$service" bash
else
  docker exec -it "$service" sh
fi
