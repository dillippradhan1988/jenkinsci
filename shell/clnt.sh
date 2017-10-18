#!/bin/sh
ssh -t -t root@172.17.0.2 <<EOF 
    cd /var/www/localhost/htdocs/jenkinsci
    #git pull origin master    
    touch phpError.txt    
    tt="$(find . -name \*.php -exec php -l {} \; | grep -v 'No syntax errors'  > /dev/null)"
    noOfLine=$(wc -l < phpError.txt)                                             
   
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