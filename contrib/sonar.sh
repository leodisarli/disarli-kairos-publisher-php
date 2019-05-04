#!/bin/sh

echo "Running Tests..."
./vendor/bin/phpunit --coverage-html coverage --coverage-clover coverage/coverage.xml --log-junit coverage/junit.xml
echo "Searching and replace paths on coverage files..."
BASEDIR=$(cd $(dirname "$1") && pwd -P)/$(basename "$1")
CHANGEDIR="./"
sed -i 's!'$BASEDIR'!'$CHANGEDIR'!g' ./coverage/coverage.xml
sed -i 's!'$BASEDIR'!'$CHANGEDIR'!g' ./coverage/junit.xml
echo "Removing docker Sonar image..."
docker rm sonar-scanner
echo "Running Sonar..."
docker run --name sonar-scanner --link sonarqube -i -v $(pwd):/root/src newtmitch/sonar-scanner
