#!/bin/bash

tests_db=$1
tests_suite=$2

# Prepares and restores DB
mysql -u root -proot -h db -e "CREATE DATABASE $tests_db"
mysql -u root -proot -h db -U $tests_db < tests/dbdump.sql.tmp

# Creating clone of Joomla site
mkdir -p tests/$tests_suite/joomla-cms
rsync -a tests/joomla-cms/ tests/$tests_suite/joomla-cms
sed -i "s/db = 'tests_db'/db = '$tests_db'/g" tests/$tests_suite/joomla-cms/configuration.php
sed -i "s,joomla-cms/,$tests_suite/joomla-cms/,g" tests/$tests_suite/joomla-cms/configuration.php
touch tests/.cache.setup.$tests_suite.tmp