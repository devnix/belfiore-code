#!/bin/bash

docker run --rm -i -t -u $(id -u):$(id -g) --name=belfiore-code \
  -v $PWD:/var/www/html \
  belfiore-code bash