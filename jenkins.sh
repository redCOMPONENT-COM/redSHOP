#!/bin/bash

CWD=$(pwd)

cp -f build.properties.dist build.properties
if [ dist ]; then
  rm -rf dist
fi
mkdir dist
sed -i "s~www.dir=~www.dir=$CWD~g" build.properties
sed -i "s~package.dir=~package.dir=$CWD/dist~g" build.properties
