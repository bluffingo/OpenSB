#!/bin/sh

common_arguments="--style expanded --no-source-map --load-path ./"

# did git forget to pull this to prod??

sass ${common_arguments} biscuit/stylesheets/:public/img/css/