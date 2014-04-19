#!/bin/bash

USER="dedipanel"
PASSWD="dedipanel"
DIR=$(dirname $(readlink -f $0))

case "$1" in
    configure)
        sudo adduser --disabled-password --gecos "" $USER || exit 1
        echo "$USER:$PASSWD" | sudo chpasswd || exit 1
        umask 077 || exit 1
        test -d /home/$USER/.ssh || sh -c 'sudo mkdir -p /home/$USER/.ssh || exit 1'
        sudo sh -c "< $DIR/id_rsa.pub cat >> /home/$USER/.ssh/authorized_keys" || exit 1
        sudo chown -R $USER:$USER /home/$USER/.ssh/ && sudo chmod -R 700 /home/$USER/.ssh/ || exit 1
    ;;

    clean)
        if [ `grep "$USER" /etc/passwd | wc -l` -eq 1 ]; then
            sudo deluser $USER || exit 1
        fi

        if [ -d /home/$USER/ ]; then
            sudo rm -Rf /home/$USER/ || exit
        fi
    ;;

    test)
        chmod 600 $DIR/id_rsa $DIR/id_rsa.pub
        ssh -o PasswordAuthentication=no -o KbdInteractiveAuthentication=no \
            -o ChallengeResponseAuthentication=no \
            -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no \
            -i $DIR/id_rsa 2>/dev/null \
            $USER@localhost "echo '[OK]'" || sh -c "echo '[KO]' && exit 1"
    ;;

    *)
        echo "Usage: $0 [configure|clean]"
    ;;
esac
