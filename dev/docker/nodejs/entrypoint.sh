#!/bin/bash
set -e

npm install --no-save --no-update-notifier --no-fund --no-audit
node node_modules/vite/bin/vite.js build -w --mode development
