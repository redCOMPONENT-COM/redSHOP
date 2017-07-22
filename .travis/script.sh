#!/bin/bash
set -ev

# Following line uses a bot account to authenticate in github and make composer stable and faster, see https://redweb.atlassian.net/wiki/pages/viewpage.action?pageId=46694753
# composer config -g github-oauth.github.com "${GITHUB_TOKEN}"
# composer update
# composer install --working-dir ./libraries/redshop --ansi
#- php vendor/bin/robo check:for-missed-debug-code
#- php vendor/bin/robo check:for-php-parse
# - php vendor/bin/robo check:codestyle
mv tests/acceptance.suite.dist.yml tests/acceptance.suite.yml # Create travis system tests config file
# Download Joomla for testing
php vendor/bin/robo prepare:site-for-system-tests
php vendor/bin/robo check:travis-webserver # Test apache
php vendor/bin/robo run:tests 1
#- php vendor/bin/robo send:codeception-output-to-slack C02L0SE5E xoxp-2309442657-4789197868-4789233706-68cec7