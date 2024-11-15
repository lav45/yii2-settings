FROM alpine:3.12

RUN apk upgrade --no-cache --available
RUN apk add --no-cache \
    php7 \
    php7-opcache \
    php7-pdo php7-pdo_sqlite php7-sqlite3 \
    php7-json \
    php7-mbstring \
    php7-xml php7-xmlwriter \
    php7-tokenizer \
    php7-dom \
    php7-phar \
    php7-curl curl \
    php7-openssl openssl \
    php7-iconv

RUN curl -sS https://getcomposer.org/installer | php -- --with-openssl --install-dir=/usr/local/bin --filename=composer