<?php

namespace Alegiac\LaravelVatChecker\Validators;

use Alegiac\LaravelVatChecker\Contracts\VatValidatorInterface;

/**
 * Validator for Canada (CA) GST/HST BN.
 * Common format: 9-digit BN, optional RT0001 suffix (program/account)
 * Examples: CA123456789RT0001 or CA123456789
 */
class CaVatValidator implements VatValidatorInterface
{
    /** @inheritDoc */
    public function supports(string $countryCode): bool
    {
        return strtoupper($countryCode) === 'CA';
    }

    /** @inheritDoc */
    public function validateFormat(string $vatNumber): bool
    {
        $n = strtoupper(trim(substr($vatNumber, 2)));
        $n = preg_replace('/\s+/', '', $n ?? '');
        return preg_match('/^\d{9}(RT\d{4})?$/', $n) === 1;
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


