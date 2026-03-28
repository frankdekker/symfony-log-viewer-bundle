#!/usr/bin/env bash
# Generates a fake Monolog-format log file compressed as .gz in the log directory.
set -e

LOG_DIR="/app/dev/var/log"
DATE=$(date -d 'yesterday' '+%Y-%m-%d')
OUTPUT="${LOG_DIR}/dev-${DATE}.log.gz"

# channel|level|message
entries=(
    'app|DEBUG|Session started for user id 17'
    'app|DEBUG|Dispatching event "kernel.request"'
    'app|DEBUG|Dispatching event "kernel.response"'
    'app|INFO|User "john@example.com" logged in successfully'
    'app|INFO|File uploaded: invoice_2024.pdf (2.3 MB)'
    'app|INFO|Queue job "SendWelcomeEmail" processed successfully'
    'app|WARNING|Deprecation notice: method Foo::bar() is deprecated since v2.0'
    'app|CRITICAL|Unhandled exception: RuntimeException - Division by zero in OrderService.php:84'
    'request|DEBUG|Request GET /api/users completed in 42ms'
    'request|INFO|Request POST /api/orders completed in 138ms'
    'request|WARNING|Route not found for path /admin/legacy-reports'
    'doctrine|DEBUG|Database query executed in 12ms: SELECT * FROM users WHERE id = 42'
    'doctrine|INFO|Cache miss for key "user_profile_42", fetching from database'
    'doctrine|ERROR|Connection to database timed out, retrying (attempt 1/3)'
    'security|INFO|User "john@example.com" logged in successfully'
    'security|WARNING|Invalid CSRF token detected on POST /contact'
    'security|WARNING|Rate limit exceeded for IP 203.0.113.42'
    'security|ERROR|Authentication failure for user "bob@example.com": bad credentials'
)

{
    count=${#entries[@]}
    for i in $(seq 1 200); do
        h=$(printf '%02d' $((RANDOM % 24)))
        m=$(printf '%02d' $((RANDOM % 60)))
        s=$(printf '%02d' $((RANDOM % 60)))
        entry=${entries[$(( (i - 1) % count ))]}
        channel=${entry%%|*}
        rest=${entry#*|}
        level=${rest%%|*}
        message=${rest#*|}
        echo "${h}:${m}:${s} [${DATE} ${h}:${m}:${s}] ${channel}.${level}: ${message} [] []"
    done | sort | sed 's/^[^ ]* //'
} | gzip > "${OUTPUT}"

echo "Generated fake gz log: $(basename "${OUTPUT}")"
