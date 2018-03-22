FROM php:5.6-apache
MAINTAINER Igor Gomes Sarmento "igorgomessarmento@gmail.com"

RUN apt-get update && apt-get upgrade -y && \
    apt-get install -y \
    bzip2 curl git less mysql-client sudo unzip zip \
    libbz2-dev libfontconfig1 libfontconfig1-dev \
    libfreetype6-dev libjpeg62-turbo-dev libpng12-dev libzip-dev && \
    rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install bz2 && \
    docker-php-ext-configure gd \
        --with-freetype-dir=/usr/include/ \
        --with-jpeg-dir=/usr/include/ && \
    docker-php-ext-install gd && \
    docker-php-ext-install iconv && \
    docker-php-ext-install opcache && \
    docker-php-ext-install pdo_mysql && \
docker-php-ext-install zip

# Install Composer.
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && ln -s $(composer config --global home) /root/composer
ENV PATH $PATH:/root/composer/vendor/bin

RUN usermod -u 1000 www-data && a2enmod rewrite

WORKDIR /var/www/html