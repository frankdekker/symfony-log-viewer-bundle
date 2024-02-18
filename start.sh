#!/usr/bin/env bash
set -e

CURRENT_DIR=$PWD
PROJECT_DIR=$(dirname $(realpath "$0"))
FOLLOW_LOG='0'
PORT='8888'

################################################################################
##                            setup env
################################################################################

# Map arguments
while [[ $# -gt 0 ]] && [[ "$1" == "-"* ]] ;
do
    opt="$1";
    shift;
    case "$opt" in
        "--follow-log" )
           FOLLOW_LOG='1';;
        "--port" )
           PORT="$1"; shift;;
        *) echo >&2 "Invalid option: $opt"; exit 1;;
   esac
done

if [ "$PORT" == '8888' ]; then
    echo "[PORT]: ${PORT}. Use '--port' flag to change the default port."
else
    echo "[PORT]: ${PORT}"
fi

if [ "$FOLLOW_LOG" == '1' ]; then
    echo "[FOLLOW_LOG]: yes"
else
    echo "[FOLLOW_LOG]: no. Use '--follow-log' flag to show docker container logs after startup."
fi
echo ""

################################################################################
##                            run docker-compose
################################################################################

cd ${PROJECT_DIR}/dev

echo ""
echo -e "\e[48;5;21m\e[38;5;226m LogViewer: building images \e[0m"

docker compose build --pull

echo ""
echo -e "\e[48;5;21m\e[38;5;226m LogViewer: Stopping current containers \e[0m"

docker compose stop

echo ""
echo -e "\e[48;5;21m\e[38;5;226m LogViewer: Starting containers \e[0m"

if [ "$FOLLOW_LOG" == '1' ]; then
    NGINX_PORT=${PORT} docker compose up -d
else
    NGINX_PORT=${PORT} docker compose up -d --wait
fi

echo ""
echo -e "\e[48;5;21m\e[38;5;226m LogViewer: Environment running \e[0m"
echo ""
echo "    Environment available at: http://localhost:${PORT}/"
echo ""

if [ "$FOLLOW_LOG" == '1' ]; then
    docker compose logs --tail=5 --follow
fi

cd ${CURRENT_DIR}
