#!/bin/sh
ssh -t -t root@172.17.0.2 <<EOF 
    cd /var/www/localhost/htdocs/jenkinsci
    #git pull origin master    
    timeStamp=$(date +%s)
    randomHash=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 32 | head -n 1)
    uniqueId="$timeStamp$randomHash"
    fileExt=".txt"
    uniqueFileName=$uniqueId$fileExt

    echo $uniqueFileName
    touch $uniqueFileName
    find . -name \*.php -exec php -l {} \; | grep -v 'No syntax errors' > $uniqueFileName
    noOfLine=$(wc -l < $uniqueFileName)                                         
   
    echo $noOfLine
    if [ $? -eq 1 && $noOfLine -gt 0]
    then
        echo "Unable to build."
        exit 1
    else
        echo "Successfully built."
        exit 0
    fi
EOF