FROM node:21

WORKDIR /app/frontend

COPY ./docker/nodejs/entrypoint.sh /run/entrypoint.sh
RUN chmod +x /run/entrypoint.sh
ENTRYPOINT ["/run/entrypoint.sh"]
