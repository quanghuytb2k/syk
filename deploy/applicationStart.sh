#!/bin/bash

if echo $DEPLOYMENT_GROUP_NAME | grep "dev"; then
    cp /usr/share/nginx/html/dev.env /usr/share/nginx/html/.env
else
    cp /usr/share/nginx/html/prd.env /usr/share/nginx/html/.env
fi



# Change rights
chown -R ec2-user:nginx /usr/share/nginx/html
chmod 2775 /usr/share/nginx/html


find /usr/share/nginx/html -type d -exec chmod 2775 {} \;
#find /usr/share/nginx/html -type f -exec chmod 0664 {} \;
find /usr/share/nginx/html -type f -exec chmod 2775 {} \;

cd /usr/share/nginx/html
export NODE_OPTIONS=--max-old-space-size=1536
npm install
npm run build


rm /usr/share/nginx/html/composer.lock
composer install -d /usr/share/nginx/html/ --no-dev

php /usr/share/nginx/html/artisan migrate
php /usr/share/nginx/html/artisan jwt:secret


# キャッシュクリア
php /usr/share/nginx/html/artisan cache:clear
chmod 777 /usr/share/nginx/html/storage -R

# nginx再起動
systemctl restart php-fpm
systemctl restart nginx
