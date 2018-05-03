#!/bin/bash
# Script for preparing the system tests in Joomla!

touch output.log
echo $CI_BUILD_DIR
echo $DRONE_PULL_REQUEST
echo $CI_BUILD_URL
echo $STAGE_NAME
cp -r /vendor vendor
echo $(git log -i | head -50)
git log -1 | head -30 | grep 'BRANCH INDEXING'
composer config -g github-oauth.github.com "4d92f9e8be0eddc0e54445ff45bf1ca5a846b609"
composer install --prefer-dist
vendor/bin/codecept --version

vendor/bin/robo prepare:site-for-system-tests 1
wget "https://chromedriver.storage.googleapis.com/2.35/chromedriver_linux64.zip" > output.log 2>&1
ln -s /usr/bin/nodejs /usr/bin/node
cd /tests/www
mkdir tests
mkdir repo
cd ${CI_BUILD_DIR}
ln -s $(pwd)/tests/joomla-cms /tests/www/tests/
ln -s $(pwd) /tests/www/tests/repo
git submodule update --init --recursive

# Install Gulp for Package Generation
npm install -g gulp > output.log 2>&1
npm install > output.log 2>&1
composer global require hirak/prestissimo

cd libraries/redshop
composer install --prefer-dist

cd ../../plugins/redshop_pdf/tcpdf/helper
composer install --prefer-dist

cd ../../../..
composer install --prefer-dist

cd /tests/www
cd tests
mkdir releases-redshop
cd ${CI_BUILD_DIR}
mv gulp-config.sample.jenkins.json gulp-config.json
gulp release --skip-version
echo $DRONE_PULL_REQUEST
cp /tests/www/tests/releases-redshop/redshop.zip .

#vendor/bin/robo upload:patch-from-jenkins-to-test-server $GITHUB_TOKEN $GITHUB_REPO_OWNER $REPO $CHANGE_ID

rm -rf /tmp/.org.chromium.Chromium*

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

# Test Setup
mv tests/acceptance.suite.dist.jenkins.yml tests/acceptance.suite.yml
# sed -i "s/{dbhostname}/db-$BUILD_TAG/g" tests/acceptance.suite.yml
chown -R www-data:www-data tests/joomla-cms

# Start Running Tests
cd ${CI_BUILD_DIR}
mysql --host=db-$BUILD_TAG -uroot -proot -e "DROP DATABASE IF EXISTS redshopSetupDb;"
vendor/bin/robo run:tests-jenkins

if [ $? -eq 0 ]
then
  echo "Tests Runs were successful"
  rm -r tests/_output/
  mysqldump --host=db-$BUILD_TAG -uroot -proot redshopSetupDb > backup.sql
  zip --symlinks -r joomla-cms-database.zip backup.sql > output.log 2>&1
  cd tests
  zip --symlinks -r joomla-cms.zip joomla-cms > output.log 2>&1
  mv *joomla-cms.zip* ..
  cd ../
  exit 0
else
  echo "Tests Runs Failed" >&2
  #send screenshot of failed test to Slack
  vendor/bin/robo send:build-report-error-slack $CLOUDINARY_CLOUD_NAME $CLOUDINARY_API_KEY $CLOUDINARY_API_SECRET $GITHUB_REPO $CHANGE_ID "$SLACK_WEBHOOK" "$SLACK_CHANNEL" "$BUILD_URL"
  rm -r tests/_output/
  cd ../
  exit 1
fi