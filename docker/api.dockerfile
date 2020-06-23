FROM php:7.2-apache

RUN echo "Building API container ......."
RUN apt-get update && apt-get install -yqq  --no-install-recommends \
    git zip unzip curl wget vim libzip-dev apt-utils \
    build-essential software-properties-common \
    libmcrypt-dev libpq-dev libpng-dev libjpeg-dev libxml2-dev libbz2-dev \
    default-mysql-client openssl libssl-dev libcurl4-openssl-dev \
    libreadline-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev

RUN echo "Installing PHP extensions .................."
RUN pecl install mcrypt-1.0.2
RUN docker-php-ext-install -j$(nproc) gd zip bcmath pdo pdo_mysql pdo_pgsql mbstring \
    && docker-php-ext-enable mcrypt pdo_mysql pdo gd \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN apt-get clean && apt-get --purge autoremove -y

RUN echo "Deploying the API .........."
COPY . /var/www/html
WORKDIR /var/www/html

RUN echo "Configure Apache ..................."
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite headers ssl
RUN mv "${PHP_INI_DIR}/php.ini-development" "$PHP_INI_DIR/php.ini"
#COPY apache/php.ini ${PHP_INI_DIR}/conf.d/custom.ini
#COPY apache/vhost-apache.conf /etc/apache2/sites-available/000-default.conf
RUN service apache2 restart

RUN echo "Installing composer ................."
#COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# a user with the same UID/GID as host user to preserve container / host permissions
ARG uid
RUN useradd -G www-data,root -u $uid -d /home/devuser devuser
RUN mkdir -p /home/devuser/.composer && \
    chown -R devuser:devuser /home/devuser

# cannot go further without .env file
RUN #!/bin/bash \
    && if [ ! -x "/var/www/.env"; ] then \
    &&   echo "Please ensure .env exists!" >&2 \
    &&   exit() \
    && fi

RUN echo "Wrapt it up!"
RUN composer dump-autoload \
    && composer install --ignore-platform-reqs --no-scripts --no-interaction -o

CMD ["apache2-foreground"]

#RUN php artisan migrate:fresh
#RUN php artisan db:seed

RUN echo "API Ready!"
