#!/bin/sh 
 # Shell script to find out all the files under a directory and 
 #its subdirectories. This takes into consideration only those files
 #or directories which do not have spaces or newlines in their names 

rm /log/phperror.txt
DIR="app/Tnq/Todo"
list_files(){
	errorCnt=0
	if !(test -d $1) 
		then 
		#echo $1; 
		return errorCnt;
	fi

	cd $1
	#echo; echo `pwd`:; #Display Directory name

 	for i in *
 		do
 			if test -d $i #if dictionary
 			then 
				list_files $i #recursively list files
				cd ..
 			else
 				#echo $i; #Display File name
 				php -l $i | grep -v "No syntax errors" >> /log/phperror.txt
 				#echo $?				
			fi
 		done
}

if [ $# -eq 0 ]
then 
	list_files .
	nofoline=$(wc -l < /log/phperror.txt)
	echo $nofoline
	if [ $nofoline -ne 0 ]
	then
		exit 1
	else
		exit 0
	fi
	exit 0
fi

for i in $*
	do
		DIR=$1 
		list_files $DIR
	shift 1 #To read next directory/file name
done

#!/bin/bash
#timeStamp=$(date +%s)
#randomHash=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 32 | head -n 1)
#uniqueId=$timeStamp$randomHash
#fileExt=".txt"
#uniqueFileName=$uniqueId$fileExt

#echo $uniqueFileName
#touch $uniqueFileName
#find . -name \*.php -exec php -l {} \; | grep -v 'No syntax errors' > $uniqueFileName
#noOfLine=$(wc -l < $uniqueFileName)
#$noOfLine