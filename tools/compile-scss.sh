#!/bin/bash

command=$1

common_arguments="--style=expanded --load-path=./scss"

if [ "$command" = "dev" ]; then
  common_arguments+=" --update"
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

# Execute Sass compilation
${sass_executable} ${common_arguments} \
  "scss/bootstrap/:public/assets/css/bootstrap" \
  "scss/finalium/:public/assets/css/finalium" \
  "scss/biscuit/:public/assets/css/biscuit"

# Check for errors
if [ $? -ne 0 ]; then
  echo "Error: Sass compilation failed."
  exit 1
fi
