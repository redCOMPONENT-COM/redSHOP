#!/bin/bash
# Script for preparing the system tests in Joomla!

# Get Chrome Headless
mkdir -p /usr/local/bin
unzip -o "chromedriver_linux64.zip" -d /usr/local/bin
chmod +x /usr/local/bin/chromedriver

export CI_BUILD_DIR=$(pwd)
cd ${CI_BUILD_DIR}
vendor/bin/robo run:tests-drone $1

if [ $? -eq 0 ]
then
  echo "Tests Runs were successful"
  rm -r tests/_output/
  exit 0
else
  echo "Tests Runs Failed" >&2
  #send screenshot of failed test to Slack
  vendor/bin/robo send:build-report-error-slack $CLOUD_NAME $API_KEY $API_SECRET $GITHUB_REPO $DRONE_PULL_REQUEST $SLACK_WEBHOOK $SLACK_CHANNEL $DRONE_BUILD_LINK
  rm -r tests/_output/
  cd ../
  exit 1
fi