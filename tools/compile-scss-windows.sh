#!/bin/sh

command=$1

if [ "$command" = "dev" ]; then
common_arguments="--style expanded --no-source-map --load-path ./ --watch"
else
common_arguments="--style expanded --no-source-map --load-path ./"
fi

sass_executable="sass.bat"

${sass_executable} ${common_arguments} biscuit/stylesheets/:public/img/css/