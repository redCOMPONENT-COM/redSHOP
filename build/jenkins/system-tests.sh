#!/bin/bash
# Script for preparing the system tests in Joomla!

# Start apache
wget https://ftp.mozilla.org/pub/firefox/releases/47.0.1/linux-x86_64/en-US/firefox-47.0.1.tar.bz2
tar -xjf firefox-47.0.1.tar.bz2
rm -rf  /opt/firefox
mv firefox /opt/firefox47
mv /usr/bin/firefox /usr/bin/firefoxold
ln -s /opt/firefox47/firefox /usr/bin/firefox
firefox --version
service apache2 restart
service mysql start
echo $WORKSPACE
grep -R "DocumentRoot" /etc/apache2/sites-enabled
cd ./tests/
ls -la
cd $WORKSPACE

# Start Xvfb
export DISPLAY=:0
Xvfb -screen 0 1280x1024x24 -ac +extension RANDR &
sleep 1 # give xvfb some time to start

# Start Fluxbox
fluxbox &
sleep 3 # give fluxbox some time to start

# Test Setup
composer update
echo $(pwd)
ls -la
whoami
mv tests/acceptance.suite.dist.jenkins.yml tests/acceptance.suite.yml
vendor/bin/robo prepare:site-for-system-tests
git submodule update --init --recursive
composer install --working-dir ./libraries/redshop --ansi
ln -s /usr/bin/nodejs /usr/bin/node
cd /tests/www
mkdir tests
cd tests
mkdir releases-redshop
cd $WORKSPACE
npm install
mv gulp-config.sample.jenkins.json gulp-config.json
gulp release --skip-version
mv tests/RoboFile.ini.dist tests/RoboFile.ini

# Move folder to /tests
ln -s $(pwd)/tests/joomla-cms3 /tests/www/tests/

# Run tests
vendor/bin/robo run:tests-jenkins
