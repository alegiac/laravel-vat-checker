<?php

namespace Alegiac\LaravelVatChecker\Validators;

use Alegiac\LaravelVatChecker\Contracts\VatValidatorInterface;

/**
 * Validator for Australia (AU) ABN (Australian Business Number).
 * Format: 11 digits with checksum per ATO rules.
 */
class AuVatValidator implements VatValidatorInterface
{
    /** @inheritDoc */
    public function supports(string $countryCode): bool
    {
        return strtoupper($countryCode) === 'AU';
    }

    /** @inheritDoc */
    public function validateFormat(string $vatNumber): bool
    {
        $n = preg_replace('/[^\d]/', '', substr($this->cleanVatNumber($vatNumber), 2));
        if (!preg_match('/^\d{11}$/', $n)) {
            return false;
        }
        return $this->isValidAbn($n);
    }

    /** @inheritDoc */
    public function validateExternal(string $vatNumber): array
    {
        $clean = $this->cleanVatNumber($vatNumber);
        return [
            'valid' => false,
            'isError' => false,
            'errorDescription' => null,
            'countryCode' => $this->extractCountryCode($clean),
            'vatNumber' => substr($clean, 2),
        ];
    }

    /** @inheritDoc */
    public function extractCountryCode(string $vatNumber): string
    {
        return substr($this->cleanVatNumber($vatNumber), 0, 2);
    }

    /** @inheritDoc */
    public function cleanVatNumber(string $vatNumber): string
    {
        return strtoupper(trim($vatNumber));
    }

    /**
     * ABN checksum validation.
     * Rules: subtract 1 from first digit; multiply by weights [10,1,3,5,7,9,11,13,15,17,19]; sum % 89 == 0
     */
    private function isValidAbn(string $digits): bool
    {
        if (!preg_match('/^\d{11}$/', $digits)) {
            return false;
        }
        $d = array_map('intval', str_split($digits));
        $d[0] -= 1;
        $weights = [10,1,3,5,7,9,11,13,15,17,19];
        $sum = 0;
        for ($i = 0; $i < 11; $i++) {
            $sum += $d[$i] * $weights[$i];
        }
        return ($sum % 89) === 0;
    }
}


