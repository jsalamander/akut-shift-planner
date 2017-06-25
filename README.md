Schicht-Plan.ch
================
[![Build Status](https://travis-ci.org/fribim/akut-shift-planner.svg?branch=develop)](https://travis-ci.org/fribim/akut-shift-planner)

## Up and running

1. Clone this repository
2. Configure database `app/config/parameters.yml`
3. Create database `php bin/console doctrine:database:create`
4. Create schema `php bin/console doctrine:schema:update --force`
5. Create a cron job to run `php bin/console swiftmailer:spool:send --env=prod` frequently
This sends all the mails saved in the spool.

## Functional Test

Run the testsuite with the following command: `composer test`.

Info: The tests will be executed against an own sqlite database, and will not alter any data of yours.

## Deployment
When merging into `develop` or `master` travis automatically deploys the current code base to the corresponding environment.
