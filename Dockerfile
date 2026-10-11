FROM php:8.3-apache
RUN apt-get update && apt-get install -y libonig-dev \
    && docker-php-ext-install pdo_mysql mbstring \
    && rm -rf /var/lib/apt/lists/*
RUN printf '<Directory /var/www/html>\nAllowOverride All\nRequire all granted\n</Directory>\n' > /etc/apache2/conf-available/hotel.conf && a2enconf hotel
COPY . /var/www/html/
