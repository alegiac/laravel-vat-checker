<?php

namespace Alegiac\LaravelVatChecker\Validators;

use Alegiac\LaravelVatChecker\Contracts\VatValidatorInterface;

/**
 * VAT validator for Norway (NO).
 *
 * Accepts 9 digits with optional suffix 'MVA'. Implements MOD11 checksum
 * on the 9-digit core per Norwegian organization number rules.
 */
class NoVatValidator implements VatValidatorInterface
{
    /** @inheritDoc */
    public function supports(string $countryCode): bool
    {
        return strtoupper($countryCode) === 'NO';
    }

    /** @inheritDoc */
    public function validateFormat(string $vatNumber): bool
    {
        $vatNumber = $this->cleanVatNumber($vatNumber);
        [$country, $number] = [$this->extractCountryCode($vatNumber), substr($vatNumber, 2)];
        $number = preg_replace('/[\s\.-]/', '', $number ?? '');

        if (!$this->supports($country)) {
            return false;
        }

        if (!preg_match('/^(\d{9})(MVA)?$/', $number, $m)) {
            return false;
        }

        $core = $m[1];
        return $this->isValidMod11No($core);
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
     * Norwegian MOD11 checksum for 9-digit org/VAT numbers.
     * Weights: [3,2,7,6,5,4,3,2] over first 8 digits.
     * Check digit = 11 - (sum % 11); 11->0; 10 invalid.
     */
    private function isValidMod11No(string $digits): bool
    {
        if (!preg_match('/^\d{9}$/', $digits)) {
            return false;
        }
        $weights = [3,2,7,6,5,4,3,2];
        $sum = 0;
        for ($i = 0; $i < 8; $i++) {
            $sum += ((int) $digits[$i]) * $weights[$i];
        }
        $rem = $sum % 11;
        $check = 11 - $rem;
        if ($check === 10) {
            return false;
        }
        if ($check === 11) {
            $check = 0;
        }
        return (int) $digits[8] === $check;
    }
}


