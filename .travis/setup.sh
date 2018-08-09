set -ev

if [ "${ACCEPTANCE}" = "false" ]; then
	# Only run PHPCS on PHP 7.0
	if [ "$(phpenv version-name)" = "7.0" ]; then
		# Get redWEB Coding Standard
		git submodule add https://github.com/redCOMPONENT-COM/coding-standards tests/checkers/phpcs
		git submodule update --init --recursive
		cd tests/checkers/phpcs
		git checkout -b redweb-phpcs-2
		cd ../../..

		# Following line uses a bot account to authenticate in github and make composer stable and faster, see https://redweb.atlassian.net/wiki/pages/viewpage.action?pageId=46694753
		composer config -g github-oauth.github.com "${GITHUB_TOKEN}"
		composer global require hirak/prestissimo
		composer install --prefer-dist
		composer install --working-dir ./libraries/redshop --ansi
	fi
else
	sudo apt-get update
	sudo apt-get install apache2 libapache2-mod-fastcgi

	# enable php-fpm
	sudo cp ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.conf.default ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.conf

	# For PHP 7.0
	if [ -f ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.d/www.conf.default ]; then
		sudo cp ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.d/www.conf.default ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.d/www.conf
	fi;

	sudo a2enmod rewrite actions fastcgi alias
	echo "cgi.fix_pathinfo = 1" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini
	sudo sed -i -e "s,www-data,travis,g" /etc/apache2/envvars
	sudo chown -R travis:travis /var/lib/apache2/fastcgi
	~/.phpenv/versions/$(phpenv version-name)/sbin/php-fpm

	# configure apache virtual hosts
	sudo cp -f ./tests/travis-ci-apache.conf /etc/apache2/sites-available/000-default.conf
	sudo sed -e "s?%TRAVIS_BUILD_DIR%?$(pwd)?g" --in-place /etc/apache2/sites-available/000-default.conf
	sudo sed -e "s?%TRAVIS_BUILD_PHP%?$(phpenv version-name)?g" --in-place /etc/apache2/sites-available/000-default.conf
	sudo service apache2 restart


	# Get ChromeDriver for headless mode
	driverversion=$(curl https://chromedriver.storage.googleapis.com/LATEST_RELEASE)
	wget "https://chromedriver.storage.googleapis.com/$driverversion/chromedriver_linux64.zip"
	unzip "chromedriver_linux64.zip"
	mkdir -p /usr/local/bin
	sudo cp -a chromedriver /usr/local/bin
	sudo chmod +x /usr/local/bin/chromedriver

	# Install Gulp for Package Generation
	composer config -g github-oauth.github.com "${GITHUB_TOKEN}"
	npm install -g gulp > output.log 2>&1
	npm install > output.log 2>&1
	composer global require hirak/prestissimo

	cd libraries/redshop
	composer install --prefer-dist

	cd ../../plugins/redshop_pdf/tcpdf/helper
	composer install --prefer-dist

	cd ../../../..
	composer install --prefer-dist

	npm install
	mv gulp-config.sample.json gulp-config.json
	node_modules/.bin/gulp release --skip-version
fi