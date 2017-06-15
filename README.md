akut-schichtplan
================

A Symfony project created on June 6, 2017, 2:17 pm.


## Up and running

1. Clone this repository
2. Configure database `app/config/parameters.yml`
3. Create database `php bin/console doctrine:database:create`
4. Create schema `php bin/console doctrine:schema:update --force`
5. Create a cron job to run `php bin/console swiftmailer:spool:send --env=prod` frequently
This sends all the mails saved in the memory.