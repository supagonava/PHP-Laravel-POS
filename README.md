composer install
yarn
yarn dev


cp /home/site/wwwroot/default.conf /etc/nginx/sites-available/default && cp /home/site/wwwroot/nginx.conf /etc/nginx/nginx.conf && service nginx reload
