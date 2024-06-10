#!/bin/bash

command=$1

if [ "$command" = "dev" ]; then
common_arguments="--style expanded --no-source-map --load-path ./ --watch"
else
common_arguments="--style expanded --no-source-map --load-path ./"
fi

machine=$(uname -s)
case "${machine}" in
CYGWIN*|MINGW*|MSYS*)   machine=Windows;;
*)                      machine=Other;;
esac

if [ "$machine" == "Windows" ]; then
sass_executable="sass.bat"
else
sass_executable="sass"
fi

${sass_executable} ${common_arguments} scss/bootstrap/:public/assets/css/ scss/finalium/:public/assets/css/ scss/biscuit/:public/assets/css/