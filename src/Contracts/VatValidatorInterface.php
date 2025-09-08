<?php

namespace Alegiac\LaravelVatChecker\Contracts;

interface VatValidatorInterface
{
    /**
     * Check if the validator supports the given country code
     */
    public function supports(string $countryCode): bool;

    /**
     * Validate the VAT number format
     */
    public function validateFormat(string $vatNumber): bool;

    /**
     * Validate the VAT number against external services
     */
    public function validateExternal(string $vatNumber): array;

    /**
     * Get the country code from VAT number
     */
    public function extractCountryCode(string $vatNumber): string;

    /**
     * Clean and normalize the VAT number
     */
    public function cleanVatNumber(string $vatNumber): string;
}
