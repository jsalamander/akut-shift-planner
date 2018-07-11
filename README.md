Schicht-Plan.ch
================
[![Build Status](https://travis-ci.org/janfriedli/akut-shift-planner.svg?branch=develop)](https://travis-ci.org/janfriedli/akut-shift-planner)
[![Issue Stats](http://issuestats.com/github/janfriedli/akut-shift-planner/badge/pr)](http://issuestats.com/github/janfriedli/akut-shift-planner)
[![Issue Stats](http://issuestats.com/github/janfriedli/akut-shift-planner/badge/issue)](http://issuestats.com/github/janfriedli/akut-shift-planner)

## Up and running

1. Clone this repository
2. Install Dependencies `composer install`
3. Configure all needed values `app/config/parameters.yml`
4. Create database `php bin/console doctrine:database:create`
5. Create schema `php bin/console doctrine:schema:update --force`
6. Create a cron job to run `php bin/console swiftmailer:spool:send --env=prod` frequently
This sends all the mails saved in the spool.
7. Create a second cron job for `php bin/console app:delete-passed-plans`
which should run once per day to delete old plans.

## Functional Tests

Run the testsuite with the following command: `composer test`.

Info: The tests will be executed against an new sqlite database, and will not alter any data of yours.

## Deployment
When merging into `develop` travis automatically deploys to the test server.
To create a production release create a `tag`. The new tag will automatically be deployed to production.
