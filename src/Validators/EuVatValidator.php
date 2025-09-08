<?php

namespace Alegiac\LaravelVatChecker\Validators;

use Alegiac\LaravelVatChecker\Contracts\VatValidatorInterface;
use Alegiac\LaravelVatChecker\Vies\Client;
use Alegiac\LaravelVatChecker\Vies\ViesException;

class EuVatValidator implements VatValidatorInterface
{
    /**
     * Regular expression patterns per country code
     *
     * @var array
     * @link http://ec.europa.eu/taxation_customs/vies/faq.html?locale=en#item_11
     */
    protected static array $patternExpression = [
        'AT' => 'U[A-Z\d]{8}',
        'BE' => '(0\d{9}|\d{10})',
        'BG' => '\d{9,10}',
        'CY' => '\d{8}[A-Z]',
        'CZ' => '\d{8,10}',
        'DE' => '\d{9}',
        'DK' => '(\d{2} ?){3}\d{2}',
        'EE' => '\d{9}',
        'EL' => '\d{9}',
        'ES' => '[A-Z]\d{7}[A-Z]|\d{8}[A-Z]|[A-Z]\d{8}',
        'FI' => '\d{8}',
        'FR' => '([A-Z]{2}|\d{2})\d{9}',
        'HR' => '\d{11}',
        'HU' => '\d{8}',
        'IE' => '[A-Z\d]{8}|[A-Z\d]{9}',
        'IT' => '\d{11}',
        'LT' => '(\d{9}|\d{12})',
        'LU' => '\d{8}',
        'LV' => '\d{11}',
        'MT' => '\d{8}',
        'NL' => '\d{9}B\d{2}',
        'PL' => '\d{10}',
        'PT' => '\d{9}',
        'RO' => '\d{2,10}',
        'SE' => '\d{12}',
        'SI' => '\d{8}',
        'SK' => '\d{10}',
    ];

    private Client $viesClient;

    public function __construct(?Client $viesClient = null)
    {
        $this->viesClient = $viesClient ?? new Client();
    }

    public function supports(string $countryCode): bool
    {
        return isset(self::$patternExpression[$countryCode]);
    }

    public function validateFormat(string $vatNumber): bool
    {
        $vatNumber = $this->cleanVatNumber($vatNumber);
        [$country, $number] = $this->splitVat($vatNumber);

        if (!$this->supports($country)) {
            return false;
        }

        $validateRule = preg_match('/^' . self::$patternExpression[$country] . '$/', $number) > 0;

        // Special validation for Italian VAT numbers using Luhn algorithm
        if ($validateRule && $country === 'IT') {
            $result = $this->luhnCheck($number);
            return $result % 10 == 0;
        }

        return $validateRule;
    }

    public function validateExternal(string $vatNumber): array
    {
        try {
            return $this->viesClient->check($vatNumber);
        } catch (ViesException $e) {
            return [
                'valid' => false,
                'error' => $e->getMessage(),
                'countryCode' => $this->extractCountryCode($vatNumber),
                'vatNumber' => substr($vatNumber, 2),
            ];
        }
    }

    public function extractCountryCode(string $vatNumber): string
    {
        $vatNumber = $this->cleanVatNumber($vatNumber);
        return substr($vatNumber, 0, 2);
    }

    public function cleanVatNumber(string $vatNumber): string
    {
        return strtoupper(trim($vatNumber));
    }

    /**
     * Split VAT number into country code and number
     */
    private function splitVat(string $vatNumber): array
    {
        return [
            substr($vatNumber, 0, 2),
            substr($vatNumber, 2),
        ];
    }

    /**
     * A php implementation of Luhn Algorithm
     *
     * @link https://en.wikipedia.org/wiki/Luhn_algorithm
     */
    private function luhnCheck(string $vat): int
    {
        $sum = 0;
        $vatArray = str_split($vat);
        $counter = count($vatArray);
        
        for ($index = 0; $index < $counter; ++$index) {
            $value = intval($vatArray[$index]);
            if ($index % 2 !== 0) {
                $value *= 2;
                if ($value > 9) {
                    $value = 1 + ($value % 10);
                }
            }
            $sum += $value;
        }

        return $sum;
    }

    /**
     * Get all supported EU country codes
     */
    public static function getSupportedCountries(): array
    {
        return array_keys(self::$patternExpression);
    }
}
