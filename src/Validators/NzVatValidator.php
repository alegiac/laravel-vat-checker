<?php

namespace Alegiac\LaravelVatChecker\Validators;

use Alegiac\LaravelVatChecker\Contracts\VatValidatorInterface;

/**
 * Validator for New Zealand (NZ) GST number.
 * Common format: 9 digits (older) or 8-9 digits; various checks exist, but we apply
 * a relaxed digit-length rule with optional separators.
 */
class NzVatValidator implements VatValidatorInterface
{
    /** @inheritDoc */
    public function supports(string $countryCode): bool
    {
        return strtoupper($countryCode) === 'NZ';
    }

    /** @inheritDoc */
    public function validateFormat(string $vatNumber): bool
    {
        $n = preg_replace('/[^\d]/', '', substr(strtoupper(trim($vatNumber)), 2));
        // Accept 8 or 9 digits (note: full NZ algorithm is complex; keep formal check)
        return (bool) preg_match('/^\d{8,9}$/', $n);
    }

    /** @inheritDoc */
    public function validateExternal(string $vatNumber): array
    {
        $clean = strtoupper(trim($vatNumber));
        return [
            'valid' => false,
            'isError' => false,
            'errorDescription' => null,
            'countryCode' => substr($clean, 0, 2),
            'vatNumber' => substr($clean, 2),
        ];
    }

    /** @inheritDoc */
    public function extractCountryCode(string $vatNumber): string
    {
        return substr(strtoupper(trim($vatNumber)), 0, 2);
    }

    /** @inheritDoc */
    public function cleanVatNumber(string $vatNumber): string
    {
        return strtoupper(trim($vatNumber));
    }
}


