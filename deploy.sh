#!/usr/bin/env bash
set -Eeuo pipefail

# LeadStar one-command deploy script
# Usage:
#   ./deploy.sh
#   ./deploy.sh main

BRANCH="${1:-main}"
APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
BACKUP_DIR="${APP_DIR}/.deploy_backups"
KEEP_BACKUPS=5

log() {
  printf "[deploy] %s\n" "$*"
}

fail() {
  printf "[deploy][error] %s\n" "$*" >&2
  exit 1
}

cd "$APP_DIR"

command -v git >/dev/null 2>&1 || fail "git is not installed."
[ -d ".git" ] || fail "Not a git repository: ${APP_DIR}"

log "App dir: ${APP_DIR}"
log "Branch: ${BRANCH}"

if [[ -n "$(git status --porcelain)" ]]; then
  fail "Working tree has local changes. Commit/stash first."
fi

mkdir -p "$BACKUP_DIR"

TS="$(date +%F_%H%M%S)"
BACKUP_FILE="${BACKUP_DIR}/leadstar_${TS}.tgz"

log "Creating lightweight backup..."
tar -czf "$BACKUP_FILE" \
  --exclude='.git' \
  --exclude='.deploy_backups' \
  --exclude='node_modules' \
  . >/dev/null 2>&1 || fail "Backup failed."

log "Fetching latest from origin/${BRANCH}..."
git fetch origin "$BRANCH"

LOCAL_SHA="$(git rev-parse HEAD)"
REMOTE_SHA="$(git rev-parse "origin/${BRANCH}")"

if [[ "$LOCAL_SHA" == "$REMOTE_SHA" ]]; then
  log "Already up to date."
else
  log "Pulling updates (ff-only)..."
  git pull --ff-only origin "$BRANCH"
fi

log "Setting safe file permissions..."
find "$APP_DIR" -type d -not -path "*/.git/*" -exec chmod 755 {} \;
find "$APP_DIR" -type f -not -path "*/.git/*" -exec chmod 644 {} \;
chmod 755 "$APP_DIR/deploy.sh"

# Writable runtime directories if present
for d in data storage cache logs tmp; do
  if [[ -d "$APP_DIR/$d" ]]; then
    chmod -R 775 "$APP_DIR/$d"
  fi
done

log "Cleaning old backups (keep last ${KEEP_BACKUPS})..."
ls -1t "$BACKUP_DIR"/leadstar_*.tgz 2>/dev/null | tail -n +$((KEEP_BACKUPS + 1)) | xargs -r rm -f

if command -v nginx >/dev/null 2>&1; then
  log "Testing nginx config..."
  if nginx -t >/dev/null 2>&1; then
    if command -v systemctl >/dev/null 2>&1; then
      log "Reloading nginx..."
      systemctl reload nginx || log "nginx reload skipped (no permission/service)."
    else
      log "systemctl not found, nginx reload skipped."
    fi
  else
    log "nginx -t failed; skipped reload."
  fi
fi

log "Deploy completed."
log "Backup: ${BACKUP_FILE}"
log "Current commit: $(git rev-parse --short HEAD)"
