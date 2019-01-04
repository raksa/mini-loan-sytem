# Loan System

Mini Loan system pratice in Laravel

https://en.wikipedia.org/wiki/Loan

## Features

* Laravel 5.7
* Separate modules
* API
* Hash base authentication once
* Unit test
* Laravel horizon
* Laravel passport
* Laravel telescope
* Laravel custom artisan command
* Laravel queue jobs
* Custom artisan command

## Contributors

* Autor : raksa <eng.raksa@gmail.com>

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

## Run test cases
* `$ composer run test`

## Update project via loan:update
* `$ php artisan loan:update`

## Configuration

All required environment variables can be found in .env.example

## License

MIT
