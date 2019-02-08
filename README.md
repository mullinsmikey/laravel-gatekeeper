# Laravel GateKeeper

Protect pages from access with a universal username/password

[![Top Languages](https://img.shields.io/github/languages/top/mullinsmikey/laravel-gatekeeper.svg?style=flat)](../../search?l=php)
[![Software License](https://img.shields.io/badge/license-MIT-green.svg?style=flat)](LICENSE.md)
[![Last Commit](https://img.shields.io/github/last-commit/mullinsmikey/laravel-gatekeeper.svg?style=flat)](../../commits)

## Requirements

`Composer`
`Laravel >= 5.5`

## Installation

via Composer:

```bash
composer require mullinsmikey/laravel-gatekeeper
```

Register the middleware like this:

```php
// app/Http/Kernel.php

protected $middlewareGroups = [
    'web' => [
        ...
        \Muffin\GateKeeper\Http\Middleware\AuthMiddleware::class,
    ],
    ...
];
```

or add to specific protected routes.

## Quickstart

You'll need a blade at root `resources/views/login.blade.php` with a login form:

```
method – GET
fields – username, password, {{ csrf_field() }}
```

Default credentials are `username` & `password`

## Customization

You may change default settings by specifying the following params in your `.env` file:

```
GATE_USER – desired username
GATE_PASS – desired password
GATE_VIEW – custom login blade path
GATE_TIME – cookie lifetime in seconds
```

## Credits

- [Spatie](https://github.com/spatie/laravel-littlegatekeeper)
- [Elic dev](https://github.com/elic-dev/laravel-site-protection)

### License

The MIT License (MIT). See the [License file](LICENSE.md) for more information.