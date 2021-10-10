#!/bin/sh

# "Compressed" style gives lowest filesize
# Load path is assuming you're running this script from the root of the sB site directory
common_arguments="--style compressed --load-path ./"

sassc ${common_arguments} assets/scss/style.scss assets/css/style.css
sassc ${common_arguments} assets/scss/darkmode.scss assets/css/darkmode.css
sassc ${common_arguments} bootstrap/bs.scss assets/bs.css
sassc ${common_arguments} bootstrap/bs-dark.scss assets/bs-dark.css
sassc ${common_arguments} bootstrap/bs-finalium.scss assets/bs-finalium.css
sassc ${common_arguments} bootstrap/bs-finalium-dark.scss assets/bs-finalium-dark.css
sassc ${common_arguments} bootstrap/bs-vanilla.scss assets/bs-vanilla.css