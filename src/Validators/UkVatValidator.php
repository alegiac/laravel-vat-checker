<?php

namespace Alegiac\LaravelVatChecker\Validators;

use Alegiac\LaravelVatChecker\Contracts\VatValidatorInterface;

/**
 * VAT validator for United Kingdom (GB).
 *
 * Implements formal validation for common GB VAT number formats:
 * - 9 digits (standard)
 * - 12 digits (branch: first 9 as standard, last 3 branch identifier)
 * - GD followed by 3 digits (Government departments) where number < 500
 * - HA followed by 3 digits (Health authorities) where number >= 500
 *
 * For 9/12 digit numbers, applies HMRC checksum (mod 97) on the 9-digit core.
 */
class UkVatValidator implements VatValidatorInterface
{
    /** @inheritDoc */
    public function supports(string $countryCode): bool
    {
        return strtoupper($countryCode) === 'GB';
    }

    /** @inheritDoc */
    public function validateFormat(string $vatNumber): bool
    {
        $vatNumber = $this->cleanVatNumber($vatNumber);
        [$country, $number] = [$this->extractCountryCode($vatNumber), substr($vatNumber, 2)];

        // Normalize common separators in the number part
        $number = preg_replace('/[\s\.-]/', '', $number ?? '');

        if (!$this->supports($country)) {
            return false;
        }

        // Government departments and Health authorities formats
        if (preg_match('/^(GD|HA)(\d{3})$/', $number, $m) === 1) {
            $code = $m[1];
            $n = (int) $m[2];
            if ($code === 'GD') {
                return $n >= 1 && $n < 500;
            }
            // HA
            return $n >= 500 && $n <= 999;
        }

        // Standard 9 or 12 digit formats
        if (!preg_match('/^(\d{9})(\d{3})?$/', $number, $m)) {
            return false;
        }

        $core9 = $m[1];
        return $this->passesGbChecksum($core9);
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
     * HMRC checksum for 9-digit GB VAT numbers.
     *
     * Algorithm:
     *  sum = 8*d1 + 7*d2 + 6*d3 + 5*d4 + 4*d5 + 3*d6 + 2*d7 + 10*d8 + 1*d9
     *  valid if sum % 97 == 0
     *  If not, then (sum + 55) % 97 == 0 is also acceptable for certain ranges.
     *
     * @param string $digits Exactly 9 digits
     * @return bool
     */
    private function passesGbChecksum(string $digits): bool
    {
        if (!preg_match('/^\d{9}$/', $digits)) {
            return false;
        }
        $d = array_map('intval', str_split($digits));
        $sum = 8*$d[0] + 7*$d[1] + 6*$d[2] + 5*$d[3] + 4*$d[4] + 3*$d[5] + 2*$d[6] + 10*$d[7] + $d[8];
        if ($sum % 97 === 0) {
            return true;
        }
        // Secondary check
        return (($sum + 55) % 97) === 0;
    }
}


