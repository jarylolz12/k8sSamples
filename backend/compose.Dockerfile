FROM php:7.4-fpm

# Arguments defined in docker-compose.yml
ARG user_name
ARG user_id

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

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $user_id -d /home/$user_name $user_name
RUN mkdir -p /home/$user_name/.composer && \
    chown -R $user_name:$user_name /home/$user_name

# Set working directory
WORKDIR /var/www

USER $user_name