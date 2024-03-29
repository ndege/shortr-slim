FROM php:7.4-fpm-alpine

LABEL authors="Frank Morgner <frnkmorgner@gmail.com>"
EXPOSE 80

ENV APP_ENV=${APP_ENV:-"production"} \
    TIMEZONE=${TIMEZONE:-"Europe/Berlin"} \
    MYSQL_DATABASE=${MYSQL_DATABASE:-"shortr"} \
    MYSQL_USER=${MYSQL_USER:-"shortener"} \
    MYSQL_PASSWORD=${MYSQL_PASSWORD:-"changeme"} \
    SERVICE_URL=${SERVICE_URL:-"http://localhost"} \
    REDIRECT_URL=${REDIRECT_URL:-"http://localhost"} \
    JWT_SECRET=""

RUN apk add --update --no-cache \
  git \
  nginx \
  supervisor \
  tzdata \
  && (rm "/tmp/"* 2>/dev/null || true) && (rm -rf /var/cache/apk/* 2>/dev/null || true)

COPY ./docker/init /tmp/init
COPY ./docker/build /tmp/build
RUN chmod 755 /tmp/init \
 && /tmp/init \
 && rm -rf /tmp/build

COPY . /var/www/html/

COPY docker/run /tmp/run
COPY docker/entrypoint /tmp/entrypoint
RUN chmod 755 /tmp/entrypoint

ENTRYPOINT ["/tmp/entrypoint"]
CMD ["run"]
