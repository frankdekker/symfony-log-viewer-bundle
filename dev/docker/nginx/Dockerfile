FROM nginx:stable-alpine

# Copy nginx config
COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

RUN rm /var/log/nginx/access.log && rm /var/log/nginx/error.log
