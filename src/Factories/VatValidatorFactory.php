<?php

namespace Alegiac\LaravelVatChecker\Factories;

use Alegiac\LaravelVatChecker\Contracts\VatValidatorFactoryInterface;
use Alegiac\LaravelVatChecker\Contracts\VatValidatorInterface;
use Alegiac\LaravelVatChecker\Validators\EuVatValidator;
use Alegiac\LaravelVatChecker\Validators\ChVatValidator;
use Alegiac\LaravelVatChecker\Validators\UkVatValidator;
use Alegiac\LaravelVatChecker\Validators\NoVatValidator;
use Alegiac\LaravelVatChecker\Validators\AuVatValidator;
use Alegiac\LaravelVatChecker\Validators\CaVatValidator;
use Alegiac\LaravelVatChecker\Validators\NzVatValidator;

/**
 * Default factory for VAT validators.
 */
class VatValidatorFactory implements VatValidatorFactoryInterface
{
    private array $validators = [];

    /**
     * Register default validators on construction.
     */
    public function __construct()
    {
        $this->registerDefaultValidators();
    }

    /**
     * Register a validator for specific country codes.
     *
     * @param VatValidatorInterface $validator
     * @return void
     */
    public function registerValidator(VatValidatorInterface $validator): void
    {
        $this->validators[] = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function createValidator(string $countryCode): ?VatValidatorInterface
    {
        foreach ($this->validators as $validator) {
            if ($validator->supports($countryCode)) {
                return $validator;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedCountries(): array
    {
        $countries = [];
        foreach ($this->validators as $validator) {
            if ($validator instanceof EuVatValidator) {
                $countries = array_merge($countries, EuVatValidator::getSupportedCountries());
            }
        }
        return array_unique($countries);
    }

    /**
     * {@inheritdoc}
     */
    public function isCountrySupported(string $countryCode): bool
    {
        return $this->createValidator($countryCode) !== null;
    }

    /**
     * Register default validators.
     *
     * @return void
     */
    private function registerDefaultValidators(): void
    {
        $this->registerValidator(new EuVatValidator());
        $this->registerValidator(new ChVatValidator());
        $this->registerValidator(new UkVatValidator());
        $this->registerValidator(new NoVatValidator());
        $this->registerValidator(new AuVatValidator());
        $this->registerValidator(new CaVatValidator());
        $this->registerValidator(new NzVatValidator());
    }
}
