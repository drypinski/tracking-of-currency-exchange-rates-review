FROM php:8.3-fpm-alpine

ARG UUID=1000
ARG UGID=1000

ENV UUID $UUID
ENV UGID $UGID

RUN apk add --no-cache postgresql-dev fcgi git linux-headers \
    && docker-php-ext-install pdo_pgsql

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

RUN apk add --no-cache pcre-dev $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis.so

RUN apk add --no-cache icu-dev \
    && docker-php-ext-install intl \
    && docker-php-ext-configure intl

COPY ./php/conf.d /usr/local/etc/php/conf.d
COPY ./php-fpm/php-fpm.d /usr/local/etc/php-fpm.d

RUN addgroup -S $UGID && adduser -u $UUID -G $UGID -S app -s /bin/bash -D app

WORKDIR /app

COPY ./php-fpm/entrypoint.sh /usr/local/bin/docker-php-entrypoint
RUN chmod +x /usr/local/bin/docker-php-entrypoint

HEALTHCHECK --interval=5s --timeout=3s --start-period=1s \
    CMD REDIRECT_STATUS=true SCRIPT_NAME=/ping SCRIPT_FILENAME=/ping REQUEST_METHOD=GET \
    cgi-fcgi -bind -connect 127.0.0.1:9000 || exit 1
