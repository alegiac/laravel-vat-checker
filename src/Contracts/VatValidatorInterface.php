<?php

namespace Alegiac\LaravelVatChecker\Contracts;

/**
 * Contract for VAT validators.
 *
 * Each implementation should support one or more countries and be able to
 * validate both format and external service responses for VAT numbers.
 */
interface VatValidatorInterface
{
    /**
     * Determine if the validator supports the given ISO country code.
     *
     * @param string $countryCode Two-letter ISO country code
     * @return bool
     */
    public function supports(string $countryCode): bool;

    /**
     * Validate the VAT number format.
     *
     * @param string $vatNumber Full VAT number including country prefix
     * @return bool
     */
    public function validateFormat(string $vatNumber): bool;

    /**
     * Validate the VAT number against external services.
     *
     * Must return a standardized payload including at least
     * - valid: bool
     * - isError: bool
     * - errorDescription: string|null
     * and any additional details from the external service.
     *
     * @param string $vatNumber Full VAT number including country prefix
     * @return array
     */
    public function validateExternal(string $vatNumber): array;

    /**
     * Extract the country code from a VAT number.
     *
     * @param string $vatNumber
     * @return string Two-letter ISO country code
     */
    public function extractCountryCode(string $vatNumber): string;

    /**
     * Clean and normalize the VAT number for processing.
     *
     * @param string $vatNumber
     * @return string Normalized VAT number
     */
    public function cleanVatNumber(string $vatNumber): string;
}
