#!/bin/bash

# เช็คว่ามีโฟลเดอร์ vendor ไหม (กรณีโดน volume ทับแล้วหายไป)
if [ ! -d "vendor" ]; then
    echo "Vendor folder not found. Installing dependencies..."
    composer install --no-interaction
fi

# (Optional) สั่ง dump-autoload ทุกครั้งที่เริ่ม
# composer dump-autoload

# เริ่มการทำงานของ Apache (command ที่ส่งมาจาก Dockerfile)
exec "$@"