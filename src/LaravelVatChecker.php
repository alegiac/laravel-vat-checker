<?php

namespace Alegiac\LaravelVatChecker;

use Alegiac\LaravelVatChecker\Contracts\VatResponseInterface;
use Alegiac\LaravelVatChecker\Contracts\VatValidatorFactoryInterface;
use Alegiac\LaravelVatChecker\Contracts\VatValidatorInterface;

class LaravelVatChecker
{
    private VatValidatorFactoryInterface $validatorFactory;
    private VatResponseInterface $response;

    public function __construct(
        ?VatValidatorFactoryInterface $validatorFactory = null,
        ?VatResponseInterface $response = null
    ) {
        $this->validatorFactory = $validatorFactory ?? new \Alegiac\LaravelVatChecker\Factories\VatValidatorFactory();
        $this->response = $response ?? new LaravelVatCheckerResponse();
    }

    /**
     * Check VAT number format and validity
     */
    public function check(string $vatNumber): array
    {
        $response = clone $this->response;
        
        $cleanVatNumber = $this->cleanVatNumber($vatNumber);
        $countryCode = $this->extractCountryCode($cleanVatNumber);
        
        $validator = $this->validatorFactory->createValidator($countryCode);
        
        if (!$validator) {
            // Country not supported
            $response->setIsFormatted(false);
            $response->setIsValid(false);
            $response->setIsError(true);
            $response->setErrorDescription('Country not supported');
            $response->setDetails([]);
            return $response->output();
        }

        // Validate format
        $isFormatted = $validator->validateFormat($cleanVatNumber);
        $response->setIsFormatted($isFormatted);

        if ($isFormatted) {
            // Validate against external services
            $externalData = $validator->validateExternal($cleanVatNumber);
            $response->setIsValid($externalData['valid'] ?? false);
            $response->setIsError($externalData['isError'] ?? false);
            $response->setErrorDescription($externalData['errorDescription'] ?? null);
            
            if (($externalData['valid'] ?? false) === true) {
                // remove non-detail keys from details payload
                $details = $externalData;
                unset($details['isError'], $details['errorDescription']);
                $response->setDetails($details);
            }
        } else {
            $response->setIsValid(false);
            $response->setIsError(false);
            $response->setErrorDescription(null);
            $response->setDetails([]);
        }

        return $response->output();
    }

    /**
     * Get all supported countries
     */
    public function getSupportedCountries(): array
    {
        return $this->validatorFactory->getSupportedCountries();
    }

    /**
     * Check if a country is supported
     */
    public function isCountrySupported(string $countryCode): bool
    {
        return $this->validatorFactory->isCountrySupported($countryCode);
    }

    /**
     * Register a custom validator
     */
    public function registerValidator(VatValidatorInterface $validator): void
    {
        $this->validatorFactory->registerValidator($validator);
    }

    /**
     * Clean and normalize VAT number
     */
    private function cleanVatNumber(string $vatNumber): string
    {
        return strtoupper(trim($vatNumber));
    }

    /**
     * Extract country code from VAT number
     */
    private function extractCountryCode(string $vatNumber): string
    {
        return substr($vatNumber, 0, 2);
    }
}
