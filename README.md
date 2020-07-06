redSHOP 3.x
==========

[![Build Status](https://travis-ci.com/redCOMPONENT-COM/redSHOP.svg?token=exSzjzLhFrzHef99DDg1&branch=develop)](https://travis-ci.com/redCOMPONENT-COM/redSHOP) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/redCOMPONENT-COM/redSHOP/badges/quality-score.png?b=develop&s=f945d89ef335953761803d0e0f0e37d5fecf0b62)](https://scrutinizer-ci.com/g/redCOMPONENT-COM/redSHOP/?branch=develop)

redSHOP 3.x is a major release and breaking change version, including a lot of enhancement in core and bugs fixes. The new version is opened for people to contribute new features, bugs fixes, improvements.

## Requirement
- For redSHOP version **<= 2.1.4**: PHP Version: **>= 5.6**
- For redSHOP version **>= 2.1.5**: PHP Version: **>= 7.0**

## Code Standard
redSHOP **>= 2.1.5** follow **PSR-12** code standard for PHP development instead of **Joomla** standard which is
outdated and not work with PHP_Codesniffer 3.x

## IDE integration
- PHPStorm: https://blog.jetbrains.com/phpstorm/2019/11/phpstorm-2019-3-release#psr

## Documentation

https://github.com/redCOMPONENT-COM/documentation

## DB Change

There are an MySQL Workbench DB Model file in src/db/redshop.mwb. When need some changes in DB structure:

- Use MySQL Workbench open this `src/db/redshop.mwb` file.
- Add some necessary changes in MySQL Workbench
- Go to File > Export > Forward Engineer SQL Create Script...
- Choose path for output script file (admin/sql/mysql/install.sql)
- Check on 2 option *Generate DROP Statements Before Each CREATE Statement* and *Omit Schema Qulifier in Object Names*.
- Open generated install.sql file and remove the comments from MySQL Workbench
- In install.sql, remove this line at top:

> SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

- In install.sql, remove this line at bottom:

> SET SQL_MODE=@OLD_SQL_MODE; 

## Twig template engine support

- Twig library is ready and embeded into redSHOP.
- Almost tags system of redSHOP using Twig template instead of PHP inline
- Remaining layouts, views of redSHOP is moving to Twig and PHP Inline will be deprecated soon.

## How to avoid twig confliction with an other component

- Update redshop to version 3.0.2
- Copy file https://github.com/redCOMPONENT-COM/redSHOP/blob/develop/libraries/redshop/composer.json to WebRoot/libraries/redshop
- run cd `webroot/libraries/redshop`
- run `composer update`
- Go to plugin `System - redSHOP` and disable twig

## Using Gulp build system

Before you can run any Gulp command you need to:

- download and install NodeJS https://nodejs.org/download/
- install npm: `sudo npm install`
- install Gulp: `npm install --save gulp-install`
- install joomla-gulp-release: `sudo npm install --save-dev joomla-gulp-release`

## Using NodeJS version >= 10 on redSHOP

- download and install NodeJS https://nodejs.org/download/. If you need to update NodeJS do as bellow:
- install: `npm install -g n`
- update Node to version you want, example NodeJS 2: `sudo n 12`
- npm install: `npm install --save-dev`
- rebuild node-sass: `npm rebuild node-sass`

## Gulp 4 support

- Currently, redSHOP support GULP 4 with all updated dependencies.
### Following tasks and switches are available:
#### Setup gulp config file. Copy and rename `gulp-config.sample.json` file into `gulp-config.json`

> Version and other information can be set in `gulp-config.json` file.

#### To Release `component` and create `.zip` file

> Use this command to release component. Version and other information can be set in `gulp-config.json` file.

    gulp release:component

#### To Release `modules` and create `.zip` file

    gulp release:module

#### To Release `plugins` and create `.zip` file

    gulp release:plugin

This command will read the base directory and create zip files for each of the folder.

#### === Switches ===
Pass an argument to choose different folder

    --folder {source direcory}  Default: "./plugins"

Pass an argument to change suffix for extension

    --suffix {text of suffix}   Default: "plg_"

#### Example Usage:

	 gulp release:extensions --folder ./modules --suffix ext_


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

Note: to match the plugins with it's compatible core version see https://github.com/redCOMPONENT-COM/redSHOP/pull/1548

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

> git log --oneline 1.4/dev25...develop --no-merges --format="* %s ( %h )" > /var/www/packages/changelog2.log

- Upload the list to http://wiki.redcomponent.com/index.php?title=redSHOP:Changelog


### Prepare software for next release
- Update component version number with next release number at https://github.com/redCOMPONENT-COM/redSHOP/blob/master/redshop.xml#L10
- Create an empty update .sql file at: https://github.com/redCOMPONENT-COM/redSHOP/tree/master/component/admin/sql/updates/mysql
- Merge development branch into Master (see successful git-branching model: http://nvie.com/posts/a-successful-git-branching-model/ )
- Create a release git TAG
 - create the tag in local:

> $ git tag -a 1.3 -m "Version 1.3 Stable"

 - Upload the tag to Github:

> $ git push --tags

 - Check that tag has been created: https://github.com/redCOMPONENT-COM/redSHOP/tags
 - Create the release: https://github.com/redCOMPONENT-COM/redSHOP/releases
 - Add a description to the release with the changelog information that you generated in the previous step

# Testing
See: [testing redSHOP](./tests/README.md)

# redSHOP Road map
Will be updated here soon.
