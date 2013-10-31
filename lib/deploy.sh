#!/bin/sh
old_dir=`pwd`
script_dir=$(dirname $0)
cd $script_dir

php vendor/dg/ftp-deployment/Deployment/deployment.php deployment.ini

cd $old_dir
