#!/bin/sh

pscss_path="vendor/scssphp/scssphp/bin/pscss"

# "Compressed" style gives lowest filesize
# Load path is assuming you're running this script from the root of the sB site directory
common_arguments="--style=compressed"

echo "Compiling Classicish Light"
php ${pscss_path} ${common_arguments} bootstrap/bs.scss > assets/bs.css
echo "Compiling Classicish Dark"
php ${pscss_path} ${common_arguments} bootstrap/bs-dark.scss > assets/bs-dark.css
echo "Compiling Finalium Light"
php ${pscss_path} ${common_arguments} bootstrap/bs-finalium.scss > assets/bs-finalium.css
echo "Compiling Finalium Dark"
php ${pscss_path} ${common_arguments} bootstrap/bs-finalium-dark.scss > assets/bs-finalium-dark.css
echo "Compiling Customized Vanilla"
php ${pscss_path} ${common_arguments} bootstrap/bs-vanilla.scss > assets/bs-vanilla.css