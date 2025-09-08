# Laravel VAT Checker

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alegiac/laravel-vat-checker.svg?style=flat-square)](https://packagist.org/packages/alegiac/laravel-vat-checker)
[![Total Downloads](https://img.shields.io/packagist/dt/alegiac/laravel-vat-checker.svg?style=flat-square)](https://packagist.org/packages/alegiac/laravel-vat-checker)

Validator per Partite IVA europee con controllo formale e verifica VIES.

## Installazione

```bash
composer require alegiac/laravel-vat-checker
```

## Utilizzo

### Facade (Laravel)

```php
use LaravelVatChecker;

$result = LaravelVatChecker::check('IT12345678901');
// $result = [
//   'isFormatted' => bool,
//   'isVies' => bool,
//   'details' => array
// ];
```

### Classe

```php
use Alegiac\LaravelVatChecker\LaravelVatChecker;

$checker = new LaravelVatChecker();
$result = $checker->check('DE123456789');
```

## Paesi supportati (EU)
AT, BE, BG, CY, CZ, DE, DK, EE, EL, ES, FI, FR, HR, HU, IE, IT, LT, LU, LV, MT, NL, PL, PT, RO, SE, SI, SK

## Test

```bash
composer test
```

## Licenza
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
