#!/bin/sh

USERNAME='dedipanel'
PASSWORD='dedipanel'
EXISTS=`id $USERNAME 2>/dev/null | grep uid | wc -l`

if [ "$1" = "install" ]; then
    sudo apt-get update
    sudo apt-get install -y sshpass

    cd ../
    composer self-udpate
    composer install --prefer-dist
elif [ "$1" = "configure" ]; then
    [ ! $EXISTS ] && sudo useradd -p `openssl passwd -1 $PASSWORD` $USERNAME;

    [ -f ./id_rsa ] && rm ./id_rsa;
    [ -f ./id_rsa.pub ] && rm ./id_rsa.pub;

    ssh-keygen -t rsa -N "" -f ./id_rsa
    sshpass -p "$PASSWORD" ssh-copy-id -i ./id_rsa.pub "$USERNAME@localhost"
elif [ "$1" = "cli-test" ]; then
    [ ! `ssh -i id_rsa "$USERNAME@localhost" "echo 1"` ] && exit 1
elif [ $EXISTS ]; then
    sudo userdel $USERNAME
fi
