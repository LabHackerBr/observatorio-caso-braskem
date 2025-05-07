#!/bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
CDIR=$( pwd )
cd $DIR/../themes
rm -f ../zips/observatorio-caso-braskem.zip
zip -r ../zips/observatorio-caso-braskem.zip observatorio-caso-braskem -x "observatorio-caso-braskem/node_modules/*" -x "observatorio-caso-braskem/library/blocks/v2/node_modules/*"
