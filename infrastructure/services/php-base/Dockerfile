FROM alpine:3.6

# mirrors in france
RUN echo "http://dl-5.alpinelinux.org/alpine/v3.6/main" > /etc/apk/repositories && \
    echo "http://dl-5.alpinelinux.org/alpine/v3.6/community" >> /etc/apk/repositories

RUN apk --update upgrade -q -U -a && rm -rf /var/cache/apk/*

# Php
RUN apk --update add \
    bash \
    php7 \
    php7-apcu \
    php7-bcmath \
    php7-calendar \
    php7-ctype \
    php7-curl \
    php7-dom \
    php7-iconv \
    php7-intl \
    php7-json \
    php7-mbstring \
    php7-opcache \
    php7-openssl \
    php7-pdo \
    php7-pdo_mysql \
    php7-pcntl \
    php7-posix \
    php7-session \
    php7-simplexml \
    php7-sockets \
    php7-tokenizer \
    php7-xml \
    php7-zip \
    pcre \
    && \
    rm -rf /var/cache/apk/*

# Install fake user barrels
ARG BARRELS_UID=1000
RUN addgroup -g 1000 barrels && \
    adduser -S -u $BARRELS_UID -h /home/barrels -s /bin/bash barrels barrels

# Configuration
COPY app.ini /etc/php7/conf.d/app.ini