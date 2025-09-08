<?php

namespace Alegiac\LaravelVatChecker\Contracts;

/**
 * Contract for obtaining validators for specific countries.
 */
interface VatValidatorFactoryInterface
{
    /**
     * Create a validator for the given country code.
     *
     * @param string $countryCode Two-letter ISO country code
     * @return VatValidatorInterface|null Null when unsupported
     */
    public function createValidator(string $countryCode): ?VatValidatorInterface;

    /**
     * Get all supported country codes.
     *
     * @return array
     */
    public function getSupportedCountries(): array;

    /**
     * Check if a country is supported.
     *
     * @param string $countryCode
     * @return bool
     */
    public function isCountrySupported(string $countryCode): bool;
}
