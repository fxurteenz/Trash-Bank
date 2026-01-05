FROM php:8.1-apache

# 1. ลง System Dependencies ที่จำเป็น (unzip จำเป็นสำหรับ composer)
RUN apt-get update && apt-get install -y unzip

# 2. ลง PHP Extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN a2enmod rewrite

COPY ./.docker/000-default.conf /etc/apache2/sites-available/000-default.conf
# 3. ตั้งค่า Document Root ให้ชี้ไปที่ folder public (ตาม Best Practice ของ Modern PHP)
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# 4. ติดตั้ง Composer (ดึงมาจาก image ของ composer โดยตรง)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. ตั้งค่า Folder ทำงาน
WORKDIR /var/www/html

# --- เทคนิค Docker Caching ---
# 6. ก๊อปปี้แค่ไฟล์ composer มาก่อน เพื่อ install dependencies
# เหตุผล: ถ้าเราแก้โค้ดแต่ไม่ได้แก้ composer.json Docker จะข้ามขั้นตอนนี้ (เร็วขึ้นมาก)
COPY ./App/composer.json ./App/composer.lock ./

# 7. รันคำสั่ง install library (แบบ Production: ไม่เอา dev dependencies)
RUN composer install --no-dev --no-scripts

# 8. ก๊อปปี้ Source Code ทั้งหมด (โฟลเดอร์ App) เข้าไป
COPY ./App ./

# 9. สร้าง class map
RUN composer dump-autoload --optimize

# 10. ตั้งค่า Permission ให้ Apache อ่าน/เขียนไฟล์ได้
RUN chown -R www-data:www-data /var/www/html

# entrypoint
COPY ./.docker/entrypoint.sh /usr/local/bin/entrypoint.sh
# ให้สิทธิ์ execute ไฟล์
RUN chmod +x /usr/local/bin/entrypoint.sh

# สั่งให้ใช้ script นี้เป็นด่านหน้าก่อนเริ่ม container
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# คำสั่งปิดท้าย (จะถูกส่งไปที่ exec "$@" ใน script)
CMD ["apache2-foreground"]