## Loan System

## Contributors

* raksa <eng.raksa@gmail.com>

## Install Dependencies for Development
* `$ composer install`

## Install Dependencies for Production
* `$ composer install --no-dev`

## Migrate Database for very first time (after create database time)
attention: this will remove everything in database
* `$ php artisan migrate:fresh --seed`

## Migrate Database for updating and apply change
this can be run every updating code time
* `$ php artisan migrate`

## Configuration

All required environment variables can be found in .env.example
