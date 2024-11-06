ARG UNIT_VERSION=1.33.0

FROM composer/composer:2-bin AS composer

FROM mlocati/php-extension-installer:latest AS php_extension_installer

FROM php:8.3-zts-alpine AS builder

ARG UNIT_VERSION

RUN apk update && apk upgrade

RUN set -eux \
    && apk add --update --no-cache alpine-sdk git curl openssl-dev pcre-dev

RUN set -eux \
    && mkdir -p /usr/lib/unit/modules \
    && mkdir -p /usr/src/unit \
    && cd /usr/src/unit \
    && git clone --depth 1 -b ${UNIT_VERSION} https://github.com/nginx/unit \
    && cd unit \
    && NCPU="$(getconf _NPROCESSORS_ONLN)" \
    && CONFIGURE_ARGS_MODULES="--prefix=/usr \
                    --statedir=/var/lib/unit \
                    --control=unix:/var/run/control.unit.sock \
                    --runstatedir=/var/run \
                    --pid=/var/run/unit.pid \
                    --logdir=/var/log \
                    --log=/var/log/unit.log \
                    --tmpdir=/var/tmp \
                    --user=unit \
                    --group=unit \
                    --openssl" \
    && ./configure ${CONFIGURE_ARGS_MODULES} \
    && make -j ${NCPU} unitd \
    && install -pm755 build/sbin/unitd /usr/sbin/unitd \
    && make clean \
    && /bin/true \
    && ./configure ${CONFIGURE_ARGS_MODULES} --modulesdir=/usr/lib/unit/modules \
    && ./configure php \
    && make -j ${NCPU} php-install

## Dev image
FROM php:8.3-zts-alpine AS unit

ENV APP_ENV=dev

RUN apk update && \
    apk upgrade

RUN apk add --no-cache \
		acl \
		fcgi \
		file \
		gettext \
		git \
        openssh-client \
        pcre \
	;

# php extensions installer: https://github.com/mlocati/docker-php-extension-installer
COPY --from=php_extension_installer /usr/bin/install-php-extensions /usr/local/bin/

# Nginx Unit
COPY --from=builder /usr/sbin/unitd /usr/sbin/
COPY --from=builder /usr/lib/unit/modules/* /usr/lib/unit/modules/

RUN set -eux \
    && mkdir -p /var/lib/unit/ \
    && mkdir -p /docker-entrypoint.d/ \
    && addgroup -S unit 2>/dev/null \
    && adduser -S -D -H -h /var/lib/unit -s /sbin/nologin -G unit -g "NGINX Unit" unit 2>/dev/null

RUN ln -sf /dev/stderr /var/log/unit.log

# PHP
RUN set -eux; \
    install-php-extensions \
    	apcu \
		opcache \
        pdo_pgsql \
        redis \
    ;

COPY --from=composer /composer /usr/local/bin/

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"
ENV COMPOSER_HOME=/var/composer
ENV COMPOSER_CACHE_DIR=$COMPOSER_HOME/cache

RUN mkdir -m 0777 $COMPOSER_HOME

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

COPY docker/conf.d/app.dev.ini $PHP_INI_DIR/conf.d/
COPY docker/unit/unit.json /docker-entrypoint.d/

COPY docker/docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

COPY docker/welcome.* /usr/share/unit/welcome/

WORKDIR /srv/app

COPY . .

RUN set -eux; \
    mkdir -p var/cache var/log; \
    chmod +x bin/console; sync;

RUN composer install

STOPSIGNAL SIGTERM

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

EXPOSE 80

CMD ["unitd", "--no-daemon", "--control", "unix:/var/run/control.unit.sock"]
