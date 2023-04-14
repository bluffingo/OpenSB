FROM bitnami/php-fpm:8.2

RUN apt update \
    && apt install ffmpeg -y

