#!/bin/sh

a=0
# Loop until selenium server is available
printf 'Waiting Selenium Server to load\n'
until $(curl --output /dev/null --silent --head --fail http://localhost:4444/wd/hub); do
    printf '.'
    sleep 1
    if [ $a -ge 30 ]; then
    	echo "Selenium could not be launched"
        exit 1
    fi
    a=`expr $a + 1`
done
printf '\n'