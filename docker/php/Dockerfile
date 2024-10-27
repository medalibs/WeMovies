FROM php:8.2-fpm

# Mise à jour des paquets et installation de Git, zip, unzip, et libzip-dev
RUN apt-get update && \
    apt-get install -y git zip unzip libzip-dev && \
    rm -rf /var/lib/apt/lists/*

# Installation de l'extension PHP zip
RUN docker-php-ext-install zip

# Vérification des extensions installées
RUN php -m

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Installer Symfony CLI en tant que root
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Créer un utilisateur non-root pour exécuter les commandes
RUN useradd -m symfony
USER symfony

EXPOSE 9000
CMD ["php-fpm"]
