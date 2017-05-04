Testing redSHOP
==========

## using composer to get Codeception

Execute
```
# You need to have Composer in your system, if not download it from here: https://getcomposer.org/
composer update
```

After that you will be able to run Codeception doing:

```
php vendor/codeception/codeception/codecept build
```

## Preparation for running the test
To prepare the system tests (Selenium) to be run in your local machine you are asked to:

- rename the file `tests/acceptance.suite.dist.yml` to `tests/acceptance.suite.yml`
- edit  the file `tests/acceptance.suite.yml` according to your system needs.
- rename the file `tests/RoboFile.ini.dist` to `tests/RoboFile.ini`
- edit  the file `tests/RoboFile.ini` according to your system needs.

## Running the tests
To run the tests please execute the following commands (for the moment only working in Linux and MacOS):

```bash
$ composer install
$ vendor/bin/robo
$ vendor/bin/robo run:tests
```

## Running individual test
You are able to run only one test. To do so type in your command line:

```bash
$ vendor/bin/robo run:test
```

And follow the instructions.

note: There are a few dependencies between the tests. You will not be able to run an individual tests before executing the main installation tests: installRedShopCest


## Firefox Addons
To generate tests really fast you can use these firefox addons:

- Selenium IDE (records your screen)
- Selenium IDE Codeception Formatter (Export your Selenium IDE test to Codeception language)
