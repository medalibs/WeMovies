FROM php:8.2-fpm

RUN apt-get update

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
