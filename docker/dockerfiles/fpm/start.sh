#! /bin/sh
crond
exec /usr/bin/supervisord -n -c /etc/supervisord.conf

