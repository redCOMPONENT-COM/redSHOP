#!/bin/bash
set -ev

if [ "${ACCEPTANCE}" = "false" ]; then
	# Check missed debug code
	php tests/checkers/debugcode.php

	# Check PHP Parse
	php tests/checkers/phppec.php

	# Check PHP Codestyle.
	php tests/checkers/phpcs.php
else
	# Create Robo Config file.
	mv tests/RoboFile.ini.dist tests/RoboFile.ini

	# Create travis system tests config file
	mv tests/acceptance.suite.dist.yml tests/acceptance.suite.yml

	# Download Joomla for testing
	php vendor/bin/robo prepare:site-for-system-tests

	# Test apache
	php vendor/bin/robo check:travis-webserver

	# Run Acceptance test
	sudo chown -R www-data:www-data $(pwd)
	php vendor/bin/robo run:tests 1

	# Send output to Slack.
	#- php vendor/bin/robo send:codeception-output-to-slack C02L0SE5E xoxp-2309442657-4789197868-4789233706-68cec7
fi