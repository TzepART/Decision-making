#!/usr/bin/env bash

Search_DIR=src/AppBundle/Services

#Needed files
file_names=file_names.txt
update_files=update_files.txt

find_pattern='namespace Controllers'
replace_pattern='namespace AppBundle\\Services'

check_file () {
if [ ! -e "$1" ] ; then
    touch "$1"
fi
}

check_file $file_names
check_file $update_files

#save list paths
find $Search_DIR -type f -maxdepth 3 > $file_names

#find matches
while read -r file_path
do
   echo "- "$file_path >> $update_files
   #replace old paths
   sed -i "s,$find_pattern,$replace_pattern,g" $file_path
done < "$file_names"

#delete unusing files
rm $file_names