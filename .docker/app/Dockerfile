FROM php:7.3-fpm-alpine3.10 as base

RUN apk add --no-cache shadow \
    openssl \
    bash \
    mysql-client \
    nodejs \
    npm \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev

RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-install -j$(nproc) gd

RUN touch /home/www-data/.bashrc | echo "PS1='\w\$ '" >> /home/www-data/.bashrc

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN npm config set cache /var/www/.npm-cache --global

RUN usermod -u 1000 www-data

WORKDIR /var/www

RUN rm -rf /var/www/html && ln -s public html

USER www-data

EXPOSE 9000

FROM base as dev
USER root
ENV DOCKERIZE_VERSION v0.6.1
RUN wget https://github.com/jwilder/dockerize/releases/download/$DOCKERIZE_VERSION/dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && tar -C /usr/local/bin -xzvf dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && rm dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz

ADD .docker/app/.env /templates/.env
ADD .docker/app/.env.testing /templates/.env.testing
ADD .docker/app/entrypoint.sh /entrypoint.sh
ADD .docker/app/entrypoint.prod.sh /entrypoint.prod.sh
RUN chmod -R 775 /entrypoint.sh
RUN chmod -R 775 /entrypoint.prod.sh

USER www-data
