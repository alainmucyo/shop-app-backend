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

## Installing

* Install [PHP](https://www.php.net/manual/en/install.php) if you don't have it installed.
* Install [Composer](https://getcomposer.org/doc/00-intro.md) if you don't have it installed.
* Install [MySQL](https://dev.mysql.com/doc/mysql-installation-excerpt/5.7/en/)
* Install [Docker](https://docs.docker.com/get-docker/) and [Docker Compose](https://docs.docker.com/compose/install/) (optional)

## Run the project

### Backend (Laravel API)

#### Locally with PHP Built-in Server

1. Run `composer install` to install dependencies.
2. Run `php artisan serve` to start the server. The API will be running at `http://localhost:8000`.

### To check if the API is up and running

Just call this endpoint: `http://localhost:8000`

## Deploying to Fly.io

The application is designed to be easily deployed to Fly.io. The `Dockerfile` provided is tailored for Fly.io deployment. Follow the instructions in the official [Fly.io documentation](https://fly.io/docs/getting-started/).

You can access the deployed application at https://alainmucyo-shop.fly.dev.

## Testing

### Backend

Run `php artisan test`

## Built With

* PHP 8.2
* Laravel 8
* MySQL
* Docker

## Authors

* **Your Name** - [Github Profile](https://github.com/yourgithubusername)

## Licence

This software is published under the [MIT licence](http://opensource.org/licenses/MIT).
