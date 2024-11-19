FROM php:8.4.0RC2-zts-alpine3.20

COPY . /web-scraper

WORKDIR /web-scraper

CMD "php public/cli.php"