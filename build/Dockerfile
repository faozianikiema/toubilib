# Partir de PHP 8 Alpine (léger)
FROM php:8.3-alpine

# Installer dépendances nécessaires
RUN apk add --no-cache bash curl icu-dev libzip-dev zlib-dev oniguruma-dev autoconf g++ make

# Installer les extensions PHP utiles pour MySQL et autres
RUN docker-php-ext-install \
    mysqli \
    pdo_mysql \
    intl \
    zip

# Installer Composer (gestionnaire de dépendances PHP)
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

# Définir le dossier de travail
WORKDIR /var/www

# Exposer le port du serveur PHP
EXPOSE 8080

# Commande par défaut (tu peux aussi la laisser dans docker-compose)
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
