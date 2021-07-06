#!/bin/sh

# "Compressed" style gives lowest filesize
# Load path is assuming you're running this script from the root of the sB site directory
common_arguments="--style compressed --load-path ./"

sassc ${common_arguments} bootstrap/bs.scss assets/bs.css
sassc ${common_arguments} bootstrap/bs-dark.scss assets/bs-dark.css