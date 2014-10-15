#!/bin/bash

if [ -z $1 ];
then
  echo "You have to pass rsync destination folder (es. user@host:/usr/share/amule/webserver/mobileMule/"
  exit 1
fi

DEST=$1

# script working directory
WORKING_DIR=$(dirname $0)

echo "Push mobileMule to $DEST"
rsync -av --del $WORKING_DIR $DEST