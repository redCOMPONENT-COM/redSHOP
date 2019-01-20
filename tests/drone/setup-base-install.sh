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
	tar -C tests/$tests_suite$php_version/joomla-cms/ -cf tests/joomla-cms$php_version.tar .
	sed -i "s/db = '$tests_db$php_version'/db = 'tests_db'/g" tests/joomla-cms$php_version/configuration.php
	sed -i "s,$tests_suite$php_version/joomla-cms/,joomla-cms/,g" tests/joomla-cms$php_version/configuration.php
	mysqldump -u root -proot -h db $tests_db$php_version > tests/dbdump$php_version.sql.tmp
done
