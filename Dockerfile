FROM php:7.3.7-apache

RUN docker-php-ext-install -j$(nproc) pdo_mysql