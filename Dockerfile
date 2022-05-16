FROM php:8.1-cli

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update && \
    apt-get install -y git && \
    docker-php-ext-install bcmath