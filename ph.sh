#!/bin/bash
timeStamp=$(date +%s)
randomHash=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 32 | head -n 1)
uniqueId=$timeStamp$randomHash
fileExt=".txt"
uniqueFileName=$uniqueId$fileExt

echo $uniqueFileName
touch $uniqueFileName
find . -name \*.php -exec php -l {} \; | grep -v 'No syntax errors' > $uniqueFileName
noOfLine=$(wc -l < $uniqueFileName)
$noOfLine
