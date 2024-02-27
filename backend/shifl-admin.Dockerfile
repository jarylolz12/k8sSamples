# FROM php:7.4-fpm

# # Install system dependencies
# RUN apt-get update && apt-get install -y \
#     git \
#     curl \
#     libpng-dev \
#     libonig-dev \
#     libxml2-dev \
#     zlib1g-dev \
#     libzip-dev \
#     unzip \
#     vim \
#     cron

# # Clear cache
# RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# # Install PHP extensions
# RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip calendar
 
# # Get latest Composer
# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# # Create system user to run Composer and Artisan Commands
# RUN useradd -G www-data,root -u 1000 -d /home/www www 
# RUN mkdir -p /home/www/.composer && \
#     chown -R www:www /home/www

# # set the default workspace
# WORKDIR /var/www/

# COPY . /var/www/

# USER www


FROM php:7.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zlib1g-dev \
    libzip-dev \
    unzip \
    vim \
    cron

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip calendar

# Install composer
RUN curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php && \
export HASH=`curl -sS https://composer.github.io/installer.sig` && \
php -r "if (hash_file('SHA384', '/tmp/composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer

# set the default workspace
WORKDIR /var/www/

COPY ./ /var/www/

EXPOSE 9000