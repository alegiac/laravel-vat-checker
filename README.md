# Laravel VAT Checker

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alegiac/laravel-vat-checker.svg?style=flat-square)](https://packagist.org/packages/alegiac/laravel-vat-checker)
[![Total Downloads](https://img.shields.io/packagist/dt/alegiac/laravel-vat-checker.svg?style=flat-square)](https://packagist.org/packages/alegiac/laravel-vat-checker)

European VAT validator for Laravel with format checks and VIES verification. Includes optional caching and email notifications on connection errors.

## Installation

```bash
composer require alegiac/laravel-vat-checker
```

## Usage

### Facade (Laravel)

```php
use LaravelVatChecker;

$result = LaravelVatChecker::check('IT12345678901');
// $result = [
//   'isFormatted' => bool,
//   'isVies' => bool,
//   'isError' => bool,
//   'errorDescription' => string|null,
//   'details' => array
// ];
```

### Class

```php
use Alegiac\LaravelVatChecker\LaravelVatChecker;

$checker = new LaravelVatChecker();
$result = $checker->check('DE123456789');
```

## Configuration (optional)

Publish config and views (for email notifications):

```bash
php artisan vendor:publish --tag=vat-checker-config
php artisan vendor:publish --tag=vat-checker-views
```

Environment variables:

```env
VAT_CHECKER_CACHE=true
VAT_CHECKER_CACHE_TTL=86400   # 0 = forever
VAT_CHECKER_NOTIFICATIONS=true
VAT_CHECKER_NOTIFICATIONS_MAIL=true
VAT_CHECKER_NOTIFICATIONS_MAIL_TO=ops@example.com
```

## Supported countries
- EU: AT, BE, BG, CY, CZ, DE, DK, EE, EL, ES, FI, FR, HR, HU, IE, IT, LT, LU, LV, MT, NL, PL, PT, RO, SE, SI, SK
- CH: Switzerland (format validation only)

## Testing

```bash
composer test
```

## License
MIT

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email alessandro.giacomella@gmail.com instead of using the issue tracker.

## Credits

-   [Alessandro Giacomella](https://github.com/alegiac)
-   [Stefano Gallina](https://github.com/tuungsteno)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
