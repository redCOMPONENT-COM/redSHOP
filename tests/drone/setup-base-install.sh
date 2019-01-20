#!/bin/bash

tests_db=$1
tests_suite=$2
php_versions=$3

for php_version in ${php_versions//,/ }
do
	# Waiting for setup to be done
	while [[ ! -f tests/.setup$php_version.tmp ]]
	do
		sleep 1
	done

	# Final DB dump with full Joomla/extension setup
	mv tests/$tests_suite$php_version/joomla-cms/configuration.php tests/$tests_suite$php_version/joomla-cms/configuration$php_version.php
	sed -i "s/db = '$tests_db$php_version'/db = 'tests_db'/g" tests/$tests_suite$php_version/joomla-cms/configuration$php_version.php
	sed -i "s,$tests_suite$php_version/joomla-cms/,joomla-cms/,g" tests/$tests_suite$php_version/joomla-cms/configuration$php_version.php
	tar -C tests/$tests_suite$php_version/joomla-cms/ -cf tests/joomla-cms$php_version.tar .
	mv tests/$tests_suite$php_version/joomla-cms/configuration$php_version.php tests/$tests_suite$php_version/joomla-cms/configuration.php
	mysqldump -u root -proot -h db $tests_db$php_version > tests/dbdump$php_version.sql.tmp
done