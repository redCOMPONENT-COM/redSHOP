#!/bin/bash
# Script for preparing the system tests in Joomla!


touch output.log
rm -rf tests/joomla-cms
unzip -o joomla-cms.zip -d tests/ >output.log 2>&1

rm -rf /tmp/.org.chromium.Chromium*
export dbName=$(date +%s | sha256sum | base64 | head -c 4 ; echo)
export fetch=${WORKSPACE: -1}
export STAGE=$(echo $STAGE_NAME | sed -e 's/\(.*\)/\L\1/' | sed -e 's/ /_/g' | sed -e 's/-/_/g')
echo $STAGE
mysql --host=db-$BUILD_TAG -uroot -proot -e "DROP DATABASE IF EXISTS ${dbName}$(echo $STAGE_NAME | sed -e 's/\(.*\)/\L\1/' | sed -e 's/ /_/g' | sed -e 's/-/_/g')${fetch};"

# Get Chrome Headless
mkdir -p /usr/local/bin
unzip -o "chromedriver_linux64.zip" -d /usr/local/bin
chmod +x /usr/local/bin/chromedriver

#setting php configuration
sed -e 's/max_input_time = 60/max_input_time = 6000/' -i /etc/php/7.1/apache2/php.ini
sed -e 's/max_execution_time = 30/max_execution_time = 6000/' -i /etc/php/7.1/apache2/php.ini
sed -e 's/memory_limit = 128M/memory_limit = 512M/' -i /etc/php/7.1/apache2/php.ini

# Start apache
a2enmod rewrite
service apache2 restart

# Trying to import the DB
mysql --host=db-$BUILD_TAG -uroot -proot -e "create database ${dbName}${STAGE}${fetch}"
unzip -o joomla-cms-database.zip > output.log 2>&1
mysql --host=db-$BUILD_TAG -uroot -proot ${dbName}${STAGE}${fetch} < backup.sql

# move redshop package to right place
cd /tests/www
mkdir tests
mkdir repo
cd $WORKSPACE
cd $(pwd)/tests/joomla-cms
sed -i "s/$db = 'redshopSetupDb'/$db = '${dbName}${STAGE}${fetch}'/g" configuration.php
export log_path='$log_path'
export tmp_path='$tmp_path'
sed -i "s|public $log_path =.*|public $log_path = '${WORKSPACE}/tests/joomla-cms/administrator/logs';|g" configuration.php
sed -i "s|public $tmp_path =.*|public $tmp_path = '${WORKSPACE}/tests/joomla-cms/tmp';|g" configuration.php

cd $WORKSPACE
ln -s $(pwd)/tests/joomla-cms /tests/www/tests/
ln -s $(pwd) /tests/www/tests/repo
cd /tests/www
cd tests
mkdir releases

cd $WORKSPACE
mv gulp-config.json.jenkins.dist gulp-config.json
mv redshop.zip /tests/www/tests/releases/redshop.zip
mv plugins.zip /tests/www/tests/releases/plugins.zip
unzip /tests/www/tests/releases/plugins.zip -d /tests/www/tests/releases/plugins
cd /tests/www/tests/releases/

# Test Setup
cd $WORKSPACE
mv tests/acceptance.suite.dist.jenkins.yml tests/acceptance.suite.yml
sed -i "s/{dbhostname}/db-$BUILD_TAG/g" tests/acceptance.suite.yml
chown -R www-data:www-data tests/joomla-cms

# Start Running Tests
cd $WORKSPACE
vendor/bin/robo run:jenkins $1

if [ $? -eq 0 ]
then
  echo "Tests Run were sucessful"
  mysql --host=db-$BUILD_TAG -uroot -proot -e "DROP DATABASE IF EXISTS ${dbName}${STAGE}${fetch};"
  exit 0
else
  echo "Tests Runs Failed" >&2
  #send screenshot of failed test to Slack
  vendor/bin/robo send:build-report-error-slack $CLOUDINARY_CLOUD_NAME $CLOUDINARY_API_KEY $CLOUDINARY_API_SECRET $GITHUB_REPO $CHANGE_ID $SLACK_WEBHOOK $SLACK_CHANNEL $BUILD_URL
  mysql --host=db-$BUILD_TAG -uroot -proot -e "DROP DATABASE IF EXISTS ${dbName}${STAGE}${fetch};"
  cd ../
  exit 1
fi

