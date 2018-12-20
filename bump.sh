#!/bin/bash
echo "----------------------"
REDSHOPVERSION=$1
if [ "$REDSHOPVERSION" = "" ]; then
	echo 'Missing version. Usage: bump.sh {version}'
else
	echo "redSHOP Release Version: $REDSHOPVERSION"

	FILES=$(find ./component/ ./libraries/ ./modules/ ./plugins/ -not -path '*/vendor/*' -name '*.php')

	for f in $FILES
	do
		sed -i "s/__DEPLOY_VERSION__/$REDSHOPVERSION/g" $f
	done
fi


