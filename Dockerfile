FROM bitnami/php-fpm:8.1

RUN apt update \
    && apt install ffmpeg -y

