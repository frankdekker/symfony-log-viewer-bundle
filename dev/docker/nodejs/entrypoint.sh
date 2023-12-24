#!/bin/bash
set -e

cd /app/frontend
npm install --no-save

node node_modules/vite/bin/vite.js  build -w --mode development
