## Installation

After cloning the project connect your database in the .env located in the root folder

Then proceed to migrate and seed the database with
```sh
php artisan migrate --seed
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
