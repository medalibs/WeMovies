
#generer un fichier css tailwind
bin/tailwindcss -i assets/styles/app.css -o assets/styles/app.tailwind.css -w
for prod
bin/tailwindcss -i assets/styles/app.css -o assets/styles/app.tailwind.css -m

# php-cs
php vendor/bin/php-cs-fixer fix

# phpstan
php vendor/bin/phpstan analyse src --level=max

# phpunit
php bin/phpunit

#
php vendor/bin/phpcs --standard=PSR12 src/
#
php vendor/bin/phpcbf --standard=PSR12 src/

