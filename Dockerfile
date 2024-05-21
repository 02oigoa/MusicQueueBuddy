FROM php:apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

# Same port as docker-compose.yml web port
EXPOSE 8082 

CMD ["apache2-foreground"]

