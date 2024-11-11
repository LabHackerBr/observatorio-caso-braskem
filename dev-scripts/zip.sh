#!/bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
CDIR=$( pwd )
cd $DIR/../themes
rm -f ../zips/hacklab-theme.zip
zip -r ../zips/hacklab-theme.zip hacklab-theme -x "hacklab-theme/node_modules/*" -x "hacklab-theme/library/blocks/v2/node_modules/*"
