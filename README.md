# Site demo Symfony 6 PHP 8

## Config Symfony

.env.local a créer et a maj

Installation des vendors

    - composer update

## Base mysql
Créer la base pizzatimes

Créer les tables :

    - php bin/console make:migration
    - php bin/console doctrine:migrations:migrate

Seed des tables users et ingredients:

    - symfony console doctrine:fixtures:load





