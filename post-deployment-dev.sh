#!/usr/bin/env bash
curl -s https://getcomposer.org/installer | php
SYMFONY_ENV=prod ./composer.phar install --no-dev --optimize-autoloader
php bin/symfony_requirements
php bin/console cache:clear --env=prod --no-debug --no-warmup
php bin/console cache:warmup --env=prod
php bin/console assetic:dump --env=prod --no-debug --env=prod
php bin/console doctrine:database:drop --force --env=prod
php bin/console doctrine:database:create --env=prod
php bin/console doctrine:schema:update --force --env=prod