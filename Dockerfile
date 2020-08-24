FROM php:7.3-fpm-alpine

LABEL authors="Frank Morgner <frnkmrgnr@gmail.com>"
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
  icu-dev \
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

RUN curl -L https://getcomposer.org/installer > composer-setup.php \
  && php -r "if (hash_file('SHA384', 'composer-setup.php') === '8a6138e2a05a8c28539c9f0fb361159823655d7ad2deecb371b04a83966c61223adc522b0189079e3e9e277cd72b8897') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); exit(1);} echo PHP_EOL;" \
  && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
  && rm composer-setup.php

COPY . /var/www/html/

RUN chown -R www-data /var/www/html \
    && su www-data -s /bin/sh -c "composer install --prefer-dist --no-dev --no-scripts" \
    && rm -rf ~www-data/.composer /usr/local/bin/composer

COPY docker/run /tmp/run
COPY docker/entrypoint /tmp/entrypoint
RUN chmod 755 /tmp/entrypoint

ENTRYPOINT ["/tmp/entrypoint"]
CMD ["run"]
