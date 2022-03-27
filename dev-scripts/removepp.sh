#!/bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
CDIR=$( pwd )
cd $DIR/../

docker-compose exec wordpress  bash -c "wp plugin deactivate password-protected" 

cd $CDIR
