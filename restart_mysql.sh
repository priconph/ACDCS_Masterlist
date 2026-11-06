#!/bin/bash

# Stop script if any unhandled error occurs
set -euo pipefail

LOG_FILE="/var/log/mysql_restart.log"

echo "[$(date '+%Y-%m-%d %H:%M:%S')] Initiating MySQL restart..." >> "$LOG_FILE"

# Restart MySQL service (legacy service syntax for older Ubuntu versions)
# Executed as root, so 'sudo' inside the command is not needed
if service mysql restart >> "$LOG_FILE" 2>&1; then
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] SUCCESS: MySQL restarted successfully." >> "$LOG_FILE"
else
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] ERROR: Failed to restart MySQL." >> "$LOG_FILE"
    exit 1
fi

#0 0-6 * * * /usr/local/bin/restart_mysql.sh >/dev/null 2>&1