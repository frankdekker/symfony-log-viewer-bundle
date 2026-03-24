#!/usr/bin/env bash
# Compresses all log files in dev/var/log/*.log into .gz archives.
set -e

LOG_DIR="$(dirname $(realpath "$0"))/var/log"

if [ ! -d "${LOG_DIR}" ]; then
    echo "Log directory not found: ${LOG_DIR}"
    echo "Start the dev environment first with ./start.sh"
    exit 1
fi

TIMESTAMP=$(date '+%Y-%m-%d_%H:%M:%S')

count=0
for f in "${LOG_DIR}"/*.log; do
    [ -f "$f" ] || continue
    basename=$(basename "${f%.log}")
    dest="${LOG_DIR}/${basename}-${TIMESTAMP}.log.gz"
    gzip -c "$f" > "$dest"
    rm "$f"
    echo "Compressed: $(basename $f) -> $(basename $dest)"
    count=$((count + 1))
done

if [ "${count}" -eq 0 ]; then
    echo "No .log files found in ${LOG_DIR}"
    echo "Start the dev environment and browse the app to generate logs first."
else
    echo ""
    echo "${count} file(s) compressed. Refresh the log viewer to see the .gz files."
fi
