#!/bin/bash
set -ev

#echo "$HOME/$TRAVIS_PULL_REQUEST-$TRAVIS_COMMIT.zip"
# Install composer dependency
composer config -g github-oauth.github.com "${GITHUB_TOKEN}"
composer global require hirak/prestissimo
composer install --working-dir ./libraries/redshop --ansi
composer install --working-dir ./plugins/redshop_pdf/tcpdf/helper --ansi

# Create redshop package
npm install
mv gulp-config.sample.json gulp-config.json
node_modules/.bin/gulp release --skip-version