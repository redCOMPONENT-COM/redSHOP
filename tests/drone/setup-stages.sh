#!/bin/bash

tests_db=$1
tests_suite=$2
php_versions=$3

for php_version in ${php_versions//,/ }
do
	# Prepares and restores DB
	mysql -u root -proot -h db -e "CREATE DATABASE $tests_db$php_version"
	mysql -u root -proot -h db -U $tests_db$php_version < tests/dbdump$php_version.sql.tmp

	# Creating clone of Joomla site
	mkdir -p tests/$tests_suite$php_version/joomla-cms
	tar xf tests/joomla-cms$php_version.tar -C tests/$tests_suite$php_version/joomla-cms/
	mv tests/$tests_suite$php_version/joomla-cms/configuration$php_version.php tests/$tests_suite$php_version/joomla-cms/configuration.php
	sed -i "s/db = 'tests_db'/db = '$tests_db$php_version'/g" tests/$tests_suite$php_version/joomla-cms/configuration.php
	sed -i "s,joomla-cms$php_version/,$tests_suite$php_version/joomla-cms/,g" tests/$tests_suite$php_version/joomla-cms/configuration.php
	touch tests/.cache.setup.$tests_suite$php_version.tmp
done