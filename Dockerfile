FROM php:8.1-fpm

# Maintainer
LABEL Henrich Barkoczy <me@barkoczy.social>

# Arguments defined in docker-compose.yml
ARG user
ARG uid
ARG UPLOAD_MAX_FILESIZE
ARG POST_MAX_SIZE

# Install system dependencies
RUN apt-get update && apt-get install -y \ 
    build-essential \
    locales \
    autoconf \
    pkg-config \
    libzip-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    libonig-dev \
    libxml2-dev \
    libmcrypt-dev \
    libpng-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    jpegoptim optipng pngquant gifsicle \
    git \
    curl \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install bcmath mbstring intl curl zip gd exif pcntl pdo_mysql opcache
RUN docker-php-ext-configure opcache --enable-opcache

# Opache configuration
RUN { \
        echo 'opcache.memory_consumption=128'; \
        echo 'opcache.interned_strings_buffer=8'; \
        echo 'opcache.max_accelerated_files=4000'; \
        echo 'opcache.revalidate_freq=2'; \
        echo 'opcache.fast_shutdown=1'; \
        echo 'opcache.enable_cli=1'; \
    } > /usr/local/etc/php/conf.d/opcache.ini


# Custom php configuration
RUN { \
        echo 'upload_max_filesize = '$UPLOAD_MAX_FILESIZE; \
        echo 'post_max_size = '$POST_MAX_SIZE; \
    } > /usr/local/etc/php/conf.d/custom.ini

# Install mcrypt extension
RUN pecl install mcrypt-1.0.5 \
    &&  echo "extension=mcrypt.so" > $PHP_INI_DIR/conf.d/docker-php-ext-mcrypt.ini \
    && docker-php-ext-enable mcrypt

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set working directory
WORKDIR /var/www

# Set user
USER $user