#!/bin/sh
if [ -e /usr/share/nginx/html/index.html ]; then
    rm /usr/share/nginx/html/*
fi
if [ -e /etc/nginx/nginx.conf ]; then
    rm /etc/nginx/nginx.conf
fi