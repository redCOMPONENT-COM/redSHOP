#!/bin/bash
# Script for preparing the system tests in Joomla!

# Start apache
service apache2 restart
service mysql start

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
sudo vendor/bin/robo prepare:site-for-system-tests
git submodule update --init --recursive
composer install --working-dir ./libraries/redshop --ansi
npm install
mv gulp-config.sample.jenkins.json gulp-config.json
gulp release --skip-version
mv tests/RoboFile.ini.dist tests/RoboFile.ini

# Move folder to /tests
rm -r /tests/www
ln -s $(pwd) /tests/www
cd /tests/www
ls -la
cd ../../

# Run tests
vendor/bin/robo run:tests-jenkins
