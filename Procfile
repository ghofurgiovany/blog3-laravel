web: vendor/bin/heroku-php-apache2 public/
queue: php artisan queue:restart && php artisan queue:work --tries=0 --daemon
schedule: php artisan migrate --force && php artisan schedule:work