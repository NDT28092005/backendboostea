#!/bin/bash
set -e

cd /home/site/wwwroot

# Start PHP-FPM (lắng nghe port 9000)
php-fpm8.2 -F -y /home/site/wwwroot/php-fpm.conf &
# Nếu php-fpm8.2 không có, dùng php-fpm
# php-fpm -F -y /home/site/wwwroot/php-fpm.conf &

# Start Nginx với file config custom
nginx -c /home/site/wwwroot/nginx.conf
