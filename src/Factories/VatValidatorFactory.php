<?php

namespace Alegiac\LaravelVatChecker\Factories;

use Alegiac\LaravelVatChecker\Contracts\VatValidatorFactoryInterface;
use Alegiac\LaravelVatChecker\Contracts\VatValidatorInterface;
use Alegiac\LaravelVatChecker\Validators\EuVatValidator;

class VatValidatorFactory implements VatValidatorFactoryInterface
{
    private array $validators = [];

    public function __construct()
    {
        $this->registerDefaultValidators();
    }

    /**
     * Register a validator for specific country codes
     */
    public function registerValidator(VatValidatorInterface $validator): void
    {
        $this->validators[] = $validator;
    }

    public function createValidator(string $countryCode): ?VatValidatorInterface
    {
        foreach ($this->validators as $validator) {
            if ($validator->supports($countryCode)) {
                return $validator;
            }
        }

        return null;
    }

    public function getSupportedCountries(): array
    {
        $countries = [];
        foreach ($this->validators as $validator) {
            if ($validator instanceof EuVatValidator) {
                $countries = array_merge($countries, EuVatValidator::getSupportedCountries());
            }
        }
        return array_unique($countries);
    }

    public function isCountrySupported(string $countryCode): bool
    {
        return $this->createValidator($countryCode) !== null;
    }

    /**
     * Register default validators
     */
    private function registerDefaultValidators(): void
    {
        $this->registerValidator(new EuVatValidator());
    }
}
