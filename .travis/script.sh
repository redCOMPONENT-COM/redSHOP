#!/bin/bash
set -ev

if [ "${ACCEPTANCE}" = "false" ]; then
	php tests/checkers/debugcode.php # Check missed debug code
	php tests/checkers/phppec.php # Check PHP Parse
	php tests/checkers/phpcs.php # Check PHP Codestyle.
elif [ "${ACCEPTANCE}" = "true" ]; then
	mv tests/RoboFile.ini.dist tests/RoboFile.ini # Create Robo Config file.
	mv tests/acceptance.suite.dist.yml tests/acceptance.suite.yml # Create travis system tests config file
	php vendor/bin/robo run:tests 1 # Run Acceptance test
	#- php vendor/bin/robo send:codeception-output-to-slack C02L0SE5E xoxp-2309442657-4789197868-4789233706-68cec7 # Send output to Slack.
else
	curl http://localhost
	curl http://localhost/tests/
	curl http://localhost/tests/joomla-cms3
	mv tests/RoboFile.ini.dist tests/RoboFile.ini # Create Robo Config file.
	mv tests/acceptance.suite.dist.yml tests/acceptance.suite.yml # Create travis system tests config file
	php vendor/bin/robo run:travis "${ACCEPTANCE}" # Run Acceptance test
	#- php vendor/bin/robo send:codeception-output-to-slack C02L0SE5E xoxp-2309442657-4789197868-4789233706-68cec7 # Send output to Slack.
fi