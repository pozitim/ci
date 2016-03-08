#!/bin/bash
if [ "$PHALCON_VERSION" ]; then
    cp "/phalcon/$PHALCON_VERSION/phalcon.so" /usr/lib64/php/modules/phalcon.so
fi
sed -i -e "s#<<public_folder>>#${NGINX_PUBLIC_FOLDER}#g" /etc/nginx/nginx.conf
sed -i -e "s#<<index_file>>#${NGINX_INDEX_FILE}#g" /etc/nginx/nginx.conf
/usr/sbin/php-fpm -R
/usr/sbin/nginx -g "daemon off;"