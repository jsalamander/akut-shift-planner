Schicht-Plan.ch
================
[![Build Status](https://travis-ci.org/janfriedli/akut-shift-planner.svg?branch=develop)](https://travis-ci.org/janfriedli/akut-shift-planner)

## Up and running

1. Clone this repository
2. Install Dependencies `composer install`
3. Configure all needed values `app/config/parameters.yml`
4. Create database `php bin/console doctrine:database:create`
5. Create schema `php bin/console doctrine:schema:update --force`
6. Create a cron job to run `d` frequently
This sends all the mails saved in the spool.
7. Create a second cron job for `php bin/console app:delete-passed-plans`
which should run once per day to delete old plans.

## Functional Tests

Run the testsuite with the following command: `composer test`.

Info: The tests will be executed against an new sqlite database, and will not alter any data of yours.

## Deployment
When merging into `develop` travis automatically deploys to the test server.
To create a production release create a `tag`. The new tag will automatically be deployed to production.

### Cron Jobs
This applications has some commands that you need to setup as cron jobs.
If you don't add these the app wont work as expected.

##### Mailer

```bash 
	php bin/console swiftmailer:spool:send --env=prod
```
You need to run this one quite often. I run it every third minute.

```
	*/3	*	*	*	*
```

##### Delete old plans

```bash 
	php bin/console app:delete-passed-plans --dueDays=30 -e prod
```

This command should run at least once a day. It makes sense to it run at night.


##### Send shift reminders

```bash 
	php bin/console app:shift-reminder --days=2 -e prod
```

This command should run once a day. It makes sense to run  it at night.
The `--days=NR_OF_DAYS_HERE` option lets you  define how many days before the shift the reminders
will be sent.


