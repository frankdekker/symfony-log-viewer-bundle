#!/usr/bin/env bash
set -e

CURRENT_DIR=$PWD
PROJECT_DIR=$(dirname $(realpath "$0"))

cd ${PROJECT_DIR}/dev

echo ""
echo -e "\e[48;5;21m\e[38;5;226m LogViewer: building images \e[0m"

docker compose build --pull

echo ""
echo -e "\e[48;5;21m\e[38;5;226m LogViewer: Stopping current containers \e[0m"

docker compose stop

echo ""
echo -e "\e[48;5;21m\e[38;5;226m LogViewer: Starting containers \e[0m"

docker compose up -d --wait

echo ""
echo -e "\e[48;5;21m\e[38;5;226m LogViewer: Environment running \e[0m"
echo ""
echo "    Environment available at: http://localhost:8888/log-viewer"
echo ""

cd ${CURRENT_DIR}
