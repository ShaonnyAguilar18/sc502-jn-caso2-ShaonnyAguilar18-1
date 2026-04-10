FROM php:8.2-apache-bookworm

# Instalar y habilitar la extensión mysqli
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Habilitar el módulo de reescritura de Apache (útil para proyectos web)
RUN a2enmod rewrite