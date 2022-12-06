FROM php:7.1-zts-buster

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php --filename=composer --install-dir=/usr/local/bin && \
    php -r "unlink('composer-setup.php');"

RUN apt update -y && apt upgrade -y && \
    apt install -y zlib1g-dev zip libpng-dev && \
    docker-php-ext-install zip gd && \
    docker-php-ext-enable zip gd

WORKDIR /var/www/html