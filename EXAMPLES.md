# Laravel VAT Checker - Usage Examples

## Basic Usage (Backward Compatible)

The package maintains full backward compatibility with the existing API:

```php
use Alegiac\LaravelVatChecker\LaravelVatChecker;

$checker = new LaravelVatChecker();
$result = $checker->check('IT12345678901');

// Result structure remains the same:
// [
//     'isFormatted' => true,
//     'isVies' => true,
//     'details' => [...]
// ]
```

## Using with Laravel Facade

```php
use LaravelVatChecker;

$result = LaravelVatChecker::check('DE123456789');
```

## Adding Custom Validators for Non-EU Countries

### 1. Create a Custom Validator

```php
use Alegiac\LaravelVatChecker\Contracts\VatValidatorInterface;

class CustomCountryVatValidator implements VatValidatorInterface
{
    public function supports(string $countryCode): bool
    {
        return $countryCode === 'XX'; // Your country code
    }

    public function validateFormat(string $vatNumber): bool
    {
        // Implement your country's VAT validation logic
        return preg_match('/^XX\d{9}$/', $vatNumber) > 0;
    }

    public function validateExternal(string $vatNumber): array
    {
        // Integrate with your country's tax authority API
        return [
            'valid' => true,
            'countryCode' => 'XX',
            'vatNumber' => $vatNumber,
            'name' => 'Company Name',
            'address' => 'Company Address',
        ];
    }

    public function extractCountryCode(string $vatNumber): string
    {
        return 'XX';
    }

    public function cleanVatNumber(string $vatNumber): string
    {
        return strtoupper(trim($vatNumber));
    }
}
```

### 2. Register the Custom Validator

```php
use Alegiac\LaravelVatChecker\LaravelVatChecker;
use Alegiac\LaravelVatChecker\Validators\CustomCountryVatValidator;

$checker = new LaravelVatChecker();
$checker->registerValidator(new CustomCountryVatValidator());

// Now you can validate your country's VAT numbers
$result = $checker->check('XX123456789');
```

### 3. Using with Laravel Service Container

In your `AppServiceProvider`:

```php
use Alegiac\LaravelVatChecker\Contracts\VatValidatorFactoryInterface;
use Alegiac\LaravelVatChecker\Validators\CustomCountryVatValidator;

public function boot()
{
    $factory = app(VatValidatorFactoryInterface::class);
    $factory->registerValidator(new CustomCountryVatValidator());
}
```

## Advanced Usage

### Get Supported Countries

```php
$checker = new LaravelVatChecker();
$countries = $checker->getSupportedCountries();
// Returns: ['AT', 'BE', 'BG', 'CY', 'CZ', 'DE', ...]
```

### Check if Country is Supported

```php
$checker = new LaravelVatChecker();
$isSupported = $checker->isCountrySupported('IT'); // true
$isSupported = $checker->isCountrySupported('XX'); // false
```

### Dependency Injection

```php
use Alegiac\LaravelVatChecker\Contracts\VatValidatorFactoryInterface;
use Alegiac\LaravelVatChecker\Contracts\VatResponseInterface;

class MyService
{
    public function __construct(
        private VatValidatorFactoryInterface $validatorFactory,
        private VatResponseInterface $response
    ) {}

    public function validateVat(string $vatNumber): array
    {
        // Custom validation logic using injected dependencies
    }
}
```

## Architecture Benefits

1. **SOLID Principles**: Each class has a single responsibility
2. **Open/Closed**: Easy to add new validators without modifying existing code
3. **Dependency Inversion**: Depends on abstractions, not concretions
4. **Interface Segregation**: Clean, focused interfaces
5. **DRY**: No code duplication between validators
6. **KISS**: Simple, straightforward implementation

## Migration from Old Version

No changes required! The existing API remains exactly the same:

```php
// This still works exactly as before
$result = LaravelVatChecker::check('IT12345678901');
```

The refactored code is fully backward compatible while providing a much more extensible and maintainable architecture.
