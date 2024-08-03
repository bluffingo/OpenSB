#!/bin/bash

command=$1

common_arguments="--style expanded --no-source-map --load-path=./scss"

if [ "$command" = "dev" ]; then
  common_arguments+=" --watch"
fi

machine=$(uname -s)
case "${machine}" in
  CYGWIN*|MINGW*|MSYS*) machine=Windows;;
  *)                    machine=Other;;
esac

if [ "$machine" == "Windows" ]; then
  sass_executable="sass.bat"
else
  sass_executable="sass"
fi

if [ ! -d "scss" ]; then
  echo "Error: 'scss' directory not found. Make sure you are running this script from the correct location."
  exit 1
fi

if [ ! -d "public/assets/css" ]; then
  mkdir -p public/assets/css || { echo "Error: Failed to create public/assets/css directory"; exit 1; }
fi

${sass_executable} ${common_arguments} \
  "scss/bootstrap/:public/assets/css" \
  "scss/finalium/:public/assets/css" \
  "scss/biscuit/:public/assets/css" \
  "scss/poktube/:public/assets/css"