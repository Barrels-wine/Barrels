FROM mycellar_basephp

# Extra
RUN apk --update add nginx php7-fpm runit curl python py2-pip mariadb-client make g++ php7-phar && \
    rm -rf /var/cache/apk/*

RUN chown -R mycellar /usr/local /usr/bin /usr/lib

# Service
COPY nginx/run             /etc/service/nginx/run
COPY php-fpm/run           /etc/service/php-fpm/run

# Configuration
COPY nginx/nginx.conf      /etc/nginx/nginx.conf
COPY php-fpm/fpm.conf      /etc/php7/php-fpm.conf

RUN chown -R mycellar:mycellar /var/lib/nginx

RUN curl https://getcomposer.org/composer.phar > /usr/local/bin/composer && \
    chmod +x /usr/local/bin/composer

RUN composer global require "friendsofphp/php-cs-fixer:^2.4" --prefer-dist --no-progress --no-suggest --optimize-autoloader --classmap-authoritative \
&& composer clear-cache

# Add composer global binaries to $PATH
ENV PATH $PATH:/home/mycellar/.composer/vendor/bin/
ENV TERM xterm

EXPOSE 80

WORKDIR /var/www

# Default command
CMD ["runsvdir", "-P", "/etc/service"]