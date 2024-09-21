FROM bitnami/php-fpm:8.3

RUN apt update \
    && apt install ffmpeg -y

