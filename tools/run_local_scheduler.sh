#!/usr/bin/env bash
set -euo pipefail

# Local helper to run Laravel scheduler as a background process on macOS/Linux
# Usage: bash tools/run_local_scheduler.sh

PROJECT_DIR="/Applications/XAMPP/ufo"
PHP_BIN="/Applications/XAMPP/xamppfiles/bin/php"
LOG_FILE="$PROJECT_DIR/storage/logs/scheduler.log"
PID_FILE="$PROJECT_DIR/storage/scheduler.pid"

mkdir -p "$(dirname "$LOG_FILE")"
cd "$PROJECT_DIR" || exit 1

echo "[scheduler] starting at $(date '+%Y-%m-%d %H:%M:%S')" >> "$LOG_FILE"
nohup "$PHP_BIN" artisan schedule:work >> "$LOG_FILE" 2>&1 &
PID=$!
# save pid for easy stop
printf "%s" "$PID" > "$PID_FILE"

echo "Scheduler started (pid: $PID). Logs: $LOG_FILE"
