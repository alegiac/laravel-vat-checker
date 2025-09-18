<?php

namespace Alegiac\LaravelVatChecker\Services;

/**
 * Simple service to retrieve VAT rates from package config.
 */
class VatRatesService
{
    /**
     * Get all supported country codes with available rates.
     *
     * @return array
     */
    public function listCountries(): array
    {
        return array_keys((array) config('vat-checker.rates'));
    }

    /**
     * Get rates for a given country code.
     *
     * @param string $countryCode Two-letter ISO country code
     * @return array{found:bool,countryCode:string,rates:array}
     */
    public function getRates(string $countryCode): array
    {
        $country = strtoupper($countryCode);
        $all = (array) config('vat-checker.rates');
        if (!isset($all[$country])) {
            return [
                'countryCode' => $country,
                'found' => false,
                'rates' => [],
            ];
        }
        return [
            'countryCode' => $country,
            'found' => true,
            'rates' => $all[$country],
        ];
    }
}


