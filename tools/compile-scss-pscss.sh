#!/bin/sh

pscss_path="vendor/scssphp/scssphp/bin/pscss"

# "Compressed" style gives lowest filesize
# Load path is assuming you're running this script from the root of the sB site directory
common_arguments="--style=compressed"

echo "Compiling Finalium CSS"
php ${pscss_path} ${common_arguments} assets/scss/style.scss > assets/css/style.css
echo "Compiling Finalium Dark CSS"
php ${pscss_path} ${common_arguments} assets/scss/darkmode.scss > assets/css/darkmode.css
