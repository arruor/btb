FROM alpine:3.9

LABEL maintainer="Dimitar Nikov <dimiter.nikov@gmail.com>"

ENV TERM xterm
ENV container docker

RUN apk add --update \
    php7-fpm \
    php7-apcu \
    php7-ctype \
    php7-curl \
    php7-dom \
    php7-gd \
    php7-iconv \
    php7-imagick \
    php7-json \
    php7-intl \
    php7-mcrypt \
    php7-mbstring \
    php7-opcache \
    php7-openssl \
    php7-pdo \
    php7-pdo_mysql \
    php7-mysqli \
    php7-xml \
    php7-zlib \
    php7-phar \
    php7-tokenizer \
    php7-session \
    php7-simplexml \
    make \
    curl \
    bash \
    mysql-client \
    yarn

RUN rm -rf /var/cache/apk/* && rm -rf /tmp/*

RUN curl --insecure https://getcomposer.org/composer.phar -o /usr/bin/composer && chmod +x /usr/bin/composer

ADD application.ini /etc/php7/conf.d/99-application.ini
ADD application.ini /etc/php7/cli/conf.d/99-application.ini
ADD application.pool.conf /etc/php7/php-fpm.d/application.conf

RUN addgroup --system --gid 1000 www-data && adduser -S -D -H -G www-data www-data
RUN rm -f /etc/php7/php-fpm.d/www.conf
RUN mkdir -p /var/lib/php7/{session,opcache,wsdlcache} && chown -R www-data:www-data /var/lib/php7
RUN mkdir -p /var/www/application/var && chmod -R 0777 /var/www/application/var

RUN yarn add bootstrap jquery popper.js --dev

CMD ["php-fpm7", "-F"]

WORKDIR /var/www/application
EXPOSE 9000
