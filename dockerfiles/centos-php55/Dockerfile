FROM centos:latest
MAINTAINER Pozitim <info@pozitim.com>
RUN yum install -y --nogpgcheck epel-release && \
    rpm -qa | grep -q remi-release || rpm -Uvh http://rpms.famillecollet.com/enterprise/remi-release-7.rpm && \
    yum --enablerepo=remi-php55,remi install --nogpgcheck -y \
        git \
        nginx \
        php \
        php-devel \
        php-common \
        php-cli \
        php-pear \
        php-fpm \
        php-bcmath \
        php-gd \
        php-mbstring \
        php-mcrypt \
        php-pdo \
        php-soap \
        php-xcache \
        php-xml \
        php-xmlrpc \
        php-json \
        php-mysql \
        php-pecl-gearmand \
        php-pecl-xdebug \
        php-pecl-xhprof \
        php-pecl-memcached && \
    rm -rf /etc/nginx/conf.d/*.conf && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    TMPDIR=/tmp yum clean metadata && \
    TMPDIR=/tmp yum clean all
ADD ./files/nginx.conf /etc/nginx/nginx.conf
ADD ./files/www.conf /etc/php-fpm.d/www.conf