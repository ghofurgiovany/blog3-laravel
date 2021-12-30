web: vendor/bin/heroku-php-apache2 public/
queue: php artisan queue:restart && php artisan queue:work
schedule: php artisan migrate --force && php artisan schedule:work