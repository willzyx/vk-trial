#!/bin/sh

ROOT_PATH=$(cd $(dirname $0) && pwd)

rsync -rvpcz ${ROOT_PATH}/main/ 52.17.93.217:/var/www/vk-trial/
