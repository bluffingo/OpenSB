#!/bin/sh

# "Compressed" style gives lowest filesize
# Load path is assuming you're running this script from the root of the sB site directory
common_arguments="--style compressed --no-source-map --load-path ./ --watch"

# did git forget to pull this to prod??

sass ${common_arguments} finalium/assets/stylesheets/:public/assets/css/