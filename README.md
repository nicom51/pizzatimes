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

    - php bin/console doctrine:fixtures:load
    - php bin/console --env=test doctrine:fixtures:load

##BUILDER

    - Execute ./build_app.sh in bash for git clone, make DB, make project and run 









