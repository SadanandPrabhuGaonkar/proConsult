#!/bin/bash
#to make this work, run "chmod 755 install_package.sh"
#change theme to your project name
cd application/themes/theme && npm install $1 $2

##TO RUN:
#./install_package.sh <package_name> <--save || --save-dev>