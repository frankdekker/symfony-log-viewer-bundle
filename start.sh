#!/usr/bin/env bash
set -e

CURRENT_DIR=$PWD
PROJECT_DIR=$(dirname $(realpath "$0"))
FOLLOW_LOG='0'

################################################################################
##                            setup env
################################################################################

# Map arguments
while [[ $# -gt 0 ]] && [[ "$1" == "-"* ]] ;
do
    opt="$1";
    shift;
    case "$opt" in
        "--logs" )
           FOLLOW_LOG='1';;
        *) echo >&2 "Invalid option: $@"; exit 1;;
   esac
done

if [ "$FOLLOW_LOG" == '1' ]; then
    echo "[FOLLOW_LOG]: yes."
else
    echo "[FOLLOW_LOG]: no.  Use '--logs' flag to show docker compose logs."
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

docker compose up -d --wait

echo ""
echo -e "\e[48;5;21m\e[38;5;226m LogViewer: Environment running \e[0m"
echo ""
echo "    Environment available at: http://localhost:8888/"
echo ""

if [ "$FOLLOW_LOG" == '1' ]; then
    docker compose logs --tail=5 --follow
fi

cd ${CURRENT_DIR}
