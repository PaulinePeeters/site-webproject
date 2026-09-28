FROM php:8.2-fpm-alpine AS php

# Extensions PHP nécessaires
RUN apk add --no-cache \
    nginx \
    supervisor \
    && docker-php-ext-install mysqli

# Config Nginx
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Config Supervisor (lance Nginx + PHP-FPM ensemble)
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copier le site
WORKDIR /var/www/html
COPY . /var/www/html/

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
