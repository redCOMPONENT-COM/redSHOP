redSHOP 1.x
==========

# Travis Status
Develop: [![Build Status](https://magnum.travis-ci.com/redCOMPONENT-COM/redSHOP.svg?token=vxVVpxnq2ZPuMp3yebRz&branch=develop)](https://magnum.travis-ci.com/redCOMPONENT-COM/redSHOP)

Documentation

https://github.com/redCOMPONENT-COM/documentation

## Release process for redSHOP
Please follow the next steps in order to release a new version of redSHOP.

- Execute component_packager.xml PHING file to generate the main component package (includes 1 module and 2 plugins).

### Languages & translation
- Move the language files to the translations repository: https://github.com/redCOMPONENT-COM/translations/tree/master/redSHOP/source
- Check in 24hours that Transifex was able to get the new translation strings adding them to the .ini resource files

### Extensions
#### Plugins
- Check if any plugin has been changed in this last release:

> $ git log --oneline --after={2013-04-18} --no-merges --name-only | grep 'plugins\/.*' | cut -d/ -f2,3 | sort | uniq

_Or Compare across versions_

> git log --oneline 1.4/dev19...develop --no-merges --name-only | grep 'plugins\/.*' | cut -d/ -f2,3 | sort | uniq > /var/www/packages/plugins.log

- if plungins have been modified:
 - Update the release number in their manifest .xml file with the same release number as the component. For example if we are releasing redSHOP 1.3 and plugin plg_default_shipping has been updated during 1.2 - 1.3 period, then you should set the plugin version to 1.3 here: https://github.com/redCOMPONENT-COM/redSHOP-1.2/blob/master/plugins/redshop_shipping/default_shipping/default_shipping.xml#L4 or leave it as it is if it has not been touch.
 - execute the plugins_packager.xml PHING file and upload the new release to redCOMPONENT.com

#### Modules
- Check if any module has been changed in this last release:

> $ git log --oneline --after={2013-04-18} --no-merges --name-only | grep 'modules\/.*' | cut -d/ -f2,3  | sort | uniq

_Or Compare across versions_

> git log --oneline 1.4/dev19...develop --no-merges --name-only | grep 'modules\/.*' | cut -d/ -f2,3 | sort | uniq > /var/www/packages/modules.log

- if modules have been modified:
 - Update the release number in their manifest .xml file in the same way you have done it with plugins https://github.com/redCOMPONENT-COM/redSHOP-1.2#plugins
 - execute the modules_packager.xml PHING file and upload the new release to redCOMPONENT.com

### Test
- test the packages to ensure that everything works properly.
- share the package in the redSHOP testing chat, so others can do some testing.
- add fast fixes if issues has been identified during testing
- Generate the final packages and contact the Product Owner (Ole) and ask him to upload the packages to redCOMPOPNENT.com

### Update Changelog
- Create the Changelog list of commits:

> $ git log --oneline --after={2013-04-18} --no-merges --format="* %s ( %h )"

_Or Compare across versions_

> git log --oneline 1.4/dev19...develop --no-merges --format="* %s ( %h )" > /var/www/packages/changelog2.log

- Upload the list to http://wiki.redcomponent.com/index.php?title=redSHOP:Changelog

### Prepare software for next release
- Update component version number with next release number at https://github.com/redCOMPONENT-COM/redSHOP-1.2/blob/master/redshop.xml#L10
- Create an empty update .sql file at: https://github.com/redCOMPONENT-COM/redSHOP-1.2/tree/master/component/admin/sql/updates/mysql
- Merge development branch into Master (see successful git-branching model: http://nvie.com/posts/a-successful-git-branching-model/ )
- Create a release git TAG
 - create the tag in local:

> $ git tag -a 1.3 -m "Version 1.3 Stable"

 - Upload the tag to Github:

> $ git push --tags

 - Check that tag has been created: https://github.com/redCOMPONENT-COM/redSHOP/tags
 - Create the release: https://github.com/redCOMPONENT-COM/redSHOP/releases


# Testing with Codeception

## Using codecept.phar

Get codeception phar:

```
wget http://codeception.com/codecept.phar .
```

Build codeception testers classes:

```
php ./codecept.phar build
```

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

## Running the tests

Rename tests/acceptance.suite.dist.yml to tests/acceptance.suite.yml

Modify the configuration at tests/acceptance.suite.yml to fit your server details. Find the instructions in the same file: https://github.com/redCOMPONENT-COM/redSHOP/blob/develop/tests/acceptance.suite.dist.yml#L3

Run Selenium server:

```
# Download
curl -O http://selenium-release.storage.googleapis.com/2.41/selenium-server-standalone-2.41.0.jar

# And start the Selenium Server
java -Xms40m -Xmx256m -jar /Applications/XAMPP/xamppfiles/htdocs/selenium/selenium-server-standalone-2.41.0.jar
```


Execute the tests:

```
php codecept.phar run
# Or php vendor/codeception/codeception/codecept run

; Or with --steps to see a step-by-step report on the performed actions.
php codecept.phar run --steps
# Or php vendor/codeception/codeception/codecept run --steps

; Or with --html. This command will run all tests for all suites, displaying the steps, and building HTML and XML reports. Reports will be store in tests/_output/ directory.
php codecept.phar run --html
# Or php vendor/codeception/codeception/codecept run --html
```

## Firefox Addons
To generate tests really fast you can use these firefox addons:

- Selenium IDE (records your screen)
- Selenium IDE Codeception Formatter (Export your Selenium IDE test to Codeception language)
