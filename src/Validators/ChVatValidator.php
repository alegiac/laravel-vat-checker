<?php

namespace Alegiac\LaravelVatChecker\Validators;

use Alegiac\LaravelVatChecker\Contracts\VatValidatorInterface;

/**
 * VAT validator for Switzerland (CH).
 *
 * Validates Swiss UID/VAT format. External validation is not implemented
 * here; it can be integrated with the official UID Register later.
 *
 * Accepted inputs (consistent with package convention):
 * - Prefix 'CH' followed by one of the following in the number part:
 *   - optional 'E', then 9 digits, optional suffix (MWST|TVA|IVA)
 *   - 9 digits (no CHE prefix), optional suffix (MWST|TVA|IVA)
 */
class ChVatValidator implements VatValidatorInterface
{
    /** @inheritDoc */
    public function supports(string $countryCode): bool
    {
        return strtoupper($countryCode) === 'CH';
    }

    /** @inheritDoc */
    public function validateFormat(string $vatNumber): bool
    {
        $vatNumber = $this->cleanVatNumber($vatNumber);
        [$country, $number] = [$this->extractCountryCode($vatNumber), substr($vatNumber, 2)];

        // Normalize common separators in number part like CHE-421.098.863 → E421098863
        $number = preg_replace('/[\s\.-]/', '', $number ?? '');

        if (!$this->supports($country)) {
            return false;
        }

        // Accept patterns:
        // - E?\d{9}(MWST|TVA|IVA)?
        // - \d{9}(MWST|TVA|IVA)?
        $pattern = '/^(E)?\d{9}(MWST|TVA|IVA)?$/';
        if (preg_match($pattern, $number) <= 0) {
            return false;
        }

        // Extract the 9-digit UID part (strip optional leading 'E' and suffix)
        $core = preg_replace('/^(E)?(\d{9}).*$/', '$2', $number);
        if (!is_string($core) || strlen($core) !== 9) {
            return false;
        }

        // Validate MOD11 checksum according to Swiss UID spec (eCH-0097)
        return $this->isValidMod11Uid($core);
    }

    /**
     * Validate Swiss UID 9-digit MOD11 checksum.
     * Weights: [5,4,3,2,7,6,5,4] applied to first 8 digits.
     * Check digit = 11 - (sum % 11); if result == 10 → invalid, if 11 → 0.
     *
     * @param string $digits 9 numeric characters
     * @return bool
     */
    private function isValidMod11Uid(string $digits): bool
    {
        if (!preg_match('/^\d{9}$/', $digits)) {
            return false;
        }
        $weights = [5, 4, 3, 2, 7, 6, 5, 4];
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
     * @return array Supported country list
     */
    public static function getSupportedCountries(): array
    {
        return ['CH'];
    }
}


