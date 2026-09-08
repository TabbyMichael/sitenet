FROM wordpress:6.6.1-php8.2-apache

# PHP extensions needed by the site's plugins (Elementor, WooCommerce, ACF Pro,
# revslider, etc.). Matches the LocalWP environment (PHP 8.2).
RUN apt-get update && apt-get install -y --no-install-recommends \
        libzip-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libonig-dev \
        unzip \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install mysqli pdo pdo_mysql gd zip mbstring exif bcmath \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# WP-CLI (used by sync.sh for serialized-safe search-replace)
RUN curl -fsSL -o /usr/local/bin/wp \
        https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x /usr/local/bin/wp

# Allow overriding the Apache run user/group from the environment so workers
# run as the same uid/gid as the LocalWP user (keeps file ownership intact).
RUN sed -i 's/^export APACHE_RUN_USER=www-data/export APACHE_RUN_USER=${APACHE_RUN_USER:-www-data}/' /etc/apache2/envvars \
    && sed -i 's/^export APACHE_RUN_GROUP=www-data/export APACHE_RUN_GROUP=${APACHE_RUN_GROUP:-www-data}/' /etc/apache2/envvars

# mod_rewrite for WordPress permalinks
RUN a2enmod rewrite

# Security: the webroot is the git repo, so block sensitive paths/files
COPY apache-deny.conf /etc/apache2/conf-enabled/docker-site-deny.conf

# PHP runtime settings
RUN printf 'memory_limit = 512M\n' > /usr/local/etc/php/conf.d/docker-php-memlimit.ini \
    && printf 'upload_max_filesize = 64M\npost_max_size = 64M\nmax_execution_time = 300\n' \
        > /usr/local/etc/php/conf.d/docker-php-upload.ini

EXPOSE 80
CMD ["apache2-foreground"]