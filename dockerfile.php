FROM php:8.4.0RC2-zts-alpine3.20

# RUN apt-get update && \
#   apt-get install -y locales libpq-dev libicu-dev curl git unzip && \
#   locale-gen en_US.UTF-8 && update-locale && \
#   docker-php-ext-install pdo pdo_pgsql gettext intl

COPY . /web-scraper

WORKDIR /web-scraper

CMD php ./cli/index.php