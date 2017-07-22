#!/bin/bash
set -ev

sudo sed -i '1s/^/127.0.0.1 localhost\n/' /etc/hosts # forcing localhost to be the 1st alias of 127.0.0.1 in /etc/hosts (https://github.com/seleniumhq/selenium/issues/2074)
sudo apt-get update -qq
sudo apt-get install -y --force-yes apache2 libapache2-mod-fastcgi php5-curl php5-mysql php5-intl php5-gd > /dev/null
sudo mkdir $(pwd)/.run

file="~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.conf"

if [ -f ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.d/www.conf.default ];
then
	file="~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.d/www.conf"
	sudo cp -f ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.d/www.conf.default ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.d/www.conf
else
	sudo cp -f ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.conf.default ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.conf
fi;

sed -e "s,listen = 127.0.0.1:9000,listen = /tmp/php${phpversionname:0:1}-fpm.sock,g" --in-place $file
sed -e "s,;listen.owner = nobody,listen.owner = $USER,g" --in-place $file
sed -e "s,;listen.group = nobody,listen.group = $USER,g" --in-place $file
sed -e "s,;listen.mode = 0660,listen.mode = 0666,g" --in-place $file
sed -e "s,user = nobody,;user = $USER,g" --in-place $file
sed -e "s,group = nobody,;group = $USER,g" --in-place $file

cat $file
sudo a2enmod rewrite actions fastcgi alias
echo "cgi.fix_pathinfo = 1" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini
~/.phpenv/versions/$(phpenv version-name)/sbin/php-fpm
sudo cp -f ./tests/travis-ci-apache.conf /etc/apache2/sites-available/default.conf
sudo sed -e "s?%TRAVIS_BUILD_DIR%?$(pwd)?g" --in-place /etc/apache2/sites-available/default.conf
sudo sed -e "s?%PHPVERSION%?${TRAVIS_PHP_VERSION:0:1}?g" --in-place /etc/apache2/sites-available/default.conf
sudo a2ensite default.conf
sudo service apache2 restart
git submodule update --init --recursive
# Window manager
sudo apt-get install fluxbox -y --force-yes
fluxbox &
sleep 3 # give fluxbox some time to start
# Gulp packages
npm install
mv gulp-config.sample.json gulp-config.json
./node_modules/.bin/gulp release --skip-version
mv tests/RoboFile.ini.dist tests/RoboFile.ini