# RocketFirm Engine

RocketFirm Yii2 Engine - is a core of websites, created in RocketFirm

## Installation

Installation should be automatically done, when you run ```composer install``` command ([get composer](http://getcomposer.org))
within Rocket site template.

Another way to install RocketEngine is to require this package from console

```BASH
$ composer require rocketfirm/engine
```

## Running migrations

Configure your database and run this package's migrations, before running your project's migrations
```BASH
$ php yii migrate --migrationPath=@vendor/rocketfirm/engine/migrations
```
