@echo off

:: "Compressed" style gives lowest filesize
:: Load path is assuming you're running this script from the root of the sB site directory
set common_arguments="--style=compressed"

sass %common_arguments% --watch assets/main.scss:assets/main.css