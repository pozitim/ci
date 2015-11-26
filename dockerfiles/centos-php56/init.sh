#!/bin/bash
sed -i -e "s#<<public_folder>>#${NGINX_PUBLIC_FOLDER}#g" /etc/nginx/nginx.conf
sed -i -e "s#<<index_file>>#${NGINX_INDEX_FILE}#g" /etc/nginx/nginx.conf
/usr/sbin/php-fpm -R
/usr/sbin/nginx -g "daemon off;"