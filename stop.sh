#!/usr/bin/env bash
set -e

CURRENT_DIR=$PWD
PROJECT_DIR=$(dirname $(realpath "$0"))

echo ""
echo -e "\e[48;5;21m\e[38;5;226m LogViewer: Stopping current containers \e[0m"

cd ${PROJECT_DIR}/dev

docker compose down

cd ${CURRENT_DIR}
