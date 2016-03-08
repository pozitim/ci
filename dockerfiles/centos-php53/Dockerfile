FROM centos:latest
MAINTAINER Pozitim <info@pozitim.com>
RUN yum install -y --nogpgcheck epel-release && \
    rpm -qa | grep -q remi-release || rpm -Uvh http://rpms.famillecollet.com/enterprise/remi-release-7.rpm && \
    yum --enablerepo=remi-php53,remi install --nogpgcheck -y \
        git \
        gcc \
        make \
        nginx \
        php \
        php-devel \
        pcre-devel \
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
        php-xml \
        php-xmlrpc \
        php-json \
        php-mysql \
        php-pecl-gearmand \
        php-pecl-memcached && \
    rm -rf /etc/nginx/conf.d/*.conf && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    TMPDIR=/tmp yum clean metadata && \
    TMPDIR=/tmp yum clean all && \
    git clone --depth=1 -b phalcon-v1.3.4 git://github.com/phalcon/cphalcon.git /cphalcon && \
    cd /cphalcon/build && ./install && \
    cd / && \
    rm -rf /cphalcon && \
    mkdir -p /phalcon/1.3.4 && \
    cp /usr/lib64/php/modules/phalcon.so /phalcon/1.3.4/phalcon.so && \
    git clone --depth=1 -b phalcon-v2.0.0 git://github.com/phalcon/cphalcon.git /cphalcon && \
    cd /cphalcon/build && ./install && \
    rm -rf /cphalcon && \
    cd / && \
    mkdir -p /phalcon/2.0.0 && \
    cp /usr/lib64/php/modules/phalcon.so /phalcon/2.0.0/phalcon.so && \
    git clone --depth=1 -b phalcon-v2.0.10 git://github.com/phalcon/cphalcon.git /cphalcon && \
    cd /cphalcon/build && ./install && \
    rm -rf /cphalcon && \
    cd / && \
    mkdir -p /phalcon/2.0.10 && \
    cp /usr/lib64/php/modules/phalcon.so /phalcon/2.0.10/phalcon.so && \
    echo "extension=phalcon.so" > /etc/php.d/phalcon.ini
ADD ./files/nginx.conf /etc/nginx/nginx.conf
ADD ./files/www.conf /etc/php-fpm.d/www.conf