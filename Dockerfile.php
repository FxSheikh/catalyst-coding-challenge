FROM php:7.2-apache

RUN useradd faizan

RUN docker-php-ext-install mysqli

RUN chown faizan:faizan /var/www/html

USER faizan

WORKDIR /var/www/html

# Copy application source
COPY ./ /var/www/html/

USER root

RUN chmod -R 755 /var/www/html/

EXPOSE 8080
