## Installation

After cloning the project first install the dependencies with
```sh
composer install
```
Rename the .env.example file in the root directory to .env and edit it to connect your database

Then proceed to migrate and seed the database with
```sh
php artisan migrate --seed
```
Get the app encryption key with
```sh
php artisan key:generate
```
The application can then be run by using
```sh
php artisan serve
```

## Testing
To ensure the app is working as intended, we can run tests by using
```sh
vendor/bin/phpunit
```
