# Welcome to ELK AuditLog

An easy way to use the [official Elastic Search client](https://github.com/elastic/elasticsearch-php) for auditlog in your Laravel applications.

[comment]: <> ([![Build Status]&#40;https://github.com/hsnbd/auditlog/workflows/tests/badge.svg?branch=master&#41;]&#40;https://github.com/hsnbd/auditlog/actions&#41;)
[![Total Downloads](https://poser.pugx.org/hsnbd/auditlog/downloads.png)](https://packagist.org/packages/hsnbd/auditlog)
[![Latest Stable Version](https://poser.pugx.org/hsnbd/auditlog/v/stable.png)](https://packagist.org/packages/hsnbd/auditlog)
[![Latest Stable Version](https://poser.pugx.org/hsnbd/auditlog/v/unstable.png)](https://packagist.org/packages/hsnbd/auditlog)
[![License](https://img.shields.io/packagist/l/hsnbd/auditlog)](LICENSE.md)

- [Installation](#installation)
- [Usage](#usage)
- [Available Commands](#available-commands)
- [Contributions and Support](#contributions-and-support)
- [Author](#author)
- [License](#license)


## Installation
Install the current version of the `hsnbd/auditlog` package via composer:

```sh
composer require hsnbd/auditlog
```

After install, Laravel will automatically register The package's service provider.

Publish the configuration file:

```sh
php artisan vendor:publish --provider="Hsnbd\AuditLogger\AuditLoggerServiceProvider"
```

## Usage
`hsnbd/auditlog` use laravel default queue.

```php artisan queue:table```

```php artisan migrate```

The `AuditLog` facade is used for logging. In order to use that.
```php
\Hsnbd\AuditLogger\AuditLog::info('Hello World');
```

```php
\Hsnbd\AuditLogger\AuditLog::debug('Hello World');
```


This command must run always.
```php artisan queue:work database --queue=listeners```

## Available Commands
Some useful console commands.

For Bootstrap basic setup for ELK stack
```sh
php artisan auditlog:bootstrap
```

Test auditlog if it is working or not.
```sh
php artisan auditlog:test
```

## Contributions and Support
Thanks to [everyone](https://github.com/hsnbd/auditlog/graphs/contributors)
who has contributed to this project!

Please see [CONTRIBUTING.md](CONTRIBUTING.md) to contribute.

If you found any bugs, Please report it using [Github](https://github.com/hsnbd/auditlog)

## Author
[Baker Hasan](http://hsnbd.github.io) :email: [Email Me](mailto:hasanbd666@gmail.com)

## License
This project is licensed under the MIT License - see the [License File](LICENSE.md).
