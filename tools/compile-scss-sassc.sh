#!/bin/sh

# "Compressed" style gives lowest filesize
# Load path is assuming you're running this script from the root of the sB site directory
common_arguments="--style compressed --load-path ./"

sassc ${common_arguments} assets/scss/style.scss assets/css/style.css
sassc ${common_arguments} assets/scss/darkmode.scss assets/css/darkmode.css
