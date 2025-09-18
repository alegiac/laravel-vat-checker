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

### Examples

UK (format validation only):

```php
$result = LaravelVatChecker::check('GB123456789');
// $result['isFormatted'] === true/false
```

Switzerland (format validation only, accepts separators and suffixes):

```php
$result = LaravelVatChecker::check('CHE-421.098.863 MWST');
// $result['isFormatted'] === true/false
```

Norway (format validation only, MOD11 check, optional MVA suffix):

```php
$result = LaravelVatChecker::check('NO999888777MVA');
// $result['isFormatted'] === true/false
```

Australia (format validation only, ABN 11 digits with checksum):

```php
$result = LaravelVatChecker::check('AU51824753556');
// $result['isFormatted'] === true/false
```

Canada (format validation only, BN 9 digits with optional RT0001):

```php
$result = LaravelVatChecker::check('CA123456789RT0001');
// $result['isFormatted'] === true/false
```

New Zealand (format validation only, GST 8–9 digits):

```php
$result = LaravelVatChecker::check('NZ123456789');
// $result['isFormatted'] === true/false
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
- GB: United Kingdom (format validation only)
- NO: Norway (format validation only)
 - AU: Australia (format validation only)
 - CA: Canada (format validation only)
 - NZ: New Zealand (format validation only)

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
