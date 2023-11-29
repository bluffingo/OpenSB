#!/bin/sh

common_arguments="--style expanded --no-source-map --load-path ./"

sass ${common_arguments} biscuit/stylesheets/:public/img/css/ bootstrap:public/img/css/