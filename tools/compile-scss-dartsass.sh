#!/bin/sh

# "Compressed" style gives lowest filesize
# Load path is assuming you're running this script from the root of the sB site directory
common_arguments="--style compressed --load-path ./"

sass ${common_arguments} bootstrap/bs.scss:assets/bs.css bootstrap/bs-dark.scss:assets/bs-dark.css bootstrap/bs-finalium.scss:assets/bs-finalium.css