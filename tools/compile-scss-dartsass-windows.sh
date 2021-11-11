#!/bin/sh

# if you wonder what the fuck this is for, it's for me.
# -gamerappa 11/10/2021

# "Compressed" style gives lowest filesize
# Load path is assuming you're running this script from the root of the sB site directory
common_arguments="--style compressed --no-source-map --load-path ./ --watch"

sass.bat ${common_arguments} assets/scss/:assets/css/