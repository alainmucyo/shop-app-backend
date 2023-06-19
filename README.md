# Online Shopping Platform

The Online Shopping Platform is a robust online store designed to track which items are added to a basket but removed before checkout. This data is beneficial for the sales team to offer targeted discounts. The application is built with Laravel 8 and MySQL.


---

## Getting Started

1. git clone this repository & cd into the project directory

## Pre-requisites

* PHP 8.2 or greater
* Laravel 8
* MySQL
* Docker (optional)
* Composer

## Installation

* Install [PHP](https://www.php.net/manual/en/install.php) if you don't have it installed.
* Install [Composer](https://getcomposer.org/doc/00-intro.md) if you don't have it installed.
* Install [MySQL](https://dev.mysql.com/doc/mysql-installation-excerpt/5.7/en/)
* Install [Docker](https://docs.docker.com/get-docker/) and [Docker Compose](https://docs.docker.com/compose/install/) (optional)
### Configuring the Application

1. Copy `.env.example` to `.env` in the project root
2. Set your database credentials in the `.env` file.
3. Run `composer install` to install PHP dependencies.
4. Run `php artisan key:generate` to generate your application key.
5. Run `php artisan migrate` to run the database migrations.
6. Run `php artisan db:seed` to seed the database with test data.

## Running the Application

1. Run `php artisan serve` to start the server. The API will be running at `http://localhost:8000`.

### Running the Tests

1. Set your testing database credentials in the `.env.testing` file.
2. Run `php artisan migrate --env=testing` to run the database migrations for testing.
3. Run `php artisan test` to run the tests.
## Deployment

The application is designed to be easily deployed to Fly.io. The `Dockerfile` provided is tailored for Fly.io deployment. Follow the instructions in the official [Fly.io documentation](https://fly.io/docs/getting-started/).

You can access the deployed application at [https://alainmucyo-shop.fly.dev](https://alainmucyo-shop.fly.dev).

## Built With

- PHP 8.2
- Laravel 8
- MySQL
- Docker

## Authors

- **Alain MUCYO** - [Github Profile](https://github.com/alainmucyo)

## Licence

This software is published under the [MIT licence](http://opensource.org/licenses/MIT).
