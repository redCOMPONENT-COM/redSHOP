#!/bin/bash
set -ev

if [ "${ACCEPTANCE}" = "false" ]; then
	# Only run PHPCS on PHP 7.0
	if [ "$(phpenv version-name)" = "7.0" ]; then
		php tests/checkers/debugcode.php # Check missed debug code
		php tests/checkers/phppec.php # Check PHP Parse
		php tests/checkers/phpcs.php # Check PHP Codestyle.
	fi
elif [ "${ACCEPTANCE}" = "true" ]; then
	mv tests/acceptance.suite.dist.yml tests/acceptance.suite.yml # Create travis system tests config file
	php vendor/bin/robo run:tests 1 # Run Acceptance test
	#- php vendor/bin/robo send:codeception-output-to-slack C02L0SE5E xoxp-2309442657-4789197868-4789233706-68cec7 # Send output to Slack.
else
	mv tests/acceptance.suite.dist.yml tests/acceptance.suite.yml # Create travis system tests config file
	php vendor/bin/robo run:travis "${ACCEPTANCE}" # Run Acceptance test
	#- php vendor/bin/robo send:codeception-output-to-slack C02L0SE5E xoxp-2309442657-4789197868-4789233706-68cec7 # Send output to Slack.
fi