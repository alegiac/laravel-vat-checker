<?php

namespace Alegiac\LaravelVatChecker\Contracts;

interface VatValidatorFactoryInterface
{
    /**
     * Create a validator for the given country code
     */
    public function createValidator(string $countryCode): ?VatValidatorInterface;

    /**
     * Get all supported country codes
     */
    public function getSupportedCountries(): array;

    /**
     * Check if a country is supported
     */
    public function isCountrySupported(string $countryCode): bool;
}
