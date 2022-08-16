#!/bin/sh

# "Compressed" style gives lowest filesize
# Load path is assuming you're running this script from the root of the sB site directory
common_arguments="--style compressed --no-source-map --load-path ./ --watch"

sass ${common_arguments} assets/scss/:assets/css/