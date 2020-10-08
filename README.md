# API demo for GoWorkaBit

This is a demo API application for GoWorkaBit using Laravel, Passport and JSON:API

## Setup development environment

[Laravel Homestead](https://laravel.com/docs/8.x/homestead) is used for development and we assume you have [Vagrant](https://www.vagrantup.com/) already installed.
> If you do not wish to use homestead see **Quick and dirty demo environment** section below

After cloning and changing into the projects directory run these setup commands
``` shell script
composer install
cp .env.example .env
php vendor/bin/homestead make
vagrant up
```
> It's recommend to use [vagrant-hostmanager](https://github.com/devopsgroup-io/vagrant-hostmanager) plugin to manage your hosts file, 
> otherwise please add `goworkabit-api-demo.local` to your hosts file manually.
> Unless changed in `Homestead.yaml` the ip defaults to `192.168.10.10`

Voila, you can access the API at http://goworkabit-api-demo.local

## Quick and dirty demo environment

``` shell script
composer install
cp .env.example.sqlite .env
php artisan app:init
php artisan serve
```

Voila, you can access the API at http://localhost:8000.

## Run automated tests
You can run automated [PHPUnit tests](https://phpunit.de/) with following command
``` shell script
php artisan test
```
> You can also call PHPUnit directly via `php vendor/bin/phpunit`

## Generate tokens for demo
I've created a command to easily generate tokens for seeded demo users.
``` shell script
php artisan demo:tokens
```
> This will generate file DEMO_TOKENS.md containing generated tokens

## Autocompletion support for Laravel Facades and Factories

[barryvdh/laravel-ide-helper](https://packagist.org/packages/barryvdh/laravel-ide-helper) is used to generate helper files for IDEs to understand laravel facade and factory patterns.

To generate these files run following commands
```
php artisan ide-helper:generate
php artisan ide-helper:meta
```

