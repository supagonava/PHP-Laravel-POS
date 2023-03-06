#!/bin/bash
export NGINX_DOCUMENT_ROOT=$HOME/site/wwwroot/public &
export APACHE_DOCUMENT_ROOT=$HOME/site/wwwroot/public &
service nginx restart
