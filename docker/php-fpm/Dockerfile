# PHP Docker image for Yii 2.0 Framework runtime
# ==============================================

ARG PHP_BASE_IMAGE_VERSION
FROM php:7.3.0-fpm-stretch

# Install system packages for PHP extensions recommended for Yii 2.0 Framework
ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update && \
    apt-get -y install \
        gnupg2 && \
    apt-key update && \
    apt-get update && \
    apt-get -y install \
            g++ \
            git \
            curl \
            wget \
            imagemagick \
            libfreetype6-dev \
            libcurl3-dev \
            libicu-dev \
            libmcrypt-dev \
            libfreetype6-dev \
            libjpeg-dev \
            libjpeg62-turbo-dev \
            libmagickwand-dev \
            libpq-dev \
            libpng-dev \
            zlib1g-dev \
            openssh-client \
            libxml2-dev \
            nano \
            linkchecker \
        --no-install-recommends && \
        apt-get clean && \
        rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# install zmq and php-zmq
# php-zmq
RUN echo "deb http://download.opensuse.org/repositories/network:/messaging:/zeromq:/release-stable/Debian_9.0/ ./" >> /etc/apt/sources.list \
    && wget https://download.opensuse.org/repositories/network:/messaging:/zeromq:/release-stable/Debian_9.0/Release.key -O- | apt-key add \
    && apt-get update \
    && apt-get install -y libzmq3-dev libzip-dev \
    && rm -rf /var/lib/apt/lists/*

RUN cd /tmp && git clone git://github.com/mkoppanen/php-zmq.git && \
    cd php-zmq && phpize && ./configure && make && make install && \
    echo "extension=zmq.so" > /usr/local/etc/php/conf.d/zmq.ini

#zeromq
#RUN wget http://download.zeromq.org/zeromq-4.0.1.tar.gz && tar xvzf zeromq-4.0.1.tar.gz && apt-get update && \
#   apt-get install -y libtool pkg-config build-essential autoconf automake uuid-dev && \
#   cd zeromq-4.0.1 && ./configure && make install && ldconfig && ldconfig -p | grep zmq && \
#   touch /usr/local/etc/php/conf.d/zmq.ini && echo "extension=zmq.so" >> /usr/local/etc/php/conf.d/zmq.ini && \
#   pecl install zmq-beta -y

#RUN apt-get -y install php-pear libzmq-dev && pecl install zmq-beta -y && docker-php-ext-enable zmq
RUN apt-get -y install libzip-dev

# Install PHP extensions required for Yii 2.0 Framework
RUN docker-php-ext-configure zip --with-libzip && \
    docker-php-ext-configure gd \
        --with-freetype-dir=/usr/include/ \
        --with-png-dir=/usr/include/ \
        --with-jpeg-dir=/usr/include/ && \
    docker-php-ext-configure bcmath && \
    docker-php-ext-install \
        soap \
        zip \
        curl \
        bcmath \
        exif \
        gd \
        iconv \
        intl \
        mbstring \
        opcache \
        xmlrpc \
        pgsql \
        pdo_pgsql

# Install PECL extensions
# see http://stackoverflow.com/a/8154466/291573) for usage of `printf`
RUN curl -fsSL 'https://pecl.php.net/get/imagick-3.4.4.tgz' -o /tmp/imagick.tgz \
    && mkdir -p /tmp/imagick \
    && tar -xf /tmp/imagick.tgz -C /tmp/imagick --strip-components=1 \
    && docker-php-ext-configure /tmp/imagick --with-imagick=/usr/local/ImageMagick/ \
    && docker-php-ext-install -j $(nproc) /tmp/imagick \
    && rm /tmp/imagick.tgz \
    && rm -r /tmp/imagick


ENV PHP_USER_ID=33 \
    VERSION_COMPOSER_ASSET_PLUGIN=^1.3.0 \
    VERSION_PRESTISSIMO_PLUGIN=^0.3.0 \
    PATH=/src:/app/vendor/bin:/root/.composer/vendor/bin:$PATH \
    TERM=linux \
    COMPOSER_ALLOW_SUPERUSER=1

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- \
        --filename=composer \
        --install-dir=/usr/local/bin

# Install composer plugins
RUN composer global require --optimize-autoloader \
        "hirak/prestissimo" && \
    composer global dumpautoload --optimize && \
    composer clear-cache

# Install supervisor
RUN apt-get update && apt-get -y install supervisor

# COPY image-files/ /

# для build
COPY docker/php-fpm/image-files/ /

WORKDIR /src/

EXPOSE 3001
