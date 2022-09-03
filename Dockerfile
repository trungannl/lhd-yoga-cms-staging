FROM php:7.4-apache

#install all the system dependencies and enable PHP modules
RUN apt-get update && apt-get install -y \
      libicu-dev \
      libpq-dev \
      libmcrypt-dev \
      gnupg2 \
      git \
      zip \
      zlib1g-dev \
      unzip \
      libfreetype6-dev \
      libjpeg62-turbo-dev \
      libpng-dev \
      libzip-dev \
      libmagickwand-dev \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && rm -r /var/lib/apt/lists/* \
    && docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-configure gd \
    && docker-php-ext-install exif \
    && docker-php-ext-install \
      pdo_mysql \
      zip \
      gd \
      opcache \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer  --version=1.10.16

# install php redis extension
RUN pecl install -o -f redis \
  &&  rm -rf /tmp/pear \
  &&  docker-php-ext-enable redis

# Update Php Settings
RUN sed -E -i -e 's/post_max_size = 8M/post_max_size = 10M/' /usr/local/etc/php/php.ini-development \
 && sed -E -i -e 's/upload_max_filesize = 2M/upload_max_filesize = 10M/' /usr/local/etc/php/php.ini-development \
 && sed -E -i -e 's/post_max_size = 8M/post_max_size = 10M/' /usr/local/etc/php/php.ini-production \
 && sed -E -i -e 's/upload_max_filesize = 2M/upload_max_filesize = 10M/' /usr/local/etc/php/php.ini-production

#set our application folder as an environment variable
ENV APP_HOME /var/www/html

#change uid and gid of apache to docker user uid/gid
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

#change the web_root to laravel /var/www/html/public folder
RUN sed -i -e "s/html/html\/public/g" /etc/apache2/sites-enabled/000-default.conf

# enable apache module rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html

COPY composer.json composer.lock ./

# COPY composer.lock ./
RUN composer install --no-scripts --no-autoloader

RUN composer clear-cache
#copy source files and run composer
COPY . .

#change ownership of our applications
RUN chown -R www-data:www-data storage public

COPY ./docker/docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh && ln -s /usr/local/bin/docker-entrypoint.sh /

ENTRYPOINT ["docker-entrypoint.sh"]
CMD [ "apache2-foreground" ]
