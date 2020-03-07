#!/bin/sh

echo "Checkout develop...";
git checkout develop -q
echo "Fetch news...";
git fetch -q
echo "See if has news..";
UPSTREAM=${1:-'@{u}'}
LOCAL=$(git rev-parse @)
REMOTE=$(git rev-parse "$UPSTREAM")
BASE=$(git merge-base @ "$UPSTREAM")

if [ $LOCAL = $REMOTE ]; then
    echo "Develop is up-to-date no need to scan"
elif [ $LOCAL = $BASE ]; then
    echo "Need to pull. Pulling..."
    git pull
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

elif [ $REMOTE = $BASE ]; then
    echo "Need to push! No way to Scan."
else
    echo "Diverged! No way to Scan."
fi
