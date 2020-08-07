FROM php:7.4-apache

RUN pecl install redis-5.1.1 \
  && pecl install igbinary-3.1.4 \
  && docker-php-ext-enable redis igbinary

COPY docker/mercure.conf /etc/apache2/sites-enabled/000-default.conf
COPY . /var/www/mercure
