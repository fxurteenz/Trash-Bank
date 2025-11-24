FROM php:8.1-apache

# ติดตั้ง extension
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Enable mod_rewrite
RUN a2enmod rewrite

# สร้าง folder sites-available ถ้ายังไม่มี (ปกติมีอยู่แล้ว)
RUN mkdir -p /etc/apache2/sites-available

# Copy virtual host config
COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf

# เปลี่ยน owner
RUN chown -R www-data:www-data /var/www/html