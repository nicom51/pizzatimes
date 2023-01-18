#!/bin/bash

PASSWDDB="pizza_times"
MAINDB="pizza_times"

function sql(){
  if [ -f /root/.my.cnf ]; then

      mysql -e "CREATE DATABASE IF NOT EXISTS ${MAINDB} /*\!40100 DEFAULT CHARACTER SET utf8 */;"
      mysql -e "CREATE USER '${MAINDB}'@'%' IDENTIFIED BY '${PASSWDDB}';"
      mysql -e "GRANT ALL PRIVILEGES ON ${MAINDB}.* TO '${MAINDB}'@'%';"
      mysql -e "FLUSH PRIVILEGES;"

  # If /root/.my.cnf doesn't exist then it'll ask for root password
  else
      echo "Please enter root user MySQL password!"
      echo "Note: password will be hidden when typing"
      read -sp rootpasswd
      mysql -uroot -p${rootpasswd} -e "CREATE DATABASE IF NOT EXISTS ${MAINDB} /*\!40100 DEFAULT CHARACTER SET utf8 */;"
      mysql -uroot -p${rootpasswd} -e "CREATE USER '${MAINDB}'@'%' IDENTIFIED BY '${PASSWDDB}';"
      mysql -uroot -p${rootpasswd} -e "GRANT ALL PRIVILEGES ON ${MAINDB}.* TO '${MAINDB}'@'%';"
      mysql -uroot -p${rootpasswd} -e "GRANT ALL PRIVILEGES ON ${MAINDB}_test.* TO '${MAINDB}'@'%';"
      mysql -uroot -p${rootpasswd} -e "FLUSH PRIVILEGES;"
  fi
}

git clone https://github.com/nicom51/pizzatimes.git
cd pizzatimes
cp .env .env.local
sql
composer update
php bin/console make:migration
php bin/console doctrine:migrations:migrate
symfony console doctrine:fixtures:load
php bin/console --env=test doctrine:database:create
php bin/console --env=test doctrine:schema:create
php bin/console --env=test doctrine:fixtures:load
php bin/phpunit
symfony server:start -d
