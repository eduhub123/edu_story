#From base image
FROM 820883240614.dkr.ecr.ap-southeast-1.amazonaws.com/php-base:latest

WORKDIR /var/www
ADD . .

#Install composer

RUN composer install 
RUN composer dump-autoload

#Config apache
EXPOSE 80
COPY custom.ini /usr/local/etc/php/php.ini
COPY apache2.conf /etc/apache2
COPY vhost.conf /etc/apache2/sites-available/000-default.conf
RUN chmod 777 -R storage && chown -R www-data:www-data /var/www
RUN a2enmod rewrite